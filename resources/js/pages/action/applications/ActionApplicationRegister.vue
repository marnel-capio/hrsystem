<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, usePage, Link } from '@inertiajs/vue3'
import { ref, watch, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const page = usePage()

const props = defineProps<{
    actionBatches?: Record<number, string>,
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
    finalInterviewAssignments?: Array<{
    id: number
    interviewer_id: number
    name: string
    role_label: string
    score?: number | null
    evaluation_result?: number | null
    evaluation_remarks?: string | null
}>
canEditFinalInterviewDecision?: boolean
user_permissions?: number
user_id?: number
    flash?: {
        error?: string
        success?: string
    }
}>()

const actionBatches = ref<Array<{ value: number, label: string }>>([])
const examVenues = ref<Array<{ value: number, label: string }>>([])
const examResults = ref<Array<{ value: number, label: string }>>([])
const examStatuses = ref<Array<{ value: number, label: string }>>([])
const interviewResults = ref<Array<{ value: number, label: string }>>([])
const interviewAppStatuses = ref<Array<{ value: number, label: string }>>([])
const jobOfferStatuses = ref<Array<{ value: number, label: string }>>([])

const actionApplicants = ref<Array<{
    value: number
    label: string
    age: number | null
    degree: string
}>>([])

const sortedActionBatches = computed(() => {
    return [...actionBatches.value].sort((a, b) => {
        return b.label.localeCompare(a.label) // DESC
    })
})

const noApplicantsError = ref<string>('')

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

const form = useForm({
    action_applicant_id: '',
    action_batch_id: '',
    upload_resume: '',
    upload_tor: '',
    upload_pic: '',
    exam_plan_date: '',
    exam_actual_date: '',
    exam_venue: '',
exam_atpp_part1_correct: '',
exam_atpp_part1_wrong: '',
exam_atpp_part2_correct: '',
exam_atpp_part2_wrong: '',
exam_atpp_part3_correct: '',
exam_atpp_part3_wrong: '',
exam_atpp_result: '',    exam_git_result: '',
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
    final_interview_assignments: (props.finalInterviewAssignments || []).map((row) => ({
    id: row.id,
    interviewer_id: row.interviewer_id,
    name: row.name,
    role_label: row.role_label,
    score: row.score ?? '',
    evaluation_result: row.evaluation_result ?? '',
    evaluation_remarks: row.evaluation_remarks ?? '',
})),
    final_interview_final: '',
    final_interview_result: '',
    final_interview_application_status: '',
    final_interview_remarks: '',
    job_offer_schedule: '',
    job_offer_status: '',
    job_offer_remarks: '',
    remarks: '',
})

const resumeFile = ref<File | null>(null)
const torFile = ref<File | null>(null)
const pictureFile = ref<File | null>(null)

const resumePreview = ref<string | null>(null)
const torPreview = ref<string | null>(null)
const picturePreview = ref<string | null>(null)

const errorMessage = computed(() => (page.props.flash as any)?.error || '')
const showError = ref(false)
const successMessage = computed(() => (page.props.flash as any)?.success || '')
const showSuccess = ref(false)

const isDropdownOpen = ref(false)
const searchQuery = ref('')

const isApplicantSelected = computed(() => !!form.action_applicant_id)

const selectedApplicant = computed(() => {
    return actionApplicants.value.find(a => a.value === Number(form.action_applicant_id)) || null
})

const selectedApplicantLabel = computed(() => {
    const selected = actionApplicants.value.find(a => a.value === Number(form.action_applicant_id))
    return selected ? selected.label : ''
})

const filteredApplicants = computed(() => {
    if (!searchQuery.value.trim()) {
        return actionApplicants.value
    }

    const query = searchQuery.value.toLowerCase()
    return actionApplicants.value.filter(applicant =>
        applicant.label.toLowerCase().includes(query)
    )
})

const liveErrors = ref<Record<string, string>>({})

const finalScoreManuallyEdited = ref(false)

function parseScore(value: string | number | null | undefined): number {
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

const isInitialBlocked = computed(() =>
    Number(props.application.exam_result) === 3
)

const isFinalBlocked = computed(() =>
    Number(props.application.exam_result) === 3 ||
    Number(props.application.initial_interview_result) === 3
)

const isJobOfferBlocked = computed(() =>
    Number(props.application.exam_result) === 3 ||
    Number(props.application.initial_interview_result) === 3 ||
    Number(props.application.final_interview_result) === 3
)

const canEditFinalInterviewDecision = computed(() => !!props.canEditFinalInterviewDecision)

const isHrDecisionEditor = computed(() => !!props.canEditFinalInterviewDecision)

const visibleFinalInterviewAssignments = computed(() => {
    const rows = form.final_interview_assignments || []

    return rows.filter((row: any) => {
        const isApproved = Number(row.schedule_approved) === 1

        if (!isApproved) return false

        if (isHrDecisionEditor.value) {
            return true
        }

        return Number(row.interviewer_id) === Number(props.user_id || 0)
    })
})
const finalInterviewEvaluatedRows = computed(() => {
    return (form.final_interview_assignments || []).filter((row: any) =>
        [2, 3].includes(Number(row.evaluation_result))
    )
})

const allFinalInterviewersPassed = computed(() => {
    const rows = finalInterviewEvaluatedRows.value
    return rows.length > 0 && rows.every((row: any) => Number(row.evaluation_result) === 2)
})

const allFinalInterviewersFailed = computed(() => {
    const rows = finalInterviewEvaluatedRows.value
    return rows.length > 0 && rows.every((row: any) => Number(row.evaluation_result) === 3)
})

const hasMixedFinalInterviewResults = computed(() => {
    const rows = finalInterviewEvaluatedRows.value
    const hasPassed = rows.some((row: any) => Number(row.evaluation_result) === 2)
    const hasFailed = rows.some((row: any) => Number(row.evaluation_result) === 3)
    return hasPassed && hasFailed
})

watch(computedAtppResult, (value) => {
    form.exam_atpp_result = value
})

watch(
    () => form.final_interview_assignments,
    (rows) => {
        const list = rows || []

        const numericScores = list
            .map((row: any) => Number(row.score))
            .filter((value: number) => !Number.isNaN(value))

        if (numericScores.length === 0) {
            if (!finalScoreManuallyEdited.value) {
                form.final_interview_final = ''
            }
            return
        }

        if (!finalScoreManuallyEdited.value) {
            const average =
                numericScores.reduce((sum: number, value: number) => sum + value, 0) / numericScores.length

            form.final_interview_final = average.toFixed(2)
        }

        if (allFinalInterviewersPassed.value) {
            form.final_interview_result = '2'
            form.final_interview_application_status = '3'
            return
        }

        if (allFinalInterviewersFailed.value) {
            form.final_interview_result = '3'
            form.final_interview_application_status = '5'
            return
        }

        if (hasMixedFinalInterviewResults.value) {
            form.final_interview_application_status = '2'
            if (!isHrDecisionEditor.value) {
                form.final_interview_result = ''
            }
            return
        }

        if (form.final_interview_date) {
            form.final_interview_application_status = '1'
        } else {
            form.final_interview_application_status = ''
            form.final_interview_result = ''
        }
    },
    { deep: true }
)

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

function handleFinalScoreManualInput() {
    finalScoreManuallyEdited.value = true
}

function setLiveError(field: string, message: string) {
    liveErrors.value[field] = message
}

function clearLiveError(field: string) {
    delete liveErrors.value[field]
}

function validateScoreField(field: string, label: string, value: string | number) {
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

watch(() => form.exam_atpp_result, (value) => {
    validateScoreField('exam_atpp_result', 'ATPP Result', value)
})

watch(() => form.exam_git_result, (value) => {
    validateScoreField('exam_git_result', 'GIT Result', value)
})

watch(() => form.exam_prg_result, (value) => {
    validateScoreField('exam_prg_result', 'PRG Result', value)
})

watch(() => form.initial_interview_final, (value) => {
    validateScoreField('initial_interview_final', 'Initial Interview Final Score', value)
})

watch(
    () => form.final_interview_assignments,
    (rows) => {
        ;(rows || []).forEach((row: any, index: number) => {
            validateScoreField(
                `final_interview_assignments.${index}.score`,
                `${row.name || 'Interviewer'} Score`,
                row.score
            )
        })
    },
    { deep: true }
)

watch(() => form.final_interview_final, (value) => {
    validateScoreField('final_interview_final', 'Final Score', value)
})

function formatDateTimeLocal(value: Date) {
    const year = value.getFullYear()
    const month = String(value.getMonth() + 1).padStart(2, '0')
    const day = String(value.getDate()).padStart(2, '0')
    const hours = String(value.getHours()).padStart(2, '0')
    const minutes = String(value.getMinutes()).padStart(2, '0')

    return `${year}-${month}-${day}T${hours}:${minutes}`
}

function addOneMinute(value: string) {
    if (!value) return ''
    const date = new Date(value)
    if (Number.isNaN(date.getTime())) return ''
    date.setMinutes(date.getMinutes() + 1)
    return formatDateTimeLocal(date)
}

const initialInterviewPlanMin = computed(() => {
    return form.exam_plan_date ? addOneMinute(form.exam_plan_date) : ''
})
function parseDateTimeLocal(value: string): Date | null {
    return value ? new Date(value) : null
}

function tomorrowStart(): string {
    const now = new Date()
    now.setHours(0, 0, 0, 0)
    now.setDate(now.getDate() + 1)
    return formatDateTimeLocal(now)
}

const examPlanMin = computed(() => tomorrowStart())
const examActualMin = computed(() => form.exam_plan_date || undefined)
const initialInterviewActualMin = computed(() => form.initial_interview_plan_date || undefined)
const finalInterviewMin = computed(() => form.initial_interview_plan_date || undefined)
const jobOfferMin = computed(() => form.final_interview_date || undefined)

const examCriteriaDisplay = computed(() => {
    if (!selectedApplicant.value) return null

    const category = getExamCategory(selectedApplicant.value)
    const rules = props.applicationScoreRules?.exam?.[category]

    if (!rules) return null

    return {
        category,
        passed: rules.passed,
        p2: rules.p2,
    }
})

watch(() => form.exam_plan_date, (planDate) => {
    if (!planDate) return

    if (form.exam_actual_date) {
        const actual = parseDateTimeLocal(form.exam_actual_date)
        const plan = parseDateTimeLocal(planDate)
        if (actual && plan && actual < plan) {
            form.exam_actual_date = ''
        }
    }
})

watch(() => form.initial_interview_plan_date, (planDate) => {
    if (!planDate) return

    if (form.initial_interview_actual_date) {
        const actual = parseDateTimeLocal(form.initial_interview_actual_date)
        const plan = parseDateTimeLocal(planDate)
        if (actual && plan && actual < plan) {
            form.initial_interview_actual_date = ''
        }
    }

    if (form.final_interview_date) {
        const finalDate = parseDateTimeLocal(form.final_interview_date)
        const plan = parseDateTimeLocal(planDate)
        if (finalDate && plan && finalDate < plan) {
            form.final_interview_date = ''
        }
    }
})

watch(() => form.final_interview_date, (finalDate) => {
    if (!finalDate) return

    if (form.job_offer_schedule) {
        const schedule = parseDateTimeLocal(form.job_offer_schedule)
        const final = parseDateTimeLocal(finalDate)
        if (schedule && final && schedule < final) {
            form.job_offer_schedule = ''
        }
    }
})

onMounted(() => {
    if (props.actionBatches) {
        actionBatches.value = Object.entries(props.actionBatches).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }))
    }

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

    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
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

watch(() => form.action_batch_id, async (newBatchId) => {
    if (!newBatchId) {
        noApplicantsError.value = ''
        actionApplicants.value = []
        form.action_applicant_id = ''
        return
    }

    noApplicantsError.value = ''
    form.action_applicant_id = ''

    try {
        const response = await axios.get(`/action/applications/eligible-applicants/${newBatchId}`)

        if (!response.data || response.data.length === 0) {
            noApplicantsError.value = 'No eligible applicants found for this batch.'
            actionApplicants.value = []
            return
        }

        actionApplicants.value = response.data.map((applicant: any) => ({
            value: Number(applicant.value),
            label: String(applicant.label),
            age: applicant.age !== null && applicant.age !== undefined ? Number(applicant.age) : null,
            degree: String(applicant.degree || ''),
        }))

        noApplicantsError.value = ''
    } catch (error: any) {
        console.error('Failed to load eligible applicants:', error)
        noApplicantsError.value = error.response?.data?.error || 'Failed to load eligible applicants. Please try again.'
        actionApplicants.value = []
    }
})

watch(() => form.action_applicant_id, async (newApplicantId, oldApplicantId) => {
    if (newApplicantId && form.action_batch_id) {
        try {
            const response = await axios.post('/action/applications/check-eligibility', {
                action_applicant_id: newApplicantId,
                action_batch_id: form.action_batch_id,
            })

            if (!response.data.eligible) {
                form.setError('action_applicant_id', 'This applicant cannot apply at this time. A previous application from the last 6 months shows a failed status.')
                form.action_applicant_id = ''
                return
            } else {
                form.clearErrors('action_applicant_id')
            }
        } catch (error) {
            console.error('Failed to check eligibility:', error)
        }
    }

    if (oldApplicantId && newApplicantId !== oldApplicantId) {
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
        form.exam_git_result = ''
        form.exam_prg_result = ''
        form.exam_application_status = ''
        form.exam_result = ''
        form.exam_remarks = ''

        form.initial_interview_plan_date = ''
        form.initial_interview_actual_date = ''
        form.initial_interview_venue = ''
        form.initial_interview_final = ''
        form.initial_interview_application_status = ''
        form.initial_interview_result = ''
        form.initial_interview_remarks = ''

        form.final_interview_date = ''
        form.final_interview_final = ''
        form.final_interview_application_status = ''
        form.final_interview_result = ''
        form.final_interview_remarks = ''

        form.job_offer_schedule = ''
        form.job_offer_status = ''
        form.job_offer_remarks = ''
        form.remarks = ''

        removeFile('resume')
        removeFile('tor')
        removeFile('picture')

        const fileInputs = document.querySelectorAll('input[type="file"]')
        fileInputs.forEach((input: any) => {
            if (input) input.value = ''
        })
    }
})

function normalizeDegree(degree: string): string {
    return String(degree || '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s]/g, ' ')
        .replace(/\s+/g, ' ')
}

function isTechDegree(degree: string): boolean {
    const normalizedDegree = normalizeDegree(degree)

    const patterns = [
        'bs information technology',
        'bachelor of science in information technology',
        'bachelor of science major in information technology',
        'information technology',
        'bsit',
        'it',
        'bs it',

        'bs computer science',
        'bachelor of science in computer science',
        'bachelor of science major in computer science',
        'computer science',
        'bscs',
        'cs',
        'bs cs',

        'bs computer engineering',
        'bachelor of science in computer engineering',
        'bachelor of science major in computer engineering',
        'computer engineering',
        'bscpe',
        'cpe',
        'bs cpe',
    ]

    for (const pattern of patterns) {
        const normalizedPattern = normalizeDegree(pattern)

        if (!normalizedPattern) continue

        if (['it', 'cs', 'cpe', 'bsit', 'bscs', 'bscpe'].includes(normalizedPattern)) {
            const regex = new RegExp(`\\b${normalizedPattern.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}\\b`)
            if (regex.test(normalizedDegree)) {
                return true
            }
            continue
        }

        if (normalizedDegree.includes(normalizedPattern)) {
            return true
        }
    }

    return false
}

function getExamCategory(applicant: any): 'young_it' | 'young_other' | 'adult' {
    if (!applicant) return 'young_other'

    const age = Number(applicant.age || 0)
    const rawDegree = applicant.others_degree || applicant.degree || applicant.course || ''

    if (age >= 25) {
        return 'adult'
    }

    return isTechDegree(rawDegree) ? 'young_it' : 'young_other'
}



function getExamApplicationStatus(attp: number, git: number, prg: number, category: 'young_it' | 'young_other' | 'adult'): string {
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

    const passedMax = Number(rules?.passed_min ?? 2.0)
    const p2Max = Number(rules?.p2_min ?? 2.5)
    const failedMax = 5.0

    if (score === 0) return '1'
    if (score > 0 && score <= passedMax) return '3'
    if (score > passedMax && score <= p2Max) return '4'
    if (score > p2Max && score <= failedMax) return '5'

    return '1'
}

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
        () => selectedApplicant.value,
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

watch(() => form.final_interview_date, (newPlanDate) => {
    if (newPlanDate && !form.final_interview_application_status) {
        form.final_interview_application_status = '1'
    }

    if (!newPlanDate && form.final_interview_application_status === '1') {
        form.final_interview_application_status = ''
    }
})

watch(() => form.job_offer_schedule, (newSchedule) => {
    if (newSchedule && !form.job_offer_status) {
        form.job_offer_status = '1'
    }

    if (!newSchedule && form.job_offer_status === '1') {
        form.job_offer_status = ''
    }
})

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

const handleResumeUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    if (target.files && target.files[0]) {
        const file = target.files[0]
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
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

        if (file.type === 'application/pdf') {
            if (resumePreview.value) URL.revokeObjectURL(resumePreview.value)
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
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
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

        if (file.type === 'application/pdf' || file.type.startsWith('image/')) {
            if (torPreview.value) URL.revokeObjectURL(torPreview.value)
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

        if (picturePreview.value) URL.revokeObjectURL(picturePreview.value)
        picturePreview.value = URL.createObjectURL(file)

        form.clearErrors('upload_pic')
    }
}

const removeFile = (type: 'resume' | 'tor' | 'picture') => {
    switch (type) {
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

function submit() {
    form.clearErrors()

    let hasError = false

    if (!form.action_applicant_id) {
        form.setError('action_applicant_id', 'This field is required.')
        hasError = true
    }

    if (!form.action_batch_id) {
        form.setError('action_batch_id', 'This field is required.')
        hasError = true
    }

    if (hasError) return

    form.transform((data) => {
        const formData = new FormData()

        Object.keys(data).forEach((key) => {
            if (key !== 'upload_resume' && key !== 'upload_tor' && key !== 'upload_pic') {
                const value = data[key as keyof typeof data]
                if (value !== null && value !== undefined && value !== '') {
                    formData.append(key, String(value))
                }
            }
        })

        if (resumeFile.value) {
            formData.append('upload_resume', resumeFile.value)
        }

        if (torFile.value) {
            formData.append('upload_tor', torFile.value)
        }

        if (pictureFile.value) {
            formData.append('upload_pic', pictureFile.value)
        }

        return formData as any
    })

    form.post('/action/applications', {
        preserveState: true,
        preserveScroll: true,
        onError: (errors) => {
            console.error('Submission errors:', errors)
        },
        onSuccess: () => {
            form.reset()
            actionApplicants.value = []
            noApplicantsError.value = ''
            finalScoreManuallyEdited.value = false

            removeFile('resume')
            removeFile('tor')
            removeFile('picture')

            const fileInputs = document.querySelectorAll('input[type="file"]')
            fileInputs.forEach((input: any) => {
                if (input) input.value = ''
            })
        },
    })
}

function toggleDropdown() {
    if (!form.action_batch_id || actionApplicants.value.length === 0) return

    isDropdownOpen.value = !isDropdownOpen.value

    if (isDropdownOpen.value) {
        searchQuery.value = ''
    }
}

function selectApplicant(applicant: { value: number, label: string }) {
    form.action_applicant_id = String(applicant.value)
    isDropdownOpen.value = false
    searchQuery.value = ''
}

function handleClickOutside(event: MouseEvent) {
    const target = event.target as HTMLElement
    if (!target.closest('.custom-select-wrapper')) {
        isDropdownOpen.value = false
    }
}

function getFinalInterviewApplicationStatus(score: number): string {
    const rules = props.applicationScoreRules?.initial_interview

    const passedMax = Number(rules?.passed_min ?? 2.0)
    const p2Max = Number(rules?.p2_min ?? 2.5)
    const failedMax = 5.0

    if (score === 0) return '1'
    if (score > 0 && score <= passedMax) return '3'
    if (score > passedMax && score <= p2Max) return '4'
    if (score > p2Max && score <= failedMax) return '5'

    return '1'
}

function clampAtppPair(obj: any, correctField: string, wrongField: string, max: number) {
    let correct = Number(obj[correctField] || 0)
    let wrong = Number(obj[wrongField] || 0)

    if (Number.isNaN(correct)) correct = 0
    if (Number.isNaN(wrong)) wrong = 0

    // Prevent negatives
    if (correct < 0) correct = 0
    if (wrong < 0) wrong = 0

    // Enforce total cap
    if (correct + wrong > max) {
        // prioritize the field being edited by reducing the other
        const excess = correct + wrong - max

        if (document.activeElement?.name === correctField) {
            wrong = Math.max(0, wrong - excess)
        } else {
            correct = Math.max(0, correct - excess)
        }
    }

    obj[correctField] = correct
    obj[wrongField] = wrong
}

watch(
    [
        () => form.final_interview_final,
        () => form.final_interview_date,
    ],
    ([score, finalDate]) => {
        if (!score) {
            form.final_interview_application_status = finalDate ? '1' : ''
            form.final_interview_result = finalDate ? '1' : ''
            return
        }

        const numericScore = Number(score)

        if (Number.isNaN(numericScore)) {
            form.final_interview_application_status = finalDate ? '1' : ''
            form.final_interview_result = finalDate ? '1' : ''
            return
        }

        form.final_interview_application_status = getFinalInterviewApplicationStatus(numericScore)
    }
)
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
                <h2 class="page-title">Create ACTION Application</h2>
            </div>

            <div class="form-wrapper">
                <div class="form-card">
                    <form @submit.prevent="submit">
                        <div class="form-section">
                            <div class="section-header">
                                <h3>Basic Information</h3>
                            </div>

                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label-required required">ACTION Batch</label>
                                    <select v-model="form.action_batch_id" class="form-select">
                                        <option disabled value="">Select Batch</option>
                                            <option
                                                v-for="batch in sortedActionBatches"
                                                :key="batch.value"
                                                :value="batch.value"
                                            >
                                                {{ batch.label }}
                                            </option>
                                    </select>
                                    <span v-if="form.errors.action_batch_id" class="error-message">{{ form.errors.action_batch_id }}</span>
                                </div>

                                <div class="form-field">
                                    <label class="field-label-required required">ACTION Applicant</label>

                                    <div class="custom-select-wrapper" :class="{ 'is-open': isDropdownOpen }">
                                        <div
                                            class="custom-select-trigger"
                                            @click="toggleDropdown"
                                            :class="{ 'is-disabled': !form.action_batch_id || actionApplicants.length === 0 }"
                                        >
                                            <span class="custom-select-value">
                                                {{ selectedApplicantLabel || 'Select Applicant' }}
                                            </span>
                                            <svg class="custom-select-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>

                                        <div class="custom-select-dropdown" v-show="isDropdownOpen">
                                            <div class="dropdown-search">
                                                <input
                                                    type="text"
                                                    v-model="searchQuery"
                                                    placeholder="Search applicants..."
                                                    class="dropdown-search-input"
                                                    @click.stop
                                                />
                                            </div>

                                            <div class="dropdown-options-list">
                                                <div
                                                    v-for="applicant in filteredApplicants"
                                                    :key="applicant.value"
                                                    class="dropdown-option-item"
                                                    :class="{ 'is-selected': Number(form.action_applicant_id) === applicant.value }"
                                                    @click="selectApplicant(applicant)"
                                                >
                                                    {{ applicant.label }}
                                                </div>

                                                <div v-if="filteredApplicants.length === 0" class="dropdown-empty-item">
                                                    No applicants found
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <span v-if="noApplicantsError" class="error-message">{{ noApplicantsError }}</span>
                                    <span v-if="form.errors.action_applicant_id" class="error-message">{{ form.errors.action_applicant_id }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-header">
                                <h3>Upload Documents</h3>
                            </div>

                            <div class="form-grid grid-3">
                                <div class="form-field">
                                    <label class="field-label">Upload Resume</label>
                                    <input
                                        type="file"
                                        @change="handleResumeUpload"
                                        accept=".pdf,.doc,.docx"
                                        class="file-input-btn w-full"
                                        :disabled="!isApplicantSelected || form.processing"
                                    />
                                    <div v-if="form.upload_resume" class="file-info">
                                        <span class="file-name">{{ form.upload_resume }}</span>
                                        <button type="button" @click="removeFile('resume')" class="remove-file" title="Remove file">×</button>
                                    </div>
                                    <span v-if="form.errors.upload_resume" class="error-message">{{ form.errors.upload_resume }}</span>
                                    <div v-if="resumePreview" class="file-preview">
                                        <a :href="resumePreview" target="_blank" class="preview-link">Preview PDF</a>
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label class="field-label">Upload TOR</label>
                                    <input
                                        type="file"
                                        @change="handleTorUpload"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                        class="file-input-btn w-full"
                                        :disabled="!isApplicantSelected || form.processing"
                                    />
                                    <div v-if="form.upload_tor" class="file-info">
                                        <span class="file-name">{{ form.upload_tor }}</span>
                                        <button type="button" @click="removeFile('tor')" class="remove-file" title="Remove file">×</button>
                                    </div>
                                    <span v-if="form.errors.upload_tor" class="error-message">{{ form.errors.upload_tor }}</span>
                                    <div v-if="torPreview" class="file-preview">
                                        <a v-if="torFile && torFile.type === 'application/pdf'" :href="torPreview" target="_blank" class="preview-link">Preview PDF</a>
                                        <img v-else-if="torFile && torFile.type.startsWith('image/')" :src="torPreview" alt="TOR preview" class="preview-image" />
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label class="field-label">Upload 2x2 Pic</label>
                                    <input
                                        type="file"
                                        @change="handlePictureUpload"
                                        accept="image/jpeg,image/png,image/jpg"
                                        class="file-input-btn w-full"
                                        :disabled="!isApplicantSelected || form.processing"
                                    />
                                    <div v-if="form.upload_pic" class="file-info">
                                        <span class="file-name">{{ form.upload_pic }}</span>
                                        <button type="button" @click="removeFile('picture')" class="remove-file" title="Remove file">×</button>
                                    </div>
                                    <span v-if="form.errors.upload_pic" class="error-message">{{ form.errors.upload_pic }}</span>
                                    <div v-if="picturePreview" class="picture-preview">
                                        <img :src="picturePreview" alt="Picture preview" class="preview-image" />
                                    </div>
                                </div>
                            </div>
                        </div>

<div class="text-gray-500 field-label pb-6">(You may leave the following fields empty if this applicant has not yet started the recruitment process.)</div>

<div class="form-section">
    <div class="section-header">
        <h3>Exam Details</h3>
    </div>
            <div class="form-grid grid-2">
                <div class="form-field">
                    <label class="field-label">Exam Plan Date</label>
                    <input
                        type="datetime-local"
                        v-model="form.exam_plan_date"
                        class="form-input"
                        :disabled="!isApplicantSelected"
                        :min="examPlanMin"
                    />
                    <span v-if="form.errors.exam_plan_date" class="error-message">
                        {{ form.errors.exam_plan_date }}
                    </span>
                </div>

                <div class="form-field">
                    <label class="field-label">Exam Actual Date</label>
                    <input
                        type="datetime-local"
                        v-model="form.exam_actual_date"
                        class="form-input"
                        :disabled="!isApplicantSelected"
                        :min="examActualMin"
                    />
                    <span v-if="form.errors.exam_actual_date" class="error-message">
                        {{ form.errors.exam_actual_date }}
                    </span>
                </div>
            </div>

            <div class="form-field">
                <label class="field-label">Exam Venue</label>
                <select v-model="form.exam_venue" class="form-select" :disabled="!isApplicantSelected">
                    <option value="">Select Venue</option>
                    <option v-for="venue in examVenues" :key="venue.value" :value="venue.value">
                        {{ venue.label }}
                    </option>
                </select>
                <span v-if="form.errors.exam_venue" class="error-message">{{ form.errors.exam_venue }}</span>
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
                            <input
                                type="number"
                                step="1"
                                min="0"
                                max="40"
                                v-model="form.exam_atpp_part1_correct"
                                placeholder="0"
                                class="form-input"
                                @input="
                                                            clampAtppPair(
                                                                form,
                                                                'exam_atpp_part1_correct',
                                                                'exam_atpp_part1_wrong',
                                                                40,
                                                            )
                                                        "
                                :disabled="!isApplicantSelected"
                            />
                            <span v-if="form.errors.exam_atpp_part1_correct" class="error-message">
                                {{ form.errors.exam_atpp_part1_correct }}
                            </span>
                            <span v-if="liveErrors.exam_atpp_part1_correct" class="error-message">
                                {{ liveErrors.exam_atpp_part1_correct }}
                            </span>
                        </div>

                        <div class="form-field">
                            <label class="field-label">Wrong</label>
                            <input
                                type="number"
                                step="1"
                                min="0"
                                max="40"
                                v-model="form.exam_atpp_part1_wrong"
                                placeholder="0"
                                class="form-input"
                                @input="
                                                            clampAtppPair(
                                                                form,
                                                                'exam_atpp_part1_correct',
                                                                'exam_atpp_part1_wrong',
                                                                40,
                                                            )
                                                        "
                                :disabled="!isApplicantSelected"
                            />
                            <span v-if="form.errors.exam_atpp_part1_wrong" class="error-message">
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
                            <input
                                type="number"
                                step="1"
                                min="0"
                                max="30"
                                v-model="form.exam_atpp_part2_correct"
                                placeholder="0"
                                class="form-input"
                                @input="clampAtppPair(form, 'exam_atpp_part2_correct', 'exam_atpp_part2_wrong', 30)"                                :disabled="!isApplicantSelected"
                            />
                            <span v-if="form.errors.exam_atpp_part2_correct" class="error-message">
                                {{ form.errors.exam_atpp_part2_correct }}
                            </span>
                            <span v-if="liveErrors.exam_atpp_part2_correct" class="error-message">
                                {{ liveErrors.exam_atpp_part2_correct }}
                            </span>
                        </div>

                        <div class="form-field">
                            <label class="field-label">Wrong</label>
                            <input
                                type="number"
                                step="1"
                                min="0"
                                max="30"
                                v-model="form.exam_atpp_part2_wrong"
                                placeholder="0"
                                class="form-input"
                                @input="clampAtppPair(form, 'exam_atpp_part2_correct', 'exam_atpp_part2_wrong', 30)"                                :disabled="!isApplicantSelected"
                            />
                            <span v-if="form.errors.exam_atpp_part2_wrong" class="error-message">
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
                            <input
                                type="number"
                                step="1"
                                min="0"
                                max="25"
                                v-model="form.exam_atpp_part3_correct"
                                placeholder="0"
                                class="form-input"
                                @input="clampAtppPair(form, 'exam_atpp_part3_correct', 'exam_atpp_part3_wrong', 25)"
                                :disabled="!isApplicantSelected"
                            />
                            <span v-if="form.errors.exam_atpp_part3_correct" class="error-message">
                                {{ form.errors.exam_atpp_part3_correct }}
                            </span>
                            <span v-if="liveErrors.exam_atpp_part3_correct" class="error-message">
                                {{ liveErrors.exam_atpp_part3_correct }}
                            </span>
                        </div>

                        <div class="form-field">
                            <label class="field-label">Wrong</label>
                            <input
                                type="number"
                                step="1"
                                min="0"
                                max="25"
                                v-model="form.exam_atpp_part3_wrong"
                                placeholder="0"
                                class="form-input"
                                @input="clampAtppPair(form, 'exam_atpp_part3_correct', 'exam_atpp_part3_wrong', 25)"
                                :disabled="!isApplicantSelected"
                            />
                            <span v-if="form.errors.exam_atpp_part3_wrong" class="error-message">
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



                    <div class="criteria-rule passed">
                        <div class="criteria-rule-title">PASSED</div>
                        <ul class="criteria-list">
                            <li>ATPP must be at least {{ examCriteriaDisplay.passed.attp }}</li>
                            <li>GIT must be at least {{ examCriteriaDisplay.passed.git }}</li>
                            <li>PRG must be at least {{ examCriteriaDisplay.passed.prg }}</li>
                        </ul>
                    </div>

                    <div v-if="examCriteriaDisplay.p2" class="criteria-rule p2">
                        <div class="criteria-rule-title">P2</div>
                        <ul class="criteria-list">
                            <li>ATPP must be at least {{ examCriteriaDisplay.p2.attp }}</li>
                            <li>GIT must be at least {{ examCriteriaDisplay.p2.git }}</li>
                            <li>PRG must be at least {{ examCriteriaDisplay.p2.prg }}</li>
                        </ul>
                    </div>

                    <div class="criteria-rule failed">
                        <div class="criteria-rule-title">FAILED</div>
                        <ul class="criteria-list">
                            <li>ATPP is below {{ examCriteriaDisplay.p2?.attp ?? examCriteriaDisplay.passed.attp }}</li>
                            <li>or GIT is below {{ examCriteriaDisplay.p2?.git ?? examCriteriaDisplay.passed.git }}</li>
                            <li>or PRG is below {{ examCriteriaDisplay.p2?.prg ?? examCriteriaDisplay.passed.prg }}</li>
                        </ul>
                    </div>
                </template>

                <template v-else>
                    <div class="criteria-panel-header">
                        <div class="criteria-panel-title">Exam Criteria</div>
                        <div class="criteria-panel-subtitle">Select an applicant first</div>
                    </div>

                    <div class="criteria-empty">
                        The applicable criteria depends on the applicant's age and degree category.
                    </div>
                </template>
            </div>
        </div>
    </div>
                <div class="form-grid grid-3 pt-5">
                <div class="form-field">
                    <label class="field-label">ATPP Final Result</label>
                    <input
                        type="number"
                        step="0.01"
                        :value="computedAtppResult"
                        placeholder="0.00"
                        class="form-input"
                        :disabled="!isApplicantSelected"
                        readonly
                    />
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
                    <label class="field-label">GIT Result</label>
                    <input
                        type="number"
                        step="0.01"
                                                                 min="0"
                                        max="12"
                                        @input="clampScore(form, 'exam_git_result', 12)"
                        v-model="form.exam_git_result"
                        placeholder="0.00"
                        class="form-input"
                        :disabled="!isApplicantSelected"
                    />
                    <span v-if="form.errors.exam_git_result" class="error-message">{{ form.errors.exam_git_result }}</span>
                    <span v-if="liveErrors.exam_git_result" class="error-message">{{ liveErrors.exam_git_result }}</span>
                </div>

                <div class="form-field">
                    <label class="field-label">PRG Result</label>
                    <input
                        type="number"
                        step="0.01"
                                                                min="0"
                                        max="80"
                                        @input="clampScore(form, 'exam_prg_result', 80)"
                        v-model="form.exam_prg_result"
                        placeholder="0.00"
                        class="form-input"
                        :disabled="!isApplicantSelected"
                    />
                    <span v-if="form.errors.exam_prg_result" class="error-message">{{ form.errors.exam_prg_result }}</span>
                    <span v-if="liveErrors.exam_prg_result" class="error-message">{{ liveErrors.exam_prg_result }}</span>
                </div>
            </div>
                <div class="form-grid grid-2">
                <div class="form-field">
                    <label class="field-label pt-5">Exam Result</label>
                    <input
                        type="text"
                        class="form-input"
                        :value="examResultLabel || (!form.exam_application_status ? 'Auto-filled from application status' : '')"
                        readonly
                        :disabled="!isApplicantSelected || !form.exam_application_status"
                    />
                    <span v-if="form.errors.exam_result" class="error-message">{{ form.errors.exam_result }}</span>
                </div>

                <div class="form-field">
                    <label class="field-label pt-5">Exam Application Status</label>
                    <select
                        v-model="form.exam_application_status"
                        class="form-select"
                        :disabled="!isApplicantSelected || !selectedApplicant"
                    >
                        <option value="">Select Status</option>
                        <option v-for="status in examStatuses" :key="status.value" :value="status.value">
                            {{ status.label }}
                        </option>
                    </select>
                    <span v-if="form.errors.exam_application_status" class="error-message">
                        {{ form.errors.exam_application_status }}
                    </span>
                </div>
            </div>

            <div class="form-field">
                <label class="field-label">Exam Comments</label>
                <textarea
                    v-model="form.exam_remarks"
                    placeholder="Enter any remarks here..."
                    rows="3"
                    class="form-textarea"
                    :disabled="!isApplicantSelected"
                ></textarea>
                <span v-if="form.errors.exam_remarks" class="error-message">{{ form.errors.exam_remarks }}</span>
            </div>
</div>

<div class="form-section">
    <div class="section-header">
        <h3>Initial Interview</h3>
    </div>

    <div class="exam-section-layout">
        <!-- LEFT SIDE -->
        <div class="exam-form-column">

            <!-- Dates -->
            <div class="form-grid grid-2">
                <div class="form-field">
                    <label class="field-label">Plan Date</label>
                    <input type="datetime-local" v-model="form.initial_interview_plan_date" class="form-input" :disabled="!isApplicantSelected" />
                </div>

                <div class="form-field">
                    <label class="field-label">Actual Date</label>
                    <input type="datetime-local" v-model="form.initial_interview_actual_date" class="form-input" :disabled="!isApplicantSelected" />
                </div>
            </div>

            <!-- Venue -->
            <div class="form-field">
                <label class="field-label">Venue</label>
                <select v-model="form.initial_interview_venue" class="form-select" :disabled="!isApplicantSelected">
                    <option value="">Select Venue</option>
                    <option v-for="venue in examVenues" :key="venue.value" :value="venue.value">
                        {{ venue.label }}
                    </option>
                </select>
            </div>

            <!-- Final Score -->
            <div class="form-field">
                <label class="field-label">Initial Interview Final Score</label>
                <input
                    type="number"
                    step="0.01"
                        min="0"
    max="5"
                    v-model="form.initial_interview_final"
                    placeholder="0.00"
                    class="form-input"
                    :disabled="!isApplicantSelected"
                    @input="clampScore(form, 'initial_interview_final', 5)"
                />
            </div>

            <!-- ✅ MOVE RESULT UP -->


        </div>

        <!-- RIGHT SIDE -->
        <div class="exam-criteria-column">
            <div class="criteria-panel compact">
                <div class="criteria-panel-title">Initial Interview Criteria</div>

                    <div class="criteria-rule passed">
        <div class="criteria-rule-title">1 - Highly Recommended</div>
    </div>
                        <div class="criteria-rule passed">
        <div class="criteria-rule-title">2- Recommended</div>
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

    <!-- ✅ COMMENTS FULL WIDTH -->
             <div class="form-grid grid-2">
                <div class="form-field">
                    <label class="field-label">Result</label>
                    <input
                        type="text"
                        class="form-input"
                        :disabled="!isApplicantSelected"
                        :value="initialInterviewResultLabel || (!form.initial_interview_application_status ? 'Auto-filled' : '')"
                        readonly
                    />
                </div>

                <div class="form-field">
                    <label class="field-label">Application Status</label>
                    <select v-model="form.initial_interview_application_status" class="form-select" :disabled="!isApplicantSelected">
                        <option value="">Select Status</option>
                        <option v-for="status in interviewAppStatuses" :key="status.value" :value="status.value">
                            {{ status.label }}
                        </option>
                    </select>
                </div>
            </div>
    <div class="form-field mt-3">

        <label class="field-label">Initial Interview Comments</label>
        <textarea
            v-model="form.initial_interview_remarks"
            placeholder="Enter any remarks here..."
            rows="3"
            class="form-textarea"
            :disabled="!isApplicantSelected"
        ></textarea>
    </div>
</div>

<div class="form-section">
    <div class="section-header">
        <h3>Final Interview</h3>
    </div>

    <div class="form-field">
        <label class="field-label">Date</label>
        <input
            type="datetime-local"
            v-model="form.final_interview_date"
            class="form-input"
            :disabled="!isApplicantSelected"
            :min="finalInterviewMin"
        />
        <span v-if="form.errors.final_interview_date" class="error-message">
            {{ form.errors.final_interview_date }}
        </span>
    </div>

    <div class="exam-section-layout">
        <div class="exam-form-column">
            <div v-if="visibleFinalInterviewAssignments.length === 0" class="criteria-empty">
                No final interviewers assigned yet. Add them first in the interviewers/conductors section.
            </div>

            <div v-else class="atpp-stack">
                <div
                    v-for="(assignment, index) in visibleFinalInterviewAssignments"
                    :key="assignment.id"
                    class="atpp-card"
                >
                    <div class="atpp-card-title">
                        {{ assignment.name }}
                        <span class="criteria-panel-subtitle">({{ assignment.role_label }})</span>
                    </div>

                    <div class="form-grid grid-2">
                        <div class="form-field">
                            <label class="field-label">Score</label>
<input
    type="number"
    v-model="assignment.score"
    min="0"
    max="5"
    step="0.01"
    @input="clampScore(assignment, 'score', 5)"
/>
                        </div>

                        <div class="form-field">
                            <label class="field-label">Result</label>
                            <select
                                v-model="assignment.evaluation_result"
                                class="form-select"
                                :disabled="!isApplicantSelected"
                            >
                                <option value="">Select Result</option>
                                <option value="1">Pending</option>
                                <option value="2">Passed</option>
                                <option value="3">Failed</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="field-label">Interviewer Remarks</label>
                        <textarea
                            v-model="assignment.evaluation_remarks"
                            rows="2"
                            class="form-textarea"
                            :disabled="!isApplicantSelected"
                        ></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="exam-criteria-column">
            <div class="criteria-panel compact">
                <div class="criteria-panel-title">Final Interview Criteria</div>

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

    <div class="form-grid grid-3 mt-3">
        <div class="form-field">
            <label class="field-label">Final Score</label>
            <input
                type="number"
                step="0.01"
                min="0"
                v-model="form.final_interview_final"
@input="handleFinalScoreManualInput(); clampScore(form, 'final_interview_final', 5)"
                class="form-input"
                :disabled="!isApplicantSelected"

            />
        </div>

        <div class="form-field">
            <label class="field-label">Result</label>
            <input
                v-if="allFinalInterviewersPassed || allFinalInterviewersFailed"
                type="text"
                class="form-input"
                :disabled="!isApplicantSelected"
                :value="finalInterviewResultLabel || (!form.final_interview_application_status ? 'Auto-filled from application status' : '')"
                readonly
            />

            <select
                v-else-if="hasMixedFinalInterviewResults && canEditFinalInterviewDecision"
                v-model="form.final_interview_result"
                class="form-select"
                :disabled="!isApplicantSelected"
            >
                <option value="">Select Final Result</option>
                <option value="2">Passed</option>
                <option value="3">Failed</option>
            </select>

            <input
                v-else
                type="text"
                class="form-input"
                :disabled="!isApplicantSelected"
                :value="finalInterviewResultLabel || 'For HR deliberation'"
                readonly
            />
        </div>

        <div class="form-field">
            <label class="field-label">Application Status</label>
            <input
                type="text"
                class="form-input"
                :disabled="!isApplicantSelected"
                :value="interviewAppStatuses.find((s) => String(s.value) === String(form.final_interview_application_status))?.label || ''"
                readonly
            />
        </div>
    </div>

    <div class="form-field mt-3">
        <label class="field-label">Final Interview Comments</label>
        <textarea
            v-model="form.final_interview_remarks"
            rows="3"
            class="form-textarea"
            :disabled="!isApplicantSelected"
        ></textarea>
    </div>
</div>

                        <div class="form-section">
                            <div class="section-header">
                                <h3>Job Offer</h3>
                            </div>

                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label">Schedule</label>
                                    <input
                                        type="datetime-local"
                                        v-model="form.job_offer_schedule"
                                        class="form-input"
                                        :disabled="!isApplicantSelected"
                                        :min="jobOfferMin"
                                    />
                                    <span v-if="form.errors.job_offer_schedule" class="error-message">{{ form.errors.job_offer_schedule }}</span>
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Status</label>
                                    <select v-model="form.job_offer_status" class="form-select" :disabled="!isApplicantSelected">
                                        <option value="">Select Status</option>
                                        <option v-for="status in jobOfferStatuses" :key="status.value" :value="status.value">
                                            {{ status.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="field-label">Job Offer Comments</label>
                                <textarea v-model="form.job_offer_remarks" placeholder="Enter any remarks here..." rows="3" class="form-textarea" :disabled="!isApplicantSelected"></textarea>
                                <span v-if="form.errors.job_offer_remarks" class="error-message">{{ form.errors.job_offer_remarks }}</span>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-header">
                                <h3>Additional Information</h3>
                            </div>

                            <div class="form-field">
                                <label class="field-label">General Remarks</label>
                                <textarea v-model="form.remarks" placeholder="General remarks" rows="3" class="form-textarea" :disabled="!isApplicantSelected"></textarea>
                                <span v-if="form.errors.remarks" class="error-message">{{ form.errors.remarks }}</span>
                            </div>
                        </div>

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

.criteria-list li + li {
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


</style>
