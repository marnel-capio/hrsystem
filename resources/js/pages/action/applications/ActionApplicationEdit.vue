<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import { Head, Link, usePage, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const page = usePage()

const props = defineProps<{
    application: any,
    examVenues?: Record<number, string>,
    examResults?: Record<number, string>,
    examStatuses?: Record<number, string>,
    interviewResults?: Record<number, string>,
    interviewAppStatuses?: Record<number, string>,
    jobOfferStatuses?: Record<number, string>,
    applicationResultMap?: {
        exam?: Record<number, number>,
        initial_interview?: Record<number, number>,
        final_interview?: Record<number, number>,
    },
    applicationScoreRules?: {
        exam?: Record<string, any>,
        initial_interview?: Record<string, number>,
    },
    editableStages?: {
        exam: boolean,
        initial_interview: boolean,
        final_interview: boolean,
        job_offer: boolean,
        general: boolean,
        documents: boolean,
    },
    user_permissions?: number,
    user_id?: number,
    flash?: { error?: string, success?: string }
}>()

const editableStages = computed(() => props.editableStages || {
    exam: false,
    initial_interview: false,
    final_interview: false,
    job_offer: false,
    general: false,
    documents: false,
})

const canEditAnything = computed(() =>
    Object.values(editableStages.value).some(Boolean)
)

// Dropdown option arrays
const examVenues = ref<Array<{ value: number, label: string }>>([])
const examResults = ref<Array<{ value: number, label: string }>>([])
const examStatuses = ref<Array<{ value: number, label: string }>>([])
const interviewResults = ref<Array<{ value: number, label: string }>>([])
const interviewAppStatuses = ref<Array<{ value: number, label: string }>>([])
const jobOfferStatuses = ref<Array<{ value: number, label: string }>>([])

// File references
const resumeFile = ref<File | null>(null)
const torFile = ref<File | null>(null)
const pictureFile = ref<File | null>(null)

// Format date for datetime-local input
const formatDateForInput = (dateString: string | null) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toISOString().slice(0, 16)
}

// Form data
const form = useForm({
    upload_resume: props.application.upload_resume || '',
    upload_tor: props.application.upload_tor || '',
    upload_pic: props.application.upload_pic || '',
    exam_plan_date: formatDateForInput(props.application.exam_plan_date),
    exam_actual_date: formatDateForInput(props.application.exam_actual_date),
    exam_venue: props.application.exam_venue || '',
    exam_atpp_result: props.application.exam_atpp_result || '',
    exam_git_result: props.application.exam_git_result || '',
    exam_prg_result: props.application.exam_prg_result || '',
    exam_result: props.application.exam_result || '',
    exam_application_status: props.application.exam_application_status || '',
    exam_remarks: props.application.exam_remarks || '',

    initial_interview_plan_date: formatDateForInput(props.application.initial_interview_plan_date),
    initial_interview_actual_date: formatDateForInput(props.application.initial_interview_actual_date),
    initial_interview_venue: props.application.initial_interview_venue || '',
    initial_interview_final: props.application.initial_interview_final || '',
    initial_interview_result: props.application.initial_interview_result || '',
    initial_interview_application_status: props.application.initial_interview_application_status || '',
    initial_interview_remarks: props.application.initial_interview_remarks || '',

    final_interview_date: formatDateForInput(props.application.final_interview_date),
    final_interview_sf: props.application.final_interview_sf || '',
    final_interview_ib: props.application.final_interview_ib || '',
    final_interview_rv: props.application.final_interview_rv || '',
    final_interview_ma: props.application.final_interview_ma || '',
    final_interview_final: props.application.final_interview_final || '',
    final_interview_result: props.application.final_interview_result || '',
    final_interview_application_status: props.application.final_interview_application_status || '',
    final_interview_remarks: props.application.final_interview_remarks || '',

    job_offer_schedule: formatDateForInput(props.application.job_offer_schedule),
    job_offer_status: props.application.job_offer_status || '',
    job_offer_remarks: props.application.job_offer_remarks || '',
    remarks: props.application.remarks || ''
})

// Computed values
const currentApplicant = computed(() => {
    return props.application?.applicant || null
})

const examResultLabel = computed(() => {
    const selected = examResults.value.find(result => result.value === Number(form.exam_result))
    return selected ? selected.label : ''
})

const initialInterviewResultLabel = computed(() => {
    const selected = interviewResults.value.find(result => result.value === Number(form.initial_interview_result))
    return selected ? selected.label : ''
})

const finalInterviewResultLabel = computed(() => {
    const selected = interviewResults.value.find(result => result.value === Number(form.final_interview_result))
    return selected ? selected.label : ''
})

const selectedApplicantLabel = computed(() => {
    if (props.application.applicant) {
        return `${props.application.applicant.last_name}, ${props.application.applicant.first_name} ${props.application.applicant.middle_name || ''}`
    }
    return 'Select Applicant'
})

const batchName = computed(() => props.application.batch?.action_batch || 'N/A')

const getFileUrl = (filename: string | null) => {
    if (!filename) return null
    return `/storage/${filename}`
}

const existingResumeUrl = computed(() => getFileUrl(props.application.upload_resume || null))
const existingTorUrl = computed(() => getFileUrl(props.application.upload_tor || null))
const existingPictureUrl = computed(() => getFileUrl(props.application.upload_pic || null))

const existingResumeName = computed(() => props.application.upload_resume || '')
const existingTorName = computed(() => props.application.upload_tor || '')
const existingPictureName = computed(() => props.application.upload_pic || '')

const originalFiles = {
    resume: props.application.upload_resume,
    tor: props.application.upload_tor,
    picture: props.application.upload_pic,
}

// Error and success handling
const errorMessage = computed(() => (page.props.flash as any)?.error || '')
const showError = ref(false)
const successMessage = computed(() => (page.props.flash as any)?.success || '')
const showSuccess = ref(false)

// Helpers copied from register logic
function getExamCategory(applicant: any): 'young_it' | 'young_other' | 'adult' {
    if (!applicant) return 'young_other'

    const age = Number(applicant.age || 0)
    const degree = String(applicant.degree || applicant.course || '').toLowerCase().trim()

    if (age >= 25) {
        return 'adult'
    }

    const isTech =
        degree.includes('computer science') ||
        degree.includes('information technology') ||
        degree.includes('computer engineering') ||
        /\bcs\b/.test(degree) ||
        /\bit\b/.test(degree) ||
        /\bcpe\b/.test(degree)

    return isTech ? 'young_it' : 'young_other'
}

function getExamApplicationStatus(
    attp: number,
    git: number,
    prg: number,
    category: 'young_it' | 'young_other' | 'adult'
): string {
    const rules = props.applicationScoreRules?.exam?.[category]

    if (!rules) return ''

    const passed = rules.passed
    const p2 = rules.p2

    if (
        passed &&
        attp >= Number(passed.attp) &&
        git >= Number(passed.git) &&
        prg >= Number(passed.prg)
    ) {
        return '5'
    }

    if (
        p2 &&
        attp >= Number(p2.attp) &&
        git >= Number(p2.git) &&
        prg >= Number(p2.prg)
    ) {
        return '3'
    }

    return '6'
}

function getInitialInterviewApplicationStatus(score: number): string {
    const rules = props.applicationScoreRules?.initial_interview

    const failedMin = Number(rules?.failed_min ?? 4.0)
    const p2Min = Number(rules?.p2_min ?? 2.5)
    const passedMin = Number(rules?.passed_min ?? 2.0)

    if (score >= failedMin) return '5'
    if (score >= p2Min) return '4'
    if (score >= passedMin) return '3'
    return '2'
}

// Load dropdown options
onMounted(() => {
    if (props.examVenues) {
        examVenues.value = Object.entries(props.examVenues).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }))
    }

    if (props.examResults) {
        examResults.value = Object.entries(props.examResults).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }))
    }

    if (props.examStatuses) {
        examStatuses.value = Object.entries(props.examStatuses).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }))
    }

    if (props.interviewResults) {
        interviewResults.value = Object.entries(props.interviewResults).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }))
    }

    if (props.interviewAppStatuses) {
        interviewAppStatuses.value = Object.entries(props.interviewAppStatuses).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }))
    }

    if (props.jobOfferStatuses) {
        jobOfferStatuses.value = Object.entries(props.jobOfferStatuses).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }))
    }
})

// Flash message watchers
watch(errorMessage, (val) => {
    if (val) {
        showError.value = true
        setTimeout(() => showError.value = false, 5000)
    }
})

watch(successMessage, (val) => {
    if (val) {
        showSuccess.value = true
        setTimeout(() => showSuccess.value = false, 5000)
    }
})

// Auto-fill logic: Exam
watch(() => form.exam_plan_date, (newPlanDate) => {
    if (newPlanDate && !form.exam_atpp_result && !form.exam_git_result && !form.exam_prg_result) {
        form.exam_application_status = '1'
    }

    if (!newPlanDate && !form.exam_atpp_result && !form.exam_git_result && !form.exam_prg_result) {
        form.exam_application_status = ''
    }
})

watch(
    [
        () => form.exam_atpp_result,
        () => form.exam_git_result,
        () => form.exam_prg_result,
        () => currentApplicant.value,
        () => form.exam_plan_date,
    ],
    ([attp, git, prg, applicant, planDate]) => {
        const hasAllScores = !!attp && !!git && !!prg

        if (!hasAllScores) {
            form.exam_application_status = planDate ? '1' : ''
            return
        }

        if (!applicant) {
            form.exam_application_status = planDate ? '1' : ''
            return
        }

        const attpNum = Number(attp)
        const gitNum = Number(git)
        const prgNum = Number(prg)

        if (Number.isNaN(attpNum) || Number.isNaN(gitNum) || Number.isNaN(prgNum)) {
            form.exam_application_status = planDate ? '1' : ''
            return
        }

        const category = getExamCategory(applicant)
        form.exam_application_status = getExamApplicationStatus(attpNum, gitNum, prgNum, category)
    }
)

// Auto-fill logic: Initial Interview
watch(() => form.initial_interview_plan_date, (newPlanDate) => {
    if (newPlanDate && !form.initial_interview_final) {
        form.initial_interview_application_status = '1'
    }

    if (!newPlanDate && !form.initial_interview_final) {
        form.initial_interview_application_status = ''
    }
})

watch(
    [
        () => form.initial_interview_final,
        () => form.initial_interview_plan_date,
    ],
    ([score, planDate]) => {
        if (!score) {
            form.initial_interview_application_status = planDate ? '1' : ''
            return
        }

        const numericScore = Number(score)

        if (Number.isNaN(numericScore)) {
            form.initial_interview_application_status = planDate ? '1' : ''
            return
        }

        form.initial_interview_application_status = getInitialInterviewApplicationStatus(numericScore)
    }
)

// Auto-fill logic: Final Interview
watch(() => form.final_interview_date, (newPlanDate) => {
    if (newPlanDate && !form.final_interview_application_status) {
        form.final_interview_application_status = '1'
    }

    if (!newPlanDate && form.final_interview_application_status === '1') {
        form.final_interview_application_status = ''
    }
})

// Auto-fill logic: Job Offer
watch(() => form.job_offer_schedule, (newSchedule) => {
    if (newSchedule && !form.job_offer_status) {
        form.job_offer_status = '1'
    }

    if (!newSchedule && form.job_offer_status === '1') {
        form.job_offer_status = ''
    }
})

// Result mapping
watch(() => form.exam_application_status, (newStatus) => {
    if (!newStatus) {
        form.exam_result = ''
        return
    }

    const mappedResult = props.applicationResultMap?.exam?.[Number(newStatus)]
    form.exam_result = mappedResult ? String(mappedResult) : ''
})

watch(() => form.initial_interview_application_status, (newStatus) => {
    if (!newStatus) {
        form.initial_interview_result = ''
        return
    }

    const mappedResult = props.applicationResultMap?.initial_interview?.[Number(newStatus)]
    form.initial_interview_result = mappedResult ? String(mappedResult) : ''
})

watch(() => form.final_interview_application_status, (newStatus) => {
    if (!newStatus) {
        form.final_interview_result = ''
        return
    }

    const mappedResult = props.applicationResultMap?.final_interview?.[Number(newStatus)]
    form.final_interview_result = mappedResult ? String(mappedResult) : ''
})

// File handling
const handleResumeUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        const file = target.files[0]
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ]

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
        form.clearErrors('upload_resume')
    }
}

const handleTorUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        const file = target.files[0]
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png'
        ]

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
        form.clearErrors('upload_pic')
    }
}

const removeFile = (type: 'resume' | 'tor' | 'picture') => {
    switch(type) {
        case 'resume':
            resumeFile.value = null
            form.upload_resume = ''
            break
        case 'tor':
            torFile.value = null
            form.upload_tor = ''
            break
        case 'picture':
            pictureFile.value = null
            form.upload_pic = ''
            break
    }
}

// Submit function
function submit() {
    form.clearErrors()

    form.transform((data) => {
        const formData = new FormData()

        Object.keys(data).forEach((key) => {
            const value = data[key as keyof typeof data]

            // FILE FIELDS
            if (key === 'upload_resume') {
                if (resumeFile.value) {
                    formData.append('upload_resume', resumeFile.value)
                } else if (value === '' && originalFiles.resume) {
                    formData.append('upload_resume', '') // REMOVE
                }
                return
            }

            if (key === 'upload_tor') {
                if (torFile.value) {
                    formData.append('upload_tor', torFile.value)
                } else if (value === '' && originalFiles.tor) {
                    formData.append('upload_tor', '') // REMOVE
                }
                return
            }

            if (key === 'upload_pic') {
                if (pictureFile.value) {
                    formData.append('upload_pic', pictureFile.value)
                } else if (value === '' && originalFiles.picture) {
                    formData.append('upload_pic', '') // REMOVE
                }
                return
            }

            // NORMAL FIELDS
            if (value !== null && value !== undefined && value !== '') {
                formData.append(key, String(value))
            }
        })

        formData.append('_method', 'PUT')
        return formData as any
    })

    form.post(`/action/applications/${props.application.id}`, {
        preserveState: true,
        preserveScroll: true,
    })
}
</script>
<template>
    <AppLayout>
        <!-- Success/Error Alerts -->
        <div v-if="showSuccess" class="full-width-alert">
            <div class="alert-banner alert-success-banner">
                <div class="alert-body">{{ successMessage }}</div>
                <button type="button" class="close-btn" @click="showSuccess = false">×</button>
            </div>
        </div>
        <div v-if="showError" class="full-width-alert">
            <div class="alert-banner alert-error-banner">
                <div class="alert-body">{{ errorMessage }}</div>
                <button type="button" class="close-btn" @click="showError = false">×</button>
            </div>
        </div>

        <div class="page-container">
            <div class="page-header">
                <h2 class="page-title">Edit ACTION Application</h2>
                <p class="page-description">Editing application for {{ application.applicant?.last_name }}, {{ application.applicant?.first_name }}</p>
            </div>

            <div class="form-wrapper">
                <div class="form-card">
                    <form @submit.prevent="submit">
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <div class="section-header"><h3>Basic Information</h3></div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label required">ACTION Applicant</label>
                                    <input type="text" :value="selectedApplicantLabel" class="form-input" disabled />
                                </div>
                                <div class="form-field">
                                    <label class="field-label required">ACTION Batch</label>
                                    <input type="text" :value="batchName" class="form-input" disabled />
                                </div>
                            </div>
                        </div>

                        <!-- Upload Documents Section -->

<div class="form-section">
    <div class="section-header"><h3>Upload Documents</h3></div>

    <div class="form-grid grid-3">
        <div class="form-field">
            <label class="field-label">Upload Resume</label>

            <div v-if="form.upload_resume && !resumeFile" class="file-info">
                <span class="file-name">{{ form.upload_resume }}</span>
                <button
                    type="button"
                    @click="removeFile('resume')"
                    class="remove-file"
                    :disabled="!editableStages.documents"
                >×</button>
            </div>

            <div v-if="resumeFile" class="file-info">
                <span class="file-name">{{ form.upload_resume }}</span>
                <button
                    type="button"
                    @click="removeFile('resume')"
                    class="remove-file"
                    :disabled="!editableStages.documents"
                >×</button>
            </div>

            <input
                type="file"
                @change="handleResumeUpload"
                accept=".pdf,.doc,.docx"
                class="file-input-btn w-full"
                :disabled="form.processing || !editableStages.documents"
            />

            <span v-if="form.errors.upload_resume" class="error-message">
                {{ form.errors.upload_resume }}
            </span>

            <div v-if="existingResumeUrl && !resumeFile" class="file-preview">
                <a :href="existingResumeUrl" target="_blank" class="preview-link">Open current resume</a>
            </div>
        </div>

        <div class="form-field">
            <label class="field-label">Upload TOR</label>

            <div v-if="form.upload_tor && !torFile" class="file-info">
                <span class="file-name">{{ form.upload_tor }}</span>
                <button
                    type="button"
                    @click="removeFile('tor')"
                    class="remove-file"
                    :disabled="!editableStages.documents"
                >×</button>
            </div>

            <div v-if="torFile" class="file-info">
                <span class="file-name">{{ form.upload_tor }}</span>
                <button
                    type="button"
                    @click="removeFile('tor')"
                    class="remove-file"
                    :disabled="!editableStages.documents"
                >×</button>
            </div>

            <input
                type="file"
                @change="handleTorUpload"
                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                class="file-input-btn w-full"
                :disabled="form.processing || !editableStages.documents"
            />

            <span v-if="form.errors.upload_tor" class="error-message">
                {{ form.errors.upload_tor }}
            </span>

            <div v-if="existingTorUrl && !torFile" class="file-preview">
                <a :href="existingTorUrl" target="_blank" class="preview-link">Open current TOR</a>
            </div>
        </div>

        <div class="form-field">
            <label class="field-label">Upload 2x2 Pic</label>

            <div v-if="form.upload_pic && !pictureFile" class="file-info">
                <span class="file-name">{{ form.upload_pic }}</span>
                <button
                    type="button"
                    @click="removeFile('picture')"
                    class="remove-file"
                    :disabled="!editableStages.documents"
                >×</button>
            </div>

            <div v-if="pictureFile" class="file-info">
                <span class="file-name">{{ form.upload_pic }}</span>
                <button
                    type="button"
                    @click="removeFile('picture')"
                    class="remove-file"
                    :disabled="!editableStages.documents"
                >×</button>
            </div>

            <input
                type="file"
                @change="handlePictureUpload"
                accept="image/jpeg,image/png,image/jpg"
                class="file-input-btn w-full"
                :disabled="form.processing || !editableStages.documents"
            />

            <span v-if="form.errors.upload_pic" class="error-message">
                {{ form.errors.upload_pic }}
            </span>

            <div v-if="existingPictureUrl && !pictureFile" class="picture-preview">
                <img :src="existingPictureUrl" alt="Current picture" class="preview-image" />
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
            <input type="datetime-local" v-model="form.exam_plan_date" class="form-input" :disabled="!editableStages.exam" />
        </div>
        <div class="form-field">
            <label class="field-label">Exam Actual Date</label>
            <input type="datetime-local" v-model="form.exam_actual_date" class="form-input" :disabled="!editableStages.exam" />
        </div>
    </div>

    <div class="form-field">
        <label class="field-label">Exam Venue</label>
        <select v-model="form.exam_venue" class="form-select" :disabled="!editableStages.exam">
            <option value="">Select Venue</option>
            <option v-for="venue in examVenues" :key="venue.value" :value="venue.value">{{ venue.label }}</option>
        </select>
    </div>

    <div class="form-grid grid-3">
        <div class="form-field">
            <label class="field-label">ATPP Result</label>
            <input type="number" step="0.01" v-model="form.exam_atpp_result" class="form-input" :disabled="!editableStages.exam" />
        </div>
        <div class="form-field">
            <label class="field-label">GIT Result</label>
            <input type="number" step="0.01" v-model="form.exam_git_result" class="form-input" :disabled="!editableStages.exam" />
        </div>
        <div class="form-field">
            <label class="field-label">PRG Result</label>
            <input type="number" step="0.01" v-model="form.exam_prg_result" class="form-input" :disabled="!editableStages.exam" />
        </div>
    </div>

    <div class="form-grid grid-2">
        <div class="form-field">
            <label class="field-label">Exam Result</label>
            <input
                type="text"
                class="form-input"
                :value="examResultLabel || (!form.exam_application_status ? 'Auto-filled from application status' : '')"
                readonly
                :disabled="!editableStages.exam"
            />
        </div>
        <div class="form-field">
            <label class="field-label">Exam Application Status</label>
            <select v-model="form.exam_application_status" class="form-select" :disabled="!editableStages.exam">
                <option value="">Select Status</option>
                <option v-for="status in examStatuses" :key="status.value" :value="status.value">{{ status.label }}</option>
            </select>
        </div>
    </div>

    <div class="form-field">
        <label class="field-label">Exam Comments</label>
        <textarea v-model="form.exam_remarks" rows="3" class="form-textarea" :disabled="!editableStages.exam"></textarea>
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
            <input type="datetime-local" v-model="form.initial_interview_plan_date" class="form-input" :disabled="!editableStages.initial_interview" />
        </div>
        <div class="form-field">
            <label class="field-label">Actual Date</label>
            <input type="datetime-local" v-model="form.initial_interview_actual_date" class="form-input" :disabled="!editableStages.initial_interview" />
        </div>
    </div>

    <div class="form-field">
        <label class="field-label">Venue</label>
        <select v-model="form.initial_interview_venue" class="form-select" :disabled="!editableStages.initial_interview">
            <option value="">Select Venue</option>
            <option v-for="venue in examVenues" :key="venue.value" :value="venue.value">{{ venue.label }}</option>
        </select>
    </div>

    <div class="form-field">
        <label class="field-label">Initial Interview Final Score</label>
        <input type="number" step="0.01" v-model="form.initial_interview_final" class="form-input" :disabled="!editableStages.initial_interview" />
    </div>

    <div class="form-grid grid-2">
        <div class="form-field">
            <label class="field-label">Result</label>
            <input
                type="text"
                class="form-input"
                :value="initialInterviewResultLabel || (!form.initial_interview_application_status ? 'Auto-filled from application status' : '')"
                readonly
                :disabled="!editableStages.initial_interview"
            />
        </div>
        <div class="form-field">
            <label class="field-label">Application Status</label>
            <select v-model="form.initial_interview_application_status" class="form-select" :disabled="!editableStages.initial_interview">
                <option value="">Select Status</option>
                <option v-for="status in interviewAppStatuses" :key="status.value" :value="status.value">{{ status.label }}</option>
            </select>
        </div>
    </div>

    <div class="form-field">
        <label class="field-label">Initial Interview Comments</label>
        <textarea v-model="form.initial_interview_remarks" rows="3" class="form-textarea" :disabled="!editableStages.initial_interview"></textarea>
    </div>
</div>

                        <!-- Final Interview Section -->
<div class="form-section">
    <div class="section-header">
        <h3>Final Interview</h3>
    </div>

    <div class="form-field">
        <label class="field-label">Date</label>
        <input type="datetime-local" v-model="form.final_interview_date" class="form-input" :disabled="!editableStages.final_interview" />
    </div>

    <div class="form-grid grid-5">
        <div class="form-field">
            <label class="field-label">SF Score</label>
            <input type="number" step="0.01" v-model="form.final_interview_sf" class="form-input" :disabled="!editableStages.final_interview" />
        </div>
        <div class="form-field">
            <label class="field-label">IB Score</label>
            <input type="number" step="0.01" v-model="form.final_interview_ib" class="form-input" :disabled="!editableStages.final_interview" />
        </div>
        <div class="form-field">
            <label class="field-label">RV Score</label>
            <input type="number" step="0.01" v-model="form.final_interview_rv" class="form-input" :disabled="!editableStages.final_interview" />
        </div>
        <div class="form-field">
            <label class="field-label">MA Score</label>
            <input type="number" step="0.01" v-model="form.final_interview_ma" class="form-input" :disabled="!editableStages.final_interview" />
        </div>
        <div class="form-field">
            <label class="field-label">Final Score</label>
            <input type="number" step="0.01" v-model="form.final_interview_final" class="form-input" :disabled="!editableStages.final_interview" />
        </div>
    </div>

    <div class="form-grid grid-2">
        <div class="form-field">
            <label class="field-label">Result</label>
            <input
                type="text"
                class="form-input"
                :value="finalInterviewResultLabel || (!form.final_interview_application_status ? 'Auto-filled from application status' : '')"
                readonly
                :disabled="!editableStages.final_interview"
            />
        </div>
        <div class="form-field">
            <label class="field-label">Application Status</label>
            <select v-model="form.final_interview_application_status" class="form-select" :disabled="!editableStages.final_interview">
                <option value="">Select Status</option>
                <option v-for="status in interviewAppStatuses" :key="status.value" :value="status.value">{{ status.label }}</option>
            </select>
        </div>
    </div>

    <div class="form-field">
        <label class="field-label">Final Interview Comments</label>
        <textarea v-model="form.final_interview_remarks" rows="3" class="form-textarea" :disabled="!editableStages.final_interview"></textarea>
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
            <input type="datetime-local" v-model="form.job_offer_schedule" class="form-input" :disabled="!editableStages.job_offer" />
        </div>
        <div class="form-field">
            <label class="field-label">Status</label>
            <select v-model="form.job_offer_status" class="form-select" :disabled="!editableStages.job_offer">
                <option value="">Select Status</option>
                <option v-for="status in jobOfferStatuses" :key="status.value" :value="status.value">{{ status.label }}</option>
            </select>
        </div>
    </div>

    <div class="form-field">
        <label class="field-label">Job Offer Comments</label>
        <textarea v-model="form.job_offer_remarks" rows="3" class="form-textarea" :disabled="!editableStages.job_offer"></textarea>
    </div>
</div>

                        <!-- Additional Information Section -->
<div class="form-section">
    <div class="section-header">
        <h3>Additional Information</h3>
    </div>

    <div class="form-field">
        <label class="field-label">General Remarks</label>
        <textarea v-model="form.remarks" rows="3" class="form-textarea" :disabled="!editableStages.general"></textarea>
    </div>
</div>

                        <!-- Form Actions -->
<div class="form-actions">
    <Link :href="`/action/applications/${application.id}`" class="btn btn-secondary">Cancel</Link>
    <button
        v-if="canEditAnything"
        type="submit"
        :disabled="form.processing"
        class="btn btn-primary"
    >
        {{ form.processing ? 'Updating…' : 'Update' }}
    </button>
</div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

/* Page Layout */
.page-container {
    --ats-accent: #2563eb;
    --ats-border: #d1d5db;
    --ats-border-soft: #e5e7eb;
    --ats-text: #374151;
    --ats-text-muted: #6b7280;
    --ats-bg-muted: #f3f4f6;
    --ats-bg-subtle: #f9fafb;
    --ats-danger: #ef4444;

    width: 100%;
    max-width: 1400px;
    min-height: calc(100vh - 64px);
    margin: 0 auto;
    padding: 1.5rem;
    background: #f8f9fa;
}

.page-header {
    margin-bottom: 1.5rem;
}

.page-title {
    margin: 0;
    font-size: 1.875rem;
    font-weight: 600;
    color: #111827;
}

.page-description {
    margin: 0.25rem 0 0;
    font-size: 0.875rem;
    color: var(--ats-text-muted);
}

/* Alerts */
.full-width-alert {
    margin-bottom: 1rem;
}

.alert-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.875rem 1rem;
    border-radius: 0.5rem;
}

.alert-body {
    flex: 1;
    font-size: 0.875rem;
    line-height: 1.5;
}

.alert-success-banner {
    background: #dcfce7;
    border-left: 4px solid #22c55e;
    color: #166534;
}

.alert-error-banner {
    background: #fee2e2;
    border-left: 4px solid #ef4444;
    color: #991b1b;
}

.close-btn {
    padding: 0;
    border: none;
    background: none;
    color: inherit;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
}

.close-btn:hover {
    opacity: 0.7;
}

/* Form Card */
.form-wrapper {
    width: 100%;
}

.form-card {
    overflow: hidden;
    background: #ffffff;
    border-radius: 0.75rem;
    box-shadow:
        0 1px 3px 0 rgba(0, 0, 0, 0.1),
        0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.form-card form {
    padding: 2rem;
}

/* Sections */
.form-section {
    padding-bottom: 1.75rem;
    margin-bottom: 1.75rem;
    border-bottom: 1px solid var(--ats-border-soft);
}

.form-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.section-header {
    margin-bottom: 1rem;
}

.section-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
}

/* Grid */
.form-grid {
    display: grid;
    gap: 1rem 1.25rem;
}

.form-section > .form-grid + .form-grid,
.form-section > .form-grid + .form-field,
.form-section > .form-field + .form-grid,
.form-section > .form-field + .form-field {
    margin-top: 1rem;
}

.grid-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.grid-3 {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.grid-4 {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}

.grid-5 {
    grid-template-columns: repeat(5, minmax(0, 1fr));
}

/* Fields */
.form-field {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    min-width: 0;
}

.field-label {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--ats-text);
    line-height: 1.4;
}

.field-label.required::after {
    content: '*';
    margin-left: 0.25rem;
    color: var(--ats-danger);
}

/* Inputs */
.form-input,
.form-select,
.form-textarea,
.custom-select-trigger,
.dropdown-search-input,
.file-input-btn {
    width: 100%;
    min-height: 42px;
    padding: 0.625rem 0.875rem;
    font: inherit;
    font-size: 0.875rem;
    color: var(--ats-text);
    background: #ffffff;
    border: 1px solid var(--ats-border);
    border-radius: 0.5rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
    box-sizing: border-box;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus,
.dropdown-search-input:focus {
    outline: none;
    border-color: var(--ats-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input:hover:not(:focus),
.form-select:hover:not(:focus),
.form-textarea:hover:not(:focus),
.custom-select-trigger:hover:not(.is-disabled),
.dropdown-search-input:hover:not(:focus),
.file-input-btn:hover:not(:disabled) {
    border-color: #9ca3af;
}

.form-input:disabled,
.form-select:disabled,
.form-textarea:disabled,
.file-input-btn:disabled,
.custom-select-trigger.is-disabled {
    background: #f3f4f6;
    border-color: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
}

.form-textarea {
    min-height: 96px;
    resize: vertical;
}

/* Custom Searchable Select */
.custom-select-wrapper {
    position: relative;
    width: 100%;
}

.custom-select-trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
}

.custom-select-value {
    flex: 1;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.custom-select-trigger.is-disabled .custom-select-value {
    color: #9ca3af;
}

.custom-select-arrow {
    width: 0.9rem;
    height: 0.9rem;
    margin-left: 0.75rem;
    color: #111827;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

.custom-select-wrapper.is-open .custom-select-arrow {
    transform: rotate(180deg);
}

.custom-select-dropdown {
    position: absolute;
    top: calc(100% + 0.375rem);
    left: 0;
    right: 0;
    z-index: 50;
    display: flex;
    flex-direction: column;
    max-height: 300px;
    overflow: hidden;
    background: #ffffff;
    border: 1px solid var(--ats-border);
    border-radius: 0.5rem;
    box-shadow:
        0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.custom-select-trigger.is-disabled .custom-select-value,
.custom-select-trigger.is-disabled .custom-select-arrow {
    color: #9ca3af;
}

.dropdown-search {
    padding: 0.5rem;
    border-bottom: 1px solid var(--ats-border-soft);
    background: #ffffff;
}

.dropdown-search-input {
    min-height: 38px;
    padding: 0.5rem 0.75rem;
}

.dropdown-options-list {
    max-height: 250px;
    overflow-y: auto;
}

.dropdown-option-item,
.dropdown-empty-item {
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    line-height: 1.4;
}

.dropdown-option-item {
    cursor: pointer;
    transition: background-color 0.15s ease;
}

.dropdown-option-item:hover {
    background: var(--ats-bg-muted);
}

.dropdown-option-item.is-selected {
    color: #ffffff;
    background: var(--ats-primary);
}

.dropdown-option-item.is-selected:hover {
    background: var(--ats-accent);
}

.dropdown-empty-item {
    color: #9ca3af;
    text-align: center;
}

/* File Upload */
.file-input-btn {
    padding: 0.55rem 0.75rem;
    background: #ffffff;
    cursor: pointer;
}

.file-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.625rem 0.75rem;
    background: var(--ats-bg-subtle);
    border: 1px solid var(--ats-border-soft);
    border-radius: 0.5rem;
}

.file-name {
    flex: 1;
    min-width: 0;
    overflow: hidden;
    font-size: 0.875rem;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.remove-file {
    padding: 0;
    margin-left: 0.25rem;
    color: var(--ats-danger);
    font-size: 1.25rem;
    line-height: 1;
    background: none;
    border: none;
    cursor: pointer;
}

.remove-file:hover {
    opacity: 0.7;
}

.file-preview,
.picture-preview {
    margin-top: 0.25rem;
}

.preview-link {
    font-size: 0.8125rem;
    color: var(--ats-primary);
    text-decoration: none;
}

.preview-link:hover {
    text-decoration: underline;
}

.preview-image {
    display: block;
    max-width: 100px;
    max-height: 100px;
    object-fit: cover;
    border: 1px solid var(--ats-border);
    border-radius: 0.5rem;
}

/* Validation */
.error-message {
    margin-top: 0.125rem;
    font-size: 0.75rem;
    line-height: 1.4;
    color: #ef4444;
}

/* Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.75rem;
    margin-top: 2rem;
    padding-top: 0.5rem;
}

.form-actions button,
.form-actions a {
    flex: 0 0 auto;
    width: auto;
    white-space: nowrap;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    min-height: 40px;
    padding: 0.5rem 1.2rem;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-primary {
    color: #ffffff;
    background: var(--ats-primary);
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

.btn-secondary,
a.btn-secondary {
    color: var(--ats-text);
    background: #ffffff;
    border: 1px solid var(--ats-border);
}

.btn-secondary:hover,
a.btn-secondary:hover {
    background: var(--ats-bg-subtle);
    border-color: #9ca3af;
}

/* Spinner */
.btn-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Responsive */
@media (max-width: 1024px) {
    .grid-4,
    .grid-5 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .page-container {
        padding: 1rem;
    }

    .page-header {
        margin-bottom: 1.25rem;
    }

    .form-card form {
        padding: 1.25rem;
    }

    .grid-2,
    .grid-3,
    .grid-4,
    .grid-5 {
        grid-template-columns: 1fr;
    }

    .form-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
    }

    .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }


}

/* Print */
@media print {
    .form-actions,
    .full-width-alert,
    .remove-file {
        display: none;
    }

    .form-card {
        box-shadow: none;
    }

    .form-input,
    .form-select,
    .form-textarea,
    .custom-select-trigger,
    .file-input-btn {
        background: #ffffff;
        border: 1px solid #dddddd;
    }
}
</style>
