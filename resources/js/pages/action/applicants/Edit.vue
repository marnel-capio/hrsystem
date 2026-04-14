<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, Link, } from '@inertiajs/vue3'
import { ref, watch, computed, onMounted } from 'vue'
import axios from 'axios';
import { reactive } from 'vue';

axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

const props = defineProps<{
    applicant: any, // the applicant object from the controller
    sourceTypes?: Record<number, string>,
    sources?: Record<number, string>,
    genders?: Record<number, string>,
    japaneseBackgrounds?: Record<number, string>,
    japaneseLevels?: Record<number, string>,
    flash?: { error?: string }
}>()


const sourceTypes = props.sourceTypes
    ? Object.entries(props.sourceTypes).map(([value, label]) => ({ value: Number(value), label }))
    : []
const sources = props.sources
    ? Object.entries(props.sources).map(([value, label]) => ({ value: Number(value), label }))
    : []
const genders = props.genders
    ? Object.entries(props.genders).map(([value, label]) => ({ value: Number(value), label }))
    : []

const form = useForm({
    source_type: props.applicant?.source_type || '',
    source: props.applicant?.source || '',
    other_source: props.applicant?.other_source || '',
    last_name: props.applicant?.last_name || '',
    first_name: props.applicant?.first_name || '',
    middle_name: props.applicant?.middle_name || '',
    email_address: props.applicant?.email_address || '',
    gender: props.applicant?.gender || '',
    age: props.applicant?.age || '',
    school: props.applicant?.school || '',
    degree: props.applicant?.degree || '',
    others_degree: props.applicant?.others_degree || '',
    expected_graduation: props.applicant?.expected_graduation || '',
    awards_recognition: props.applicant?.awards_recognition || '',
    other_examination_certificate: props.applicant?.other_examination_certificate || '',
    thesis_project: props.applicant?.thesis_project || '',
    extra_curricular: props.applicant?.extra_curricular || '',
    remarks: props.applicant?.remarks || '',
    japanese_background: props.applicant?.japanese_background || '',
    japanese_level: props.applicant?.japanese_level || '',
    background_remarks: props.applicant?.background_remarks || '',
})

const originalSourceType = props.applicant?.source_type || null
const originalSource = props.applicant?.source || ''
const originalOtherSource = props.applicant?.other_source || ''

const isSourceDisabled = computed(() => Number(form.source_type) !== 3)
const isOtherSourceDisabled = computed(() => ![1, 2, 4, 5].includes(Number(form.source_type)))

watch(() => form.source_type, (val) => {
    const sourceType = Number(val)

    // Handle Source dropdown
    if (sourceType === 3) {
        // If this is the original source_type, restore original value
        form.source = sourceType === originalSourceType ? originalSource : ''
        form.clearErrors('source')
    } else {
        form.source = ''
        form.clearErrors('source')
    }

    // Handle Other Source
    if ([1, 2, 4, 5].includes(sourceType)) {
        // If this is the original source_type, restore original other_source
        form.other_source = sourceType === originalSourceType ? originalOtherSource : form.other_source
        form.clearErrors('other_source')
    } else {
        form.other_source = ''
        form.clearErrors('other_source')
    }
})

const pendingFormData = ref<typeof form | null>(null);

async function submit() {
    const url = props.applicant?.id
        ? `/action/applicants/${props.applicant.id}/update`
        : '/action/applicants';

    const method = props.applicant?.id ? 'put' : 'post';

    form[method](url, {
        onFinish: () => console.log(props.applicant?.id ? 'Updated!' : 'Created!')
    });
}

const minGraduationDate = computed(() => {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // months are 0-indexed
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`; // format: YYYY-MM-DD
});

const rules = {
    last_name: (val: string) => !!val || 'This is a required field.',
    first_name: (val: string) => !!val || 'This is a required field.',
    email_address: (val: string) => {
        if (!val) return 'This is a required field.';
        if (typeof val !== 'string') return 'The email must be a string';
        if (!val.includes('@') || !val.includes('.')) return "The email must contain '@' and '.'";
        // stricter validation using regex (optional)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(val)) return 'The email must be a valid email address';
        return true;
    },
    gender: (val: string) => !!val || 'This is a required field.',
    age: (val: string) => /^\d+$/.test(val) || 'Age must be a valid number',
    school: (val: string) => !!val || 'This is a required field.',
    degree: (val: string) => !!val || 'This is a required field.',
    expected_graduation: (val: string) => {
        const currentYear = new Date().getFullYear();
        if (!val || val.trim() === '') return 'Expected Graduation Year is required';
        const year = Number(val);
        if (isNaN(year)) return 'Expected Graduation Year must be numeric';
        return true;
    },
};

function validateField(field: keyof typeof rules) {
    const value = (form as any)[field];
    const rule = rules[field];
    const result = rule(value);
    form.setError(field, result === true ? '' : result);
}

function validateAge() {
    const age = Number(form.age); // convert string to number

    if (form.age === null || form.age === '') {
        form.setError('age', 'This is a required field.');
    } else if (age < 1) {
        form.setError('age', 'The age field must be at least 1.');
    } else if (age > 99) {
        form.setError('age', 'The age field must not be greater than 99.');
    } else {
        form.setError('age', ''); // valid
    }
}

watch(() => form.last_name, () => validateField('last_name'));
watch(() => form.first_name, () => validateField('first_name'));
watch(() => form.email_address, () => validateField('email_address'));
watch(() => form.gender, () => validateField('gender'));
watch(() => form.age, () => validateField('age'));
watch(() => form.school, () => validateField('school'));
watch(() => form.degree, () => validateField('degree'));

const showError = ref(false)
const errorMessage = ref<string | null>(null)

watch(
    () => props.flash,
    (flash) => {
        if (flash?.error) {
            errorMessage.value = flash.error
            showError.value = true

            setTimeout(() => {
                showError.value = false
            }, 10000)
        }
    },
    { immediate: true, deep: true }
)
watch(() => form.expected_graduation, () => validateField('expected_graduation'));

const japaneseBackgrounds = props.japaneseBackgrounds
    ? Object.entries(props.japaneseBackgrounds).map(([value, label]) => ({
        value: Number(value),
        label
    }))
    : []

const japaneseLevels = props.japaneseLevels
    ? Object.entries(props.japaneseLevels).map(([value, label]) => ({
        value: Number(value),
        label
    }))
    : []
const isJapaneseLevelDisabled = computed(() => {
    return Number(form.japanese_background) !== 3
})

watch(() => form.japanese_background, (val) => {
    const bg = Number(val)

    if (bg === 3) {
        // enable JLPT level
        form.japanese_level = form.japanese_level
    } else {
        // clear when not JLPT
        form.japanese_level = ''
        form.clearErrors('japanese_level')
    }
})
</script>

<template>
    <AppLayout>
        <!-- <Index :users="[]" /> -->

        <div v-if="showError" class="full-width-alert">
            <div class="alert-banner alert-error-banner">
                <div class="alert-body">{{ errorMessage }}</div>
                <button type="button" class="close-btn" @click="showError = false">×</button>
            </div>
        </div>

        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
        </head>
        <div class="page-header">
            <h2 class="page-title">Edit ACTION Applicant</h2>
        </div>

        <div class="form-center">
            <div class="card create-user-card">
                <form @submit.prevent="submit">

                    <!-- Source Fields -->
                    <div class="form-group">
                        <label>Source Type</label>
                        <select v-model="form.source_type" disabled>
                            <option disabled value="">Select Source Type</option>
                            <option v-for="type in sourceTypes" :key="type.value" :value="type.value">{{ type.label }}
                            </option>
                        </select>
                        <span v-if="form.errors.source_type" class="error">{{ form.errors.source_type }}</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label>Source</label>
                            <select v-model="form.source" disabled>
                                <option disabled value="">Select Source</option>
                                <option v-for="s in sources" :key="s.value" :value="s.value">{{ s.label }}</option>
                            </select>
                        </div>

                        <div class="form-group half">
                            <label>Other Source</label>
                            <input type="text" v-model="form.other_source" placeholder="Specify other source"
                                disabled />
                        </div>
                    </div>

                    <!-- Name Fields -->
                    <div class="form-row name-fields">
                        <div class="form-group last-first">
                            <label style="font-weight: bold;">Last Name *</label>
                            <input type="text" v-model="form.last_name" placeholder="Last Name" />
                            <span v-if="form.errors.last_name" class="error">{{ form.errors.last_name }}</span>
                        </div>
                        <div class="form-group last-first">
                            <label style="font-weight: bold;">First Name *</label>
                            <input type="text" v-model="form.first_name" placeholder="First Name" />
                            <span v-if="form.errors.first_name" class="error">{{ form.errors.first_name }}</span>
                        </div>
                        <div class="form-group middle-name">
                            <label>Middle Name</label>
                            <input type="text" v-model="form.middle_name" placeholder="MI" />
                            <span v-if="form.errors.middle_name" class="error">{{ form.errors.middle_name }}</span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label style="font-weight: bold;">Email Address *</label>
                        <input type="text" v-model="form.email_address" placeholder="Email Address" />
                        <span v-if="form.errors.email_address" class="error">{{ form.errors.email_address }}</span>
                    </div>

                    <!-- Age & Gender -->
                    <div class="form-row">
                        <div class="form-group half">
                            <label style="font-weight: bold;">Gender *</label>
                            <select v-model="form.gender">
                                <option disabled value="">Select Gender</option>
                                <option v-for="g in genders" :key="g.value" :value="g.value">{{ g.label }}</option>
                            </select>
                            <span v-if="form.errors.gender" class="error">{{ form.errors.gender }}</span>
                        </div>
                        <div class="form-group half">
                            <label style="font-weight: bold;">Age *</label>
                            <input type="number" v-model="form.age" placeholder="Age" @input="validateAge" />
                            <span v-if="form.errors.age" class="error">{{ form.errors.age }}</span>
                        </div>
                    </div>

                    <!-- School & Degree -->
                    <div class="form-row">
                        <div class="form-group half">
                            <label style="font-weight: bold;">School *</label>
                            <input type="text" v-model="form.school" placeholder="School" />
                            <span v-if="form.errors.school" class="error">{{ form.errors.school }}</span>
                        </div>
                        <div class="form-group half">
                            <label style="font-weight: bold;">Degree *</label>
                            <input type="text" v-model="form.degree" placeholder="Degree" />
                            <span v-if="form.errors.degree" class="error">{{ form.errors.degree }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Other Degree</label>
                        <input type="text" v-model="form.others_degree" placeholder="Specify other degree" />
                        <span v-if="form.errors.others_degree" class="error">{{ form.errors.others_degree }}</span>
                    </div>

                    <!-- Expected Graduation -->
                    <div class="form-group">
                        <label style="font-weight: bold;">Expected Graduation (Year) *</label>
                        <input type="text" v-model="form.expected_graduation" placeholder="YYYY" />
                        <span v-if="form.errors.expected_graduation" class="error">{{ form.errors.expected_graduation
                        }}</span>
                    </div>

                    <!-- Achievements / Remarks -->
                    <div class="form-group">
                        <label>Awards / Recognition</label>
                        <textarea v-model="form.awards_recognition" placeholder="Awards or recognition"></textarea>
                        <span v-if="form.errors.awards_recognition" class="error">{{ form.errors.awards_recognition
                            }}</span>
                    </div>

                    <!-- Japanese Background (RADIO) -->
                    <div class="form-group">
                        <label style="font-weight: bold;">Japanese Background *</label>

                        <div class="radio-group">
                            <label v-for="j in japaneseBackgrounds" :key="j.value">
                                <input type="radio" :value="j.value" v-model="form.japanese_background" />
                                {{ j.label }}
                            </label>
                        </div>

                        <span v-if="form.errors.japanese_background" class="error">
                            {{ form.errors.japanese_background }}
                        </span>
                    </div>

                    <!-- Japanese Level (Conditional Dropdown) -->
                    <div class="form-group">
                        <label>Japanese Level</label>

                        <select v-model="form.japanese_level" :disabled="isJapaneseLevelDisabled">
                            <option disabled value="">Select Level</option>
                            <option v-for="j in japaneseLevels" :key="j.value" :value="j.value">
                                {{ j.label }}
                            </option>
                        </select>

                        <span v-if="!isJapaneseLevelDisabled && form.errors.japanese_level" class="error">
                            {{ form.errors.japanese_level }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Japanese Background Remarks</label>
                        <textarea v-model="form.background_remarks"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Other Exam Certificate</label>
                        <textarea v-model="form.other_examination_certificate"></textarea>
                        <span v-if="form.errors.other_examination_certificate" class="error">{{
                            form.errors.other_examination_certificate }}</span>
                    </div>

                    <div class="form-group">
                        <label>Thesis / Project</label>
                        <textarea v-model="form.thesis_project"></textarea>
                        <span v-if="form.errors.thesis_project" class="error">{{ form.errors.thesis_project }}</span>
                    </div>

                    <div class="form-group">
                        <label>Extra Curricular</label>
                        <textarea v-model="form.extra_curricular"></textarea>
                        <span v-if="form.errors.extra_curricular" class="error">{{ form.errors.extra_curricular
                            }}</span>
                    </div>

                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea v-model="form.remarks"></textarea>
                        <span v-if="form.errors.remarks" class="error">{{ form.errors.remarks }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <!-- Cancel goes to the applicant's show page -->
                        <Link :href="`/action/applicants/${props.applicant.id}`" class="btn btn-secondary">Cancel</Link>

                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            {{ form.processing ? 'Updating..' : 'Update' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

.radio-group {
    display: flex;
    gap: 1.5rem;
    margin-top: 0.5rem;
}

.radio-group label {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.9rem;
    cursor: pointer;
}
/* Reuse the same User form styles */
.form-row {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.form-group.half {
    flex: 1 1 48%;
}

.form-group.third {
    flex: 1 1 32%;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 600px;
    margin: 0 auto 1.5rem;
}

/* Name Fields specific styling */
.name-fields {
    display: flex;
    gap: 1rem;
    flex-wrap: nowrap;
    /* force all inputs in one line */
}

.name-fields .last-first {
    flex: 2 1 0;
    /* takes more space, flexible */
    min-width: 0;
    /* allow shrinking */
}

.name-fields .middle-name {
    flex: 1 1 0;
    /* smaller than last/first */
    min-width: 0;
}

/* Optional: make input take full width of flex item */
.name-fields input {
    width: 100%;
    box-sizing: border-box;
    /* ensures padding doesn't overflow */
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
}

.form-center {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}

.card.create-user-card {
    width: 100%;
    max-width: 600px;
    padding: 2rem;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}

input,
select,
textarea {
    padding: 0.6rem 1rem;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 0.95rem;
    transition: border 0.15s ease, background 0.15s ease, color 0.15s ease;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: var(--ats-primary);
}

/* Disabled styling */
input:disabled,
select:disabled,
textarea:disabled {
    background-color: #f5f5f5;
    color: #a1a1a1;
    cursor: not-allowed;
    border-color: #d1d1d1;
}

.error {
    color: #ff4d4f;
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

textarea {
    resize: vertical;
    min-height: 60px;
}

.form-actions {
    margin-top: 2rem;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.5rem;
    /* spacing between buttons */
}

.form-actions button,
.form-actions a {
    flex: 0 0 auto;
    width: auto;
    white-space: nowrap;
}

/* Primary button */
button.btn-primary {
    padding: 0.5rem 1.2rem;
    font-size: 0.85rem;
    border-radius: 5px;
    font-weight: 500;
    border: none;
    background: var(--ats-primary);
    color: #fff;
    cursor: pointer;
    transition: background 0.15s ease;
}

button.btn-primary:hover:not(:disabled) {
    background: var(--ats-accent);
}

button.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Secondary button */
.btn-secondary {
    padding: 0.5rem 1.2rem;
    font-size: 0.85rem;
    border-radius: 5px;
    font-weight: 500;
    border: 1px solid #d1d5db;
    background: #f3f4f6;
    color: #374151;
    text-decoration: none;
    transition: background 0.15s ease;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

/* Make placeholder text appear grayed out */
select option[value=""] {
    color: #999;
    font-style: italic;
}

input:disabled,
textarea:disabled,
select:disabled {
    background-color: #d4d4d8;
    /* lighter gray */
    color: #6b7280;
    /* muted text */
    cursor: not-allowed;
    border-color: #9ca3af;
    /* slightly darker border for definition */
}


.full-width-alert {
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 9999;
}

.alert-banner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 1rem;
    font-weight: 500;
    color: #fff;
    background-color: #dc2626;
    /* error red */
}
</style>