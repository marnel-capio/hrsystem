<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, computed, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    User,
    FileText,
    Award,
    Calendar,
    Star,
    CheckCircle,
    Users,
    Plus,
} from 'lucide-vue-next';
import axios from 'axios';

const page = usePage<any>();

const application = computed(() => page.props.application);
const workExperiences = computed(() => page.props.workExperiences || []);
const skills = computed(() => page.props.skills || []);
const interviews = ref<any[]>(page.props.interviews || []);
const allInterviewers = computed(() => page.props.availableInterviewers || []);
const userPermissions = computed(() =>
    Number(page.props.userPermissions || 0),
);
const examVenues = computed(() => page.props.examVenues || {});

const canManageInterviewers = computed(() =>
    [1, 2, 3].includes(userPermissions.value),
);
const canNotify = computed(() => ![5, 6].includes(userPermissions.value));
const successMessage = ref((page.props.flash as any)?.success || '');
const showSuccess = ref(!!successMessage.value);
const errorMessage = ref((page.props.flash as any)?.error || '');
const showError = ref(!!errorMessage.value);

const canAcceptDecline = (interview: any) => {
    return (
        [2, 3, 5, 6].includes(userPermissions.value) &&
        interview.interviewer_id === page.props.user_id &&
        interview.status === 1
    );
};

const bulkEditScheduleErrors = ref({
    selectedInterviewers: '',
    scheduledDate: '',
});

const acceptDeclineErrors = ref({
    decision: '',
    reason: '',
});

const notificationErrors = ref({
    type: '',
});

const showAcceptDeclineModal = ref(false);
const acceptDeclineSubmitting = ref(false);
const bulkAddScheduledDate = ref('');
const acceptDeclineInterviewer = ref<any>(null);
const acceptDeclineDecision = ref('');
const acceptDeclineReason = ref('');

const showBulkEditScheduleModal = ref(false);
const bulkEditScheduledDate = ref('');
const bulkEditingSchedule = ref(false);

const showNotificationModal = ref(false);
const showBulkAddModal = ref(false);
const sendingNotification = ref(false);
const bulkAdding = ref(false);

const bulkAddPlannedDate = computed(() => {
    const stage = Number(bulkAddStage.value);

    if (stage === 1) return application.value?.exam_plan_date || '';
    if (stage === 2)
        return application.value?.initial_interview_plan_date || '';
    if (stage === 3) return application.value?.final_interview_date || '';

    return '';
});

const notification = ref({
    type: '',
});

const selectedBulkInterviewers = ref<any[]>([]);
const interviewerSearch = ref('');
const showInterviewerDropdown = ref(false);
const bulkAddStage = ref('1');

const selectedInterviewers = ref<number[]>([]);
const selectAll = ref(false);

const showDeclineReasonModal = ref(false);
const selectedDeclinedInterview = ref<any>(null);

const lockedInterviewStatuses = [1, 2, 4];

const editableInterviewIds = computed(() => {
    return interviews.value
        .filter((i: any) => !lockedInterviewStatuses.includes(Number(i.status)))
        .map((i: any) => i.id);
});

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedInterviewers.value = [...editableInterviewIds.value];
    } else {
        selectedInterviewers.value = [];
    }
};

const hasScheduledJobOffer = computed(() => {
    return !!application.value?.job_offer_schedule;
});

watch(selectedInterviewers, (newVal) => {
    selectAll.value =
        editableInterviewIds.value.length > 0 &&
        newVal.length === editableInterviewIds.value.length;
});

watch(bulkAddStage, () => {
    interviewerSearch.value = '';
    showInterviewerDropdown.value = false;
});

const selectedInterviewDetails = computed(() => {
    return interviews.value.filter((interview: any) =>
        selectedInterviewers.value.includes(interview.id),
    );
});

const filteredAvailableInterviewers = computed(() => {
    const selectedIds = selectedBulkInterviewers.value.map((i) => i.id);
    const stage = Number(bulkAddStage.value);

    const alreadyAssignedForStage = interviews.value
        .filter((i: any) => Number(i.interview_type) === stage)
        .map((i: any) => Number(i.interviewer_id));

    const baseList = allInterviewers.value.filter(
        (i: any) =>
            !selectedIds.includes(i.id) &&
            !alreadyAssignedForStage.includes(Number(i.id)),
    );

    if (!interviewerSearch.value.trim()) {
        return baseList;
    }

    const query = interviewerSearch.value.toLowerCase();

    return baseList.filter((i: any) =>
        String(i.name || '')
            .toLowerCase()
            .includes(query),
    );
});

const initialInterviewAssignments = computed(
    () => page.props.initialInterviewAssignments || [],
);

const finalInterviewAssignments = computed(
    () => page.props.finalInterviewAssignments || [],
);

const applicantFullName = computed(() => {
    const a = application.value?.applicant;
    if (!a) return '-';
    return `${a.first_name} ${a.last_name}`.trim();
});

const pendingApprovalInterviewers = computed(() => {
    return interviews.value.filter(
        (i: any) => Number(i.status) === 1 && !i.pending_approval_notified_at,
    );
});

const failedStage = computed(() => {
    if (Number(application.value?.final_interview_result) === 3) {
        return 'final_interview';
    }
    if (Number(application.value?.initial_interview_result) === 3) {
        return 'initial_interview';
    }
    if (Number(application.value?.exam_result) === 3) {
        return 'exam';
    }
    return null;
});

const failedStageLabel = computed(() => {
    if (failedStage.value === 'final_interview') return 'Final Interview';
    if (failedStage.value === 'initial_interview') return 'Initial Interview';
    if (failedStage.value === 'exam') return 'Exam';
    return null;
});

const availableNotificationOptions = computed(() => {
    const options: Array<{
        value: string;
        label: string;
        description: string;
    }> = [];

    if (pendingApprovalInterviewers.value.length > 0) {
        options.push({
            value: 'interviewer_pending_approval',
            label: 'Send pending approval to interviewer(s)',
            description:
                'Notify assigned interviewer(s) that they need to approve or decline their schedule.',
        });
    }

    if (failedStage.value) {
        options.push({
            value: 'applicant_failed',
            label: `Send applicant failed notification (${failedStageLabel.value})`,
            description: `Notify the applicant that they did not pass the ${failedStageLabel.value}.`,
        });
    }

    if (hasScheduledJobOffer.value) {
        options.push({
            value: 'hr_recruiters_job_offer',
            label: 'Notify all HR recruiters of scheduled job offer',
            description:
                'Send the scheduled job offer details to all HR recruiters.',
        });
    }

    return options;
});

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
            };

        case 'applicant_failed':
            return {
                subject: '【HR System】Application Update',
                recipients: [
                    {
                        name: applicantFullName.value,
                        email:
                            application.value?.applicant?.email_address || '',
                        extra: 'Applicant',
                    },
                ],
                summary: `This email tells the applicant that they did not pass the ${failedStageLabel.value}.`,
            };

        case 'hr_recruiters_job_offer':
            return {
                subject: '【HR System】Scheduled Job Offer',
                recipients: [
                    {
                        name: 'All HR Recruiters',
                        email: 'HR Recruiter distribution',
                        extra: formatDateTime(
                            application.value?.job_offer_schedule,
                        ),
                    },
                ],
                summary: `This email notifies all HR recruiters that a job offer has been scheduled for ${applicantFullName.value}.`,
            };

        default:
            return null;
    }
});

const willTriggerApplicantAutoEmail = computed(() => {
    const current = acceptDeclineInterviewer.value;
    if (!current) return false;

    const sameStageRows = interviews.value.filter(
        (i: any) => Number(i.interview_type) === Number(current.interview_type),
    );

    const otherRows = sameStageRows.filter((i: any) => i.id !== current.id);

    return (
        sameStageRows.length > 0 &&
        Number(current.status) === 1 &&
        otherRows.every((i: any) => Number(i.status) === 2)
    );
});

// Helper functions
const normalizeDateTimeForSubmit = (value: string) => {
    if (!value) return value;

    if (/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/.test(value)) {
        return value.replace('T', ' ') + ':00';
    }

    return value;
};

const formatDateTime = (dateString: string | null) => {
    if (!dateString) return '-';

    const raw = String(dateString)
        .replace('T', ' ')
        .replace('Z', '')
        .slice(0, 16);
    const [datePart, timePart] = raw.split(' ');

    if (!datePart || !timePart) return String(dateString);

    const [year, month, day] = datePart.split('-').map(Number);
    const [hour, minute] = timePart.split(':').map(Number);

    const localDate = new Date(
        year,
        (month || 1) - 1,
        day || 1,
        hour || 0,
        minute || 0,
    );

    return localDate.toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
    });
};

const getEvaluationResultLabel = (result: number | null) => {
    const results: Record<number, string> = {
        1: 'Pending',
        2: 'Passed',
        3: 'Failed',
    };

    if (!result) return '-';
    return results[result] || '-';
};

const getEvaluationBadgeClass = (result: number | null) => {
    const classes: Record<number, string> = {
        1: 'bg-yellow-100 text-yellow-800',
        2: 'bg-green-100 text-green-800',
        3: 'bg-red-100 text-red-800',
    };

    if (!result) return 'bg-gray-100 text-gray-800';
    return classes[result] || 'bg-gray-100 text-gray-800';
};

const formatScore = (score: number | null) => {
    if (score === null || score === undefined) return '-';
    return `${score}`;
};

const getPicUrl = (filename: string | null) => {
    if (!filename) return null;
    if (filename.startsWith('pictures/')) {
        return `/storage/${filename}`;
    }
    return `/storage/pictures/${filename}`;
};

const getFileUrl = (filename: string | null) => {
    if (!filename) return null;
    if (filename.startsWith('resumes/')) {
        return `/storage/${filename}`;
    }
    return `/storage/resumes/${filename}`;
};

const getExamApplicationStatusLabel = (status: number) => {
    const statuses: Record<number, string> = {
        1: 'Pending',
        2: 'Done',
        3: 'Passed',
        4: '2nd Priority (P2)',
        5: 'Failed',
    };
    return statuses[status] || '-';
};

const getInterviewApplicationStatusLabel = (status: number) => {
    const statuses: Record<number, string> = {
        1: 'Pending',
        2: 'Done',
        3: 'Passed',
        4: 'P2',
        5: 'Failed',
    };
    return statuses[status] || '-';
};

const getJobOfferStatusLabel = (status: number) => {
    const statuses: Record<number, string> = {
        1: 'Pending',
        2: 'Done',
        3: 'Accept',
        4: 'Decline',
        5: 'Withdraw',
        6: 'Retracted',
    };
    return statuses[status] || '-';
};

const getStageLabel = (type: number) => {
    const stages: Record<number, string> = {
        1: 'Exam',
        2: 'Initial Interview',
        3: 'Final Interview',
    };
    return stages[type] || '-';
};

const getInterviewStatusLabel = (status: number) => {
    const statuses: Record<number, string> = {
        1: 'Pending Approval',
        2: 'Approved',
        3: 'Declined',
        4: 'Done',
    };
    return statuses[status] || '-';
};

const getInterviewStatusBadgeClass = (status: number) => {
    const classes: Record<number, string> = {
        1: 'bg-yellow-100 text-yellow-800',
        2: 'bg-blue-100 text-blue-800',
        3: 'bg-red-100 text-red-800',
        4: 'bg-green-100 text-green-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const getStageBadgeClass = (type: number) => {
    const classes: Record<number, string> = {
        1: 'bg-purple-100 text-purple-800',
        2: 'bg-blue-100 text-blue-800',
        3: 'bg-green-100 text-green-800',
    };
    return classes[type] || 'bg-gray-100 text-gray-800';
};



function getExamStatusBadgeClass(status: number | null | undefined) {
    switch (Number(status)) {
        case 1:
            return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300';
        case 2:
            return 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300';
        case 3:
            return 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300';
        case 4:
            return 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300';
        case 5:
            return 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300';
        default:
            return 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300';
    }
}

function getApplicationStatusBadgeClass(status: number | null | undefined) {
    switch (Number(status)) {
        case 1:
            return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300';
        case 2:
            return 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300';
        case 3:
            return 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300';
        case 4:
            return 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300';
        case 5:
            return 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300';
        default:
            return 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300';
    }
}

const getOverallStatus = () => {
    const stage = Number(application.value.application_stage);

    const stageLabels = {
        1: 'New',
        2: 'For Exam',
        3: 'For Initial Interview',
        4: 'For Final Interview',
        5: 'For Job Offer',
        6: 'Failed',
    };

    return stageLabels[stage] || 'New';
};

const getOverallStatusColor = () => {
    const stage = Number(application.value.application_stage);

    switch (stage) {
        case 1: // New
            return 'bg-yellow-100 text-yellow-800';
        case 2: // For Exam
        case 3: // For Initial Interview
        case 4: // For Final Interview
        case 5: // For Job Offer
            return 'bg-blue-100 text-blue-800';
        case 6: // Failed
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const canViewDeclineReason = (interview: any) => {
    return (
        Number(interview.status) === 3 &&
        !!String(interview.decline_reason || '').trim()
    );
};

// Modal actions
const openBulkEditScheduleModal = () => {
    bulkEditScheduleErrors.value.selectedInterviewers = '';
    bulkEditScheduleErrors.value.scheduledDate = '';

    if (selectedInterviewers.value.length === 0) {
        bulkEditScheduleErrors.value.selectedInterviewers =
            'This is a required field.';
        return;
    }

    bulkEditScheduledDate.value = '';
    showBulkEditScheduleModal.value = true;
};

const openAcceptDeclineModal = (interview: any) => {
    acceptDeclineInterviewer.value = interview;
    acceptDeclineDecision.value = '';
    acceptDeclineReason.value = '';
    acceptDeclineErrors.value.decision = '';
    acceptDeclineErrors.value.reason = '';
    showAcceptDeclineModal.value = true;
};

const openDeclineReasonModal = (interview: any) => {
    selectedDeclinedInterview.value = interview;
    showDeclineReasonModal.value = true;
};

const closeDeclineReasonModal = () => {
    showDeclineReasonModal.value = false;
    selectedDeclinedInterview.value = null;
};

const openBulkAddModal = () => {
    selectedBulkInterviewers.value = [];
    interviewerSearch.value = '';
    bulkAddStage.value = '1';
    showBulkAddModal.value = true;
};

const addToBulkSelection = (interviewer: any) => {
    if (!selectedBulkInterviewers.value.some((i) => i.id === interviewer.id)) {
        selectedBulkInterviewers.value.push(interviewer);
    }
    interviewerSearch.value = '';
    showInterviewerDropdown.value = false;
};

const removeFromBulkSelection = (id: number) => {
    selectedBulkInterviewers.value = selectedBulkInterviewers.value.filter(
        (i) => i.id !== id,
    );
};

const searchInterviewers = () => {
    showInterviewerDropdown.value = !!interviewerSearch.value.trim();
};

const syncApplicationDatesFromInterviews = () => {
    const exam = interviews.value.find((i) => Number(i.interview_type) === 1);
    const initial = interviews.value.find(
        (i) => Number(i.interview_type) === 2,
    );
    const final = interviews.value.find((i) => Number(i.interview_type) === 3);

    if (exam) application.value.exam_plan_date = exam.scheduled_date;
    if (initial)
        application.value.initial_interview_plan_date = initial.scheduled_date;
    if (final) application.value.final_interview_date = final.scheduled_date;
};

// API calls
const submitBulkEditSchedule = async () => {
    bulkEditScheduleErrors.value.selectedInterviewers = '';
    bulkEditScheduleErrors.value.scheduledDate = '';

    let hasError = false;

    if (selectedInterviewers.value.length === 0) {
        bulkEditScheduleErrors.value.selectedInterviewers =
            'This is a required field.';
        hasError = true;
    }

    if (!bulkEditScheduledDate.value) {
        bulkEditScheduleErrors.value.scheduledDate =
            'This is a required field.';
        hasError = true;
    }

    if (hasError) return;

    bulkEditingSchedule.value = true;

    try {
        const response = await axios.post(
            `/intermediate/applications/${application.value.id}/interviews/bulk-update-schedule`,
            {
                interview_ids: selectedInterviewers.value,
                scheduled_date: normalizeDateTimeForSubmit(
                    bulkEditScheduledDate.value,
                ),
            },
        );

        interviews.value = response.data.interviews || interviews.value;
        syncApplicationDatesFromInterviews();
        showBulkEditScheduleModal.value = false;
        selectedInterviewers.value = [];
        selectAll.value = false;
        bulkEditScheduledDate.value = '';
        bulkEditScheduleErrors.value.selectedInterviewers = '';
        bulkEditScheduleErrors.value.scheduledDate = '';

        showToast(
            response.data.message || 'Record updated successfully!',
            'success',
        );
    } catch (error: any) {
        showToast(
            error?.response?.data?.error ||
            error?.response?.data?.message ||
            'Failed to update schedules',
            'error',
        );
    } finally {
        bulkEditingSchedule.value = false;
    }
};

const submitBulkAdd = async () => {
    if (selectedBulkInterviewers.value.length === 0) {
        showToast('Please select at least one interviewer', 'error');
        return;
    }

    if (!bulkAddPlannedDate.value) {
        showToast('No plan date is set for the selected stage', 'error');
        return;
    }

    bulkAdding.value = true;

    try {
        const payload = {
            interviewer_ids: selectedBulkInterviewers.value.map((i) => i.id),
            interview_type: Number(bulkAddStage.value),
            scheduled_date: normalizeDateTimeForSubmit(
                bulkAddPlannedDate.value,
            ),
        };

        const response = await axios.post(
            `/intermediate/applications/${application.value.id}/interviews/bulk-add`,
            payload,
        );

        interviews.value = response.data.interviews || interviews.value;
        syncApplicationDatesFromInterviews();

        showBulkAddModal.value = false;
        selectedBulkInterviewers.value = [];
        interviewerSearch.value = '';
        bulkAddScheduledDate.value = '';
        bulkAddStage.value = '1';

        showToast(
            response.data.message || 'Record updated successfully.',
            'success',
        );
    } catch (error: any) {
        console.error('Bulk add failed:', error?.response || error);
        showToast(
            error?.response?.data?.error ||
            error?.response?.data?.message ||
            'Failed to add interviewers',
            'error',
        );
    } finally {
        bulkAdding.value = false;
    }
};

const submitAcceptDecline = async () => {
    acceptDeclineErrors.value.decision = '';
    acceptDeclineErrors.value.reason = '';

    let hasError = false;

    if (!acceptDeclineDecision.value) {
        acceptDeclineErrors.value.decision = 'This field is required.';
        hasError = true;
    }

    if (
        acceptDeclineDecision.value === 'decline' &&
        !acceptDeclineReason.value.trim()
    ) {
        acceptDeclineErrors.value.reason = 'This field is required.';
        hasError = true;
    }

    if (hasError) return;

    acceptDeclineSubmitting.value = true;

    try {
        await axios.post(
            `/intermediate/applications/${application.value.id}/interviews/${acceptDeclineInterviewer.value.id}/decision`,
            {
                decision: acceptDeclineDecision.value,
                reason: acceptDeclineReason.value,
            },
        );

        interviews.value = interviews.value.map((interview: any) => {
            if (interview.id !== acceptDeclineInterviewer.value.id) {
                return interview;
            }

            return {
                ...interview,
                status: acceptDeclineDecision.value === 'accept' ? 2 : 3,
                decline_reason:
                    acceptDeclineDecision.value === 'decline'
                        ? acceptDeclineReason.value
                        : null,
            };
        });

        showAcceptDeclineModal.value = false;
        acceptDeclineErrors.value.decision = '';
        acceptDeclineErrors.value.reason = '';

        showToast(
            acceptDeclineDecision.value === 'accept'
                ? 'Interview assignment accepted successfully!'
                : 'Interview assignment declined.',
            'success',
        );
    } catch (error: any) {
        showToast(
            error?.response?.data?.message || 'Failed to submit decision',
            'error',
        );
    } finally {
        acceptDeclineSubmitting.value = false;
    }
};

const sendNotification = async () => {
    notificationErrors.value.type = '';

    if (!notification.value.type) {
        notificationErrors.value.type = 'This is a required field.';
        return;
    }

    sendingNotification.value = true;

    try {
        const response = await axios.post(
            `/intermediate/applications/${application.value.id}/send-notification`,
            {
                type: notification.value.type,
            },
        );

        if (notification.value.type === 'interviewer_pending_approval') {
            interviews.value = interviews.value.map((i: any) => {
                if (Number(i.status) === 1 && !i.pending_approval_notified_at) {
                    return {
                        ...i,
                        pending_approval_notified_at: new Date().toISOString(),
                    };
                }
                return i;
            });
        }

        showNotificationModal.value = false;
        notification.value.type = '';
        notificationErrors.value.type = '';

        showToast(
            response.data.message || 'Email sent successfully.',
            'success',
        );
    } catch (error: any) {
        showToast(
            error?.response?.data?.error ||
            error?.response?.data?.message ||
            'Failed to send notification',
            'error',
        );
    } finally {
        sendingNotification.value = false;
    }
};

const downloadApplication = () => {
    window.open(
        `/intermediate/applications/${application.value.id}/print`,
        '_blank',
    );
};

// Toast
const toastMessage = ref<string | null>(null);
const toastType = ref<'success' | 'error'>('success');
const showToastMessage = ref(false);

const showToast = (message: string, type: 'success' | 'error') => {
    toastMessage.value = message;
    toastType.value = type;
    showToastMessage.value = true;
    setTimeout(() => {
        showToastMessage.value = false;
    }, 3000);
};

watch(
    successMessage,
    (newVal) => {
        if (newVal) {
            showSuccess.value = true;
            setTimeout(() => {
                showSuccess.value = false;
                successMessage.value = '';
            }, 5000);
        }
    },
    { immediate: true },
);

watch(
    errorMessage,
    (newVal) => {
        if (newVal) {
            showError.value = true;
            setTimeout(() => {
                showError.value = false;
                errorMessage.value = '';
            }, 5000);
        }
    },
    { immediate: true },
);

watch(acceptDeclineReason, (newVal) => {
    if (newVal.trim()) {
        acceptDeclineErrors.value.reason = '';
    }
});

watch(acceptDeclineDecision, () => {
    acceptDeclineErrors.value.decision = '';
    if (acceptDeclineDecision.value !== 'decline') {
        acceptDeclineErrors.value.reason = '';
    }
});

// Paper Screening Modal
const showPaperScreeningModal = ref(false);
const paperScreeningSubmitting = ref(false);
const selectedPaperScreeningStatus = ref<number | null>(null);

// Paper screening status options
const paperScreeningStatusOptions = [
    { value: 1, label: 'Pending' },
    { value: 2, label: 'Done' },
    { value: 3, label: 'Passed' },
    { value: 4, label: '2nd Priority (P2)' },
    { value: 5, label: 'Failed' },
];

const openPaperScreeningModal = () => {
    selectedPaperScreeningStatus.value = application.value.paper_screening_status || 1;
    showPaperScreeningModal.value = true;
};

const updatePaperScreeningStatus = async () => {
    if (!selectedPaperScreeningStatus.value) {
        showToast('Please select a status', 'error');
        return;
    }

    paperScreeningSubmitting.value = true;

    try {
        const response = await axios.post(
            `/intermediate/applications/${application.value.id}/update-paper-screening`,
            {
                paper_screening_status: selectedPaperScreeningStatus.value
            }
        );

        // Update the local application status
        application.value.paper_screening_status = selectedPaperScreeningStatus.value;

        // If the response includes updated application_stage, update it too
        if (response.data.application_stage) {
            application.value.application_stage = response.data.application_stage;
        }

        showPaperScreeningModal.value = false;

        showToast(
            response.data.message || 'Paper screening status updated successfully!',
            'success'
        );
    } catch (error: any) {
        showToast(
            error?.response?.data?.message || 'Failed to update paper screening status',
            'error'
        );
    } finally {
        paperScreeningSubmitting.value = false;
    }
};


const getPaperScreeningStatusLabel = (status: number) => {
    const option = paperScreeningStatusOptions.find(opt => opt.value === status);
    return option?.label || '-';
};

const getPaperScreeningStatusBadgeClass = (status: number) => {
    switch (status) {
        case 1: // Pending
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
        case 2: // 1st Priority (Passed)
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300';
        case 3: // 2nd Priority (P2)
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        case 4: // Done
            return 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
        case 5: // Passed
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }
};

</script>

<template>
    <AppLayout>

        <div style="background: yellow; padding: 10px; margin: 10px;">
            DEBUG - Application: {{ application }}
        </div>
        <div class="intermediate-application-detail">
            <!-- Toast Messages -->
            <div v-if="showSuccess" class="full-width-alert">
                <div class="alert-banner alert-success-banner">
                    <div class="alert-body">{{ successMessage }}</div>
                    <button type="button" class="close-btn" @click="showSuccess = false">
                        ×
                    </button>
                </div>
            </div>

            <div v-if="showError" class="full-width-alert">
                <div class="alert-banner alert-error-banner">
                    <div class="alert-body">{{ errorMessage }}</div>
                    <button type="button" class="close-btn" @click="showError = false">
                        ×
                    </button>
                </div>
            </div>

            <div v-if="showToastMessage" class="full-width-alert">
                <div class="alert-banner" :class="toastType === 'success'
                    ? 'alert-success-banner'
                    : 'alert-error-banner'
                    ">
                    <div class="alert-body">{{ toastMessage }}</div>
                    <button type="button" class="close-btn" @click="showToastMessage = false">
                        ×
                    </button>
                </div>
            </div>

            <div class="flex min-h-screen flex-1 flex-col gap-6 bg-zinc-50/50 p-8 dark:bg-zinc-950">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">
                            Intermediate Application Details
                        </h1>
                    </div>
                    <div class="flex gap-3">
                        <button @click="downloadApplication" class="btn-primary">
                            Print or Save as PDF
                        </button>
                        <button v-if="canNotify" @click="showNotificationModal = true" class="btn-send">
                            Notify Applicant, Interviewer, or Conductor
                        </button>
                        <Link :href="`/intermediate/applications/${application.id}/edit`" class="btn-edit">
                            Edit Application
                        </Link>
                    </div>
                </div>


                <!-- Applicant Info Card -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="col-span-1">

                        <div class="flex h-full flex-col items-center justify-center rounded-xl px-5 py-4 text-center text-white shadow-md"
                            style="background-color: #2f359e">
                            <div
                                class="mb-3 flex h-24 w-24 items-center justify-center overflow-hidden rounded-full bg-white">

                                <!-- <img src="storage\app\public\pictures\NJIqtRIp5zplmVzoCeRUKuIBAtqCR2bv5aI9WEv5.jpg" alt="Test" class="h-full w-full object-cover" /> -->
                                <img v-if="application.upload_pic" :src="getPicUrl(application.upload_pic) || ''"
                                    alt="Applicant Photo" class="h-full w-full object-cover" />
                                <User v-else class="h-10 w-10 text-blue-600" />
                            </div>
                            <h2 class="text-2xl font-extrabold tracking-wide drop-shadow">
                                {{ application.applicant?.last_name }},
                                {{ application.applicant?.first_name }}
                                {{ application.applicant?.middle_name || '' }}
                            </h2>
                            <p class="mt-2 text-xs opacity-75">
                                {{ application.applicant?.email_address }}
                            </p>
                            <p class="text-xs opacity-75">
                                {{ application.applicant?.contact_no }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-6 md:col-span-2">
                        <div
                            class="rounded-xl border border-zinc-200 bg-white p-5 shadow dark:border-zinc-800 dark:bg-zinc-900">
                            <div class="space-y-3 text-sm">
                                <div
                                    class="flex items-center justify-between rounded-lg bg-zinc-100 px-4 py-2 dark:bg-zinc-800">
                                    <span class="font-semibold">Project:</span>
                                    <span class="font-extrabold text-blue-600">{{ application.project_name }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between rounded-lg bg-zinc-100 px-4 py-2 dark:bg-zinc-800">
                                    <span class="font-semibold">Status:</span>
                                    <span :class="[
                                        'inline-flex rounded-full px-2 py-1 text-xs font-semibold',
                                        getOverallStatusColor(),
                                    ]">
                                        {{ getOverallStatus() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div
                            class="rounded-xl border border-zinc-200 bg-white p-6 shadow dark:border-zinc-800 dark:bg-zinc-900">
                            <h2 class="mb-4 text-lg font-bold">
                                Uploaded Documents
                            </h2>

                            <div class="space-y-3" v-if="application.upload_resume">
                                <div v-if="application.upload_resume"
                                    class="flex items-center justify-between rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                                    <div class="flex items-center gap-2">
                                        <FileText class="h-4 w-4 text-blue-600" />
                                        <span class="text-sm">Resume/CV</span>
                                    </div>
                                    <a :href="getFileUrl(
                                        application.upload_resume,
                                    )
                                        " target="_blank"
                                        class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                        View File
                                    </a>
                                </div>
                            </div>

                            <div v-else class="text-sm text-gray-500 dark:text-gray-400">
                                No uploaded documents available.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Screening Details -->
                <div
                    class="rounded-xl border border-zinc-200 bg-white p-6 shadow dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="flex items-center gap-2 text-lg font-bold">
                            <CheckCircle class="h-5 w-5 text-blue-600" />
                            SCREENING DETAILS
                        </h2>

                        <!-- Paper Screening Status - Top Right -->
                        <div class="flex items-center gap-2">
                            <span :class="[
                                'inline-flex rounded-full px-4 py-1.5 text-base font-medium',
                                getPaperScreeningStatusBadgeClass(application.paper_screening_status)
                            ]">
                                {{ getPaperScreeningStatusLabel(application.paper_screening_status) }}
                            </span>
                            <button v-if="canManageInterviewers" @click="openPaperScreeningModal"
                                class="rounded-md border border-zinc-300 px-2 py-0.5 text-xs font-medium text-zinc-600 transition hover:bg-zinc-50 hover:text-zinc-900 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                                title="Update paper screening status">
                                Set Paper Screening Status
                            </button>
                        </div>
                    </div>

                    <!-- Screening Questions -->
                    <div class="mb-5">
                        <div class="mb-3 text-sm font-semibold">Screening Questions</div>
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <div class="flex items-center justify-between rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                                <span class="text-sm">1. Have you ever filed an application in AWS, Inc. before?</span>
                                <span :class="[
                                    'inline-flex rounded-full px-2 py-1 text-xs font-semibold',
                                    application.answer_q1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                ]">
                                    {{ application.answer_q1 ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                                <span class="text-sm">2. Do any of your friends or relatives, other than a spouse, work
                                    in AWS?</span>
                                <span :class="[
                                    'inline-flex rounded-full px-2 py-1 text-xs font-semibold',
                                    application.answer_q2 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                ]">
                                    {{ application.answer_q2 ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                                <span class="text-sm">3. Have you worked in AWS before?</span>
                                <span :class="[
                                    'inline-flex rounded-full px-2 py-1 text-xs font-semibold',
                                    application.answer_q3 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                ]">
                                    {{ application.answer_q3 ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                                <span class="text-sm">4. Will you travel if the job requires it?</span>
                                <span :class="[
                                    'inline-flex rounded-full px-2 py-1 text-xs font-semibold',
                                    application.answer_q4 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                ]">
                                    {{ application.answer_q4 ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Availability & Preferences -->
                    <div class="mb-5">
                        <div class="mb-3 text-sm font-semibold">Availability & Preferences</div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">When is the applicant available to start?</div>
                                <div class="text-sm font-medium">{{ application.availability_date || '-' }}</div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Work Preference</div>
                                <div class="text-sm font-medium">{{ application.work_preference || '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Compensation - Current/Previous -->
                    <div class="mb-5">
                        <div class="mb-3 text-sm font-semibold">Current/Previous Compensation</div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Basic Pay</div>
                                <div class="text-sm font-medium">{{ application.basic_pay || '-' }}</div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Bonuses</div>
                                <div class="text-sm font-medium">{{ application.bonuses || '-' }}</div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Allowances</div>
                                <div class="text-sm font-medium">{{ application.allowances || '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits -->
                    <div class="mb-5">
                        <div class="mb-3 text-sm font-semibold">Benefits</div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">HMO</div>
                                <div class="text-sm font-medium">{{ application.hmo || '-' }}</div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Leaves</div>
                                <div class="text-sm font-medium">{{ application.leaves || '-' }}</div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Other Benefits</div>
                                <div class="text-sm font-medium">{{ application.other_benefits || '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Desired & Target -->
                    <div class="mb-5">
                        <div class="mb-3 text-sm font-semibold">Desired & Target</div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Desired Salary Range</div>
                                <div class="text-sm font-medium">{{ application.desired_salary_range || '-' }}</div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Targeted Company</div>
                                <div class="text-sm font-medium">{{ application.targeted_company || '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Industry Experience -->
                    <div>
                        <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                            <div class="mb-1 text-xs text-zinc-500">Industry Experience</div>
                            <div class="text-sm font-medium">{{ application.industry_experience || '-' }}</div>
                        </div>
                    </div>

                    <!-- Work Experience -->
                    <div class="mt-5">
                        <div class="mb-3 text-sm font-semibold">Work Experience</div>

                        <div v-if="workExperiences.length === 0"
                            class="rounded-lg border border-dashed border-zinc-300 p-4 text-sm text-zinc-500 dark:border-zinc-700">
                            No work experience recorded. Try referring to Resumé.
                        </div>

                        <div v-else class="space-y-3">
                            <div v-for="(experience, index) in workExperiences" :key="experience.id"
                                class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="text-sm font-medium text-blue-600">Experience #{{ index + 1 }}</span>
                                </div>
                                <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                                    <div>
                                        <div class="text-xs text-zinc-500">Employer</div>
                                        <div class="text-sm font-medium">{{ experience.employer || '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-zinc-500">Job Title</div>
                                        <div class="text-sm font-medium">{{ experience.job_title || '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-zinc-500">Supervisor Name</div>
                                        <div class="text-sm font-medium">{{ experience.name_supervisor || '-' }}</div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="text-xs text-zinc-500">Work Description</div>
                                        <div class="mt-1 text-sm whitespace-pre-wrap">{{ experience.work_description
                                            || '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Skills -->
                    <div class="mt-5">
                        <div class="mb-3 text-sm font-semibold">Skills</div>

                        <div v-if="skills.length === 0"
                            class="rounded-lg border border-dashed border-zinc-300 p-4 text-sm text-zinc-500 dark:border-zinc-700">
                            No skills recorded. Try referring to Resumé.
                        </div>

                        <div v-else class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                            <div v-for="(skill, index) in skills" :key="skill.id"
                                class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-2 flex items-center justify-between">
                                    <span class="text-xs font-medium text-blue-600">#{{ index + 1 }}</span>
                                </div>
                                <div>
                                    <div class="text-xs text-zinc-500">Skill</div>
                                    <div class="text-sm font-medium">{{ skill.skill || '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paper Screening Status Modal -->
                    <div v-if="showPaperScreeningModal" class="fixed inset-0 z-50 flex items-center justify-center">
                        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                            @click="showPaperScreeningModal = false"></div>

                        <div class="relative mx-4 w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-zinc-900">
                            <h3 class="mb-2 text-lg font-bold">Set Paper Screening Status</h3>
                            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                                Select the paper screening status for this application.
                            </p>

                            <!-- Applicant Info Summary -->
                            <div class="mb-4 rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                                <div class="space-y-1.5 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Applicant:</span>
                                        <span class="font-medium">{{ applicantFullName }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Position:</span>
                                        <span class="font-medium">{{ application.position || 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Current Status:</span>
                                        <span :class="[
                                            'inline-flex rounded-full px-2 py-0.5 text-xs font-medium',
                                            getPaperScreeningStatusBadgeClass(application.paper_screening_status)
                                        ]">
                                            {{ getPaperScreeningStatusLabel(application.paper_screening_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Selection -->
                            <div class="mb-4">
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Select New Status
                                </label>
                                <div class="space-y-2">
                                    <label v-for="option in paperScreeningStatusOptions" :key="option.value"
                                        class="flex cursor-pointer items-center gap-3 rounded-lg border p-3 transition hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800"
                                        :class="selectedPaperScreeningStatus === option.value ? 'border-blue-500 bg-blue-50 dark:border-blue-500 dark:bg-blue-900/30' : 'border-zinc-200'">
                                        <input type="radio" :value="option.value" v-model="selectedPaperScreeningStatus"
                                            class="h-4 w-4 text-blue-600" />
                                        <span :class="[
                                            'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                            getPaperScreeningStatusBadgeClass(option.value)
                                        ]">
                                            {{ option.label }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button @click="showPaperScreeningModal = false"
                                    class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm transition hover:bg-gray-50 dark:border-zinc-700 dark:hover:bg-zinc-800">
                                    Cancel
                                </button>

                                <button @click="updatePaperScreeningStatus"
                                    :disabled="paperScreeningSubmitting || !selectedPaperScreeningStatus"
                                    class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm text-white transition hover:bg-blue-700 disabled:opacity-50">
                                    <span v-if="!paperScreeningSubmitting">Save Status</span>
                                    <span v-else>Saving...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exam Details -->
                <div class="space-y-6">
                    <div
                        class="rounded-xl border border-zinc-200 bg-white p-6 shadow dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="flex items-center gap-2 text-lg font-bold">
                                <Award class="h-5 w-5 text-blue-600" /> EXAM DETAILS
                            </h2>
                        </div>

                        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-4">
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Plan Date</div>
                                <div class="text-sm font-medium">
                                    {{ formatDateTime(application.exam_plan_date) }}
                                </div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Actual Date</div>
                                <div class="text-sm font-medium">
                                    {{ formatDateTime(application.exam_actual_date) }}
                                </div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Venue</div>
                                <div class="text-sm font-medium">
                                    {{ examVenues[application.exam_venue] ?? '-' }}
                                </div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Application Status</div>
                                <div>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                                        :class="getExamStatusBadgeClass(application.exam_application_status)">
                                        {{ getExamApplicationStatusLabel(application.exam_application_status) || '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                            <div>
                                <div class="mb-3 text-sm font-semibold">ATPP Breakdown</div>
                                <div class="overflow-x-auto rounded-lg border border-zinc-200 dark:border-zinc-700">
                                    <table class="w-full text-sm">
                                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                                            <tr>
                                                <th class="px-4 py-3 text-left font-semibold">Part</th>
                                                <th class="px-4 py-3 text-left font-semibold">Correct</th>
                                                <th class="px-4 py-3 text-left font-semibold">Wrong</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-t border-zinc-200 dark:border-zinc-700">
                                                <td class="px-4 py-3">
                                                    <div class="font-medium">Part I</div>
                                                    <div class="text-xs text-zinc-500">Sequence / Pattern Analysis</div>
                                                </td>
                                                <td class="px-4 py-3">{{
                                                    formatScore(application.exam_atpp_part1_correct) }}</td>
                                                <td class="px-4 py-3">{{ formatScore(application.exam_atpp_part1_wrong)
                                                }}</td>
                                            </tr>
                                            <tr class="border-t border-zinc-200 dark:border-zinc-700">
                                                <td class="px-4 py-3">
                                                    <div class="font-medium">Part II</div>
                                                    <div class="text-xs text-zinc-500">Abstract Reasoning</div>
                                                </td>
                                                <td class="px-4 py-3">{{
                                                    formatScore(application.exam_atpp_part2_correct) }}</td>
                                                <td class="px-4 py-3">{{ formatScore(application.exam_atpp_part2_wrong)
                                                }}</td>
                                            </tr>
                                            <tr class="border-t border-zinc-200 dark:border-zinc-700">
                                                <td class="px-4 py-3">
                                                    <div class="font-medium">Part III</div>
                                                    <div class="text-xs text-zinc-500">Problem Solving</div>
                                                </td>
                                                <td class="px-4 py-3">{{
                                                    formatScore(application.exam_atpp_part3_correct) }}</td>
                                                <td class="px-4 py-3">{{ formatScore(application.exam_atpp_part3_wrong)
                                                }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div>
                                <div class="mb-3 text-sm font-semibold">Scores Summary</div>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                        <div class="mb-1 text-xs text-zinc-500">ATPP Final Result</div>
                                        <div class="text-lg font-bold">{{ formatScore(application.exam_atpp_result) }}
                                        </div>
                                    </div>
                                    <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                        <div class="mb-1 text-xs text-zinc-500">Technical Exam Result</div>
                                        <div class="text-lg font-bold">{{ formatScore(application.exam_tech_result) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="mb-2 text-sm font-semibold">Comments</div>
                            <div class="rounded-lg bg-zinc-50 p-4 text-sm dark:bg-zinc-800">
                                {{ application.exam_remarks || 'No comments' }}
                            </div>
                        </div>
                    </div>

                    <!-- Initial Interview Details -->
                    <div
                        class="rounded-xl border border-zinc-200 bg-white p-6 shadow dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="flex items-center gap-2 text-lg font-bold">
                                <Calendar class="h-5 w-5 text-blue-600" /> INITIAL INTERVIEW DETAILS
                            </h2>
                        </div>

                        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Interview Plan Date</div>
                                <div class="text-sm font-medium">{{
                                    formatDateTime(application.initial_interview_plan_date) }}</div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Interview Actual Date</div>
                                <div class="text-sm font-medium">{{
                                    formatDateTime(application.initial_interview_actual_date) }}</div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Final Score</div>
                                <div class="text-lg font-bold">{{ formatScore(application.initial_interview_final) }}
                                </div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Application Status</div>
                                <div>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                                        :class="getApplicationStatusBadgeClass(application.initial_interview_application_status)">
                                        {{
                                            getInterviewApplicationStatusLabel(application.initial_interview_application_status)
                                            || '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 text-sm font-semibold">Comments</div>
                            <div class="rounded-lg bg-zinc-50 p-4 text-sm dark:bg-zinc-800">
                                {{ application.initial_interview_remarks || 'No comments' }}
                            </div>
                        </div>
                    </div>

                    <!-- Final Interview Details -->
                    <div
                        class="rounded-xl border border-zinc-200 bg-white p-6 shadow dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="flex items-center gap-2 text-lg font-bold">
                                <Star class="h-5 w-5 text-blue-600" /> FINAL INTERVIEW DETAILS
                            </h2>
                        </div>

                        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Interview Date</div>
                                <div class="text-sm font-medium">{{ formatDateTime(application.final_interview_date) }}
                                </div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Final Score</div>
                                <div class="text-lg font-bold">{{ formatScore(application.final_interview_final) }}
                                </div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Application Status</div>
                                <div>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                                        :class="getApplicationStatusBadgeClass(application.final_interview_application_status)">
                                        {{
                                            getInterviewApplicationStatusLabel(application.final_interview_application_status)
                                            || '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 text-sm font-semibold">Comments</div>
                            <div class="rounded-lg bg-zinc-50 p-4 text-sm dark:bg-zinc-800">
                                {{ application.final_interview_remarks || 'No comments' }}
                            </div>
                        </div>
                    </div>

                    <!-- Job Offer Details -->
                    <div
                        class="rounded-xl border border-zinc-200 bg-white p-6 shadow dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="flex items-center gap-2 text-lg font-bold">
                                <CheckCircle class="h-5 w-5 text-blue-600" /> JOB OFFER DETAILS
                            </h2>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Schedule</div>
                                <div class="text-sm font-medium">{{ formatDateTime(application.job_offer_schedule) }}
                                </div>
                            </div>
                            <div class="rounded-lg bg-zinc-50 p-4 dark:bg-zinc-800">
                                <div class="mb-1 text-xs text-zinc-500">Status</div>
                                <div>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                                        :class="getApplicationStatusBadgeClass(application.job_offer_status)">
                                        {{ getJobOfferStatusLabel(application.job_offer_status) || '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="mb-2 text-sm font-semibold">Comments</div>
                            <div class="rounded-lg bg-zinc-50 p-4 text-sm dark:bg-zinc-800">
                                {{ application.job_offer_remarks || 'No comments' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Add Interviewers Modal -->
                <div v-if="showBulkAddModal" class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showBulkAddModal = false"></div>
                    <div
                        class="relative mx-4 max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-8 shadow-2xl dark:bg-zinc-900">
                        <h3 class="mb-2 text-xl font-bold">Add Interviewers/Conductors</h3>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                            Add multiple interviewers or conductors at once
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Select
                                    Interviewers</label>
                                <div
                                    class="tag-input-container rounded-lg border border-gray-300 bg-white p-2 dark:border-zinc-700 dark:bg-zinc-800">
                                    <div class="mb-2 flex flex-wrap gap-2">
                                        <div v-for="interviewer in selectedBulkInterviewers" :key="interviewer.id"
                                            class="tag-item">
                                            <span class="tag-text">
                                                {{ interviewer.name }}
                                                <span class="tag-role">({{ interviewer.role_label }})</span>
                                            </span>
                                            <button @click="removeFromBulkSelection(interviewer.id)" class="remove-tag"
                                                type="button">
                                                <svg class="remove-icon" viewBox="0 0 14 14" width="14" height="14">
                                                    <path d="M1 12.5L12.5 1M1 1l11.5 11.5" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input type="text" v-model="interviewerSearch"
                                            @focus="showInterviewerDropdown = true" @input="searchInterviewers"
                                            placeholder="Type to search interviewers..."
                                            class="w-full border-0 bg-transparent p-2 text-sm focus:ring-0" />
                                        <div v-if="showInterviewerDropdown && filteredAvailableInterviewers.length > 0"
                                            class="absolute top-full right-0 left-0 z-10 mt-1 max-h-48 overflow-y-auto rounded-lg border border-gray-300 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-800">
                                            <div v-for="interviewer in filteredAvailableInterviewers"
                                                :key="interviewer.id" @click="addToBulkSelection(interviewer)"
                                                class="cursor-pointer px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700">
                                                {{ interviewer.name }} ({{ interviewer.role_label }})
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">{{ selectedBulkInterviewers.length }}
                                    interviewer(s) selected</p>
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Stage</label>
                                <select v-model="bulkAddStage"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-zinc-700 dark:bg-zinc-800">
                                    <option value="1">Exam</option>
                                    <option value="2">Initial Interview</option>
                                    <option value="3">Final Interview</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Scheduled
                                    Date</label>
                                <div
                                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800">
                                    {{ bulkAddPlannedDate ? formatDateTime(bulkAddPlannedDate) : 'No plan date set for this stage' }}
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    This is automatically taken from the selected stage's planned date.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button @click="showBulkAddModal = false"
                                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 transition hover:bg-gray-50 dark:border-zinc-700 dark:hover:bg-zinc-800">
                                Cancel
                            </button>
                            <button @click="submitBulkAdd"
                                :disabled="bulkAdding || selectedBulkInterviewers.length === 0 || !bulkAddPlannedDate"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                                <span v-if="!bulkAdding">Add Interviewers</span>
                                <span v-else>Adding...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bulk Edit Schedule Modal -->
                <div v-if="showBulkEditScheduleModal" class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                        @click="showBulkEditScheduleModal = false"></div>
                    <div class="relative mx-4 w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl dark:bg-zinc-900">
                        <h3 class="mb-2 text-xl font-bold">Bulk Edit Schedule</h3>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                            Update the schedule of checked interviewers
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Selected
                                    Interviewers</label>
                                <div class="rounded-lg bg-gray-50 px-3 py-3 dark:bg-zinc-800">
                                    <div v-if="selectedInterviewDetails.length > 0" class="space-y-2">
                                        <div v-for="interview in selectedInterviewDetails" :key="interview.id"
                                            class="flex items-center justify-between gap-3 border-b border-gray-200 pb-2 text-sm last:border-b-0 last:pb-0 dark:border-zinc-700">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{
                                                    interview.name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ interview.role_label || '-' }} • {{
                                                    getStageLabel(interview.interview_type) }}
                                                </div>
                                            </div>
                                            <div class="text-right text-xs text-gray-500 dark:text-gray-400">
                                                {{ formatDateTime(interview.scheduled_date) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="text-sm text-gray-500">No interviewers selected.</div>
                                </div>
                                <p v-if="bulkEditScheduleErrors.selectedInterviewers" class="mt-1 text-sm text-red-600">
                                    {{ bulkEditScheduleErrors.selectedInterviewers }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">New
                                    Scheduled Date</label>
                                <input type="datetime-local" v-model="bulkEditScheduledDate"
                                    :min="new Date().toISOString().slice(0, 16)"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-zinc-700 dark:bg-zinc-800" />
                                <p v-if="bulkEditScheduleErrors.scheduledDate" class="mt-1 text-sm text-red-600">
                                    {{ bulkEditScheduleErrors.scheduledDate }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button @click="showBulkEditScheduleModal = false"
                                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 transition hover:bg-gray-50 dark:border-zinc-700 dark:hover:bg-zinc-800">
                                Cancel
                            </button>
                            <button @click="submitBulkEditSchedule" :disabled="bulkEditingSchedule"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700 disabled:opacity-50">
                                <span v-if="!bulkEditingSchedule">Update Schedule</span>
                                <span v-else>Updating...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Accept/Decline Modal -->
                <div v-if="showAcceptDeclineModal" class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showAcceptDeclineModal = false">
                    </div>
                    <div class="relative mx-4 w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl dark:bg-zinc-900">
                        <h3 class="mb-2 text-xl font-bold">Interview Assignment Confirmation</h3>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                            Please confirm your availability for this interview
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Interviewer</label>
                                <input type="text" :value="acceptDeclineInterviewer?.name" disabled
                                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 dark:border-zinc-700" />
                            </div>
                            <div>
                                <label
                                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Stage</label>
                                <input type="text" :value="getStageLabel(acceptDeclineInterviewer?.interview_type)"
                                    disabled
                                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 dark:border-zinc-700" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Scheduled
                                    Date</label>
                                <input type="text" :value="formatDateTime(acceptDeclineInterviewer?.scheduled_date)"
                                    disabled
                                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 dark:border-zinc-700" />
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Decision</label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" v-model="acceptDeclineDecision" value="accept"
                                            class="rounded-full border-gray-300" />
                                        <span>Accept</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" v-model="acceptDeclineDecision" value="decline"
                                            class="rounded-full border-gray-300" />
                                        <span>Decline</span>
                                    </label>
                                </div>
                                <p v-if="acceptDeclineErrors.decision" class="mt-1 text-sm text-red-600">{{
                                    acceptDeclineErrors.decision }}</p>
                            </div>
                            <div v-if="acceptDeclineDecision === 'decline'">
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Reason
                                    for Declining</label>
                                <textarea v-model="acceptDeclineReason" rows="3"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-zinc-700"
                                    placeholder="Please provide reason for declining..."></textarea>
                                <p v-if="acceptDeclineErrors.reason" class="mt-1 text-sm text-red-600">{{
                                    acceptDeclineErrors.reason }}</p>
                            </div>
                            <div v-if="willTriggerApplicantAutoEmail"
                                class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-3 text-sm text-blue-800">
                                Accepting will send an automatic email to the applicant, informing them of their
                                scheduled assessment.
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button @click="showAcceptDeclineModal = false"
                                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 transition hover:bg-gray-50">
                                Cancel
                            </button>
                            <button @click="submitAcceptDecline" :disabled="acceptDeclineSubmitting"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700 disabled:opacity-50">
                                <span v-if="!acceptDeclineSubmitting">Submit</span>
                                <span v-else>Submitting...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Decline Reason Modal -->
                <div v-if="showDeclineReasonModal" class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeDeclineReasonModal"></div>
                    <div class="relative mx-4 w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl dark:bg-zinc-900">
                        <h3 class="mb-2 text-xl font-bold">Reason for Decline</h3>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                            Decline details for this interview assignment
                        </p>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Interviewer</label>
                                <input type="text" :value="selectedDeclinedInterview?.name || '-'" disabled
                                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 dark:border-zinc-700" />
                            </div>
                            <div>
                                <label
                                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Stage</label>
                                <input type="text" :value="getStageLabel(selectedDeclinedInterview?.interview_type)"
                                    disabled
                                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 dark:border-zinc-700" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Scheduled
                                    Date</label>
                                <input type="text" :value="formatDateTime(selectedDeclinedInterview?.scheduled_date)"
                                    disabled
                                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 dark:border-zinc-700" />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Decline
                                    Reason</label>
                                <div
                                    class="w-full rounded-lg border border-gray-300 bg-zinc-50 px-3 py-3 text-sm whitespace-pre-wrap dark:border-zinc-700 dark:bg-zinc-800">
                                    {{ selectedDeclinedInterview?.decline_reason || '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button @click="closeDeclineReasonModal"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 transition hover:bg-gray-50 dark:border-zinc-700 dark:hover:bg-zinc-800">
                                Close
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notification Modal -->
                <div v-if="showNotificationModal" class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showNotificationModal = false">
                    </div>
                    <div
                        class="relative mx-4 max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-8 shadow-2xl dark:bg-zinc-900">
                        <h3 class="mb-2 text-center text-xl font-bold">Send Email</h3>
                        <p class="mb-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            Only valid email actions for this application are shown.
                        </p>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Available
                                    Email Action</label>
                                <div v-if="availableNotificationOptions.length === 0"
                                    class="rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500 dark:border-zinc-700">
                                    No email actions are currently available for this application.
                                </div>
                                <div v-else class="space-y-3">
                                    <label v-for="option in availableNotificationOptions" :key="option.value"
                                        class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 hover:bg-gray-50 dark:border-zinc-700 dark:hover:bg-zinc-800">
                                        <input type="radio" :value="option.value" v-model="notification.type"
                                            class="mt-1" />
                                        <div>
                                            <div class="font-medium">{{ option.label }}</div>
                                            <div class="text-xs text-gray-500">{{ option.description }}</div>
                                        </div>
                                    </label>
                                </div>
                                <p v-if="notificationErrors.type" class="mt-2 text-sm text-red-600">{{
                                    notificationErrors.type }}</p>
                            </div>

                            <div>
                                <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Email
                                    Preview</label>
                                <div v-if="!notificationPreview"
                                    class="rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-500 dark:border-zinc-700">
                                    Select an available email action to preview recipients and message details.
                                </div>
                                <div v-else
                                    class="space-y-4 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
                                    <div>
                                        <div class="mb-1 text-xs tracking-wide text-gray-500 uppercase">Subject</div>
                                        <div class="text-sm font-medium">{{ notificationPreview.subject }}</div>
                                    </div>
                                    <div>
                                        <div class="mb-1 text-xs tracking-wide text-gray-500 uppercase">Recipients</div>
                                        <div class="space-y-2">
                                            <div v-for="(recipient, idx) in notificationPreview.recipients" :key="idx"
                                                class="rounded-lg border bg-white px-3 py-2 text-sm dark:bg-zinc-900">
                                                <div class="font-medium">{{ recipient.name || '-' }}</div>
                                                <div class="text-xs text-gray-500">{{ recipient.email || 'No email address' }}</div>
                                                <div class="mt-1 text-xs text-gray-400">{{ recipient.extra }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="mb-1 text-xs tracking-wide text-gray-500 uppercase">Message Summary
                                        </div>
                                        <div class="text-sm text-gray-700 dark:text-gray-300">{{
                                            notificationPreview.summary }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button @click="showNotificationModal = false"
                                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 transition hover:bg-gray-50">
                                Cancel
                            </button>
                            <button @click="sendNotification" :disabled="sendingNotification || !notification.type"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700 disabled:opacity-50">
                                <span v-if="!sendingNotification">Send</span>
                                <span v-else>Sending...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Interviewers Table -->
                <div
                    class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow dark:border-zinc-800 dark:bg-zinc-900">
                    <div
                        class="border-b border-zinc-200 bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 dark:border-zinc-700 dark:from-zinc-800 dark:to-zinc-800/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="flex items-center gap-2 text-lg font-bold">
                                    <Users class="h-5 w-5 text-blue-600" />
                                    INTERVIEWERS AND EXAM CONDUCTORS
                                </h2>
                                <p class="mt-1 text-xs text-gray-500">
                                    Manage interviewers and exam conductors assigned to this application
                                </p>
                            </div>

                            <div class="flex gap-2" v-if="canManageInterviewers">
                                <button @click="openBulkAddModal" class="btn-bulk-add">
                                    <Plus class="h-4 w-4" />
                                    Add
                                </button>
                                <button @click="openBulkEditScheduleModal" :disabled="selectedInterviewers.length === 0"
                                    :class="['btn-bulk-edit', selectedInterviewers.length === 0 && 'cursor-not-allowed opacity-50']">
                                    Edit Schedule
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-50 dark:bg-zinc-800">
                                <tr>
                                    <th class="w-10 px-4 py-3 text-left" v-if="canManageInterviewers">
                                        <input type="checkbox" v-model="selectAll" @change="toggleSelectAll"
                                            class="rounded border-gray-300"
                                            :disabled="editableInterviewIds.length === 0" />
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
                                <tr v-for="interview in interviews" :key="interview.id"
                                    class="border-t border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                    <td class="px-4 py-3" v-if="canManageInterviewers">
                                        <div class="flex items-center justify-center">
                                            <input v-if="!lockedInterviewStatuses.includes(Number(interview.status))"
                                                type="checkbox" v-model="selectedInterviewers" :value="interview.id"
                                                class="rounded border-gray-300" />
                                            <div v-else class="h-4 w-4"></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <User class="h-4 w-4 text-gray-400" />
                                            <span>{{ interview.name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ interview.role_label || '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            :class="['rounded-full px-2 py-1 text-xs', getStageBadgeClass(interview.interview_type)]">
                                            {{ getStageLabel(interview.interview_type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ formatDateTime(interview.scheduled_date) }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            :class="['inline-flex rounded-full px-2 py-1 text-xs font-semibold', getInterviewStatusBadgeClass(interview.status)]">
                                            {{ getInterviewStatusLabel(interview.status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <button v-if="canAcceptDecline(interview)"
                                                @click="openAcceptDeclineModal(interview)"
                                                class="inline-flex items-center justify-center rounded-md px-1 py-2 text-green-600 transition hover:bg-green-50 hover:text-green-800"
                                                title="Accept/Decline">
                                                <CheckCircle class="mr-1 h-4 w-4" /> Respond
                                            </button>
                                            <button v-if="canViewDeclineReason(interview)"
                                                @click="openDeclineReasonModal(interview)"
                                                class="font-sm inline-flex items-center justify-center rounded-md px-1 py-2 text-sm text-red-600 transition hover:bg-red-50 hover:text-red-800"
                                                title="View Reason for Decline">
                                                View Reason
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="interviews.length === 0">
                                    <td :colspan="canManageInterviewers ? 7 : 6"
                                        class="px-4 py-6 text-center text-gray-500">
                                        No interview assignments found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
    padding: 0.5rem 1rem;
    background-color: #2563eb;
    color: #fff;
    border-radius: 5px;
    font-size: 14px;
}

.btn-send {
    display: inline-block;
    width: auto;
    font-weight: 600;
    padding: 0.5rem 1rem;
    background-color: #7c3aed;
    color: #fff;
    border-radius: 5px;
    font-size: 14px;
}

.btn-edit {
    display: inline-block;
    width: auto;
    font-weight: 600;
    padding: 0.5rem 1rem;
    background-color: #6b7280;
    color: #fff;
    border-radius: 5px;
    font-size: 14px;
    text-decoration: none;
}

.btn-edit:hover {
    background-color: #4b5563;
}

.close-btn {
    background: transparent;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
}

.full-width-alert {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    width: auto;
    max-width: 500px;
}

.alert-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.alert-success-banner {
    background-color: #10b981;
    color: white;
}

.alert-error-banner {
    background-color: #ef4444;
    color: white;
}

.alert-body {
    margin-right: 1rem;
}

.tag-item {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.5rem;
    background: #e5e7eb;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.remove-tag {
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    cursor: pointer;
    color: #6b7280;
}

.remove-tag:hover {
    color: #374151;
}
</style>