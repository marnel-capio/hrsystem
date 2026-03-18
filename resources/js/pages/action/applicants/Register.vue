<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { watch, computed } from 'vue'

// Props (optional for dynamic dropdowns)
const props = defineProps<{
    sourceTypes?: Record<number, string>,
    sources?: Record<number, string>
}>()

const isSourceDisabled = computed(() => {
    // Source is only enabled if source_type is 3
    return Number(form.source_type) !== 3
})

const isOtherSourceDisabled = computed(() => {
    const val = Number(form.source_type)
    // Other Source enabled if source_type is 1, 2, 4, 5
    return ![1, 2, 4, 5].includes(val)
})

// Dropdowns
const sourceTypes = props.sourceTypes
    ? Object.entries(props.sourceTypes).map(([value, label]) => ({ value: Number(value), label }))
    : [
        { value: 1, label: 'Campus Recruitment' },
        { value: 2, label: 'Academe Partner' },
        { value: 3, label: 'Recruitment Portals' },
        { value: 4, label: 'Employee Referral' },
        { value: 5, label: 'Walk-in' }
    ]

const sources = props.sources
    ? Object.entries(props.sources).map(([value, label]) => ({ value: Number(value), label }))
    : [
        { value: 1, label: 'Mynimo' },
        { value: 2, label: 'Indeed' },
        { value: 3, label: 'Kalibrr' },
        { value: 4, label: 'FoundIt' },
        { value: 5, label: 'LinkedIn' },
        { value: 6, label: 'Facebook' },
        { value: 7, label: 'Jobstreet' }
    ]

const genders = [
    { value: 1, label: 'Male' },
    { value: 2, label: 'Female' }
]

// Form state using Inertia useForm
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

// Watch source_type to enable/disable fields
watch(() => form.source_type, (val) => {
    const otherSourceTypes = [1, 2, 4, 5]
    if (otherSourceTypes.includes(Number(val))) {
        form.other_source = ''
        form.source = ''
    } else if (Number(val) === 3) {
        form.source = ''
        form.other_source = ''
    }
})

// Submit handler
function submit() {
    form.post('/action/applicants')
}
</script>

<template>
    <AppLayout>
        <div class="page-header">
            <h2 class="page-title">Create Applicant</h2>
        </div>

        <div class="form-center">
            <div class="card create-user-card">
                <form @submit.prevent="submit">
                    <!-- Source Type -->
                    <div class="form-group">
                        <label>Source Type</label>
                        <select v-model="form.source_type">
                            <option value="">Select Source Type</option>
                            <option v-for="type in sourceTypes" :key="type.value" :value="type.value">
                                {{ type.label }}
                            </option>
                        </select>
                        <span v-if="form.errors.source_type" class="error">{{ form.errors.source_type }}</span>
                    </div>

                    <!-- Source -->
                    <div class="form-group">
                        <label>Source</label>
                        <select v-model="form.source" :disabled="isSourceDisabled">
                            <option value="">Select Source</option>
                            <option v-for="s in sources" :key="s.value" :value="s.value">{{ s.label }}</option>
                        </select>
                        <span v-if="form.errors.source" class="error">{{ form.errors.source }}</span>
                    </div>

                    <!-- Other Source -->
                    <div class="form-group">
                        <label>Other Source</label>
                        <input type="text" v-model="form.other_source" placeholder="Specify other source"
                            :disabled="isOtherSourceDisabled" />
                        <span v-if="form.errors.other_source" class="error">{{ form.errors.other_source }}</span>
                    </div>

                    <!-- Name Fields -->
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" v-model="form.last_name" placeholder="Enter last name" />
                        <span v-if="form.errors.last_name" class="error">{{ form.errors.last_name }}</span>
                    </div>

                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" v-model="form.first_name" placeholder="Enter first name" />
                        <span v-if="form.errors.first_name" class="error">{{ form.errors.first_name }}</span>
                    </div>

                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="text" v-model="form.middle_name" placeholder="Enter middle name (optional)" />
                        <span v-if="form.errors.middle_name" class="error">{{ form.errors.middle_name }}</span>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" v-model="form.email_address" placeholder="Enter email address" />
                        <span v-if="form.errors.email_address" class="error">{{ form.errors.email_address }}</span>
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label>Gender</label>
                        <select v-model="form.gender">
                            <option value="">Select Gender</option>
                            <option v-for="g in genders" :key="g.value" :value="g.value">{{ g.label }}</option>
                        </select>
                        <span v-if="form.errors.gender" class="error">{{ form.errors.gender }}</span>
                    </div>

                    <!-- Age -->
                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" v-model="form.age" min="0" max="99" placeholder="Enter age" />
                        <span v-if="form.errors.age" class="error">{{ form.errors.age }}</span>
                    </div>

                    <!-- School & Degree -->
                    <div class="form-group">
                        <label>School</label>
                        <input type="text" v-model="form.school" placeholder="Enter school" />
                        <span v-if="form.errors.school" class="error">{{ form.errors.school }}</span>
                    </div>

                    <div class="form-group">
                        <label>Degree</label>
                        <input type="text" v-model="form.degree" placeholder="Enter degree" />
                        <span v-if="form.errors.degree" class="error">{{ form.errors.degree }}</span>
                    </div>

                    <div class="form-group">
                        <label>Other Degree</label>
                        <input type="text" v-model="form.others_degree" placeholder="Specify other degree" />
                        <span v-if="form.errors.others_degree" class="error">{{ form.errors.others_degree }}</span>
                    </div>

                    <div class="form-group">
                        <label>Expected Graduation</label>
                        <input type="date" v-model="form.expected_graduation" />
                        <span v-if="form.errors.expected_graduation" class="error">{{ form.errors.expected_graduation
                        }}</span>
                    </div>

                    <!-- Achievements -->
                    <div class="form-group">
                        <label>Awards / Recognition</label>
                        <textarea v-model="form.awards_recognition"
                            placeholder="Enter awards or recognition"></textarea>
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

                    <!-- Submit -->
                    <div class="form-actions">
                        <Link href="/action/applicants" class="btn btn-secondary">
                        Cancel
                        </Link>

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
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 600px;
    margin: 0 auto 1.5rem;
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
    gap: 0.5rem; /* spacing between buttons */
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


</style>