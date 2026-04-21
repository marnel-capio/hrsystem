<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { ref, watch, computed } from 'vue'
import axios from 'axios'

axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

const props = defineProps<{
    sourceTypes?: Record<number, string>,
    sources?: Record<number, string>,
    genders?: Record<number, string>,
    japaneseBackgrounds?: Record<number, string>,
    japaneseLevels?: Record<number, string>,
    flash?: {
        error?: string
    }
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

const form = useForm({
    source_type: '',
    source: '',
    other_source: '',
    last_name: '',
    first_name: '',
    middle_name: '',
    gender: '',
    birthdate: '',
    age: '',
    address: '',
    email_address: '',
    contact_no: '',
    school_graduated_from: '',
    course: '',
    year_attended: '',
    others: '',
    spouse_details: '',
    children: '',
    father_details: '',
    mother_details: '',
    sibling_details: '',
    emergency_contact_name: '',
    emergency_contact_number: '',
    emergency_contact_address: '',
    remarks: '',
    japanese_background: '',
    japanese_level: '',
    background_remarks: '',
})

const isSourceDisabled = computed(() => ![1, 2].includes(Number(form.source_type)))
const isOtherSourceDisabled = computed(() => Number(form.source_type) !== 3)
const isJapaneseLevelDisabled = computed(() => Number(form.japanese_background) !== 3)

const filteredSources = computed(() => {
    const type = Number(form.source_type)

    if (type === 2) {
        return sources.filter(s => s.value >= 1 && s.value <= 7)
    }

    if (type === 1) {
        return sources.filter(s => s.value >= 8 && s.value <= 12)
    }

    return []
})

watch(() => form.source_type, () => {
    form.source = ''
    form.other_source = ''
    form.clearErrors('source')
    form.clearErrors('other_source')
})

watch(() => form.birthdate, (val) => {
    if (!val) {
        form.age = ''
        return
    }

    const today = new Date()
    const birth = new Date(val)

    if (Number.isNaN(birth.getTime())) {
        form.age = ''
        return
    }

    let age = today.getFullYear() - birth.getFullYear()
    const monthDiff = today.getMonth() - birth.getMonth()

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--
    }

    form.age = age >= 0 ? String(age) : ''
})

const showEmailExistsModal = ref(false)
const pendingFormData = ref<Record<string, any> | null>(null)

const rules = {
    source_type: (_val: string | number) => true,

    source: (_val: string | number) => true,

    other_source: (_val: string) => true,

    last_name: (_val: string) => true,

    first_name: (_val: string) => true,

    gender: (_val: string | number) => true,

    birthdate: (_val: string) => true,

    age: (val: string | number) => {
        if (val === '' || val === null || val === undefined) return true
        const age = Number(val)
        if (Number.isNaN(age)) return 'Age must be a valid number'
        if (age < 1) return 'Value must be greater than or equal to 1.'
        if (age > 99) return 'Value must be less than or equal to 99.'
        return true
    },

    children: (val: string | number) => {
    if (val === '' || val === null || val === undefined) return true
    const num = Number(val)
    if (Number.isNaN(num)) return 'Children must be a valid number'
    if (num < 0) return 'Value must be greater than or equal to 0.'
    if (num > 99) return 'Value must be less than or equal to 99.'
    return true
},

    email_address: (val: string) => {
        if (!val) return true
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if (!emailRegex.test(val)) return 'The email must be a valid email address'
        return true
    },

    contact_no: (_val: string) => true,

    emergency_contact_number: (_val: string) => true,

    japanese_background: (val: string | number) => !!val || 'This is a required field',
    japanese_level: (val: string | number) => {
        if (Number(form.japanese_background) === 3 && !val) {
            return 'This is a required field'
        }
        return true
    },
}

function validateField(field: keyof typeof rules) {
    const value = (form as any)[field]
    const rule = rules[field]
    const result = rule(value)
    form.setError(field, result === true ? '' : result)
}

function validateFrontendFields() {
    validateField('age')
    validateField('email_address')
}

function hasBlockingFrontendErrors() {
    const fields: Array<keyof typeof rules> = [
        'age',
        'email_address',
    ]

    return fields.some(field => {
        const msg = (form.errors as any)[field]
        return !!msg
    })
}

function hasBlockingErrors() {
    const requiredFields: Array<keyof typeof rules> = [
        'source_type',
        'source',
        'other_source',
        'last_name',
        'first_name',
        'gender',
        'birthdate',
        'age',
        'email_address',
        'contact_no',
        'japanese_background',
        'japanese_level',
    ]

    return requiredFields.some(field => {
        const msg = (form.errors as any)[field]
        return !!msg
    })
}

watch(() => form.japanese_background, (val) => {
    if (Number(val) !== 3) {
        form.japanese_level = ''
        form.clearErrors('japanese_level')
    }
})

watch(() => form.japanese_background, () => validateField('japanese_background'))
watch(() => form.japanese_level, () => validateField('japanese_level'))

watch(() => form.age, () => validateField('age'))
watch(() => form.email_address, () => validateField('email_address'))
watch(() => form.children, () => validateField('children'))

async function submit() {
    form.clearErrors()

    validateFrontendFields()

    if (hasBlockingFrontendErrors()) return

    try {
        const { data } = await axios.post('/intermediate/applicants/check-email', {
            email_address: form.email_address
        })

        if (data.exists) {
            pendingFormData.value = { ...form.data() }
            showEmailExistsModal.value = true
        } else {
            form.post('/intermediate/applicants')
        }
    } catch (error) {
        console.error('Email check failed', error)
    }
}

function confirmUpdate() {
    if (pendingFormData.value) {
        Object.assign(form, pendingFormData.value)
        form.post('/intermediate/applicants', {
            onFinish: () => {
                showEmailExistsModal.value = false
            }
        })
    }
}

function cancelUpdate() {
    showEmailExistsModal.value = false
}

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
</script>

<template>
    <AppLayout>
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
            <h2 class="page-title">Create Intermediate Applicant</h2>
        </div>

        <div v-if="showEmailExistsModal" class="modal-overlay">
            <div class="modal-content modal-confirm">
                <p class="modal-text" style="text-align: center;">
                    <strong>This applicant data already exists.</strong>
                </p>
                <p class="modal-text" style="text-align: center;">
                    Do you want to update the applicant's data with the current information?
                </p>
                <div class="modal-actions">
                    <button @click="confirmUpdate" class="btn-primary">Confirm</button>
                    <button @click="cancelUpdate" class="btn-secondary">Cancel</button>
                </div>
            </div>
        </div>

        <div class="form-center">
            <div class="card create-user-card">
                <form @submit.prevent="submit">
                    <div class="form-group">
                        <label class="field-label-required required">Source Type</label>
                        <select v-model="form.source_type">
                            <option disabled value="">Select Source Type</option>
                            <option v-for="type in sourceTypes" :key="type.value" :value="type.value">
                                {{ type.label }}
                            </option>
                        </select>
                        <span v-if="form.errors.source_type" class="error">{{ form.errors.source_type }}</span>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label :class="[isSourceDisabled ? 'field-label' : 'field-label-required required']">
                                Source
                            </label>
                            <select v-model="form.source" :disabled="isSourceDisabled">
                                <option disabled value="">Select Source</option>
                                <option v-for="s in filteredSources" :key="s.value" :value="s.value">
                                    {{ s.label }}
                                </option>
                            </select>
                            <span v-if="!isSourceDisabled && form.errors.source" class="error">
                                {{ form.errors.source }}
                            </span>
                        </div>

                        <div class="form-group half">
                            <label :class="[isOtherSourceDisabled ? 'field-label' : 'field-label-required required']">
                                Other Source
                            </label>
                            <input
                                type="text"
                                v-model="form.other_source"
                                placeholder="Specify other source"
                                :disabled="isOtherSourceDisabled"
                            />
                            <span v-if="!isOtherSourceDisabled && form.errors.other_source" class="error">
                                {{ form.errors.other_source }}
                            </span>
                        </div>
                    </div>

                    <div class="form-row name-fields">
                        <div class="form-group last-first">
                            <label class="field-label-required required">Last Name</label>
                            <input type="text" v-model="form.last_name" placeholder="Last Name" />
                            <span v-if="form.errors.last_name" class="error">{{ form.errors.last_name }}</span>
                        </div>

                        <div class="form-group last-first">
                            <label class="field-label-required required">First Name</label>
                            <input type="text" v-model="form.first_name" placeholder="First Name" />
                            <span v-if="form.errors.first_name" class="error">{{ form.errors.first_name }}</span>
                        </div>

                        <div class="form-group middle-name">
                            <label class="field-label">Middle Name</label>
                            <input type="text" v-model="form.middle_name" placeholder="MI" />
                            <span v-if="form.errors.middle_name" class="error">{{ form.errors.middle_name }}</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label class="field-label-required required">Gender</label>
                            <select v-model="form.gender">
                                <option disabled value="">Select Gender</option>
                                <option v-for="g in genders" :key="g.value" :value="g.value">{{ g.label }}</option>
                            </select>
                            <span v-if="form.errors.gender" class="error">{{ form.errors.gender }}</span>
                        </div>

                        <div class="form-group half">
                            <label class="field-label-required required">Birthdate</label>
                            <input type="date" v-model="form.birthdate" />
                            <span v-if="form.errors.birthdate" class="error">{{ form.errors.birthdate }}</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label class="field-label-required required">Age</label>
                            <input type="number" v-model="form.age" readonly />
                            <span v-if="form.errors.age" class="error">{{ form.errors.age }}</span>
                        </div>

                        <div class="form-group half">
                            <label class="field-label">Address</label>
                            <input type="text" v-model="form.address" placeholder="Address" />
                            <span v-if="form.errors.address" class="error">{{ form.errors.address }}</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label class="field-label-required required">Email Address</label>
                            <input type="text" v-model="form.email_address" placeholder="Email Address" />
                            <span v-if="form.errors.email_address" class="error">{{ form.errors.email_address }}</span>
                        </div>

                        <div class="form-group half">
                            <label class="field-label-required required">Contact Number</label>
                            <input type="text" v-model="form.contact_no" placeholder="Contact Number" />
                            <span v-if="form.errors.contact_no" class="error">{{ form.errors.contact_no }}</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label class="field-label">School Graduated From</label>
                            <input
                                type="text"
                                v-model="form.school_graduated_from"
                                placeholder="School Graduated From"
                            />
                            <span v-if="form.errors.school_graduated_from" class="error">
                                {{ form.errors.school_graduated_from }}
                            </span>
                        </div>

                        <div class="form-group half">
                            <label class="field-label">Course</label>
                            <input type="text" v-model="form.course" placeholder="Course" />
                            <span v-if="form.errors.course" class="error">{{ form.errors.course }}</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label class="field-label">Year Attended</label>
                            <input type="text" v-model="form.year_attended" placeholder="e.g. 2007-2011, etc." />
                            <span v-if="form.errors.year_attended" class="error">{{ form.errors.year_attended }}</span>
                        </div>

                        <div class="form-group half">
                            <label class="field-label">Others</label>
                            <input type="text" v-model="form.others" placeholder="Indicate other schools and degrees taken." />
                            <span v-if="form.errors.others" class="error">{{ form.errors.others }}</span>
                        </div>
                    </div>

                    <div class="form-group">
    <label class="field-label-required required">Japanese Background</label>

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

<div class="form-group">
    <label :class="[isJapaneseLevelDisabled ? 'field-label' : 'field-label-required required']">
        Japanese Level
    </label>

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
    <label class="field-label">Japanese Background Remarks</label>
    <textarea v-model="form.background_remarks"></textarea>
    <span v-if="form.errors.background_remarks" class="error">
        {{ form.errors.background_remarks }}
    </span>
</div>

                    <div class="form-group">
                        <label class="field-label">Spouse Details</label>
                        <textarea v-model="form.spouse_details" placeholder="NAME - AGE - OCCUPATION (e.g. Juan Dela Cruz - 34 - Nurse)"></textarea>
                        <span v-if="form.errors.spouse_details" class="error">{{ form.errors.spouse_details }}</span>
                    </div>

                    <div class="form-group">
                        <label class="field-label">Children</label>
                        <input type="number" v-model="form.children" placeholder="Number of Children" />
                        <span v-if="form.errors.children" class="error">{{ form.errors.children }}</span>
                    </div>

                    <div class="form-group">
                        <label class="field-label">Father Details</label>
                        <textarea v-model="form.father_details" placeholder="NAME - AGE - OCCUPATION (e.g. Juan Dela Cruz - 34 - Nurse)"></textarea>
                        <span v-if="form.errors.father_details" class="error">{{ form.errors.father_details }}</span>
                    </div>

                    <div class="form-group">
                        <label class="field-label">Mother Details</label>
                        <textarea v-model="form.mother_details" placeholder="NAME - AGE - OCCUPATION (e.g. Juan Dela Cruz - 34 - Nurse)"></textarea>
                        <span v-if="form.errors.mother_details" class="error">{{ form.errors.mother_details }}</span>
                    </div>

                    <div class="form-group">
                        <label class="field-label">Sibling/s Details</label>
                        <textarea v-model="form.sibling_details" placeholder="NAME - AGE - OCCUPATION (e.g. Juan Dela Cruz - 34 - Nurse)"></textarea>
                        <span v-if="form.errors.sibling_details" class="error">{{ form.errors.sibling_details }}</span>
                    </div>

                    <div class="form-group">
                        <label class="field-label">Emergency Contact Name</label>
                        <input
                            type="text"
                            v-model="form.emergency_contact_name"
                            placeholder="Emergency Contact Name"
                        />
                        <span v-if="form.errors.emergency_contact_name" class="error">
                            {{ form.errors.emergency_contact_name }}
                        </span>
                    </div>

                    <div class="form-row">
                        <div class="form-group half">
                            <label class="field-label">Emergency Contact Number</label>
                            <input
                                type="text"
                                v-model="form.emergency_contact_number"
                                placeholder="Emergency Contact Number"
                            />
                            <span v-if="form.errors.emergency_contact_number" class="error">
                                {{ form.errors.emergency_contact_number }}
                            </span>
                        </div>

                        <div class="form-group half">
                            <label class="field-label">Emergency Contact Address</label>
                            <input
                                type="text"
                                v-model="form.emergency_contact_address"
                                placeholder="Emergency Contact Address"
                            />
                            <span v-if="form.errors.emergency_contact_address" class="error">
                                {{ form.errors.emergency_contact_address }}
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="field-label">Remarks</label>
                        <textarea v-model="form.remarks"></textarea>
                        <span v-if="form.errors.remarks" class="error">{{ form.errors.remarks }}</span>
                    </div>

                    <div class="form-actions">
                        <Link href="/intermediate/applicants" class="btn btn-secondary">Cancel</Link>
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

.name-fields {
    display: flex;
    gap: 1rem;
    flex-wrap: nowrap;
}

.name-fields .last-first {
    flex: 2 1 0;
    min-width: 0;
}

.name-fields .middle-name {
    flex: 1 1 0;
    min-width: 0;
}

.name-fields input {
    width: 100%;
    box-sizing: border-box;
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

.field-label {
    margin: 0 0 0.25rem 0;
    font-size: 0.875rem;
    color: #374151;
    line-height: 1.4;
}

.field-label-required {
    margin: 0 0 0.25rem 0;
    font-size: 0.875rem;
    font-weight: 600;
    color: #000000;
    line-height: 1.4;
}

.required::after {
    content: '*';
    margin-left: 0.25rem;
    color: #da0f19;
}

input:not([type="radio"]):not([type="checkbox"]),
select,
textarea {
    padding: 0.6rem 1rem;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 0.95rem;
    transition: border 0.15s ease, background 0.15s ease, color 0.15s ease;
}

input:not([type="radio"]):not([type="checkbox"]):focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: var(--ats-primary, #1C7BA5);
}

input:not([type="radio"]):not([type="checkbox"]):disabled,
select:disabled,
textarea:disabled {
    background-color: #d4d4d8;
    color: #6b7280;
    cursor: not-allowed;
    border-color: #9ca3af;
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
}

.form-actions button,
.form-actions a {
    flex: 0 0 auto;
    width: auto;
    white-space: nowrap;
}

button.btn-primary {
    padding: 0.5rem 1.2rem;
    font-size: 0.85rem;
    border-radius: 5px;
    font-weight: 500;
    border: none;
    background: var(--ats-primary, #1C7BA5);
    color: #fff;
    cursor: pointer;
    transition: background 0.15s ease;
}

button.btn-primary:hover:not(:disabled) {
    background: var(--ats-accent, #16658a);
}

button.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

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

select option[value=""] {
    color: #999;
    font-style: italic;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 3rem;
    z-index: 9999;
    animation: slide-down 0.25s ease-out;
}

.modal-content.modal-confirm {
    background: #fff;
    padding: 1.5rem 2rem;
    border-radius: 8px;
    max-width: 400px;
    width: 100%;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    position: relative;
}

.modal-text {
    font-size: 0.95rem;
    color: #374151;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.modal-actions .btn-primary {
    background-color: var(--ats-primary, #1C7BA5);
    color: #fff;
    padding: 0.5rem 1.2rem;
    border-radius: 5px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: background 0.15s ease;
}

.modal-actions .btn-primary:hover {
    background-color: var(--ats-accent, #16658a);
}

.modal-actions .btn-secondary {
    background-color: #f3f4f6;
    color: #374151;
    padding: 0.5rem 1.2rem;
    border-radius: 5px;
    font-weight: 500;
    border: 1px solid #d1d5db;
    cursor: pointer;
    transition: background 0.15s ease;
}

.modal-actions .btn-secondary:hover {
    background-color: #e5e7eb;
}

@keyframes slide-down {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
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
}

@media (max-width: 768px) {
    .form-row,
    .name-fields {
        flex-direction: column;
    }

    .form-group.half,
    .name-fields .last-first,
    .name-fields .middle-name {
        flex: 1 1 100%;
    }
}

.radio-group {
    display: flex;
    gap: 1.5rem;
    margin-top: 0.5rem;
    flex-wrap: wrap;
}

.radio-group label {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.9rem;
    cursor: pointer;
}

.radio-group input[type="radio"] {
    margin: 0;
    padding: 0;
    width: 16px;
    height: 16px;
    accent-color: var(--ats-primary, #1C7BA5);
    cursor: pointer;
    border: none;
    background: transparent;
    appearance: auto;
}
</style>
