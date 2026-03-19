<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { watch, computed } from 'vue'

const props = defineProps<{
    sourceTypes?: Record<number, string>,
    sources?: Record<number, string>,
    genders?: Record<number, string>
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
    source_type: '',
    source: '',
    other_source: '',
    last_name: '',
    first_name: '',
    middle_name: '',
    email_address: '',
    gender: '',
    age: '',
    school: '',
    degree: '',
    others_degree: '',
    expected_graduation: '',
    awards_recognition: '',
    other_examination_certificate: '',
    thesis_project: '',
    extra_curricular: '',
    remarks: ''
})

const isSourceDisabled = computed(() => Number(form.source_type) !== 3)
const isOtherSourceDisabled = computed(() => ![1, 2, 4, 5].includes(Number(form.source_type)))

watch(() => form.source_type, (val) => {
    const otherSourceTypes = [1, 2, 4, 5];
    form.source = ''
    form.other_source = ''
    form.clearErrors('source')
    form.clearErrors('other_source')
})

function submit() {
    form.post('/action/applicants')
}
</script>

<template>
    <AppLayout>
        <div class="page-header">
            <h2 class="page-title">Create ACTION Applicant</h2>
        </div>

        <div class="form-center">
            <div class="card create-user-card">
                <form @submit.prevent="submit">

                    <!-- Source Fields -->
                    <div class="form-group">
                        <label>Source Type</label>
                        <select v-model="form.source_type">
                            <option disabled value="">Select Source Type</option>
                            <option v-for="type in sourceTypes" :key="type.value" :value="type.value">{{ type.label }}
                            </option>
                        </select>
                        <span v-if="form.errors.source_type" class="error">{{ form.errors.source_type }}</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label>Source</label>
                            <select v-model="form.source" :disabled="isSourceDisabled">
                                <option disabled value="">Select Source</option>
                                <option v-for="s in sources" :key="s.value" :value="s.value">{{ s.label }}</option>
                            </select>
                            <span v-if="!isSourceDisabled && form.errors.source" class="error">{{ form.errors.source
                                }}</span>
                        </div>

                        <div class="form-group half">
                            <label>Other Source</label>
                            <input type="text" v-model="form.other_source" placeholder="Specify other source"
                                :disabled="isOtherSourceDisabled" />
                            <span v-if="!isOtherSourceDisabled && form.errors.other_source" class="error">{{
                                form.errors.other_source }}</span>
                        </div>
                    </div>

                    <!-- Name Fields -->
                    <div class="form-row name-fields">
                        <div class="form-group last-first">
                            <label>Last Name</label>
                            <input type="text" v-model="form.last_name" placeholder="Last Name" />
                            <span v-if="form.errors.last_name" class="error">{{ form.errors.last_name }}</span>
                        </div>
                        <div class="form-group last-first">
                            <label>First Name</label>
                            <input type="text" v-model="form.first_name" placeholder="First Name" />
                            <span v-if="form.errors.first_name" class="error">{{ form.errors.first_name }}</span>
                        </div>
                        <div class="form-group middle-name">
                            <label>Middle Initial</label>
                            <input type="text" v-model="form.middle_name" placeholder="MI" />
                            <span v-if="form.errors.middle_name" class="error">{{ form.errors.middle_name }}</span>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" v-model="form.email_address" placeholder="Email Address" />
                        <span v-if="form.errors.email_address" class="error">{{ form.errors.email_address }}</span>
                    </div>

                    <!-- Age & Gender -->
                    <div class="form-row">
                        <div class="form-group half">
                            <label>Gender</label>
                            <select v-model="form.gender">
                                <option disabled value="">Select Gender</option>
                                <option v-for="g in genders" :key="g.value" :value="g.value">{{ g.label }}</option>
                            </select>
                            <span v-if="form.errors.gender" class="error">{{ form.errors.gender }}</span>
                        </div>
                        <div class="form-group half">
                            <label>Age</label>
                            <input type="text" v-model="form.age" min="0" max="99" placeholder="Age" />
                            <span v-if="form.errors.age" class="error">{{ form.errors.age }}</span>
                        </div>
                    </div>

                    <!-- School & Degree -->
                    <div class="form-row">
                        <div class="form-group half">
                            <label>School</label>
                            <input type="text" v-model="form.school" placeholder="School" />
                            <span v-if="form.errors.school" class="error">{{ form.errors.school }}</span>
                        </div>
                        <div class="form-group half">
                            <label>Degree</label>
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
                        <label>Expected Graduation</label>
                        <input type="date" v-model="form.expected_graduation" />
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
                        <Link href="/action/applicants" class="btn btn-secondary">Cancel</Link>
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            {{ form.processing ? 'Creating…' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
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
    background-color: #d4d4d8; /* lighter gray */
    color: #6b7280; /* muted text */
    cursor: not-allowed;
    border-color: #9ca3af; /* slightly darker border for definition */
}

</style>