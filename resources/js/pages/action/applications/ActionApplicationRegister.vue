<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { ref, watch, onMounted } from 'vue'
import axios from 'axios';

const props = defineProps<{
    actionBatches?: Record<number, string>,
    examVenues?: Record<number, string>,
    examResults?: Record<number, string>,
    examStatuses?: Record<number, string>,
    interviewResults?: Record<number, string>,
    interviewAppStatuses?: Record<number, string>,
    jobOfferStatuses?: Record<number, string>,
    flash?: {
        error?: string
    }
}>()

// Convert props to arrays for dropdowns
const actionBatches = ref<Array<{value: number, label: string}>>([])
const examVenues = ref<Array<{value: number, label: string}>>([])
const examResults = ref<Array<{value: number, label: string}>>([])
const examStatuses = ref<Array<{value: number, label: string}>>([])
const interviewResults = ref<Array<{value: number, label: string}>>([])
const interviewAppStatuses = ref<Array<{value: number, label: string}>>([])
const jobOfferStatuses = ref<Array<{value: number, label: string}>>([])

// Eligible applicants (loaded dynamically)
const actionApplicants = ref<Array<{value: number, label: string}>>([])

// Initialize dropdowns
onMounted(() => {
    if (props.actionBatches) {
        actionBatches.value = Object.entries(props.actionBatches).map(([value, label]) => ({
            value: Number(value),
            label: String(label)
        }))
    }
    if (props.examVenues) {
        examVenues.value = Object.entries(props.examVenues).map(([value, label]) => ({
            value: Number(value),
            label: String(label)
        }))
    }
    if (props.examResults) {
        examResults.value = Object.entries(props.examResults).map(([value, label]) => ({
            value: Number(value),
            label: String(label)
        }))
    }
    if (props.examStatuses) {
        examStatuses.value = Object.entries(props.examStatuses).map(([value, label]) => ({
            value: Number(value),
            label: String(label)
        }))
    }
    if (props.interviewResults) {
        interviewResults.value = Object.entries(props.interviewResults).map(([value, label]) => ({
            value: Number(value),
            label: String(label)
        }))
    }
    if (props.interviewAppStatuses) {
        interviewAppStatuses.value = Object.entries(props.interviewAppStatuses).map(([value, label]) => ({
            value: Number(value),
            label: String(label)
        }))
    }
    if (props.jobOfferStatuses) {
        jobOfferStatuses.value = Object.entries(props.jobOfferStatuses).map(([value, label]) => ({
            value: Number(value),
            label: String(label)
        }))
    }
})

// File references
const resumeFile = ref<File | null>(null)
const torFile = ref<File | null>(null)
const pictureFile = ref<File | null>(null)

// File preview URLs
const resumePreview = ref<string | null>(null)
const torPreview = ref<string | null>(null)
const picturePreview = ref<string | null>(null)

// Form data - matches database columns exactly
const form = useForm({
    action_applicant_id: '',
    action_batch_id: '',
    upload_resume: '',
    upload_tor: '',
    upload_pic: '',
    exam_plan_date: '',
    exam_actual_date: '',
    exam_venue: '',
    exam_atpp_result: '',
    exam_git_result: '',
    exam_prg_result: '',
    exam_result: '',
    exam_application_status: '',
    exam_remarks: '',
    initial_interview_plan_date: '',
    initial_interview_actual_date: '',
    initial_interview_venue: '',
    initial_interview_final: '',
    initial_interview_result: '',
    initial_interview_application_status: '',
    initial_interview_remarks: '',
    final_interview_date: '',
    final_interview_sf: '',
    final_interview_ib: '',
    final_interview_rv: '',
    final_interview_ma: '',
    final_interview_final: '',
    final_interview_result: '',
    final_interview_application_status: '',
    final_interview_remarks: '',
    job_offer_schedule: '',
    job_offer_status: '',
    job_offer_remarks: '',
    remarks: ''
})

// Validation rules
const rules = {
    action_applicant_id: (val: string) => !!val || 'ACTION Applicant is required',
    action_batch_id: (val: string) => !!val || 'ACTION Batch is required',
}

function validateField(field: keyof typeof rules) {
    const value = (form as any)[field];
    const rule = rules[field];
    const result = rule(value);
    form.setError(field, result === true ? '' : result);
}

watch(() => form.action_applicant_id, () => validateField('action_applicant_id'));
watch(() => form.action_batch_id, () => validateField('action_batch_id'));

// Watch for batch selection to load eligible applicants
watch(() => form.action_batch_id, async (newBatchId) => {
    if (newBatchId) {
        try {
            const response = await axios.get(`/action/applications/eligible-applicants/${newBatchId}`);
            actionApplicants.value = Object.entries(response.data).map(([value, label]) => ({
                value: Number(value),
                label: String(label)
            }));
            form.action_applicant_id = ''; // Reset applicant selection
        } catch (error) {
            console.error('Failed to load eligible applicants:', error);
        }
    }
});

// Watch for applicant selection to check eligibility
watch(() => form.action_applicant_id, async (newApplicantId) => {
    if (newApplicantId && form.action_batch_id) {
        try {
            const response = await axios.post('/action/applications/check-eligibility', {
                action_applicant_id: newApplicantId,
                action_batch_id: form.action_batch_id
            });

            if (!response.data.eligible) {
                errorMessage.value = 'This applicant cannot apply at this time. A previous application from the last 6 months shows a failed status.';
                showError.value = true;
                form.action_applicant_id = ''; // Clear selection
                setTimeout(() => {
                    showError.value = false;
                }, 5000);
            }
        } catch (error) {
            console.error('Failed to check eligibility:', error);
        }
    }
});

const showError = ref(false)
const errorMessage = ref<string | null>(null)

// File handling functions
const handleResumeUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        const file = target.files[0]
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        if (!allowedTypes.includes(file.type)) {
            form.setError('upload_resume', 'Please upload a PDF or Word document')
            return
        }
        if (file.size > 5 * 1024 * 1024) {
            form.setError('upload_resume', 'File size must be less than 5MB')
            return
        }

        resumeFile.value = file
        form.upload_resume = file.name

        if (file.type === 'application/pdf') {
            resumePreview.value = URL.createObjectURL(file)
        } else {
            resumePreview.value = null
        }

        form.clearErrors('upload_resume')
    }
}

const handleTorUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        const file = target.files[0]
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png']
        if (!allowedTypes.includes(file.type)) {
            form.setError('upload_tor', 'Please upload a PDF, Word document, or image')
            return
        }
        if (file.size > 5 * 1024 * 1024) {
            form.setError('upload_tor', 'File size must be less than 5MB')
            return
        }

        torFile.value = file
        form.upload_tor = file.name

        if (file.type === 'application/pdf') {
            torPreview.value = URL.createObjectURL(file)
        } else {
            torPreview.value = null
        }

        form.clearErrors('upload_tor')
    }
}

const handlePictureUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        const file = target.files[0]
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']
        if (!allowedTypes.includes(file.type)) {
            form.setError('upload_pic', 'Please upload a JPG or PNG image')
            return
        }
        if (file.size > 2 * 1024 * 1024) {
            form.setError('upload_pic', 'File size must be less than 2MB')
            return
        }

        pictureFile.value = file
        form.upload_pic = file.name
        picturePreview.value = URL.createObjectURL(file)
        form.clearErrors('upload_pic')
    }
}

const removeFile = (type: 'resume' | 'tor' | 'picture') => {
    switch(type) {
        case 'resume':
            resumeFile.value = null
            form.upload_resume = ''
            if (resumePreview.value) {
                URL.revokeObjectURL(resumePreview.value)
                resumePreview.value = null
            }
            break
        case 'tor':
            torFile.value = null
            form.upload_tor = ''
            if (torPreview.value) {
                URL.revokeObjectURL(torPreview.value)
                torPreview.value = null
            }
            break
        case 'picture':
            pictureFile.value = null
            form.upload_pic = ''
            if (picturePreview.value) {
                URL.revokeObjectURL(picturePreview.value)
                picturePreview.value = null
            }
            break
    }
}

async function submit() {
    // Validate required fields
    if (!form.action_applicant_id) {
        form.setError('action_applicant_id', 'ACTION Applicant is required');
        return;
    }
    if (!form.action_batch_id) {
        form.setError('action_batch_id', 'ACTION Batch is required');
        return;
    }

    // Create FormData for file upload
    const formData = new FormData()

    // Append all form fields
    Object.keys(form.data()).forEach(key => {
        if (key !== 'upload_resume' && key !== 'upload_tor' && key !== 'upload_pic') {
            const value = (form as any)[key];
            if (value !== null && value !== undefined && value !== '') {
                formData.append(key, value)
            }
        }
    })

    // Append files
    if (resumeFile.value) {
        formData.append('upload_resume', resumeFile.value)
    }
    if (torFile.value) {
        formData.append('upload_tor', torFile.value)
    }
    if (pictureFile.value) {
        formData.append('upload_pic', pictureFile.value)
    }

    // Submit with FormData
    form.post('/action/applications', {
        data: formData,
        headers: {
            'Content-Type': 'multipart/form-data'
        },
        onError: (errors) => {
            console.error('Submission errors:', errors);
            if (errors.eligibility) {
                errorMessage.value = errors.eligibility;
                showError.value = true;
                setTimeout(() => {
                    showError.value = false;
                }, 5000);
            }
        }
    });
}
</script>

<template>
    <AppLayout>
        <!-- Alert Banner -->
        <div v-if="showError" class="full-width-alert">
            <div class="alert-banner alert-error-banner">
                <div class="alert-body">{{ errorMessage }}</div>
                <button type="button" class="close-btn" @click="showError = false">×</button>
            </div>
        </div>

        <div class="page-container">
            <div class="page-header">
                <h2 class="page-title">Create ACTION Application</h2>
            </div>

            <div class="form-wrapper">
                <div class="form-card">
                    <form @submit.prevent="submit">
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>Basic Information</h3>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label required">ACTION Applicant</label>
                                    <select
                                        v-model="form.action_applicant_id"
                                        class="form-select"
                                        :disabled="!form.action_batch_id"
                                    >
                                        <option disabled value="">Select Applicant</option>
                                        <option v-for="applicant in actionApplicants" :key="applicant.value" :value="applicant.value">
                                            {{ applicant.label }}
                                        </option>
                                    </select>
                                    <span v-if="form.errors.action_applicant_id" class="error-message">{{ form.errors.action_applicant_id }}</span>
                                </div>
                                <div class="form-field">
                                    <label class="field-label required">ACTION Batch</label>
                                    <select v-model="form.action_batch_id" class="form-select">
                                        <option disabled value="">Select Batch</option>
                                        <option v-for="batch in actionBatches" :key="batch.value" :value="batch.value">
                                            {{ batch.label }}
                                        </option>
                                    </select>
                                    <span v-if="form.errors.action_batch_id" class="error-message">{{ form.errors.action_batch_id }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Documents Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>Upload Documents</h3>
                            </div>
                            <div class="form-grid grid-3">
                                <!-- Resume Upload -->
                                <div class="form-field">
                                    <label class="field-label">Upload Resume</label>
                                    <div class="file-upload-container">
                                        <input
                                            type="file"
                                            @change="handleResumeUpload"
                                            accept=".pdf,.doc,.docx"
                                            class="file-input"
                                            :disabled="form.processing"
                                        />
                                        <div class="file-upload-button" @click="$event => ($event.target as HTMLElement).previousElementSibling?.click()">
                                            <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                            </svg>
                                            <span>Choose File</span>
                                        </div>
                                        <div v-if="form.upload_resume" class="file-info">
                                            <span class="file-name">{{ form.upload_resume }}</span>
                                            <button type="button" @click="removeFile('resume')" class="remove-file" title="Remove file">×</button>
                                        </div>
                                        <div v-else class="file-info empty">
                                            <span class="file-name">No file chosen</span>
                                        </div>
                                    </div>
                                    <span v-if="form.errors.upload_resume" class="error-message">{{ form.errors.upload_resume }}</span>
                                </div>

                                <!-- TOR Upload -->
                                <div class="form-field">
                                    <label class="field-label">Upload TOR</label>
                                    <div class="file-upload-container">
                                        <input
                                            type="file"
                                            @change="handleTorUpload"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                            class="file-input"
                                            :disabled="form.processing"
                                        />
                                        <div class="file-upload-button" @click="$event => ($event.target as HTMLElement).previousElementSibling?.click()">
                                            <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                            </svg>
                                            <span>Choose File</span>
                                        </div>
                                        <div v-if="form.upload_tor" class="file-info">
                                            <span class="file-name">{{ form.upload_tor }}</span>
                                            <button type="button" @click="removeFile('tor')" class="remove-file" title="Remove file">×</button>
                                        </div>
                                        <div v-else class="file-info empty">
                                            <span class="file-name">No file chosen</span>
                                        </div>
                                    </div>
                                    <span v-if="form.errors.upload_tor" class="error-message">{{ form.errors.upload_tor }}</span>
                                </div>

                                <!-- Picture Upload -->
                                <div class="form-field">
                                    <label class="field-label">Upload 2x2 Pic</label>
                                    <div class="file-upload-container">
                                        <input
                                            type="file"
                                            @change="handlePictureUpload"
                                            accept="image/jpeg,image/png,image/jpg"
                                            class="file-input"
                                            :disabled="form.processing"
                                        />
                                        <div class="file-upload-button" @click="$event => ($event.target as HTMLElement).previousElementSibling?.click()">
                                            <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                            </svg>
                                            <span>Choose File</span>
                                        </div>
                                        <div v-if="form.upload_pic" class="file-info">
                                            <span class="file-name">{{ form.upload_pic }}</span>
                                            <button type="button" @click="removeFile('picture')" class="remove-file" title="Remove file">×</button>
                                        </div>
                                        <div v-else class="file-info empty">
                                            <span class="file-name">No file chosen</span>
                                        </div>
                                    </div>
                                    <span v-if="form.errors.upload_pic" class="error-message">{{ form.errors.upload_pic }}</span>
                                    <div v-if="picturePreview" class="picture-preview">
                                        <img :src="picturePreview" alt="Picture preview" class="preview-image" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Exam Details Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>Exam Details</h3>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label">Exam Plan Date</label>
                                    <input type="datetime-local" v-model="form.exam_plan_date" class="form-input" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Exam Actual Date</label>
                                    <input type="datetime-local" v-model="form.exam_actual_date" class="form-input" />
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="field-label">Exam Venue</label>
                                <select v-model="form.exam_venue" class="form-select">
                                    <option value="">Select Venue</option>
                                    <option v-for="venue in examVenues" :key="venue.value" :value="venue.value">
                                        {{ venue.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-grid grid-3">
                                <div class="form-field">
                                    <label class="field-label">ATTP Result</label>
                                    <input type="number" step="0.01" v-model="form.exam_atpp_result" placeholder="0.00" class="form-input" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label">GIT Result</label>
                                    <input type="number" step="0.01" v-model="form.exam_git_result" placeholder="0.00" class="form-input" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label">PRG Result</label>
                                    <input type="number" step="0.01" v-model="form.exam_prg_result" placeholder="0.00" class="form-input" />
                                </div>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label">Exam Result</label>
                                    <select v-model="form.exam_result" class="form-select">
                                        <option value="">Select Result</option>
                                        <option v-for="result in examResults" :key="result.value" :value="result.value">
                                            {{ result.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Exam Application Status</label>
                                    <select v-model="form.exam_application_status" class="form-select">
                                        <option value="">Select Status</option>
                                        <option v-for="status in examStatuses" :key="status.value" :value="status.value">
                                            {{ status.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="field-label">Exam Comments</label>
                                <textarea v-model="form.exam_remarks" placeholder="(recruiter only)" rows="3" class="form-textarea"></textarea>
                                <span v-if="form.errors.exam_remarks" class="error-message">{{ form.errors.exam_remarks }}</span>
                            </div>
                        </div>

                        <!-- Initial Interview Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>Initial Interview</h3>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label">Plan Date</label>
                                    <input type="datetime-local" v-model="form.initial_interview_plan_date" class="form-input" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Actual Date</label>
                                    <input type="datetime-local" v-model="form.initial_interview_actual_date" class="form-input" />
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="field-label">Venue</label>
                                <select v-model="form.initial_interview_venue" class="form-select">
                                    <option value="">Select Venue</option>
                                    <option v-for="venue in examVenues" :key="venue.value" :value="venue.value">
                                        {{ venue.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label class="field-label">Initial Interview Final Score</label>
                                <input type="number" step="0.01" v-model="form.initial_interview_final" placeholder="0.00" class="form-input" />
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label">Result</label>
                                    <select v-model="form.initial_interview_result" class="form-select">
                                        <option value="">Select Result</option>
                                        <option v-for="result in interviewResults" :key="result.value" :value="result.value">
                                            {{ result.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Application Status</label>
                                    <select v-model="form.initial_interview_application_status" class="form-select">
                                        <option value="">Select Status</option>
                                        <option v-for="status in interviewAppStatuses" :key="status.value" :value="status.value">
                                            {{ status.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="field-label">Initial Interview Comments</label>
                                <textarea v-model="form.initial_interview_remarks" placeholder="(recruiter only)" rows="3" class="form-textarea"></textarea>
                                <span v-if="form.errors.initial_interview_remarks" class="error-message">{{ form.errors.initial_interview_remarks }}</span>
                            </div>
                        </div>

                        <!-- Final Interview Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>Final Interview</h3>
                            </div>
                            <div class="form-field">
                                <label class="field-label">Date</label>
                                <input type="datetime-local" v-model="form.final_interview_date" class="form-input" />
                            </div>
                            <div class="form-grid grid-5">
                                <div class="form-field">
                                    <label class="field-label">SF Score</label>
                                    <input type="number" step="0.01" v-model="form.final_interview_sf" placeholder="0.00" class="form-input" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label">IB Score</label>
                                    <input type="number" step="0.01" v-model="form.final_interview_ib" placeholder="0.00" class="form-input" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label">RV Score</label>
                                    <input type="number" step="0.01" v-model="form.final_interview_rv" placeholder="0.00" class="form-input" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label">MA Score</label>
                                    <input type="number" step="0.01" v-model="form.final_interview_ma" placeholder="0.00" class="form-input" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Final Score</label>
                                    <input type="number" step="0.01" v-model="form.final_interview_final" placeholder="0.00" class="form-input" />
                                </div>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label">Result</label>
                                    <select v-model="form.final_interview_result" class="form-select">
                                        <option value="">Select Result</option>
                                        <option v-for="result in interviewResults" :key="result.value" :value="result.value">
                                            {{ result.label }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Application Status</label>
                                    <select v-model="form.final_interview_application_status" class="form-select">
                                        <option value="">Select Status</option>
                                        <option v-for="status in interviewAppStatuses" :key="status.value" :value="status.value">
                                            {{ status.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="field-label">Final Interview Comments</label>
                                <textarea v-model="form.final_interview_remarks" placeholder="(recruiter only)" rows="3" class="form-textarea"></textarea>
                                <span v-if="form.errors.final_interview_remarks" class="error-message">{{ form.errors.final_interview_remarks }}</span>
                            </div>
                        </div>

                        <!-- Job Offer Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>Job Offer</h3>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label">Schedule</label>
                                    <input type="datetime-local" v-model="form.job_offer_schedule" class="form-input" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Status</label>
                                    <select v-model="form.job_offer_status" class="form-select">
                                        <option value="">Select Status</option>
                                        <option v-for="status in jobOfferStatuses" :key="status.value" :value="status.value">
                                            {{ status.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="field-label">Job Offer Comments</label>
                                <textarea v-model="form.job_offer_remarks" placeholder="(recruiter only)" rows="3" class="form-textarea"></textarea>
                                <span v-if="form.errors.job_offer_remarks" class="error-message">{{ form.errors.job_offer_remarks }}</span>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>Additional Information</h3>
                            </div>
                            <div class="form-field">
                                <label class="field-label">General Remarks</label>
                                <textarea v-model="form.remarks" placeholder="General remarks" rows="3" class="form-textarea"></textarea>
                                <span v-if="form.errors.remarks" class="error-message">{{ form.errors.remarks }}</span>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <Link href="/action/applications" class="btn btn-secondary">Cancel</Link>
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">
                                {{ form.processing ? 'Creating…' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* CSS Variables */
:root {
    --ats-primary: #3b82f6;
    --ats-accent: #2563eb;
}

/* Page Layout */
.page-container {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
    background: #f8f9fa;
    min-height: calc(100vh - 64px);
}

/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.page-description {
    color: #6b7280;
    font-size: 0.875rem;
    margin: 0;
}

/* Form Card */
.form-wrapper {
    width: 100%;
}

.form-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.form-card form {
    padding: 2rem;
}

/* Form Sections */
.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.section-header {
    margin-bottom: 1.5rem;
}

.section-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

/* Form Grid System */
.form-grid {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.grid-2 {
    grid-template-columns: repeat(2, 1fr);
}

.grid-3 {
    grid-template-columns: repeat(3, 1fr);
}

.grid-4 {
    grid-template-columns: repeat(4, 1fr);
}

.grid-5 {
    grid-template-columns: repeat(5, 1fr);
}

/* Form Fields */
.form-field {
    display: flex;
    flex-direction: column;
}

.field-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.field-label.required::after {
    content: '*';
    color: #ef4444;
    margin-left: 0.25rem;
}

/* Form Inputs */
.form-input,
.form-select,
.form-textarea {
    padding: 0.625rem 0.875rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    font-family: inherit;
    background: white;
    width: 100%;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--ats-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input:hover:not(:focus),
.form-select:hover:not(:focus),
.form-textarea:hover:not(:focus) {
    border-color: #9ca3af;
}

.form-input:disabled,
.form-select:disabled,
.form-textarea:disabled {
    background: #f3f4f6;
    cursor: not-allowed;
    color: #9ca3af;
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

/* File Upload Styles */
.file-upload-container {
    position: relative;
    width: 100%;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.file-upload-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s ease;
    width: auto;
    margin-bottom: 0.5rem;
}

.file-upload-button:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
}

.upload-icon {
    width: 1.25rem;
    height: 1.25rem;
}

.file-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0.75rem;
    background: #f9fafb;
    border-radius: 0.5rem;
    font-size: 0.875rem;
}

.file-info.empty {
    color: #9ca3af;
}

.file-name {
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.remove-file {
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: #ef4444;
    padding: 0 0.25rem;
    line-height: 1;
    margin-left: 0.5rem;
}

.remove-file:hover {
    opacity: 0.7;
}

.file-preview {
    margin-top: 0.5rem;
}

.preview-link {
    font-size: 0.75rem;
    color: var(--ats-primary);
    text-decoration: none;
}

.preview-link:hover {
    text-decoration: underline;
}

.picture-preview {
    margin-top: 0.5rem;
}

.preview-image {
    max-width: 100px;
    max-height: 100px;
    border-radius: 0.5rem;
    border: 1px solid #d1d5db;
    object-fit: cover;
}

/* Error Messages */
.error-message {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.375rem;
}

/* Form Actions */
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

/* Buttons - Unified Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1.2rem;
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
    gap: 0.5rem;
}

/* Primary Button */
.btn-primary {
    background: var(--ats-primary);
    color: white;
    border: none;
}

.btn-primary:hover:not(:disabled) {
    background: var(--ats-accent);
    transform: translateY(-1px);
}

.btn-primary:active:not(:disabled) {
    transform: translateY(0);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Secondary Button - For both button and Link components */
.btn-secondary,
a.btn-secondary {
    background: white;
    color: #374151;
    border: 1px solid #d1d5db;
}

.btn-secondary:hover,
a.btn-secondary:hover {
    background: #f9fafb;
    border-color: #9ca3af;
}

/* Button Spinner */
.btn-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Alert Banner Styles */
.full-width-alert {
    margin-bottom: 1rem;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.alert-banner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.alert-error-banner {
    background: #fee2e2;
    border-left: 4px solid #ef4444;
    color: #991b1b;
}

.alert-body {
    flex: 1;
    font-size: 0.875rem;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #991b1b;
    padding: 0 0.5rem;
    line-height: 1;
}

.close-btn:hover {
    opacity: 0.7;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .grid-4,
    .grid-5 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .page-container {
        padding: 1rem;
    }

    .form-card form {
        padding: 1.5rem;
    }

    .grid-2,
    .grid-3,
    .grid-4,
    .grid-5 {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn,
    .btn-primary,
    .btn-secondary,
    a.btn-secondary {
        width: 100%;
    }

    .form-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
    }
}

/* Print Styles */
@media print {
    .form-actions,
    .full-width-alert,
    .file-upload-button,
    .remove-file {
        display: none;
    }

    .form-card {
        box-shadow: none;
    }

    .form-input,
    .form-select,
    .form-textarea {
        border: 1px solid #ddd;
        background: white;
    }
}
</style>
