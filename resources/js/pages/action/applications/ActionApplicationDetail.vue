<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, computed, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import {
    User, FileText, File, Image, Award, Calendar, Star, CheckCircle,
    Users, Plus
} from 'lucide-vue-next'
import axios from 'axios'

const canAcceptDecline = (interview: any) => {
    return [2, 3, 5, 6].includes(userPermissions.value) &&
        interview.interviewer_id === page.props.user_id &&
        interview.status === 1
}

const bulkEditScheduleErrors = ref({
    selectedInterviewers: '',
    scheduledDate: '',
})

const acceptDeclineErrors = ref({
    decision: '',
    reason: '',
})

const notificationErrors = ref({
    type: '',
})

const showAcceptDeclineModal = ref(false)
const acceptDeclineSubmitting = ref(false)
const bulkAddScheduledDate = ref('')
const acceptDeclineInterviewer = ref<any>(null)
const acceptDeclineDecision = ref('')
const acceptDeclineReason = ref('')

const showBulkEditScheduleModal = ref(false)
const bulkEditScheduledDate = ref('')
const bulkEditingSchedule = ref(false)

const openBulkEditScheduleModal = () => {
    bulkEditScheduleErrors.value.selectedInterviewers = ''
    bulkEditScheduleErrors.value.scheduledDate = ''

    if (selectedInterviewers.value.length === 0) {
        bulkEditScheduleErrors.value.selectedInterviewers = 'This is a required field.'
        return
    }

    bulkEditScheduledDate.value = ''
    showBulkEditScheduleModal.value = true
}

const submitBulkEditSchedule = async () => {
    bulkEditScheduleErrors.value.selectedInterviewers = ''
    bulkEditScheduleErrors.value.scheduledDate = ''

    let hasError = false

    if (selectedInterviewers.value.length === 0) {
        bulkEditScheduleErrors.value.selectedInterviewers = 'This is a required field.'
        hasError = true
    }

    if (!bulkEditScheduledDate.value) {
        bulkEditScheduleErrors.value.scheduledDate = 'This is a required field.'
        hasError = true
    }

    if (hasError) return

    bulkEditingSchedule.value = true

    try {
        const response = await axios.post(
            `/action/applications/${application.value.id}/interviews/bulk-update-schedule`,
            {
                interview_ids: selectedInterviewers.value,
                scheduled_date: bulkEditScheduledDate.value,
            }
        )

        interviews.value = response.data.interviews || interviews.value
        showBulkEditScheduleModal.value = false
        selectedInterviewers.value = []
        selectAll.value = false
        bulkEditScheduledDate.value = ''
        bulkEditScheduleErrors.value.selectedInterviewers = ''
        bulkEditScheduleErrors.value.scheduledDate = ''

        showToast(
            response.data.message || 'Record updated successfully!',
            'success'
        )
    } catch (error: any) {
        showToast(
            error?.response?.data?.error ||
            error?.response?.data?.message ||
            'Failed to update schedules',
            'error'
        )
    } finally {
        bulkEditingSchedule.value = false
    }
}

const openAcceptDeclineModal = (interview: any) => {
    acceptDeclineInterviewer.value = interview
    acceptDeclineDecision.value = ''
    acceptDeclineReason.value = ''
    acceptDeclineErrors.value.decision = ''
    acceptDeclineErrors.value.reason = ''
    showAcceptDeclineModal.value = true
}

const page = usePage<any>()

const application = computed(() => page.props.application)
const interviews = ref<any[]>(page.props.interviews || [])
const allInterviewers = computed(() => page.props.availableInterviewers || [])
const userPermissions = computed(() => Number(page.props.user_permissions || 0))
const examVenues = computed(() => page.props.examVenues || {})

const canManageInterviewers = computed(() => [1, 2, 3].includes(userPermissions.value))
const canNotify = computed(() => ![5, 6].includes(userPermissions.value))
const successMessage = ref((page.props.flash as any)?.success || '')
const showSuccess = ref(!!successMessage.value)
const errorMessage = ref((page.props.flash as any)?.error || '')
const showError = ref(!!errorMessage.value)

const showNotificationModal = ref(false)
const showBulkAddModal = ref(false)
const sendingNotification = ref(false)
const bulkAdding = ref(false)
const bulkAddPlannedDate = computed(() => {
    const stage = Number(bulkAddStage.value)

    if (stage === 1) return application.value?.exam_plan_date || ''
    if (stage === 2) return application.value?.initial_interview_plan_date || ''
    if (stage === 3) return application.value?.final_interview_date || ''

    return ''
})

const notification = ref({
    type: '',
})

const selectedBulkInterviewers = ref<any[]>([])
const interviewerSearch = ref('')
const showInterviewerDropdown = ref(false)
const bulkAddStage = ref('1')

const selectedInterviewers = ref<number[]>([])
const selectAll = ref(false)

const getVenueLabel = (venue: number | null) => {
    if (!venue) return '-'
    return examVenues.value?.[venue] || '-'
}

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedInterviewers.value = interviews.value.map((i: any) => i.id)
    } else {
        selectedInterviewers.value = []
    }
}

const hasScheduledJobOffer = computed(() => {
    return !!application.value?.job_offer_schedule
})

watch(selectedInterviewers, (newVal) => {
    selectAll.value = newVal.length === interviews.value.length && interviews.value.length > 0
})

watch(bulkAddStage, () => {
    interviewerSearch.value = ''
    showInterviewerDropdown.value = false
})

const selectedInterviewDetails = computed(() => {
    return interviews.value.filter((interview: any) =>
        selectedInterviewers.value.includes(interview.id)
    )
})

const filteredAvailableInterviewers = computed(() => {
    const selectedIds = selectedBulkInterviewers.value.map(i => i.id)
    const stage = Number(bulkAddStage.value)

    const alreadyAssignedForStage = interviews.value
        .filter((i: any) => Number(i.interview_type) === stage)
        .map((i: any) => Number(i.interviewer_id))

    const baseList = allInterviewers.value.filter((i: any) =>
        !selectedIds.includes(i.id) &&
        !alreadyAssignedForStage.includes(Number(i.id))
    )

    if (!interviewerSearch.value.trim()) {
        return baseList
    }

    const query = interviewerSearch.value.toLowerCase()

    return baseList.filter((i: any) =>
        String(i.name || '').toLowerCase().includes(query)
    )
})

const formatDateTime = (dateString: string | null) => {
    if (!dateString) return '-'

    const date = new Date(dateString)

    return date.toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
    })
}

const formatScore = (score: number | null) => {
    if (score === null || score === undefined) return '-'
    return `${score}`
}

const getFileUrl = (filename: string | null) => {
    if (!filename) return null
    return `/storage/${filename}`
}

const getExamResultLabel = (result: number) => {
    const results = { 1: 'Pending', 2: 'Passed', 3: 'Failed' }
    return results[result as keyof typeof results]
}

const getExamApplicationStatusLabel = (status: number) => {
    const statuses = { 1: 'Pending', 2: '1st Priority (Passed)', 3: '2nd Priority (P2)', 4: 'Done', 5: 'Passed', 6: 'Failed', 7: 'No Show' }
    return statuses[status as keyof typeof statuses]
}

const getInterviewResultLabel = (result: number) => {
    const results = { 1: 'Pending', 2: 'Passed', 3: 'Failed' }
    return results[result as keyof typeof results]
}

const getInterviewApplicationStatusLabel = (status: number) => {
    const statuses = { 1: 'Pending', 2: 'Done', 3: 'Passed', 4: 'P2', 5: 'Failed' }
    return statuses[status as keyof typeof statuses]
}

const getJobOfferStatusLabel = (status: number) => {
    const statuses = { 1: 'Pending', 2: 'Done', 3: 'Accept', 4: 'Decline', 5: 'Withdraw', 6: 'Retracted' }
    return statuses[status as keyof typeof statuses]
}

const getStageLabel = (type: number) => {
    const stages: Record<number, string> = {
        1: 'Exam',
        2: 'Initial Interview',
        3: 'Final Interview',
    }
    return stages[type] || '-'
}

const getInterviewStatusLabel = (status: number) => {
    const statuses: Record<number, string> = {
        1: 'Pending Approval',
        2: 'Approved',
        3: 'Declined',
        4: 'Done',
    }
    return statuses[status] || '-'
}

const getInterviewStatusBadgeClass = (status: number) => {
    const classes: Record<number, string> = {
        1: 'bg-yellow-100 text-yellow-800',
        2: 'bg-blue-100 text-blue-800',
        3: 'bg-red-100 text-red-800',
        4: 'bg-green-100 text-green-800',
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const getJobOfferStatusBadgeClass = (status: number) => {
    const classes: Record<number, string> = {
        1: 'bg-yellow-100 text-yellow-800', // Pending
        2: 'bg-blue-100 text-blue-800',     // Done
        3: 'bg-green-100 text-green-800',   // Accept
        4: 'bg-red-100 text-red-800',       // Decline
        5: 'bg-gray-100 text-gray-800',     // Withdraw
        6: 'bg-gray-100 text-gray-800',     // Retracted
    }

    return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStageBadgeClass = (type: number) => {
    const classes: Record<number, string> = {
        1: 'bg-purple-100 text-purple-800',
        2: 'bg-blue-100 text-blue-800',
        3: 'bg-green-100 text-green-800',
    }
    return classes[type] || 'bg-gray-100 text-gray-800'
}

const getStatusBadgeColor = (status: string | null) => {
    if (!status) return 'bg-gray-200 text-gray-700'
    const statusLower = status.toLowerCase()
    if (statusLower.includes('passed')) return 'bg-green-100 text-green-800'
    if (statusLower.includes('failed')) return 'bg-red-100 text-red-800'
    if (statusLower.includes('pending')) return 'bg-yellow-100 text-yellow-800'
    return 'bg-gray-200 text-gray-700'
}

const getOverallStatus = () => {
    const jobStatus = Number(application.value.job_offer_status)

    if (jobStatus === 3) return 'Offer Accepted'
    if (jobStatus === 4) return 'Offer Declined'
    if (jobStatus === 5) return 'Offer Withdrawn'
    if (jobStatus === 6) return 'Offer Retracted'

    if (application.value.final_interview_result === 2) return 'Passed Final Interview'
    if (application.value.final_interview_result === 3) return 'Failed Final Interview'
    if (application.value.initial_interview_result === 2) return 'Passed Initial Interview'
    if (application.value.initial_interview_result === 3) return 'Failed Initial Interview'
    if (application.value.exam_result === 2) return 'Passed Exam'
    if (application.value.exam_result === 3) return 'Failed Exam'

    return 'In Progress'
}

const getOverallStatusColor = () => {
    const status = getOverallStatus()
    if (status === 'Offer Accepted') return 'bg-green-100 text-green-800'
    if (status.includes('Failed') || status.includes('Declined')) return 'bg-red-100 text-red-800'
    if (status.includes('Passed')) return 'bg-blue-100 text-blue-800'
    return 'bg-yellow-100 text-yellow-800'
}

const addToBulkSelection = (interviewer: any) => {
    if (!selectedBulkInterviewers.value.some(i => i.id === interviewer.id)) {
        selectedBulkInterviewers.value.push(interviewer)
    }
    interviewerSearch.value = ''
    showInterviewerDropdown.value = false
}

const removeFromBulkSelection = (id: number) => {
    selectedBulkInterviewers.value = selectedBulkInterviewers.value.filter(i => i.id !== id)
}

const searchInterviewers = () => {
    showInterviewerDropdown.value = !!interviewerSearch.value.trim()
}

const openBulkAddModal = () => {
    selectedBulkInterviewers.value = []
    interviewerSearch.value = ''
    bulkAddStage.value = '1'
    showBulkAddModal.value = true
}

const showDeclineReasonModal = ref(false)
const selectedDeclinedInterview = ref<any>(null)

const canViewDeclineReason = (interview: any) => {
    return Number(interview.status) === 3 && !!String(interview.decline_reason || '').trim()
}

const openDeclineReasonModal = (interview: any) => {
    selectedDeclinedInterview.value = interview
    showDeclineReasonModal.value = true
}

const closeDeclineReasonModal = () => {
    showDeclineReasonModal.value = false
    selectedDeclinedInterview.value = null
}

const submitBulkAdd = async () => {
    if (selectedBulkInterviewers.value.length === 0) {
        showToast('Please select at least one interviewer', 'error')
        return
    }

    if (!bulkAddPlannedDate.value) {
        showToast('No plan date is set for the selected stage', 'error')
        return
    }

    bulkAdding.value = true

    try {
        const payload = {
            interviewer_ids: selectedBulkInterviewers.value.map(i => i.id),
            interview_type: Number(bulkAddStage.value),
            scheduled_date: bulkAddPlannedDate.value,
        }

        const response = await axios.post(
            `/action/applications/${application.value.id}/interviews/bulk-add`,
            payload
        )

        interviews.value = response.data.interviews || interviews.value

        showBulkAddModal.value = false
        selectedBulkInterviewers.value = []
        interviewerSearch.value = ''
        bulkAddScheduledDate.value = ''
        bulkAddStage.value = '1'

        showToast(response.data.message || 'Record updated successfully.', 'success')
    } catch (error: any) {
        console.error('Bulk add failed:', error?.response || error)
        showToast(
            error?.response?.data?.error ||
            error?.response?.data?.message ||
            'Failed to add interviewers',
            'error'
        )
    } finally {
        bulkAdding.value = false
    }
}

const sendNotification = async () => {
    notificationErrors.value.type = ''

    if (!notification.value.type) {
        notificationErrors.value.type = 'This is a required field.'
        return
    }

    sendingNotification.value = true

    try {
        const response = await axios.post(
            `/action/applications/${application.value.id}/send-notification`,
            {
                type: notification.value.type,
            }
        )

        if (notification.value.type === 'interviewer_pending_approval') {
            interviews.value = interviews.value.map((i: any) => {
                if (Number(i.status) === 1 && !i.pending_approval_notified_at) {
                    return {
                        ...i,
                        pending_approval_notified_at: new Date().toISOString(),
                    }
                }
                return i
            })
        }

        showNotificationModal.value = false
        notification.value.type = ''
        notificationErrors.value.type = ''

        showToast(response.data.message || 'Email sent successfully.', 'success')
    } catch (error: any) {
        showToast(
            error?.response?.data?.error ||
            error?.response?.data?.message ||
            'Failed to send notification',
            'error'
        )
    } finally {
        sendingNotification.value = false
    }
}

const downloadApplication = () => {
    window.open(`/action/applications/${application.value.id}/print`, '_blank')
}

const stageLabel = (type: number) => {
    const stages: Record<number, string> = {
        1: 'Exam',
        2: 'Initial Interview',
        3: 'Final Interview',
    }
    return stages[type] || 'Interview'
}

const applicantFullName = computed(() => {
    const a = application.value?.applicant
    if (!a) return '-'
    return `${a.first_name} ${a.last_name}`.trim()
})

const pendingApprovalInterviewers = computed(() => {
    return interviews.value.filter((i: any) =>
        Number(i.status) === 1 && !i.pending_approval_notified_at
    )
})

const examRows = computed(() =>
    interviews.value.filter((i: any) => Number(i.interview_type) === 1)
)

const initialInterviewRows = computed(() =>
    interviews.value.filter((i: any) => Number(i.interview_type) === 2)
)

const finalInterviewRows = computed(() =>
    interviews.value.filter((i: any) => Number(i.interview_type) === 3)
)

const isApproved = (status: number) => Number(status) === 2
const isDone = (status: number) => Number(status) === 4

const examReadyForApplicantNotification = computed(() => {
    if (examRows.value.length === 0) return false

    const allApproved = examRows.value.every((i: any) => isApproved(i.status))
    const anyDone = examRows.value.some((i: any) => isDone(i.status))

    return allApproved && !anyDone
})

const initialReadyForApplicantNotification = computed(() => {
    if (initialInterviewRows.value.length === 0) return false

    const allApproved = initialInterviewRows.value.every((i: any) => isApproved(i.status))
    const anyDone = initialInterviewRows.value.some((i: any) => isDone(i.status))

    return allApproved && !anyDone
})

const finalReadyForApplicantNotification = computed(() => {
    if (finalInterviewRows.value.length === 0) return false

    const allApproved = finalInterviewRows.value.every((i: any) => isApproved(i.status))
    const anyDone = finalInterviewRows.value.some((i: any) => isDone(i.status))

    return allApproved && !anyDone
})

const failedStage = computed(() => {
    if (Number(application.value?.final_interview_result) === 3) {
        return 'final_interview'
    }
    if (Number(application.value?.initial_interview_result) === 3) {
        return 'initial_interview'
    }
    if (Number(application.value?.exam_result) === 3) {
        return 'exam'
    }
    return null
})

const failedStageLabel = computed(() => {
    if (failedStage.value === 'final_interview') return 'Final Interview'
    if (failedStage.value === 'initial_interview') return 'Initial Interview'
    if (failedStage.value === 'exam') return 'Exam'
    return null
})

const availableNotificationOptions = computed(() => {
    const options: Array<{
        value: string
        label: string
        description: string
    }> = []

    if (pendingApprovalInterviewers.value.length > 0) {
        options.push({
            value: 'interviewer_pending_approval',
            label: 'Send pending approval to interviewer(s)',
            description: 'Notify assigned interviewer(s) that they need to approve or decline their schedule.',
        })
    }

    if (examReadyForApplicantNotification.value) {
        options.push({
            value: 'applicant_exam_scheduled',
            label: 'Send applicant exam schedule',
            description: 'Notify the applicant about the approved exam schedule.',
        })
    }

    if (initialReadyForApplicantNotification.value) {
        options.push({
            value: 'applicant_initial_scheduled',
            label: 'Send applicant initial interview schedule',
            description: 'Notify the applicant about the approved initial interview schedule.',
        })
    }

    if (finalReadyForApplicantNotification.value) {
        options.push({
            value: 'applicant_final_scheduled',
            label: 'Send applicant final interview schedule',
            description: 'Notify the applicant about the approved final interview schedule.',
        })
    }

    if (failedStage.value) {
        options.push({
            value: 'applicant_failed',
            label: `Send applicant failed notification (${failedStageLabel.value})`,
            description: `Notify the applicant that they did not pass the ${failedStageLabel.value}.`,
        })
    }

    if (hasScheduledJobOffer.value) {
        options.push({
            value: 'hr_recruiters_job_offer',
            label: 'Notify all HR recruiters of scheduled job offer',
            description: 'Send the scheduled job offer details to all HR recruiters.',
        })
    }

    return options
})

const notificationPreview = computed(() => {
    switch (notification.value.type) {
        case 'interviewer_pending_approval':
            return {
                subject: '【HR System】Applicant Schedule Pending Approval',
                recipients: pendingApprovalInterviewers.value.map((i: any) => ({
                    name: i.name,
                    email: i.email_address || '',
                    extra: `${getStageLabel(i.interview_type)} • ${formatDateTime(i.scheduled_date)}`,
                })),
                summary: `This email tells interviewer(s) that they have a pending interview assignment for ${applicantFullName.value} and includes a direct link to the application page so they can review and respond.`,
            }

        case 'applicant_exam_scheduled':
            return {
                subject: '【HR System】AWS Application Schedule',
                recipients: [
                    {
                        name: applicantFullName.value,
                        email: application.value?.applicant?.email_address || '',
                        extra: 'Applicant',
                    }
                ],
                summary: `This email tells the applicant that their exam schedule has been confirmed.`,
            }

        case 'applicant_initial_scheduled':
            return {
                subject: '【HR System】AWS Application Schedule',
                recipients: [
                    {
                        name: applicantFullName.value,
                        email: application.value?.applicant?.email_address || '',
                        extra: 'Applicant',
                    }
                ],
                summary: `This email tells the applicant that their initial interview schedule has been confirmed.`,
            }

        case 'applicant_final_scheduled':
            return {
                subject: '【HR System】AWS Application Schedule',
                recipients: [
                    {
                        name: applicantFullName.value,
                        email: application.value?.applicant?.email_address || '',
                        extra: 'Applicant',
                    }
                ],
                summary: `This email tells the applicant that their final interview schedule has been confirmed.`,
            }

        case 'applicant_failed':
            return {
                subject: '【HR System】Application Update',
                recipients: [
                    {
                        name: applicantFullName.value,
                        email: application.value?.applicant?.email_address || '',
                        extra: 'Applicant',
                    }
                ],
                summary: `This email tells the applicant that they did not pass the ${failedStageLabel.value}.`,
            }

        case 'hr_recruiters_job_offer':
            return {
                subject: '【HR System】Scheduled Job Offer',
                recipients: [
                    {
                        name: 'All HR Recruiters',
                        email: 'HR Recruiter distribution',
                        extra: formatDateTime(application.value?.job_offer_schedule),
                    }
                ],
                summary: `This email notifies all HR recruiters that a job offer has been scheduled for ${applicantFullName.value}.`,
            }

        default:
            return null
    }
})

watch(acceptDeclineReason, (newVal) => {
    if (newVal.trim()) {
        acceptDeclineErrors.value.reason = ''
    }
})

watch(acceptDeclineDecision, () => {
    acceptDeclineErrors.value.decision = ''
    if (acceptDeclineDecision.value !== 'decline') {
        acceptDeclineErrors.value.reason = ''
    }
})

const submitAcceptDecline = async () => {
    acceptDeclineErrors.value.decision = ''
    acceptDeclineErrors.value.reason = ''

    let hasError = false

    if (!acceptDeclineDecision.value) {
        acceptDeclineErrors.value.decision = 'This field is required.'
        hasError = true
    }

    if (acceptDeclineDecision.value === 'decline' && !acceptDeclineReason.value.trim()) {
        acceptDeclineErrors.value.reason = 'This field is required.'
        hasError = true
    }

    if (hasError) return

    acceptDeclineSubmitting.value = true

    try {
        await axios.post(
            `/action/applications/${application.value.id}/interviews/${acceptDeclineInterviewer.value.id}/decision`,
            {
                decision: acceptDeclineDecision.value,
                reason: acceptDeclineReason.value,
            }
        )

        interviews.value = interviews.value.map((interview: any) => {
            if (interview.id !== acceptDeclineInterviewer.value.id) {
                return interview
            }

            return {
                ...interview,
                status: acceptDeclineDecision.value === 'accept' ? 2 : 3,
                decline_reason: acceptDeclineDecision.value === 'decline'
                    ? acceptDeclineReason.value
                    : null,
            }
        })

        showAcceptDeclineModal.value = false
        acceptDeclineErrors.value.decision = ''
        acceptDeclineErrors.value.reason = ''

        showToast(
            acceptDeclineDecision.value === 'accept'
                ? 'Interview assignment accepted successfully!'
                : 'Interview assignment declined.',
            'success'
        )
    } catch (error: any) {
        showToast(error?.response?.data?.message || 'Failed to submit decision', 'error')
    } finally {
        acceptDeclineSubmitting.value = false
    }
}

const toastMessage = ref<string | null>(null)
const toastType = ref<'success' | 'error'>('success')
const showToastMessage = ref(false)

const showToast = (message: string, type: 'success' | 'error') => {
    toastMessage.value = message
    toastType.value = type
    showToastMessage.value = true
    setTimeout(() => { showToastMessage.value = false }, 3000)
}

watch(successMessage, (newVal) => {
    if (newVal) {
        showSuccess.value = true
        setTimeout(() => {
            showSuccess.value = false
            successMessage.value = ''
        }, 5000)
    }
}, { immediate: true })

watch(errorMessage, (newVal) => {
    if (newVal) {
        showError.value = true
        setTimeout(() => {
            showError.value = false
            errorMessage.value = ''
        }, 5000)
    }
}, { immediate: true })
</script>

<template>
    <AppLayout>
        <div class="action-application-detail">
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

            <div v-if="showToastMessage" class="full-width-alert">
                <div
                    class="alert-banner"
                    :class="toastType === 'success' ? 'alert-success-banner' : 'alert-error-banner'"
                >
                    <div class="alert-body">{{ toastMessage }}</div>
                    <button type="button" class="close-btn" @click="showToastMessage = false">×</button>
                </div>
            </div>

            <div class="flex flex-1 flex-col gap-6 p-8 bg-zinc-50/50 dark:bg-zinc-950 min-h-screen">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">
                            ACTION Application Details
                        </h1>
                    </div>
                    <div class="flex gap-3">
                        <button @click="downloadApplication" class="btn-primary">
                            Print or Save as PDF
                        </button>
                        <button v-if="canNotify" @click="showNotificationModal = true" class="btn-send">
                            Notify Applicant, Interviewer, or Conductor
                        </button>
                        <Link :href="`/action/applications/${application.id}/edit`" class="btn-edit">
                            Edit Application
                        </Link>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="col-span-1">
                        <div class="text-white px-5 py-4 rounded-xl shadow-md flex flex-col items-center justify-center text-center h-full"
                            style="background-color: #2f359e;">
                            <div class="w-30 h-30 bg-white rounded-full flex items-center justify-center mb-3 overflow-hidden">
                                <img
                                    v-if="application.upload_pic"
                                    :src="getFileUrl(application.upload_pic) || ''"
                                    alt="Applicant Photo"
                                    class="w-full h-full object-cover"
                                />
                                <User v-else class="w-10 h-10 text-blue-600" />
                            </div>
                            <h2 class="text-2xl font-extrabold tracking-wide drop-shadow">
                                {{ application.applicant.last_name }}, {{ application.applicant.first_name }}
                            </h2>
                            <p class="text-sm opacity-90 mt-1">{{ application.applicant.middle_name }}</p>
                            <p class="text-xs opacity-75 mt-2">{{ application.applicant.email_address }}</p>
                            <p class="text-xs opacity-75">{{ application.applicant.contact_number }}</p>
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-6">
                        <div class="bg-white dark:bg-zinc-900 p-5 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between items-center bg-zinc-100 dark:bg-zinc-800 px-4 py-2 rounded-lg">
                                    <span class="font-semibold">Batch:</span>
                                    <span class="font-extrabold text-blue-600">{{ application.batch?.action_batch || 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between items-center bg-zinc-100 dark:bg-zinc-800 px-4 py-2 rounded-lg">
                                    <span class="font-semibold">Status:</span>
                                    <span :class="['inline-flex px-2 py-1 text-xs font-semibold rounded-full', getOverallStatusColor()]">
                                        {{ getOverallStatus() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">
                            <h2 class="text-lg font-bold mb-4">Uploaded Documents</h2>

                            <div class="space-y-3" v-if="application.upload_resume || application.upload_tor || application.upload_pic">
                                <div
                                    v-if="application.upload_resume"
                                    class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg"
                                >
                                    <div class="flex items-center gap-2">
                                        <FileText class="w-4 h-4 text-blue-600" />
                                        <span class="text-sm">Resume/CV</span>
                                    </div>
                                    <a
                                        :href="getFileUrl(application.upload_resume)"
                                        target="_blank"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    >
                                        View File
                                    </a>
                                </div>

                                <div
                                    v-if="application.upload_tor"
                                    class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg"
                                >
                                    <div class="flex items-center gap-2">
                                        <File class="w-4 h-4 text-green-600" />
                                        <span class="text-sm">Transcript of Records</span>
                                    </div>
                                    <a
                                        :href="getFileUrl(application.upload_tor)"
                                        target="_blank"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    >
                                        View File
                                    </a>
                                </div>

                                <div
                                    v-if="application.upload_pic"
                                    class="flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg"
                                >
                                    <div class="flex items-center gap-2">
                                        <Image class="w-4 h-4 text-purple-600" />
                                        <span class="text-sm">2x2 Picture</span>
                                    </div>
                                    <a
                                        :href="getFileUrl(application.upload_pic)"
                                        target="_blank"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    >
                                        View Image
                                    </a>
                                </div>
                            </div>

                            <div v-else class="text-sm text-gray-500 dark:text-gray-400">
                                No uploaded documents available.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">
                        <h2 class="text-lg font-bold flex items-center gap-2 mb-4">
                            <Award class="w-5 h-5 text-blue-600" /> EXAM DETAILS
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse border">
                                <tbody>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800 w-1/3">Plan Date</td>
                                        <td class="px-3 py-2 border">{{ formatDateTime(application.exam_plan_date) }}</td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800 w-1/3">Actual Date</td>
                                        <td class="px-3 py-2 border">{{ formatDateTime(application.exam_actual_date) }}</td>
                                    </tr>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Venue</td>
                                        <td class="px-3 py-2 border">{{ examVenues[application.exam_venue] ?? '-' }}</td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Programming Result</td>
                                        <td class="px-3 py-2 border">{{ formatScore(application.exam_prg_result) }}</td>
                                    </tr>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">ATPP Result</td>
                                        <td class="px-3 py-2 border">{{ formatScore(application.exam_atpp_result) }}</td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">GIT Result</td>
                                        <td class="px-3 py-2 border">{{ formatScore(application.exam_git_result) }}</td>
                                    </tr>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Result</td>
                                        <td class="px-3 py-2 border">
                                            <span :class="['inline-flex px-2 py-1 text-xs font-semibold rounded-full', getStatusBadgeColor(getExamResultLabel(application.exam_result))]">
                                                {{ getExamResultLabel(application.exam_result) || '-' }}
                                            </span>
                                        </td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Application Status</td>
                                        <td class="px-3 py-2 border">
                                            <span :class="['inline-flex px-2 py-1 text-xs font-semibold rounded-full', getStatusBadgeColor(getExamApplicationStatusLabel(application.exam_application_status))]">
                                                {{ getExamApplicationStatusLabel(application.exam_application_status) || '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <strong class="text-sm">Comments:</strong>
                            <p class="text-sm mt-1 bg-zinc-50 dark:bg-zinc-800 p-3 rounded">{{ application.exam_remarks || 'No comments' }}</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">
                        <h2 class="text-lg font-bold flex items-center gap-2 mb-4">
                            <Calendar class="w-5 h-5 text-blue-600" /> INITIAL INTERVIEW DETAILS
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse border">
                                <tbody>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800 w-1/3">Plan Date</td>
                                        <td class="px-3 py-2 border">{{ formatDateTime(application.initial_interview_plan_date) }}</td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800 w-1/3">Actual Date</td>
                                        <td class="px-3 py-2 border">{{ formatDateTime(application.initial_interview_actual_date) }}</td>
                                    </tr>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Venue</td>
                                        <td class="px-3 py-2 border">{{ getVenueLabel(application.initial_interview_venue) || '-' }}</td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Final Score</td>
                                        <td class="px-3 py-2 border">{{ formatScore(application.initial_interview_final) }}</td>
                                    </tr>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Result</td>
                                        <td class="px-3 py-2 border">
                                            <span :class="['inline-flex px-2 py-1 text-xs font-semibold rounded-full', getStatusBadgeColor(getInterviewResultLabel(application.initial_interview_result))]">
                                                {{ getInterviewResultLabel(application.initial_interview_result) || '-' }}
                                            </span>
                                        </td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Application Status</td>
                                        <td class="px-3 py-2 border">
                                            <span :class="['inline-flex px-2 py-1 text-xs font-semibold rounded-full', getStatusBadgeColor(getInterviewApplicationStatusLabel(application.initial_interview_application_status))]">
                                                {{ getInterviewApplicationStatusLabel(application.initial_interview_application_status) || '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <strong class="text-sm">Comments:</strong>
                            <p class="text-sm mt-1 bg-zinc-50 dark:bg-zinc-800 p-3 rounded">{{ application.initial_interview_remarks || 'No comments' }}</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">
                        <h2 class="text-lg font-bold flex items-center gap-2 mb-4">
                            <Star class="w-5 h-5 text-blue-600" /> FINAL INTERVIEW DETAILS
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse border">
                                <tbody>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800 w-1/3">Interview Date</td>
                                        <td class="px-3 py-2 border">{{ formatDateTime(application.final_interview_date) }}</td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800 w-1/3">Final Score</td>
                                        <td class="px-3 py-2 border">{{ formatScore(application.final_interview_final) }}</td>
                                    </tr>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Score 1</td>
                                        <td class="px-3 py-2 border">{{ formatScore(application.final_interview_score_1) }}</td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Score 2</td>
                                        <td class="px-3 py-2 border">{{ formatScore(application.final_interview_score_2) }}</td>
                                    </tr>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Score 3</td>
                                        <td class="px-3 py-2 border">{{ formatScore(application.final_interview_score_3) }}</td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Score 4</td>
                                        <td class="px-3 py-2 border">{{ formatScore(application.final_interview_score_4) }}</td>
                                    </tr>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Result</td>
                                        <td class="px-3 py-2 border">
                                            <span :class="['inline-flex px-2 py-1 text-xs font-semibold rounded-full', getStatusBadgeColor(getInterviewResultLabel(application.final_interview_result))]">
                                                {{ getInterviewResultLabel(application.final_interview_result) || '-' }}
                                            </span>
                                        </td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800">Application Status</td>
                                        <td class="px-3 py-2 border">
                                            <span :class="['inline-flex px-2 py-1 text-xs font-semibold rounded-full', getStatusBadgeColor(getInterviewApplicationStatusLabel(application.final_interview_application_status))]">
                                                {{ getInterviewApplicationStatusLabel(application.final_interview_application_status) || '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <strong class="text-sm">Comments:</strong>
                            <p class="text-sm mt-1 bg-zinc-50 dark:bg-zinc-800 p-3 rounded">{{ application.final_interview_remarks || 'No comments' }}</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">
                        <h2 class="text-lg font-bold flex items-center gap-2 mb-4">
                            <CheckCircle class="w-5 h-5 text-blue-600" /> JOB OFFER DETAILS
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse border">
                                <tbody>
                                    <tr class="border">
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800 w-1/3">Schedule</td>
                                        <td class="px-3 py-2 border">{{ formatDateTime(application.job_offer_schedule) }}</td>
                                        <td class="font-semibold px-3 py-2 border bg-zinc-50 dark:bg-zinc-800 w-1/3">Status</td>
                                        <td class="px-3 py-2 border">
<span
    :class="[
        'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
        getJobOfferStatusBadgeClass(application.job_offer_status)
    ]"
>
    {{ getJobOfferStatusLabel(application.job_offer_status) || '-' }}
</span>                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <strong class="text-sm">Comments:</strong>
                            <p class="text-sm mt-1 bg-zinc-50 dark:bg-zinc-800 p-3 rounded">{{ application.job_offer_remarks || 'No comments' }}</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-zinc-800 dark:to-zinc-800/50 px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-lg font-bold flex items-center gap-2">
                                        <Users class="w-5 h-5 text-blue-600" /> INTERVIEWERS AND EXAM CONDUCTORS
                                    </h2>
                                    <p class="text-xs text-gray-500 mt-1">Manage interviewers and exam conductors assigned to this application</p>
                                </div>

                                <div class="flex gap-2" v-if="canManageInterviewers">
                                    <button @click="openBulkAddModal" class="btn-bulk-add">
                                        <Plus class="w-4 h-4" />
                                        Add
                                    </button>

                                    <button
                                        @click="openBulkEditScheduleModal"
                                        :disabled="selectedInterviewers.length === 0"
                                        :class="['btn-bulk-edit', selectedInterviewers.length === 0 && 'opacity-50 cursor-not-allowed']"
                                    >
                                        Edit Schedule
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-zinc-50 dark:bg-zinc-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left w-10" v-if="canManageInterviewers">
                                            <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" class="rounded border-gray-300" />
                                        </th>
                                        <th class="px-4 py-3 text-left font-semibold">Interviewer/Conductor</th>
                                        <th class="px-4 py-3 text-left font-semibold">Role</th>
                                        <th class="px-4 py-3 text-left font-semibold">Stage</th>
                                        <th class="px-4 py-3 text-left font-semibold">Scheduled Date</th>
                                        <th class="px-4 py-3 text-left font-semibold">Stage Status</th>
                                        <th class="px-4 py-3 text-left font-semibold">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        v-for="interview in interviews"
                                        :key="interview.id"
                                        class="border-t border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50"
                                    >
                                        <td class="px-4 py-3" v-if="canManageInterviewers">
                                            <input type="checkbox" v-model="selectedInterviewers" :value="interview.id" class="rounded border-gray-300" />
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <User class="w-4 h-4 text-gray-400" />
                                                <span>{{ interview.name }}</span>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3">{{ interview.role_label || '-' }}</td>

                                        <td class="px-4 py-3">
                                            <span :class="['px-2 py-1 text-xs rounded-full', getStageBadgeClass(interview.interview_type)]">
                                                {{ getStageLabel(interview.interview_type) }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3">{{ formatDateTime(interview.scheduled_date) }}</td>

                                        <td class="px-4 py-3">
                                            <span :class="['inline-flex px-2 py-1 text-xs font-semibold rounded-full', getInterviewStatusBadgeClass(interview.status)]">
                                                {{ getInterviewStatusLabel(interview.status) }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <button
                                                    v-if="canAcceptDecline(interview)"
                                                    @click="openAcceptDeclineModal(interview)"
                                                    class="inline-flex items-center justify-center rounded-md text-green-600 hover:text-green-800 hover:bg-green-50 transition px-1 py-2"
                                                    title="Accept/Decline"
                                                >
                                                    <CheckCircle class="w-4 h-4 mr-1" />
                                                    Respond
                                                </button>

                                                <button
                                                    v-if="canViewDeclineReason(interview)"
                                                    @click="openDeclineReasonModal(interview)"
                                                    class="inline-flex items-center justify-center rounded-md text-red-600 hover:text-red-800 hover:bg-red-50 transition px-1 py-2 text-sm font-sm"
                                                    title="View Reason for Decline"
                                                >
                                                    View Reason
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr v-if="interviews.length === 0">
                                        <td :colspan="canManageInterviewers ? 7 : 6" class="px-4 py-6 text-center text-gray-500">
                                            No interview assignments found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow">
                        <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <Users class="w-5 h-5 text-blue-600" /> ADDITIONAL INFORMATION
                        </h2>
                        <div class="mb-4">
                            <strong class="text-sm">General Remarks:</strong>
                            <p class="text-sm mt-1 bg-zinc-50 dark:bg-zinc-800 p-3 rounded">{{ application.remarks || '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="showDeclineReasonModal" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeDeclineReasonModal"></div>

                <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold mb-2">Reason for Decline</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Decline details for this interview assignment
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Interviewer
                            </label>
                            <input
                                type="text"
                                :value="selectedDeclinedInterview?.name || '-'"
                                disabled
                                class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 bg-gray-100"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Stage
                            </label>
                            <input
                                type="text"
                                :value="getStageLabel(selectedDeclinedInterview?.interview_type)"
                                disabled
                                class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 bg-gray-100"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Scheduled Date
                            </label>
                            <input
                                type="text"
                                :value="formatDateTime(selectedDeclinedInterview?.scheduled_date)"
                                disabled
                                class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 bg-gray-100"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Decline Reason
                            </label>
                            <div class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-3 bg-zinc-50 dark:bg-zinc-800 text-sm whitespace-pre-wrap">
                                {{ selectedDeclinedInterview?.decline_reason || '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button
                            @click="closeDeclineReasonModal"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showBulkEditScheduleModal" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showBulkEditScheduleModal = false"></div>

                <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold mb-2">Bulk Edit Schedule</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Update the schedule of checked interviewers
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Selected Interviewers
                            </label>

                            <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg px-3 py-3">
                                <div v-if="selectedInterviewDetails.length > 0" class="space-y-2">
                                    <div
                                        v-for="interview in selectedInterviewDetails"
                                        :key="interview.id"
                                        class="flex items-center justify-between gap-3 text-sm border-b border-gray-200 dark:border-zinc-700 last:border-b-0 pb-2 last:pb-0"
                                    >
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ interview.name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ interview.role_label || '-' }} • {{ getStageLabel(interview.interview_type) }}
                                            </div>
                                        </div>

                                        <div class="text-xs text-gray-500 dark:text-gray-400 text-right">
                                            {{ formatDateTime(interview.scheduled_date) }}
                                        </div>
                                    </div>
                                </div>

                                <div v-else class="text-sm text-gray-500">
                                    No interviewers selected.
                                </div>
                            </div>

                            <p v-if="bulkEditScheduleErrors.selectedInterviewers" class="mt-1 text-sm text-red-600">
                                {{ bulkEditScheduleErrors.selectedInterviewers }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Scheduled Date
                            </label>

                            <input
                                type="datetime-local"
                                v-model="bulkEditScheduledDate"
                                class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 bg-white dark:bg-zinc-800"
                            />

                            <p v-if="bulkEditScheduleErrors.scheduledDate" class="mt-1 text-sm text-red-600">
                                {{ bulkEditScheduleErrors.scheduledDate }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button
                            @click="showBulkEditScheduleModal = false"
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition"
                        >
                            Cancel
                        </button>

                        <button
                            @click="submitBulkEditSchedule"
                            :disabled="bulkEditingSchedule"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                        >
                            <span v-if="!bulkEditingSchedule">Update Schedule</span>
                            <span v-else>Updating...</span>
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showBulkAddModal" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showBulkAddModal = false"></div>
                <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <h3 class="text-xl font-bold mb-2">Add Interviewers/Conductors</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Add multiple interviewers or conductors at once</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Interviewers</label>
                            <div class="tag-input-container border border-gray-300 dark:border-zinc-700 rounded-lg p-2 bg-white dark:bg-zinc-800">
                                <div class="flex flex-wrap gap-2 mb-2">
  <div v-for="interviewer in selectedBulkInterviewers" :key="interviewer.id" class="tag-item">
    <span class="tag-text">
      {{ interviewer.name }}
      <span class="tag-role">({{ interviewer.role_label }})</span>
    </span>
    <button
      @click="removeFromBulkSelection(interviewer.id)"
      class="remove-tag"
      type="button"
      aria-label="Remove interviewer"
    >
      <svg class="remove-icon" viewBox="0 0 14 14" width="14" height="14" aria-hidden="true">
        <path d="M1 12.5L12.5 1M1 1l11.5 11.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
  </div>
                                </div>
                                <div class="relative">
                                    <input type="text" v-model="interviewerSearch" @focus="showInterviewerDropdown = true" @input="searchInterviewers"
                                        placeholder="Type to search interviewers..." class="w-full border-0 focus:ring-0 p-2 text-sm bg-transparent" />
                                    <div v-if="showInterviewerDropdown && filteredAvailableInterviewers.length > 0" class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto">
                                        <div v-for="interviewer in filteredAvailableInterviewers" :key="interviewer.id" @click="addToBulkSelection(interviewer)"
                                            class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 cursor-pointer text-sm">
                                            {{ interviewer.name }} ({{ interviewer.role_label }})
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ selectedBulkInterviewers.length }} interviewer(s) selected</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stage</label>
                            <select v-model="bulkAddStage" class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 bg-white dark:bg-zinc-800">
                                <option value="1">Exam</option>
                                <option value="2">Initial Interview</option>
                                <option value="3">Final Interview</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Scheduled Date
                            </label>
                            <div class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 bg-gray-100 dark:bg-zinc-800 text-sm">
                                {{ bulkAddPlannedDate ? formatDateTime(bulkAddPlannedDate) : 'No plan date set for this stage' }}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                This is automatically taken from the selected stage's planned date.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button @click="showBulkAddModal = false" class="flex-1 px-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition">Cancel</button>
                        <button
                            @click="submitBulkAdd"
                            :disabled="
                                bulkAdding ||
                                selectedBulkInterviewers.length === 0 ||
                                !bulkAddPlannedDate
                            "
                            class="flex-1 px-4 py-2 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed bg-blue-600 text-white hover:bg-blue-700"
                        >
                            <span v-if="!bulkAdding">Add Interviewers</span>
                            <span v-else>Adding...</span>
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showAcceptDeclineModal" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showAcceptDeclineModal = false"></div>
                <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold mb-2">Interview Assignment Confirmation</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Please confirm your availability for this interview</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Interviewer</label>
                            <input type="text" :value="acceptDeclineInterviewer?.name" disabled class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stage</label>
                            <input type="text" :value="getStageLabel(acceptDeclineInterviewer?.interview_type)" disabled class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scheduled Date</label>
                            <input type="text" :value="formatDateTime(acceptDeclineInterviewer?.scheduled_date)" disabled class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 bg-gray-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Decision</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2">
                                    <input type="radio" v-model="acceptDeclineDecision" value="accept" class="rounded-full border-gray-300" />
                                    <span>Accept</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" v-model="acceptDeclineDecision" value="decline" class="rounded-full border-gray-300" />
                                    <span>Decline</span>
                                </label>
                            </div>
                            <p v-if="acceptDeclineErrors.decision" class="mt-1 text-sm text-red-600">
                                {{ acceptDeclineErrors.decision }}
                            </p>
                        </div>
                        <div v-if="acceptDeclineDecision === 'decline'">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Declining</label>
                            <textarea
                                v-model="acceptDeclineReason"
                                rows="3"
                                class="w-full border border-gray-300 dark:border-zinc-700 rounded-lg px-3 py-2 bg-white"
                                placeholder="Please provide reason for declining..."
                            ></textarea>
                            <p v-if="acceptDeclineErrors.reason" class="mt-1 text-sm text-red-600">
                                {{ acceptDeclineErrors.reason }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button @click="showAcceptDeclineModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                        <button @click="submitAcceptDecline" :disabled="acceptDeclineSubmitting" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50">
                            <span v-if="!acceptDeclineSubmitting">Submit</span><span v-else>Submitting...</span>
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showNotificationModal" class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showNotificationModal = false"></div>

                <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <h3 class="text-xl font-bold text-center mb-2">Send Email</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">
                        Only valid email actions for this application are shown.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Available Email Action
                            </label>

                            <div v-if="availableNotificationOptions.length === 0"
                                class="border border-dashed border-gray-300 dark:border-zinc-700 rounded-lg p-4 text-sm text-gray-500">
                                No email actions are currently available for this application.
                            </div>

                            <div v-else class="space-y-3">
                                <label
                                    v-for="option in availableNotificationOptions"
                                    :key="option.value"
                                    class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800 cursor-pointer"
                                >
                                    <input
                                        type="radio"
                                        :value="option.value"
                                        v-model="notification.type"
                                        class="mt-1"
                                    />
                                    <div>
                                        <div class="font-medium">{{ option.label }}</div>
                                        <div class="text-xs text-gray-500">{{ option.description }}</div>
                                    </div>
                                </label>
                            </div>

                            <p v-if="notificationErrors.type" class="mt-2 text-sm text-red-600">
                                {{ notificationErrors.type }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Email Preview
                            </label>

                            <div
                                v-if="!notificationPreview"
                                class="border border-dashed border-gray-300 dark:border-zinc-700 rounded-lg p-4 text-sm text-gray-500"
                            >
                                Select an available email action to preview recipients and message details.
                            </div>

                            <div v-else class="border border-gray-200 dark:border-zinc-700 rounded-xl p-4 bg-gray-50 dark:bg-zinc-800/50 space-y-4">
                                <div>
                                    <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Subject</div>
                                    <div class="text-sm font-medium">{{ notificationPreview.subject }}</div>
                                </div>

                                <div>
                                    <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Recipients</div>
                                    <div class="space-y-2">
                                        <div
                                            v-for="(recipient, idx) in notificationPreview.recipients"
                                            :key="idx"
                                            class="text-sm border rounded-lg px-3 py-2 bg-white dark:bg-zinc-900"
                                        >
                                            <div class="font-medium">{{ recipient.name || '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ recipient.email || 'No email address' }}</div>
                                            <div class="text-xs text-gray-400 mt-1">{{ recipient.extra }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs uppercase tracking-wide text-gray-500 mb-1">Message Summary</div>
                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ notificationPreview.summary }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button
                            @click="showNotificationModal = false"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                        >
                            Cancel
                        </button>

                        <button
                            @click="sendNotification"
                            :disabled="sendingNotification || !notification.type"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                        >
                            <span v-if="!sendingNotification">Send</span>
                            <span v-else>Sending...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

  <style scoped>

  .btn-bulk-add {
    background-color: #2563eb;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background-color 0.2s;
}

.btn-bulk-add:hover {
    background-color: #1d4ed8;
}

.btn-bulk-edit {
    background-color: #059669;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background-color 0.2s;
}

.btn-bulk-edit:hover:not(:disabled) {
    background-color: #047857;
}

  .btn-primary {
  display: inline-block;
  width: auto;
  font-weight: 600;
    padding: 0.25rem 0.75rem;
    background-color: var(--ats-primary);
    color: #fff;
    border-radius: 5px;
    font-size: 16px;
  }
  .btn-danger {
    padding: 0.5rem 1rem;
    background-color: #dc2626;
    color: #fff;
    border-radius: 5px;
  }
  .btn-secondary {
    padding: 0.5rem 1rem;
    background-color: #f3f4f6;
    color: #374151;
    border-radius: 5px;
  }

  .close-btn {
    background: transparent;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
  }

  .tag-item {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.75rem;
  margin: 0.125rem 0.25rem 0.125rem 0;
  background: #f8f9fa;
  color: #374151;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  line-height: 1.25;
  transition: all 0.15s ease;
  cursor: default;
}

.tag-item:hover {
  background: #f1f5f9;
  border-color: #d1d5db;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.tag-text {
  font-size: 0.875rem;
}

.tag-role {
  font-size: 0.8125rem;
  opacity: 0.7;
  font-weight: 400;
  margin-left: 0.25rem;
}

.remove-tag {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 1.5rem;
  height: 1.5rem;
  margin: 0;
  padding: 0;
  border: none;
  background: transparent;
  color: #6b7280;
  border-radius: 0.375rem;
  cursor: pointer;
  flex-shrink: 0;
  transition: all 0.15s ease;
  outline: none;
}

.remove-tag:hover {
  background: #f3f4f6;
  color: #374151;
}

.remove-tag:focus-visible {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

.remove-tag:active {
  transform: scale(0.95);
}

.remove-icon {
  display: block;
}
  </style>
