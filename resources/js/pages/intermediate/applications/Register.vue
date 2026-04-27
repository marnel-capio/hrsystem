<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, usePage, Link } from '@inertiajs/vue3'
import { ref, watch, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const page = usePage<{
    positions?: Record<number, string>
    sourceProjects?: Array<{ value: number, label: string }>
    examVenues?: Record<number, string>
    examStatuses?: Record<number, string>
    hrStatuses?: Record<number, string>
    buStatuses?: Record<number, string>
    finalStatuses?: Record<number, string>
    jobOfferStatuses?: Record<number, string>
    applicationResultMap?: {
        exam?: Record<number, number>
        hr_interview?: Record<number, number>
        bu_interview?: Record<number, number>
        final_interview?: Record<number, number>
    }
    applicationScoreRules?: {
        exam?: Record<string, any>
    }
    //canEditFinalInterviewDecision?: boolean
    user_permissions?: number
    user_id?: number
    flash?: {
        error?: string
        success?: string
    }
    intermediateApplicants?: Array<{
        id: number
        name: string
        email_address: string
        work_experiences?: string[]
        skills?: string[]
    }>
}>()

const props = defineProps<{
    fyWeeks?: Record<number, string>
    positions?: Record<number, string>
    sourceProjects?: Array<{ value: number, label: string }>
    examVenues?: Record<number, string>
    examStatuses?: Record<number, string>
    hrStatuses?: Record<number, string>
    buStatuses?: Record<number, string>
    finalStatuses?: Record<number, string>
    jobOfferStatuses?: Record<number, string>
    applicationResultMap?: {
        exam?: Record<number, number>
        initial_interview?: Record<number, number>
        final_interview?: Record<number, number>
    }
    applicationScoreRules?: {
        exam?: Record<string, any>
    }
    //canEditFinalInterviewDecision?: boolean
    user_permissions?: number
    user_id?: number
    flash?: {
        error?: string
        success?: string
    }
    intermediateApplicants?: Array<{
        id: number
        name: string
        email_address: string
        work_experiences?: string[]
        skills?: string[]
    }>
}>()

// Dropdown options
const sourceProjects = ref<Array<{ value: number, label: string }>>([])
const examVenues = ref<Array<{ value: number, label: string }>>([])
const examStatuses = ref<Array<{ value: number, label: string }>>([])
const hrStatuses = ref<Array<{ value: number, label: string }>>([])
const buStatuses = ref<Array<{ value: number, label: string }>>([])
const finalStatuses = ref<Array<{ value: number, label: string }>>([])
const jobOfferStatuses = ref<Array<{ value: number, label: string }>>([])
const currentFyWeek = ref<number | null>(null)
const examResults = ref<Array<{ value: number, label: string }>>([])

const intermediateApplicants = ref<Array<{
    value: number
    label: string
    email_address: string
}>>([])

// Computed labels
const examResultLabel = computed(() => {
    // ✅ Map exam_result (1,2,3) to labels
    const selected = examResultOptions.value.find(status => status.value === Number(form.exam_result))
    return selected ? selected.label : 'Pending'
})


const form = useForm({
    // Core
    application_stage: 1 as number | null,
    intermediate_applicant_id: '',
    position: '',
    source_project_id: '',
    resource_schedule_id: '',

    // Files
    upload_resume: '',
    upload_pic: '',

    // Screening answers
    answer_q1: null as boolean | null,
    answer_q2: null as boolean | null,
    answer_q3: null as boolean | null,
    answer_q4: null as boolean | null,

    // Profile / preference
    availability_date: '',
    desired_salary_range: '',
    work_preference: '',
    basic_pay: '',
    bonuses: '',
    hmo: '',
    leaves: '',
    allowances: '',
    other_benefits: '',
    targeted_company: '',
    industry_experience: '',
    current_employer: '',
    asking_rate: '',
    site_assignment: '',

    // Screening
    paper_screening_status: null as number | null,

    // Exam
    exam_plan_date: '',
    exam_actual_date: '',
    exam_venue: '',
    exam_atpp_part1_correct: '',
    exam_atpp_part1_wrong: '',
    exam_atpp_part2_correct: '',
    exam_atpp_part2_wrong: '',
    exam_atpp_part3_correct: '',
    exam_atpp_part3_wrong: '',
    exam_atpp_result: '',
    exam_tech_result: '',
    exam_result: null as number | null,
    exam_application_status: '',
    exam_remarks: '',

    // Initial Interview (HR)
    initial_interview_plan_date: '',
    initial_interview_actual_date: '',
    initial_interview_venue: '',
    initial_interview_final: null as number | null,
    initial_interview_result: null as number | null,
    initial_interview_application_status: '',
    initial_interview_remarks: '',

    // Final Interview
    final_interview_date: '',
    final_interview_final: null as number | null,
    final_interview_result: null as number | null,
    final_interview_application_status: '',
    final_interview_remarks: '',

    // Job offer
    job_offer_schedule: '',
    job_offer_status: null as number | null,
    job_offer_remarks: '',

    // Post offer
    aws_start_date: '',
    aws_rank: '',
    parked_to: '',
    reason_by_category: '',
    reason_for_decline: '',

    // Tracking
    contacted_by: null as number | null,
    contacted_date: '',
    replied: null as number | null,
    replied_date: '',

    remarks: '',
})

const resumeFile = ref<File | null>(null)
const pictureFile = ref<File | null>(null)
const resumePreview = ref<string | null>(null)
const picturePreview = ref<string | null>(null)

const errorMessage = computed(() => page.props.flash?.error || '')
const showError = ref(false)
const successMessage = computed(() => page.props.flash?.success || '')
const showSuccess = ref(false)

const isDropdownOpen = ref(false)
const searchQuery = ref('')

// MAIN REACTIVE STATE
const isApplicantSelected = computed(() => !!form.intermediate_applicant_id)
const selectedApplicant = computed(() => {
    return intermediateApplicants.value.find(a => a.value === Number(form.intermediate_applicant_id)) || null
})
const selectedApplicantLabel = computed(() => {
    const selected = intermediateApplicants.value.find(a => a.value === Number(form.intermediate_applicant_id))
    return selected ? selected.label : ''
})

const filteredApplicants = computed(() => {
    if (!searchQuery.value.trim()) return intermediateApplicants.value

    const query = searchQuery.value.toLowerCase()
    return intermediateApplicants.value.filter(applicant => {
        // Search in name and email
        if (applicant.label.toLowerCase().includes(query)) {
            return true
        }

        // Search in work experience job titles
        if (applicant.work_experiences?.some(exp =>
            exp.toLowerCase().includes(query)
        )) {
            return true
        }

        // Search in skills
        if (applicant.skills?.some(skill =>
            skill.toLowerCase().includes(query)
        )) {
            return true
        }

        return false
    })
})

// EVERYTHING DISABLED UNTIL APPLICANT SELECTED
const isFormFieldDisabled = computed(() => !isApplicantSelected.value)

const liveErrors = ref<Record<string, string>>({})

function parseScore(value: string | number | null): number {
    if (value === '' || value === null || value === undefined) return 0
    const num = Number(value)
    return Number.isNaN(num) ? 0 : num
}

const computedAtppResult = computed(() => {
    const p1c = parseScore(form.exam_atpp_part1_correct)
    const p1w = parseScore(form.exam_atpp_part1_wrong)
    const p2c = parseScore(form.exam_atpp_part2_correct)
    const p2w = parseScore(form.exam_atpp_part2_wrong)
    const p3c = parseScore(form.exam_atpp_part3_correct)
    const p3w = parseScore(form.exam_atpp_part3_wrong)

    const hasAny =
        form.exam_atpp_part1_correct !== '' ||
        form.exam_atpp_part1_wrong !== '' ||
        form.exam_atpp_part2_correct !== '' ||
        form.exam_atpp_part2_wrong !== '' ||
        form.exam_atpp_part3_correct !== '' ||
        form.exam_atpp_part3_wrong !== ''

    if (!hasAny) return ''

    const totalCorrect = p1c + p2c + p3c
    const totalWrong = (p1w + p2w + p3w) / 4
    const finalScore = totalCorrect - totalWrong

    return finalScore.toFixed(2)
})

// ✅ Simplified examCriteriaDisplay
const examCriteriaDisplay = computed(() => {
    return {
        passed: { atpp: 60, tech_exam: 30 },
        p2: { atpp: 55, tech_exam: 20 }
    }
})

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

// ENHANCED WATCHER - RESETS EVERYTHING WHEN APPLICANT CHANGES
watch(() => form.intermediate_applicant_id, async (newApplicantId, oldApplicantId) => {
    // Reset FY week check first
    if (newApplicantId) {
        try {
            const response = await axios.post('/intermediate/applications/check-eligibility', {
                intermediate_applicant_id: newApplicantId,
            })

            if (!response.data.eligible) {
                form.setError('intermediate_applicant_id', 'This applicant cannot apply at this time.')
                form.intermediate_applicant_id = ''
                return
            }
        } catch (error) {
            console.error('Failed to check eligibility:', error)
        }
    }

    // COMPLETE FORM RESET WHEN APPLICANT CHANGES
    if (newApplicantId && newApplicantId !== oldApplicantId) {
        await resetCompleteForm()
    }
}, { immediate: true })

// COMPLETE FORM RESET FUNCTION
// COMPLETE FORM RESET FUNCTION
async function resetCompleteForm() {
    // Clear all files first
    clearFilePreviews()

    // ✅ SAVE the current requisition value before resetting
    const currentRequisitionId = form.resource_schedule_id

    // Reset ALL form fields to match model
    form.application_stage = 1
    form.position = ''
    form.source_project_id = ''
    // ✅ Don't reset resource_schedule_id - keep the selected requisition
    // form.resource_schedule_id = ''  ← REMOVE THIS LINE
    form.upload_resume = ''
    form.upload_pic = ''

    // Screening answers
    form.answer_q1 = null
    form.answer_q2 = null
    form.answer_q3 = null
    form.answer_q4 = null

    // Profile fields
    form.availability_date = ''
    form.desired_salary_range = ''
    form.work_preference = ''
    form.basic_pay = ''
    form.bonuses = ''
    form.hmo = ''
    form.leaves = ''
    form.allowances = ''
    form.other_benefits = ''
    form.targeted_company = ''
    form.industry_experience = ''
    form.current_employer = ''
    form.asking_rate = ''
    form.site_assignment = ''

    // Screening
    form.paper_screening_status = null

    // Exam
    form.exam_plan_date = ''
    form.exam_actual_date = ''
    form.exam_venue = ''
    form.exam_atpp_part1_correct = ''
    form.exam_atpp_part1_wrong = ''
    form.exam_atpp_part2_correct = ''
    form.exam_atpp_part2_wrong = ''
    form.exam_atpp_part3_correct = ''
    form.exam_atpp_part3_wrong = ''
    form.exam_atpp_result = ''
    form.exam_tech_result = ''
    form.exam_result = null
    form.exam_application_status = ''
    form.exam_remarks = ''

    // Initial Interview (HR)
    form.initial_interview_plan_date = ''
    form.initial_interview_actual_date = ''
    form.initial_interview_venue = ''
    form.initial_interview_final = null
    form.initial_interview_result = null
    form.initial_interview_application_status = ''
    form.initial_interview_remarks = ''

    // Final Interview
    form.final_interview_date = ''
    form.final_interview_final = null
    form.final_interview_result = null
    form.final_interview_application_status = ''
    form.final_interview_remarks = ''

    // Job offer
    form.job_offer_schedule = ''
    form.job_offer_status = null
    form.job_offer_remarks = ''

    // Post offer
    form.aws_start_date = ''
    form.aws_rank = ''
    form.parked_to = ''
    form.reason_by_category = ''
    form.reason_for_decline = ''

    // Tracking
    form.contacted_by = null
    form.contacted_date = ''
    form.replied = null
    form.replied_date = ''

    form.remarks = ''

    // ✅ RESTORE the requisition value
    form.resource_schedule_id = currentRequisitionId

    // Clear all errors
    form.clearErrors()
    liveErrors.value = {}
}

function clearFilePreviews() {
    if (resumeFile.value) {
        resumeFile.value = null
        form.upload_resume = ''
        if (resumePreview.value) {
            URL.revokeObjectURL(resumePreview.value)
            resumePreview.value = null
        }
    }

    if (pictureFile.value) {
        pictureFile.value = null
        form.upload_pic = ''
        if (picturePreview.value) {
            URL.revokeObjectURL(picturePreview.value)
            picturePreview.value = null
        }
    }

    // Clear file inputs
    const fileInputs = document.querySelectorAll('input[type="file"]')
    fileInputs.forEach((input: any) => input.value = '')
}


function getExamStatus(atpp: number, tech: number): string {
    // 1st Priority (PASSED) - Status 3
    if (atpp >= 60 && tech >= 30) {
        return '3'  // PASSED
    }

    // 2nd Priority (P2) - Status 4  
    if (atpp >= 55 && tech >= 20) {
        return '4'  // P2
    }

    // Failed - Status 5
    return '5'
}

// Add this with your other dropdown refs
const initialInterviewStatuses = ref<Array<{ value: number, label: string }>>([
    { value: 1, label: 'Pending' },
    { value: 2, label: 'Done' },
    { value: 3, label: 'Passed' },
    { value: 4, label: 'P2' },
    { value: 5, label: 'Failed' }
])


// Auto-status watchers
watch(() => form.exam_atpp_part1_correct, (value) => {
    validateScoreField('exam_atpp_part1_correct', 'ATPP Part I Correct', value)
})
watch(() => form.exam_atpp_part1_wrong, (value) => {
    validateScoreField('exam_atpp_part1_wrong', 'ATPP Part I Wrong', value)
})
watch(() => form.exam_atpp_part2_correct, (value) => {
    validateScoreField('exam_atpp_part2_correct', 'ATPP Part II Correct', value)
})
watch(() => form.exam_atpp_part2_wrong, (value) => {
    validateScoreField('exam_atpp_part2_wrong', 'ATPP Part II Wrong', value)
})
watch(() => form.exam_atpp_part3_correct, (value) => {
    validateScoreField('exam_atpp_part3_correct', 'ATPP Part III Correct', value)
})
watch(() => form.exam_atpp_part3_wrong, (value) => {
    validateScoreField('exam_atpp_part3_wrong', 'ATPP Part III Wrong', value)
})
watch(() => form.exam_atpp_result, (value) => {
    validateScoreField('exam_atpp_result', 'ATPP Result', value)
})
watch(() => form.exam_tech_result, (value) => {
    validateScoreField('exam_tech_result', 'Tech Result', value)
})

function mapInterviewScore(score: any) {
    const num = Number(score)

    // ❗ Empty / invalid → Pending
    if (!score || Number.isNaN(num) || num === 0) {
        return { result: 1, status: '1' }  // Pending
    }

    // ✅ 1.00-2.00 → Passed (Result 2, Status 3)
    if (num >= 1.00 && num <= 2.00) {
        return { result: 2, status: '3' }  // Passed
    }

    // ✅ 2.01-3.00 → P2 (Result 2, Status 4)  
    if (num >= 2.01 && num <= 3.00) {
        return { result: 2, status: '4' }  // P2
    }

    // ❌ 3.01-5.00 → Failed (Result 3, Status 5)
    if (num >= 3.01 && num <= 5.00) {
        return { result: 3, status: '5' }  // Failed
    }

    // Fallback safety
    return { result: 1, status: '1' }
}

// Auto-fill ATPP result
watch(computedAtppResult, (value) => {
    form.exam_atpp_result = value
})

// Update exam status watcher
watch([() => form.exam_atpp_result, () => form.exam_tech_result], ([atpp, tech]) => {
    if (!isApplicantSelected.value) return

    const hasAllScores = !!atpp && !!tech
    if (!hasAllScores) {
        form.exam_application_status = ''
        return
    }

    const atppNum = Number(atpp)
    const techNum = Number(tech)
    if (Number.isNaN(atppNum) || Number.isNaN(techNum)) {
        form.exam_application_status = ''
        return
    }

    // ✅ SIMPLIFIED - No applicant category needed!
    form.exam_application_status = getExamStatus(atppNum, techNum)
}, { immediate: true })

watch(() => form.exam_application_status, (status) => {
    if (!isApplicantSelected.value) return

    const statusNum = Number(status)

    if (statusNum === 3 || statusNum === 4) {  // Passed or P2
        form.exam_result = 2  // Passed ✅
    }
    else if (statusNum === 5) {  // Failed
        form.exam_result = 3  // Failed ✅
    }
    else {  // No scores or empty
        form.exam_result = 1  // Pending ✅
    }
}, { immediate: true })

// File handling
const handleResumeUpload = (event: Event) => {
    if (!isApplicantSelected.value) return

    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        const file = target.files[0]
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']

        if (!allowedTypes.includes(file.type)) {
            form.setError('upload_resume', 'Please upload PDF or Word document')
            return
        }

        if (file.size > 5 * 1024 * 1024) {
            form.setError('upload_resume', 'File size must be less than 5MB')
            return
        }

        resumeFile.value = file
        form.upload_resume = file.name

        // ✅ KEY FIX: Always create preview URL for PDFs (like working code)
        if (resumePreview.value) {
            URL.revokeObjectURL(resumePreview.value)  // Always cleanup first
        }
        if (file.type === 'application/pdf') {
            resumePreview.value = URL.createObjectURL(file)  // Always create for PDF
        } else {
            resumePreview.value = null  // Clear for non-PDF
        }

        form.clearErrors('upload_resume')
    }
}

const handlePictureUpload = (event: Event) => {
    if (!isApplicantSelected.value) return

    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        const file = target.files[0]
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg']

        if (!allowedTypes.includes(file.type)) {
            form.setError('upload_pic', 'Please upload JPG or PNG image')
            return
        }

        if (file.size > 2 * 1024 * 1024) {
            form.setError('upload_pic', 'File size must be less than 2MB')
            return
        }

        pictureFile.value = file
        form.upload_pic = file.name

        if (picturePreview.value) URL.revokeObjectURL(picturePreview.value)
        picturePreview.value = URL.createObjectURL(file)

        form.clearErrors('upload_pic')
    }
}

// ✅ UPDATE THIS (add proper cleanup like working code)
const removeFile = (type: 'resume' | 'picture') => {
    if (type === 'resume') {
        resumeFile.value = null
        form.upload_resume = ''
        if (resumePreview.value) {  // ✅ Always check & cleanup
            URL.revokeObjectURL(resumePreview.value)
            resumePreview.value = null
        }
    } else {
        pictureFile.value = null
        form.upload_pic = ''
        if (picturePreview.value) {
            URL.revokeObjectURL(picturePreview.value)
            picturePreview.value = null
        }
    }

    // ✅ Clear file input (like working code)
    const fileInputs = document.querySelectorAll('input[type="file"]')
    fileInputs.forEach((input: any) => input.value = '')
}

function setLiveError(field: string, message: string) {
    liveErrors.value[field] = message
}

function clearLiveError(field: string) {
    delete liveErrors.value[field]
}

function validateScoreField(field: string, label: string, value: string | number) {
    if (!isApplicantSelected.value) return

    if (value === '' || value === null || value === undefined) {
        clearLiveError(field)
        return
    }

    const num = Number(value)
    if (Number.isNaN(num)) {
        setLiveError(field, `${label} must be a valid number.`)
        return
    }

    if (num < 0 || num > 999.99) {
        setLiveError(field, `${label} must be between 0 and 999.99.`)
        return
    }

    clearLiveError(field)
}

function formatDateTimeLocal(value: Date) {
    const year = value.getFullYear()
    const month = String(value.getMonth() + 1).padStart(2, '0')
    const day = String(value.getDate()).padStart(2, '0')
    const hours = String(value.getHours()).padStart(2, '0')
    const minutes = String(value.getMinutes()).padStart(2, '0')
    return `${year}-${month}-${day}T${hours}:${minutes}`
}

function tomorrowStart(): string {
    const now = new Date()
    now.setHours(0, 0, 0, 0)
    now.setDate(now.getDate() + 1)
    return formatDateTimeLocal(now)
}

const examPlanMin = computed(() => tomorrowStart())
const examActualMin = computed(() => form.exam_plan_date || undefined)
const initialInterviewPlanMin = computed(() => tomorrowStart())
const initialInterviewActualMin = computed(() => {
    return form.initial_interview_plan_date || tomorrowStart()
})
// ✅ JOB OFFER MIN (tomorrow)
const jobOfferMin = computed(() => tomorrowStart())

// ✅ FINAL INTERVIEW MIN (after initial interview OR tomorrow)
const finalInterviewMin = computed(() => {
    return form.initial_interview_actual_date || tomorrowStart()
})

onMounted(async () => {
    try {
        const response = await axios.get('/intermediate/applications/current-fy-week')
        currentFyWeek.value = response.data.fy_week
    } catch (error) {
        console.error('Failed to get current FY week:', error)
    }

    if (props.sourceProjects) {
        sourceProjects.value = props.sourceProjects
    }
    if (props.examStatuses) examStatuses.value = Object.entries(props.examStatuses).map(([k, v]) => ({ value: Number(k), label: String(v) }))
    if (props.hrStatuses) {
        hrStatuses.value = Object.entries(props.hrStatuses).map(([k, v]) => ({ value: Number(k), label: String(v) }))
        initialInterviewStatuses.value = Object.entries(props.hrStatuses).map(([k, v]) => ({ value: Number(k), label: String(v) }))
    }
    if (props.buStatuses) buStatuses.value = Object.entries(props.buStatuses).map(([k, v]) => ({ value: Number(k), label: String(v) }))
    if (props.finalStatuses) finalStatuses.value = Object.entries(props.finalStatuses).map(([k, v]) => ({ value: Number(k), label: String(v) }))
    if (props.jobOfferStatuses) jobOfferStatuses.value = Object.entries(props.jobOfferStatuses).map(([k, v]) => ({ value: Number(k), label: String(v) }))

    if (props.intermediateApplicants) {
        intermediateApplicants.value = props.intermediateApplicants.map(applicant => ({
            value: applicant.id,
            label: `${applicant.name} (${applicant.email_address})`,
            email_address: applicant.email_address,
            work_experiences: applicant.work_experiences || [],
            skills: applicant.skills || [],
        }))
    }

    if (props.applicationResultMap?.initial_interview) {
        initialResultOptions.value = Object.entries(props.applicationResultMap.initial_interview).map(([k, v]) => ({
            value: Number(k),
            label: String(v)
        }))
    }

    // ✅ CORRECT: final_interview → finalResultOptions
    if (props.applicationResultMap?.final_interview) {
        finalResultOptions.value = Object.entries(props.applicationResultMap.final_interview).map(([k, v]) => ({
            value: Number(k),
            label: String(v)
        }))
    }

    examVenues.value = [
        { value: 1, label: 'Online' },
        { value: 2, label: 'Face to Face' }
    ]

    document.addEventListener('click', handleClickOutside)
})

const initialResultOptions = ref<Array<{ value: number, label: string }>>([
    { value: 1, label: 'Pending' },
    { value: 2, label: 'Passed' },
    { value: 3, label: 'Failed' }
])

// ✅ Updated Final Interview Results  
const finalResultOptions = ref<Array<{ value: number, label: string }>>([
    { value: 1, label: 'Pending' },
    { value: 2, label: 'Passed' },
    { value: 3, label: 'Failed' }
])

const initialResultLabel = computed(() => {
    const selected = initialResultOptions.value.find(status => status.value === form.initial_interview_result)
    return selected ? selected.label : 'Pending'
})

// ✅ Final Result Label
const finalResultLabel = computed(() => {
    const selected = finalResultOptions.value.find(status => status.value === form.final_interview_result)
    return selected ? selected.label : 'Pending'
})
onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})

function toggleDropdown() {
    isDropdownOpen.value = !isDropdownOpen.value
    if (isDropdownOpen.value) searchQuery.value = ''
}

function selectApplicant(applicant: { value: number, label: string }) {
    form.intermediate_applicant_id = String(applicant.value)
    isDropdownOpen.value = false
    searchQuery.value = ''
}

function handleClickOutside(event: MouseEvent) {
    const target = event.target as HTMLElement
    if (!target.closest('.custom-select-wrapper')) {
        isDropdownOpen.value = false
        isRequisitionDropdownOpen.value = false
    }
}

function clampScore(obj: any, field: string, max: number) {
    let value = obj[field];
    if (value === '' || value === null || value === undefined) return;

    let num = Number(value);
    if (Number.isNaN(num)) {
        obj[field] = '';
        return;
    }

    if (num < 0) num = 0;
    if (num > max) num = max;
    obj[field] = num;
}

function toTimestamp(value: string) {
    return new Date(value.replace('T', ' ')).getTime()
}

function submit() {
    form.clearErrors()

    let hasError = false

    if (!form.intermediate_applicant_id) {
        form.setError('intermediate_applicant_id', 'Applicant is required.')
        hasError = true
    }

    if (form.remarks && form.remarks.length > 65535) {
        remarksError.value = true
        hasError = true
    }

    // Replied date validation
    if (form.replied_date && form.contacted_date) {
        const contactedDate = new Date(form.contacted_date)
        const repliedDate = new Date(form.replied_date)

        if (repliedDate < contactedDate) {
            form.setError('replied_date', 'Replied date cannot be earlier than contacted date.')
            hasError = true
        }
    }

    // Check initial interview remarks length
    if (form.initial_interview_remarks && form.initial_interview_remarks.length > 65535) {
        initialRemarksError.value = true
        hasError = true
    }

    if (form.exam_plan_date && form.exam_actual_date) {
        if (form.exam_actual_date < form.exam_plan_date) {
            form.setError(
                'exam_actual_date',
                'Actual date/time cannot be earlier than plan date/time.'
            )
            hasError = true
        }
    }

    if (form.initial_interview_plan_date && form.initial_interview_actual_date) {
        if (toTimestamp(form.initial_interview_actual_date) < toTimestamp(form.initial_interview_plan_date)) {
            form.setError('initial_interview_actual_date', 'Actual date/time cannot be earlier than plan date/time.')
            hasError = true
        }
    }

    if (liveErrors.value['initial_interview_final'] || liveErrors.value['final_interview_final']) {
        hasError = true
    }

    // HARD STOP — THIS is what you were missing
    if (hasError) return

    form.transform((data) => {
        const formData = new FormData()

        Object.keys(data).forEach((key) => {
            if (key !== 'upload_resume' && key !== 'upload_pic') {
                const value = data[key as keyof typeof data]
                if (value !== null && value !== undefined && value !== '') {
                    formData.append(key, String(value))
                }
            }
        })

        if (resumeFile.value) formData.append('upload_resume', resumeFile.value)
        if (pictureFile.value) formData.append('upload_pic', pictureFile.value)

        return formData as any
    })

    form.post('/intermediate/applications', {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            intermediateApplicants.value = []
            clearFilePreviews()
        }
    })
}

function clampAtppPair(
    obj: any,
    correctField: string,
    wrongField: string,
    max: number,
) {
    let correct = Number(obj[correctField] || 0);
    let wrong = Number(obj[wrongField] || 0);

    if (Number.isNaN(correct)) correct = 0;
    if (Number.isNaN(wrong)) wrong = 0;

    // NEW: clamp individually
    if (correct < 0) correct = 0;
    if (wrong < 0) wrong = 0;

    if (correct > max) correct = max;
    if (wrong > max) wrong = max;

    // EXISTING: clamp total
    if (correct + wrong > max) {
        const excess = correct + wrong - max;

        if (
            (document.activeElement as HTMLInputElement | null)?.name ===
            correctField
        ) {
            wrong = Math.max(0, wrong - excess);
        } else {
            correct = Math.max(0, correct - excess);
        }
    }

    obj[correctField] = correct;
    obj[wrongField] = wrong;
}
// Add with your other dropdown refs (~line 60)
// Replace examResults with this (~line 60, after other dropdown refs)
const examResultOptions = ref<Array<{ value: number, label: string }>>([
    { value: 1, label: 'Pending' },
    { value: 2, label: 'Passed' },
    { value: 3, label: 'Failed' }
])

watch(
    () => form.exam_plan_date,
    (planDate) => {
        if (!planDate) return

        // Only clear invalid actual date (no forced overwrite)
        if (
            form.exam_actual_date &&
            form.exam_actual_date < planDate
        ) {
            form.exam_actual_date = ''
        }
    }
)

// ✅ STEP 3: INITIAL INTERVIEW COMPLETE DATE LOGIC

// When PLAN DATE changes → Clear invalid ACTUAL date
watch(
    () => form.initial_interview_plan_date,
    (planDate) => {
        if (!planDate) {
            // If plan date is cleared, clear actual date too
            form.initial_interview_actual_date = ''
            form.clearErrors('initial_interview_actual_date')
            return
        }

        // If actual date exists but is BEFORE new plan date → clear it
        if (form.initial_interview_actual_date && form.initial_interview_actual_date < planDate) {
            form.initial_interview_actual_date = ''
            form.clearErrors('initial_interview_actual_date')
        }
    }
)

watch(() => form.initial_interview_actual_date, (newVal) => {
    if (!newVal || !form.initial_interview_plan_date) {
        form.clearErrors('initial_interview_actual_date')
        clearLiveError('initial_interview_actual_date')
        return
    }

    const plan = new Date(form.initial_interview_plan_date.replace('T', ' '))
    const actual = new Date(newVal.replace('T', ' '))

    if (actual < plan) {
        form.setError('initial_interview_actual_date', 'Actual date/time cannot be earlier than plan date/time.')
        setLiveError('initial_interview_actual_date', 'Actual date/time cannot be earlier than plan date/time.')
    } else {
        form.clearErrors('initial_interview_actual_date')
        clearLiveError('initial_interview_actual_date')
    }
})

watch(() => form.initial_interview_final, (score) => {
    if (!isApplicantSelected.value) return

    const { result, status } = mapInterviewScore(score)

    form.initial_interview_result = result
    form.initial_interview_application_status = status
}, { immediate: true })

// ✅ Status changes → Update Result (add this after the score watcher)
watch(() => form.initial_interview_application_status, (status) => {
    if (!isApplicantSelected.value) return

    const statusNum = Number(status)

    if (statusNum === 3 || statusNum === 4) {  // Passed/P2
        form.initial_interview_result = 2  // Passed ✅
    }
    else if (statusNum === 5) {  // Failed
        form.initial_interview_result = 3  // Failed ✅
    }
    else {  // Pending/Done/empty
        form.initial_interview_result = 1  // Pending ✅
    }
}, { immediate: true })

// ✅ FINAL INTERVIEW Auto-correlation (New Logic)
watch(() => form.final_interview_final, (score) => {
    if (!isApplicantSelected.value) return

    const { result, status } = mapInterviewScore(score)

    form.final_interview_result = result
    form.final_interview_application_status = status
}, { immediate: true })

// ✅ Interview Score Validation Function
function validateInterviewScore(field: string, label: string, value: string | number) {
    if (!isApplicantSelected.value) return

    if (value === '' || value === null || value === undefined) {
        clearLiveError(field)
        return
    }

    const num = Number(value)

    if (Number.isNaN(num)) {
        setLiveError(field, `${label} must be a valid number.`)
        return
    }

    // ✅ Updated range: 1.00-5.00
    if (num < 1.00 || num > 5.00) {
        setLiveError(field, `${label} must be between 1.00 and 5.00.`)
        return
    }

    clearLiveError(field)
}

watch(() => form.initial_interview_final, (value) => {
    validateInterviewScore(
        'initial_interview_final',
        'Initial Interview Final Score',
        value
    )
})

watch(() => form.final_interview_final, (value) => {
    validateInterviewScore(
        'final_interview_final',
        'Final Interview Final Score',
        value
    )
})

const initialInterviewResultOptions = ref<Array<{ value: number, label: string }>>([
    { value: 1, label: 'Pending' },
    { value: 2, label: 'Passed' },
    { value: 3, label: 'Failed' }
])

const initialInterviewResultLabel = computed(() => {
    const selected = initialInterviewResultOptions.value.find(status => status.value === Number(form.initial_interview_result))
    return selected ? selected.label : 'Pending'
})

const finalStatusLabel = computed(() => {
    const selected = finalStatuses.value.find(
        s => s.value === Number(form.final_interview_application_status)
    )
    return selected ? selected.label : 'Pending'
})

const filterSalaryInput = (e) => {
    let value = e.target.value;

    // allow only numbers, comma, dash
    value = value.replace(/[^0-9,\-]/g, '');

    form.desired_salary_range = value;
};

const filterBasicPay = (e) => {
    let value = e.target.value;

    // allow only numbers and commas (NO DASHES)
    value = value.replace(/[^0-9,]/g, '');

    form.basic_pay = value;
};

const errors = usePage().props.errors;

watch(
    () => form.exam_actual_date,
    (newVal) => {
        if (!newVal || !form.exam_plan_date) return

        const plan = new Date(form.exam_plan_date)
        const actual = new Date(newVal)

        if (actual < plan) {
            form.exam_actual_date = form.exam_plan_date
            form.errors.exam_actual_date = "Actual date/time cannot be earlier than planned date"
        }
    }
)

watch(() => form.initial_interview_actual_date, (newVal) => {
    if (!newVal || !form.initial_interview_plan_date) {
        form.clearErrors('initial_interview_actual_date')
        return
    }

    const plan = new Date(form.initial_interview_plan_date)
    const actual = new Date(newVal)

    if (actual < plan) {
        // ✅ ONLY show error (no overwrite)
        form.setError(
            'initial_interview_actual_date',
            'Actual date/time cannot be earlier than planned date'
        )
    } else {
        // ✅ Clear error when valid
        form.clearErrors('initial_interview_actual_date')
    }
})


watch(() => form.remarks, (newValue) => {
    if (newValue && newValue.length > 1024) {
        form.setError('remarks', 'This field exceeds the maximum allowed length.')
    } else {
        form.clearErrors('remarks')
    }
})

watch(() => form.initial_interview_remarks, (newValue) => {
    if (newValue && newValue.length > 1024) {
        form.setError('initial_interview_remarks', 'This field exceeds the maximum allowed length.')
    } else {
        form.clearErrors('initial_interview_remarks')
    }
})

// Add these refs after your existing refs (around line 60-70)
const isRequisitionDropdownOpen = ref(false)
const requisitionSearchQuery = ref('')

// Add this computed property after your existing computed properties
const filteredRequisitions = computed(() => {
    if (!requisitionSearchQuery.value.trim()) return sourceProjects.value

    const query = requisitionSearchQuery.value.toLowerCase()
    return sourceProjects.value.filter(requisition => {
        const resource = (requisition.label || '').toLowerCase()
        // Extract project name and location from the label format: "Resource - ProjectName (Location)"
        const match = requisition.label.match(/^(.+?)\s*-\s*(.+?)\s*\((.+?)\)$/)
        const projectName = match ? match[2].toLowerCase() : ''
        const location = match ? match[3].toLowerCase() : ''

        return resource.includes(query) ||
            projectName.includes(query) ||
            location.includes(query)
    })
})

const selectedRequisitionLabel = computed(() => {
    const selected = sourceProjects.value.find(r => r.value === Number(form.resource_schedule_id))
    return selected ? selected.label : ''
})

// Update the source projects data structure to include parsed fields
const parsedRequisitions = computed(() => {
    return sourceProjects.value.map(req => {
        const match = req.label.match(/^(.+?)\s*-\s*(.+?)\s*\((.+?)\)$/)
        return {
            ...req,
            resource: match ? match[1].trim() : req.label,
            project_name: match ? match[2].trim() : '',
            location: match ? match[3].trim() : ''
        }
    })
})

// Add these functions after your existing functions
function toggleRequisitionDropdown() {
    isRequisitionDropdownOpen.value = !isRequisitionDropdownOpen.value
    if (isRequisitionDropdownOpen.value) {
        requisitionSearchQuery.value = ''
        // Close the applicant dropdown if it's open
        if (isDropdownOpen.value) {
            isDropdownOpen.value = false
        }
    }
}

function selectRequisition(requisition) {
    form.resource_schedule_id = requisition.value
    isRequisitionDropdownOpen.value = false
    requisitionSearchQuery.value = ''
}

// Get current user info from page props
const currentUser = computed(() => {
    // If you have user info in page props, use it
    // Otherwise, you might need to fetch it
    return page.props.auth?.user || null
})

const currentUserName = computed(() => {
    if (!currentUser.value) return 'Unknown'
    return `${currentUser.value.first_name || ''} ${currentUser.value.last_name || ''}`.trim() || currentUser.value.name || 'Unknown'
})

const currentUserId = computed(() => {
    return currentUser.value?.id || null
})

// Handle contacted date change
function handleContactedDateChange() {
    if (form.contacted_date && currentUserId.value) {
        form.contacted_by = currentUserId.value
    } else if (!form.contacted_date) {
        // Clear contacted_by if date is removed
        form.contacted_by = null
        // Also clear replied fields since there's no contact date
        form.replied = null
        form.replied_date = ''
    }
}

// Update the existing watcher to also clear replied fields
watch(() => form.contacted_date, (newVal) => {
    if (!newVal) {
        form.contacted_by = null
        form.replied = null
        form.replied_date = ''
    } else if (currentUserId.value) {
        form.contacted_by = currentUserId.value
    }
})

// Validate replied date is not before contacted date
watch(() => form.replied_date, (newVal) => {
    if (!isApplicantSelected.value || !form.contacted_date) return

    if (newVal) {
        const contactedDate = new Date(form.contacted_date)
        const repliedDate = new Date(newVal)

        if (repliedDate < contactedDate) {
            setLiveError('replied_date', 'Replied date cannot be earlier than contacted date.')
        } else {
            clearLiveError('replied_date')
        }
    }
})

// Also validate when contacted date changes (might invalidate existing replied date)
watch(() => form.contacted_date, (newVal) => {
    if (!form.replied_date) return

    if (newVal) {
        const contactedDate = new Date(newVal)
        const repliedDate = new Date(form.replied_date)

        if (repliedDate < contactedDate) {
            setLiveError('replied_date', 'Replied date cannot be earlier than contacted date.')
        } else {
            clearLiveError('replied_date')
        }
    } else if (!newVal) {
        // Contacted date cleared - clear any error
        clearLiveError('replied_date')
    }
})


</script>

<template>
    <AppLayout>
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
                <h2 class="page-title">Create Intermediate Application</h2>
            </div>

            <div class="form-wrapper">
                <div class="form-card">
                    <form @submit.prevent="submit">
                        <!-- Basic Information -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>Basic Information</h3>
                            </div>

                            <!-- Applicant + Requisitions - SIDE BY SIDE -->
                            <div class="form-grid grid-2 mb-6">

                                <!-- RIGHT: Request Requisitions -->
                                <div class="form-field">
                                    <label class="field-label">Request Requisition Forms</label>
                                    <div class="custom-select-wrapper"
                                        :class="{ 'is-open': isRequisitionDropdownOpen }">
                                        <div class="custom-select-trigger" @click="toggleRequisitionDropdown"
                                            tabindex="0">
                                            <span class="custom-select-value">
                                                {{ selectedRequisitionLabel || 'Select Requisition Form' }}
                                            </span>
                                            <svg class="custom-select-arrow" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        <div class="custom-select-dropdown" v-show="isRequisitionDropdownOpen">
                                            <div class="dropdown-search">
                                                <input type="text" v-model="requisitionSearchQuery"
                                                    placeholder="Search by project, location, or resource..."
                                                    class="dropdown-search-input" @click.stop />
                                            </div>
                                            <!-- Update the filteredRequisitions template to use parsed data -->
                                            <div class="dropdown-options-list requisition-list">
                                                <div v-for="requisition in filteredRequisitions"
                                                    :key="requisition.value" class="dropdown-option-item"
                                                    :class="{ 'is-selected': form.resource_schedule_id === requisition.value }"
                                                    @click="selectRequisition(requisition)">
                                                    <div class="option-main">{{ requisition.label }}</div>
                                                </div>
                                                <div v-if="filteredRequisitions.length === 0"
                                                    class="dropdown-empty-item">
                                                    No requisitions found
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span v-if="form.errors.resource_schedule_id" class="error-message">
                                        {{ form.errors.resource_schedule_id }}
                                    </span>
                                </div>

                                <!-- LEFT: Intermediate Applicant -->
                                <div class="form-field">
                                    <label class="field-label-required required">Intermediate Applicant</label>
                                    <div class="custom-select-wrapper" :class="{ 'is-open': isDropdownOpen }">
                                        <div class="custom-select-trigger" @click="toggleDropdown" tabindex="0">
                                            <span class="custom-select-value">
                                                {{ selectedApplicantLabel || 'Select Applicant' }}
                                            </span>
                                            <svg class="custom-select-arrow" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        <div class="custom-select-dropdown" v-show="isDropdownOpen">
                                            <div class="dropdown-search">
                                                <input type="text" v-model="searchQuery"
                                                    placeholder="Search applicants..." class="dropdown-search-input"
                                                    @click.stop />
                                            </div>
                                            <div class="dropdown-options-list">
                                                <div v-for="applicant in filteredApplicants" :key="applicant.value"
                                                    class="dropdown-option-item"
                                                    :class="{ 'is-selected': Number(form.intermediate_applicant_id) === applicant.value }"
                                                    @click="selectApplicant(applicant)">
                                                    {{ applicant.label }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span v-if="form.errors.intermediate_applicant_id" class="error-message">{{
                                        form.errors.intermediate_applicant_id }}</span>
                                </div>


                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="form-section" :class="{ 'disabled-section': isFormFieldDisabled }">
                            <div class="section-header">
                                <h3>Upload Documents</h3>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Upload Resume</label>
                                    <input type="file" @change="handleResumeUpload" accept=".pdf,.doc,.docx"
                                        class="file-input-btn w-full"
                                        :disabled="isFormFieldDisabled || form.processing" />
                                    <div v-if="form.upload_resume" class="file-info">
                                        <span class="file-name">{{ form.upload_resume }}</span>
                                        <button type="button" @click="removeFile('resume')"
                                            class="remove-file">×</button>
                                    </div>
                                    <span v-if="form.errors.upload_resume" class="error-message">{{
                                        form.errors.upload_resume }}</span>
                                    <div v-if="resumePreview" class="file-preview">
                                        <a :href="resumePreview" target="_blank" class="preview-link">Preview
                                            PDF</a>
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Upload Picture</label>
                                    <input type="file" @change="handlePictureUpload"
                                        accept="image/jpeg,image/png,image/jpg" class="file-input-btn w-full"
                                        :disabled="isFormFieldDisabled || form.processing" />
                                    <div v-if="form.upload_pic" class="file-info">
                                        <span class="file-name">{{ form.upload_pic }}</span>
                                        <button type="button" @click="removeFile('picture')"
                                            class="remove-file">×</button>
                                    </div>
                                    <span v-if="form.errors.upload_pic" class="error-message">{{
                                        form.errors.upload_pic }}</span>
                                    <div v-if="picturePreview" class="picture-preview">
                                        <img :src="picturePreview" alt="Picture preview" class="preview-image" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact & Response Tracking -->
                        <div class="form-section" :class="{ 'disabled-section': isFormFieldDisabled }">
                            <div class="section-header">
                                <h3>Contact & Response Tracking</h3>
                            </div>

                            <div class="form-grid grid-2 mb-6">
                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Contacted Date</label>
                                    <input type="datetime-local" v-model="form.contacted_date" class="form-input"
                                        :disabled="isFormFieldDisabled" @change="handleContactedDateChange" />
                                    <span v-if="form.errors.contacted_date" class="error-message">{{
                                        form.errors.contacted_date }}</span>
                                </div>

                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Contacted By</label>
                                    <input type="text" :value="currentUserName" class="form-input" disabled />
                                </div>
                            </div>

                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Replied</label>
                                    <select v-model="form.replied" class="form-select"
                                        :disabled="isFormFieldDisabled || !form.contacted_date">
                                        <option :value="null">Select Status</option>
                                        <option :value="1">Yes</option>
                                        <option :value="0">No</option>
                                    </select>
                                    <span v-if="form.errors.replied" class="error-message">{{ form.errors.replied
                                    }}</span>
                                </div>

                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Replied Date</label>
                                    <input type="datetime-local" v-model="form.replied_date" class="form-input"
                                        :disabled="!isApplicantSelected || !form.contacted_date || form.replied !== 1" />
                                    <span v-if="form.errors.replied_date" class="error-message">{{
                                        form.errors.replied_date }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Screening Questions & Preferences -->
                        <div class="form-section" :class="{ 'disabled-section': isFormFieldDisabled }">
                            <div class="section-header">
                                <h3>Screening Questions & Preferences</h3>
                            </div>

                            <div class="position-preferences-grid mb-6">
                                <!-- 1. Position -->
                                <div class="form-field">
                                    <label class="field-label position-label">Position</label>
                                    <input type="text" v-model="form.position" class="form-input position-input"
                                        placeholder="Enter aspiring position" :disabled="isFormFieldDisabled" />
                                </div>

                                <!-- 2. Availability Date -->
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 position-label">When are you available to
                                        start?</label>
                                    <input type="text" v-model="form.availability_date"
                                        class="form-input position-input" placeholder="ex: 2024-12-01 or ASAP"
                                        :disabled="isFormFieldDisabled" />
                                </div>

                                <!-- 3. Desired Salary -->
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 position-label">Desired Salary</label>
                                    <input type="text" v-model="form.desired_salary_range"
                                        class="form-input position-input" placeholder="ex: 50,000 - 70,000"
                                        :disabled="isFormFieldDisabled" @input="filterSalaryInput" />
                                </div>

                                <!-- 4. Work Preference -->
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 position-label">Work Preference</label>
                                    <input type="text" v-model="form.work_preference" class="form-input position-input"
                                        placeholder="ex: Regular/Part-time" :disabled="isFormFieldDisabled" />
                                </div>

                                <!-- 5. Current Employer 👈 Now in the same row -->
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 position-label">Current Employer</label>
                                    <input type="text" v-model="form.current_employer" class="form-input position-input"
                                        placeholder="Enter current employer" :disabled="isFormFieldDisabled" />
                                    <span v-if="form.errors.current_employer" class="error-message">{{
                                        form.errors.current_employer }}</span>
                                </div>
                            </div>

                            <div class="form-grid grid-2 mb-8 gap-4">
                                <div class="form-field">
                                    <label class="field-label text-sm">1. Have you ever filed an application in AWS,
                                        Inc. before?</label>
                                    <select v-model.number="form.answer_q1" class="form-select"
                                        :disabled="isFormFieldDisabled">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label text-sm">2. Do any of your friends or relatives, other
                                        than a spouse, work in AWS?</label>
                                    <select v-model.number="form.answer_q2" class="form-select"
                                        :disabled="isFormFieldDisabled">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label text-sm">3. Have you worked in AWS before?</label>
                                    <select v-model.number="form.answer_q3" class="form-select"
                                        :disabled="isFormFieldDisabled">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label text-sm">4. Will you travel if the job requires
                                        it?</label>
                                    <select v-model.number="form.answer_q4" class="form-select"
                                        :disabled="isFormFieldDisabled">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="compensation-grid">
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Basic Pay</label>

                                    <input type="text" v-model="form.basic_pay" class="form-input compensation-input"
                                        placeholder="ex: 50,000" :disabled="isFormFieldDisabled"
                                        @input="filterBasicPay" />

                                    <span v-if="errors.basic_pay" class="text-red-500 text-xs mt-1 block">
                                        {{ errors.basic_pay }}
                                    </span>
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Bonuses</label>
                                    <input type="text" v-model="form.bonuses" class="form-input compensation-input"
                                        placeholder="ex: 13-month bonus" :disabled="isFormFieldDisabled" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">HMO</label>
                                    <input type="text" v-model="form.hmo" class="form-input compensation-input"
                                        placeholder="ex: Healthcare" :disabled="isFormFieldDisabled" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Leaves</label>
                                    <input type="text" v-model="form.leaves" class="form-input compensation-input"
                                        placeholder="ex: 15 VL" :disabled="isFormFieldDisabled" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Allowance</label>
                                    <input type="text" v-model="form.allowances" class="form-input compensation-input"
                                        placeholder="ex: Transportation - 5000" :disabled="isFormFieldDisabled" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Other Benefits</label>
                                    <input type="text" v-model="form.other_benefits"
                                        class="form-input compensation-input" placeholder="ex: Wi-fi"
                                        :disabled="isFormFieldDisabled" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Target Company</label>
                                    <input type="text" v-model="form.targeted_company"
                                        class="form-input compensation-input" placeholder="ex: AWS"
                                        :disabled="isFormFieldDisabled" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Industry
                                        Experience</label>
                                    <input type="text" v-model="form.industry_experience"
                                        class="form-input compensation-input" placeholder="ex: 2 years"
                                        :disabled="isFormFieldDisabled" />
                                </div>
                            </div>
                        </div>

                        <!-- Exam Section -->
                        <div class="form-section">
                            <div class="text-gray-500 field-label pb-6">
                                (You may leave the following fields empty if the applicant is not yet finished with the
                                screening process.)
                            </div>
                            <div class="section-header">
                                <h3>Exam Details</h3>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Exam Plan Date</label>
                                    <input type="datetime-local" v-model="form.exam_plan_date" class="form-input"
                                        :disabled="!isApplicantSelected" :min="examPlanMin" />
                                    <span v-if="form.errors.exam_plan_date" class="error-message">
                                        {{ form.errors.exam_plan_date }}
                                    </span>
                                </div>

                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Exam Actual Date</label>
                                    <input type="datetime-local" v-model="form.exam_actual_date" class="form-input"
                                        :disabled="!form.exam_plan_date || !isApplicantSelected" :min="examActualMin" />
                                    <span v-if="form.errors.exam_actual_date" class="error-message">
                                        {{ form.errors.exam_actual_date }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="field-label !text-gray-500">Exam Venue</label>
                                <select v-model="form.exam_venue" class="form-select" :disabled="!isApplicantSelected">
                                    <option value="">Select Venue</option>
                                    <option v-for="venue in examVenues" :key="venue.value" :value="venue.value">
                                        {{ venue.label }}
                                    </option>
                                </select>
                                <span v-if="form.errors.exam_venue" class="error-message">{{ form.errors.exam_venue
                                    }}</span>
                            </div>
                            <div class="exam-section-layout">
                                <!-- LEFT SIDE -->
                                <div class="exam-form-column">


                                    <div class="atpp-stack">
                                        <div class="atpp-card">
                                            <div class="atpp-card-title">ATPP Part I (Sequence / Pattern Analysis)</div>
                                            <div class="form-grid grid-2">
                                                <div class="form-field">
                                                    <label class="field-label">Correct</label>
                                                    <input type="number" step="1" min="0" max="40"
                                                        v-model="form.exam_atpp_part1_correct" placeholder="0"
                                                        class="form-input" @input="
                                                            clampAtppPair(
                                                                form,
                                                                'exam_atpp_part1_correct',
                                                                'exam_atpp_part1_wrong',
                                                                40,
                                                            )
                                                            " :disabled="!isApplicantSelected" />
                                                    <span v-if="form.errors.exam_atpp_part1_correct"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part1_correct }}
                                                    </span>
                                                    <span v-if="liveErrors.exam_atpp_part1_correct"
                                                        class="error-message">
                                                        {{ liveErrors.exam_atpp_part1_correct }}
                                                    </span>
                                                </div>

                                                <div class="form-field">
                                                    <label class="field-label">Wrong</label>
                                                    <input type="number" step="1" min="0" max="40"
                                                        v-model="form.exam_atpp_part1_wrong" placeholder="0"
                                                        class="form-input" @input="
                                                            clampAtppPair(
                                                                form,
                                                                'exam_atpp_part1_correct',
                                                                'exam_atpp_part1_wrong',
                                                                40,
                                                            )
                                                            " :disabled="!isApplicantSelected" />
                                                    <span v-if="form.errors.exam_atpp_part1_wrong"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part1_wrong }}
                                                    </span>
                                                    <span v-if="liveErrors.exam_atpp_part1_wrong" class="error-message">
                                                        {{ liveErrors.exam_atpp_part1_wrong }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="atpp-card">
                                            <div class="atpp-card-title">ATPP Part II (Abstract Reasoning)</div>
                                            <div class="form-grid grid-2">
                                                <div class="form-field">
                                                    <label class="field-label">Correct</label>
                                                    <input type="number" step="1" min="0" max="30"
                                                        v-model="form.exam_atpp_part2_correct" placeholder="0"
                                                        class="form-input"
                                                        @input="clampAtppPair(form, 'exam_atpp_part2_correct', 'exam_atpp_part2_wrong', 30)"
                                                        :disabled="!isApplicantSelected" />
                                                    <span v-if="form.errors.exam_atpp_part2_correct"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part2_correct }}
                                                    </span>
                                                    <span v-if="liveErrors.exam_atpp_part2_correct"
                                                        class="error-message">
                                                        {{ liveErrors.exam_atpp_part2_correct }}
                                                    </span>
                                                </div>

                                                <div class="form-field">
                                                    <label class="field-label">Wrong</label>
                                                    <input type="number" step="1" min="0" max="30"
                                                        v-model="form.exam_atpp_part2_wrong" placeholder="0"
                                                        class="form-input"
                                                        @input="clampAtppPair(form, 'exam_atpp_part2_correct', 'exam_atpp_part2_wrong', 30)"
                                                        :disabled="!isApplicantSelected" />
                                                    <span v-if="form.errors.exam_atpp_part2_wrong"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part2_wrong }}
                                                    </span>
                                                    <span v-if="liveErrors.exam_atpp_part2_wrong" class="error-message">
                                                        {{ liveErrors.exam_atpp_part2_wrong }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="atpp-card">
                                            <div class="atpp-card-title">ATPP Part III (Problem Solving)</div>
                                            <div class="form-grid grid-2">
                                                <div class="form-field">
                                                    <label class="field-label">Correct</label>
                                                    <input type="number" step="1" min="0" max="25"
                                                        v-model="form.exam_atpp_part3_correct" placeholder="0"
                                                        class="form-input"
                                                        @input="clampAtppPair(form, 'exam_atpp_part3_correct', 'exam_atpp_part3_wrong', 25)"
                                                        :disabled="!isApplicantSelected" />
                                                    <span v-if="form.errors.exam_atpp_part3_correct"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part3_correct }}
                                                    </span>
                                                    <span v-if="liveErrors.exam_atpp_part3_correct"
                                                        class="error-message">
                                                        {{ liveErrors.exam_atpp_part3_correct }}
                                                    </span>
                                                </div>

                                                <div class="form-field">
                                                    <label class="field-label">Wrong</label>
                                                    <input type="number" step="1" min="0" max="25"
                                                        v-model="form.exam_atpp_part3_wrong" placeholder="0"
                                                        class="form-input"
                                                        @input="clampAtppPair(form, 'exam_atpp_part3_correct', 'exam_atpp_part3_wrong', 25)"
                                                        :disabled="!isApplicantSelected" />
                                                    <span v-if="form.errors.exam_atpp_part3_wrong"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part3_wrong }}
                                                    </span>
                                                    <span v-if="liveErrors.exam_atpp_part3_wrong" class="error-message">
                                                        {{ liveErrors.exam_atpp_part3_wrong }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT SIDE -->
                                <div class="exam-criteria-column">
                                    <div class="criteria-panel">

                                        <template v-if="examCriteriaDisplay">
                                            <div class="criteria-panel-header">
                                                <div class="criteria-panel-title">Exam Criteria</div>
                                            </div>

                                            <!-- 1st Priority (PASSED) -->
                                            <div class="criteria-rule passed">
                                                <div class="criteria-rule-title">Passed (Priority 1)</div>
                                                <ul class="criteria-list">
                                                    <li>ATPP: ≥ {{ examCriteriaDisplay.passed.atpp }}</li>
                                                    <li>Technical: ≥ {{ examCriteriaDisplay.passed.tech_exam }}</li>
                                                </ul>
                                            </div>

                                            <!-- 2nd Priority (P2) -->
                                            <div class="criteria-rule p2">
                                                <div class="criteria-rule-title">Passed (Priority 2)</div>
                                                <ul class="criteria-list">
                                                    <li>ATPP: ≥ {{ examCriteriaDisplay.p2.atpp }}</li>
                                                    <li>Technical: ≥ {{ examCriteriaDisplay.p2.tech_exam }}</li>
                                                </ul>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="form-grid grid-3 pt-5">
                                <div class="form-field">
                                    <label class="field-label !text-gray-500">ATPP Final Result</label>
                                    <input type="number" step="0.01" :value="computedAtppResult" placeholder="0.00"
                                        class="form-input" :disabled="!isApplicantSelected" readonly />
                                    <small class="helper-text">
                                        ATPP Final Result= Total Correct - (Total Wrong / 4)
                                    </small>
                                    <span v-if="form.errors.exam_atpp_result" class="error-message">
                                        {{ form.errors.exam_atpp_result }}
                                    </span>
                                    <span v-if="liveErrors.exam_atpp_result" class="error-message">
                                        {{ liveErrors.exam_atpp_result }}
                                    </span>
                                </div>

                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Technical Exam Result</label>
                                    <input type="number" step="0.01" min="0" max="80"
                                        @input="clampScore(form, 'exam_tech_result', 80)"
                                        v-model="form.exam_tech_result" placeholder="0.00" class="form-input"
                                        :disabled="!isApplicantSelected" />
                                    <span v-if="form.errors.exam_tech_result" class="error-message">{{
                                        form.errors.exam_tech_result }}</span>
                                    <span v-if="liveErrors.exam_tech_result" class="error-message">{{
                                        liveErrors.exam_tech_result }}
                                    </span>
                                </div>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label pt-5 !text-gray-500">Exam Result</label>
                                    <input type="text" class="form-input" :value="examResultLabel || 'Pending'" readonly
                                        :disabled="!isApplicantSelected" />
                                    <span v-if="form.errors.exam_result" class="error-message">{{
                                        form.errors.exam_result }}</span>
                                </div>

                                <div class="form-field">
                                    <label class="field-label pt-5 !text-gray-500">Exam Application Status</label>
                                    <select v-model="form.exam_application_status" class="form-select"
                                        :disabled="!isApplicantSelected">
                                        <option value="">Select Status</option>
                                        <option v-for="status in examStatuses" :key="status.value"
                                            :value="status.value">
                                            {{ status.label }}
                                        </option>
                                    </select>
                                    <span v-if="form.errors.exam_application_status" class="error-message">
                                        {{ form.errors.exam_application_status }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="field-label !text-gray-500">Exam Comments</label>
                                <textarea v-model="form.exam_remarks" placeholder="Enter any remarks here..." rows="3"
                                    class="form-textarea" :disabled="!isApplicantSelected"></textarea>
                                <span v-if="form.errors.exam_remarks" class="error-message">{{ form.errors.exam_remarks
                                    }}</span>
                            </div>
                        </div>

                        <!-- Initial Interview HR/BU -->
                        <div class="form-section" :class="{ 'disabled-section': isFormFieldDisabled }">

                            <div class="section-header">
                                <h3>Initial Interview</h3>
                            </div>

                            <div class="exam-section-layout">
                                <!-- LEFT SIDE -->
                                <div class="exam-form-column">

                                    <div class="form-grid grid-2 mb-6">
                                        <div class="form-field">
                                            <label class="field-label">Initial Interview Plan Date</label>
                                            <input type="datetime-local" v-model="form.initial_interview_plan_date"
                                                class="form-input" :disabled="isFormFieldDisabled"
                                                :min="initialInterviewPlanMin" />
                                        </div>

                                        <div class="form-field">
                                            <label class="field-label">Initial Interview Actual Date</label>
                                            <input type="datetime-local" v-model="form.initial_interview_actual_date"
                                                class="form-input"
                                                :disabled="!form.initial_interview_plan_date || isFormFieldDisabled"
                                                :min="initialInterviewActualMin" />
                                            <span v-if="form.errors.initial_interview_actual_date"
                                                class="error-message">
                                                {{ form.errors.initial_interview_actual_date }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Venue -->
                                    <div class="form-field mb-6">
                                        <label class="field-label">Initial Interview Venue</label>
                                        <select v-model="form.initial_interview_venue" class="form-select"
                                            :disabled="isFormFieldDisabled">

                                            <option value="">Select Venue</option>
                                            <option v-for="venue in examVenues" :key="venue.value" :value="venue.value">
                                                {{ venue.label }}
                                            </option>
                                        </select>

                                        <span v-if="form.errors.initial_interview_venue" class="error-message">
                                            {{ form.errors.initial_interview_venue }}
                                        </span>
                                    </div>

                                    <div class="form-field">
                                        <label class="field-label">Initial Interview Remarks</label>
                                        <textarea v-model="form.initial_interview_remarks" class="form-textarea"
                                            :disabled="isFormFieldDisabled" rows="3"></textarea>
                                        <span v-if="form.errors.initial_interview_remarks" class="error-message">{{
                                            form.errors.initial_interview_remarks }}</span>
                                    </div>

                                </div>


                                <!-- RIGHT SIDE (CRITERIA PANEL - MATCHED STYLE) -->
                                <div class="exam-criteria-column">
                                    <div class="criteria-panel compact">
                                        <div class="criteria-panel-title">
                                            Initial Interview Criteria
                                        </div>

                                        <div class="criteria-rule passed">
                                            <div class="criteria-rule-title">1 - Highly Recommended</div>
                                        </div>

                                        <div class="criteria-rule passed">
                                            <div class="criteria-rule-title">2 - Recommended</div>
                                        </div>

                                        <div class="criteria-rule p2">
                                            <div class="criteria-rule-title">3 - Average</div>
                                        </div>

                                        <div class="criteria-rule failed">
                                            <div class="criteria-rule-title">4 - Not Recommended</div>
                                        </div>

                                        <div class="criteria-rule failed">
                                            <div class="criteria-rule-title">5 - Never Recommended</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- General Remarks -->
                        <div class="form-section" :class="{ 'disabled-section': isFormFieldDisabled }">
                            <div class="section-header">
                                <h3>General Remarks</h3>
                            </div>
                            <div class="form-field">
                                <label class="field-label !text-gray-500">Overall Remarks</label>
                                <textarea v-model="form.remarks" class="form-textarea" :disabled="isFormFieldDisabled"
                                    rows="4" placeholder="Any additional notes or comments..."></textarea>
                                <span v-if="form.errors.remarks" class="error-message">{{ form.errors.remarks }}</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-actions">
                            <Link href="/intermediate/applications" class="btn btn-secondary">Cancel</Link>
                            <button type="submit" :disabled="form.processing || isFormFieldDisabled"
                                class="btn btn-primary">
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
/* Add these styles at the end of your style section */
.option-main {
    font-weight: 500;
    color: #111827;
    margin-bottom: 2px;
}

.option-details {
    display: flex;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: #6b7280;
}

.option-project {
    background: #eff6ff;
    color: #1e40af;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
}

.option-location {
    background: #f0fdf4;
    color: #166534;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
}

.dropdown-option-item {
    padding: 0.625rem 0.875rem;
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    transition: background-color 0.15s ease;
}

.dropdown-option-item:last-child {
    border-bottom: none;
}

.dropdown-option-item:hover {
    background: #f9fafb;
}

.dropdown-option-item.is-selected {
    background: #eff6ff;
    border-left: 3px solid #3b82f6;
}

/* Rest of your existing styles remain the same */
.position-preferences-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    /* Changed from 4 to 5 */
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.position-input {
    height: 36px !important;
    padding: 0.375rem 0.5rem !important;
    font-size: 0.875rem !important;
}

.position-label {
    font-size: 0.8125rem !important;
    line-height: 1.3 !important;
    margin-bottom: 0.25rem !important;
}

@media (max-width: 768px) {
    .position-preferences-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

.compensation-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.5rem;
    margin-top: 1rem;
}

.compensation-grid .form-field {
    margin-bottom: 0.5rem;
}

.compensation-input {
    height: 32px !important;
    padding: 0.25rem 0.5rem !important;
    font-size: 0.8125rem !important;
}

.compensation-label {
    font-size: 0.6875rem !important;
    line-height: 1.2 !important;
    margin-bottom: 0.125rem !important;
}

@media (max-width: 768px) {
    .position-preferences-grid {
        grid-template-columns: 1fr;
        /* Stack on mobile */
    }
}

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

.form-section>.form-grid+.form-grid,
.form-section>.form-grid+.form-field,
.form-section>.form-field+.form-grid,
.form-section>.form-field+.form-field {
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
    color: var(--ats-text);
    line-height: 1.4;
}

.field-label-required {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 600;
    color: black;
    line-height: 1.4;
}

.required::after {
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
    .position-preferences-grid {
        grid-template-columns: repeat(3, 1fr);
        /* 3 columns on tablet */
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

.exam-layout {
    display: grid;
    grid-template-columns: minmax(0, 2fr) 320px;
    gap: 1rem;
    align-items: start;
}

.exam-main {
    min-width: 0;
}

.exam-side {
    min-width: 0;
}

.criteria-side {
    position: sticky;
    top: 1rem;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
    font-size: 0.85rem;
}

.criteria-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.criteria-category {
    font-size: 0.75rem;
    color: #6b7280;
    margin-bottom: 0.75rem;
}

.criteria-section {
    margin-bottom: 0.75rem;
    padding: 0.5rem;
    border-radius: 0.375rem;
}

.criteria-label {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.criteria-section.passed {
    background: #ecfdf5;
    color: #065f46;
}

.criteria-section.p2 {
    background: #fffbeb;
    color: #92400e;
}

.criteria-section.failed {
    background: #fef2f2;
    color: #991b1b;
}

@media (max-width: 1024px) {
    .exam-layout {
        grid-template-columns: 1fr;
    }

    .criteria-side {
        position: static;
    }
}

.exam-section-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 1.25rem;
    align-items: start;
    margin-top: 1rem;
}

.exam-form-column {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.exam-criteria-column {
    min-width: 0;
}

.atpp-stack {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.atpp-card {
    padding: 1rem;
    border: 1px solid var(--ats-border-soft);
    border-radius: 0.75rem;
    background: #fafafa;
}

.atpp-card-title {
    margin-bottom: 0.875rem;
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
}

.helper-text {
    font-size: 0.75rem;
    color: var(--ats-text-muted);
    line-height: 1.4;
}

.criteria-panel {
    position: sticky;
    top: 1rem;
    padding: 1rem;
    border: 1px solid var(--ats-border-soft);
    border-radius: 0.75rem;
    background: #ffffff;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

.criteria-panel-header {
    margin-bottom: 1rem;
}

.criteria-panel-title {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
}

.criteria-panel-subtitle {
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: var(--ats-text-muted);
    letter-spacing: 0.02em;
}

.criteria-formula {
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.625rem;
}

.criteria-formula-title {
    margin-bottom: 0.35rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #334155;
    text-transform: uppercase;
}

.criteria-formula-text {
    font-size: 0.78rem;
    line-height: 1.5;
    color: #475569;
}

.criteria-rule {
    margin-bottom: 0.875rem;
    padding: 0.85rem;
    border-radius: 0.625rem;
}

.criteria-rule:last-child {
    margin-bottom: 0;
}

.criteria-rule-title {
    font-size: 0.82rem;
    font-weight: 700;
}

.criteria-list {
    margin: 0;
    padding-left: 1rem;
    font-size: 0.8rem;
    line-height: 1.5;
}

.criteria-list li+li {
    margin-top: 0.2rem;
}

.criteria-rule.passed {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #bbf7d0;
}

.criteria-rule.p2 {
    background: #fffbeb;
    color: #92400e;
    border: 1px solid #fde68a;
}

.criteria-rule.failed {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.criteria-empty {
    padding: 0.85rem;
    font-size: 0.8rem;
    line-height: 1.5;
    color: #475569;
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 0.625rem;
}

@media (max-width: 1100px) {
    .exam-section-layout {
        grid-template-columns: 1fr;
    }

    .exam-criteria-column {
        order: -1;
    }

    .criteria-panel {
        position: static;
    }
}

.criteria-panel.compact {
    padding: 0.75rem;
}

.criteria-panel.compact .criteria-rule {
    padding: 0.5rem;
    margin-bottom: 0.5rem;
}

.criteria-panel.compact .criteria-rule-title {
    font-size: 0.75rem;
}

.criteria-panel.compact .criteria-panel-title {
    font-size: 0.9rem;
}

.criteria-panel.compact .criteria-panel-header {
    margin-bottom: 0.5rem;
}

/* Add these toast notification styles at the beginning of your style section */
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
}

.alert-error-banner {
    background-color: #dc2626;
    /* error red */
}

.alert-success-banner {
    background-color: #10b981;
    /* success green */
}

.close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0 0.5rem;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.close-btn:hover {
    opacity: 1;
}
</style>
