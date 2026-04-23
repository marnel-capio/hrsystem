<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { Link, usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const page = usePage();

const props = defineProps<{
    application: any;
    sourceProjects?: Array<{ value: number; label: string }>;
    examVenues?: Record<number, string>;
    examStatuses?: Record<number, string>;
    interviewStatuses?: Record<number, string>;
    jobOfferStatuses?: Record<number, string>;
    userPermissions?: number;
    userId?: number;
    interviews?: Array<any>;
    flash?: { error?: string; success?: string };
}>();

// Options from props
const examVenuesList = ref<Array<{ value: number; label: string }>>([]);
const examStatusesList = ref<Array<{ value: number; label: string }>>([]);
const interviewStatusesList = ref<Array<{ value: number; label: string }>>([]);
const sourceProjectsList = ref<Array<{ value: number; label: string }>>([]);


// File handling
const resumeFile = ref<File | null>(null);
const pictureFile = ref<File | null>(null);
const resumePreview = ref<string | null>(null);
const picturePreview = ref<string | null>(null);

// Error and success messages
const errorMessage = computed(() => (page.props.flash as any)?.error || '');
const showError = ref(false);
const successMessage = computed(() => (page.props.flash as any)?.success || '');
const showSuccess = ref(false);

// Helper functions
const formatDateForInput = (dateString: string | null) => {
    if (!dateString) return '';
    return String(dateString).slice(0, 16).replace(' ', 'T');
};

const parseScore = (value: string | number | null | undefined): number => {
    if (value === '' || value === null || value === undefined) return 0;
    const num = Number(value);
    return Number.isNaN(num) ? 0 : num;
};

const getFileUrl = (filename: string | null) => {
    if (!filename) return null;

    // If it's already a full URL, return as is
    if (filename.startsWith('http://') || filename.startsWith('https://')) {
        return filename;
    }

    // Handle paths that already include the folder
    if (filename.startsWith('pictures/')) return `/storage/${filename}`;
    if (filename.startsWith('resumes/')) return `/storage/${filename}`;

    // For picture files without folder prefix, assume pictures folder
    if (filename.match(/\.(jpg|jpeg|png|gif|webp)$/i)) {
        return `/storage/pictures/${filename}`;
    }

    // For document files, assume resumes folder
    if (filename.match(/\.(pdf|doc|docx)$/i)) {
        return `/storage/resumes/${filename}`;
    }

    // Default fallback
    return `/storage/${filename}`;
};

const existingResumeUrl = computed(() => getFileUrl(props.application.upload_resume));
const existingPictureUrl = computed(() => getFileUrl(props.application.upload_pic));

const userPermissions = computed(() => props.userPermissions || 0);

// HR/Admin permissions
const canManageInterviewers = computed(() =>
    [1, 2, 3].includes(userPermissions.value),
);

// Check if current user has ANY approved interview assignment
const userApprovedInterviews = computed(() => {
    return props.interviews?.filter((i: any) =>
        i.interviewer_id === props.userId && i.interview_status === 2
    ) || [];
});

// Check if user is approved for Exam (interview_type = 1)
const isApprovedForExam = computed(() => {
    return userApprovedInterviews.value.some((i: any) => i.interview_type === 1);
});

// Check if user is approved for Initial Interview (interview_type = 2)
const isApprovedForInitial = computed(() => {
    return userApprovedInterviews.value.some((i: any) => i.interview_type === 2);
});

// Check if user is approved for Final Interview (interview_type = 3)
const isApprovedForFinal = computed(() => {
    return userApprovedInterviews.value.some((i: any) => i.interview_type === 3);
});

// Check if user has ANY approved interview (for general permissions)
const hasAnyApprovedInterview = computed(() => {
    return userApprovedInterviews.value.length > 0;
});

// Get current user's initial interview record
const currentUserInitialInterview = computed(() => {
    return props.interviews?.find((i: any) =>
        i.interview_type === 2 && i.interviewer_id === props.userId
    ) || null;
});

// Get current user's final interview record
const currentUserFinalInterview = computed(() => {
    return props.interviews?.find((i: any) =>
        i.interview_type === 3 && i.interviewer_id === props.userId
    ) || null;
});

// Paper screening status values
const PAPER_SCREENING = {
    PENDING: 1,
    DONE: 2,
    PASSED: 3,
    P2: 4,
    FAILED: 5,
};

const isPaperScreeningCompleted = computed(() => {
    const status = Number(props.application.paper_screening_status);
    return status === PAPER_SCREENING.DONE ||
        status === PAPER_SCREENING.PASSED ||
        status === PAPER_SCREENING.P2;
});

const isPaperScreeningFailed = computed(() => {
    return Number(props.application.paper_screening_status) === PAPER_SCREENING.FAILED;
});

const isEarlySectionsLocked = computed(() => {
    return isPaperScreeningCompleted.value || isPaperScreeningFailed.value;
});

// Stage result statuses
const isExamFailed = computed(() => {
    return Number(props.application.exam_application_status) === 5 ||
        Number(props.application.exam_result) === 3;
});

const isInitialInterviewFailed = computed(() => {
    return Number(props.application.initial_interview_application_status) === 5 ||
        Number(props.application.initial_interview_result) === 3;
});

const isFinalInterviewFailed = computed(() => {
    return Number(props.application.final_interview_application_status) === 5 ||
        Number(props.application.final_interview_result) === 3;
});

// Section locking based on progression rules
const isExamApplicable = computed(() => {
    if (isPaperScreeningFailed.value) return false;
    return true;
});

const isInitialInterviewApplicable = computed(() => {
    if (isPaperScreeningFailed.value) return false;
    return true;
});

const isFinalInterviewApplicable = computed(() => {
    if (isPaperScreeningFailed.value) return false;
    if (isExamFailed.value) return false;
    if (isInitialInterviewFailed.value) return false;
    return true;
});

const isJobOfferApplicable = computed(() => {
    if (isPaperScreeningFailed.value) return false;
    if (isFinalInterviewFailed.value) return false;
    if (isExamFailed.value) return false;
    if (isInitialInterviewFailed.value) return false;
    return true;
});

// Section edit permissions based on user role and interview type
const canEditExamSection = computed(() => {
    if (canManageInterviewers.value) return true;
    if (!isExamApplicable.value) return false;
    return isApprovedForExam.value;
});

const canEditInitialSection = computed(() => {
    if (canManageInterviewers.value) return true;
    if (!isInitialInterviewApplicable.value) return false;
    return isApprovedForInitial.value;
});

const canEditFinalSection = computed(() => {
    if (canManageInterviewers.value) return true;
    if (!isFinalInterviewApplicable.value) return false;
    return isApprovedForFinal.value;
});

const canEditJobOfferSection = computed(() => {
    if (canManageInterviewers.value) return true;
    if (!isJobOfferApplicable.value) return false;
    return false;
});

const canEditGeneralRemarks = computed(() => {
    if (canManageInterviewers.value) return true;
    return false;
});

// Computed ATPP Result
const computedAtppResult = computed(() => {
    const p1c = parseScore(form.exam_atpp_part1_correct);
    const p1w = parseScore(form.exam_atpp_part1_wrong);
    const p2c = parseScore(form.exam_atpp_part2_correct);
    const p2w = parseScore(form.exam_atpp_part2_wrong);
    const p3c = parseScore(form.exam_atpp_part3_correct);
    const p3w = parseScore(form.exam_atpp_part3_wrong);

    const hasAny =
        form.exam_atpp_part1_correct !== '' ||
        form.exam_atpp_part1_wrong !== '' ||
        form.exam_atpp_part2_correct !== '' ||
        form.exam_atpp_part2_wrong !== '' ||
        form.exam_atpp_part3_correct !== '' ||
        form.exam_atpp_part3_wrong !== '';

    if (!hasAny) return '';

    const totalCorrect = p1c + p2c + p3c;
    const totalWrong = (p1w + p2w + p3w) / 4;
    const finalScore = totalCorrect - totalWrong;

    return finalScore.toFixed(2);
});

// Calculate average score from all interviewer evaluations for Initial Interview
const computedInitialAverageScore = computed(() => {
    const initialInterviews = props.interviews?.filter((i: any) => i.interview_type === 2) || [];
    const scores = initialInterviews
        .map((i: any) => i.evaluation_score)
        .filter((score: any) => score !== null && score !== undefined && score !== '')
        .map((score: any) => Number(score))
        .filter((score: number) => !isNaN(score) && score >= 1 && score <= 5);

    if (scores.length === 0) return '';

    const sum = scores.reduce((acc: number, score: number) => acc + score, 0);
    return (sum / scores.length).toFixed(2);
});

// Calculate average score from all interviewer evaluations for Final Interview
const computedFinalAverageScore = computed(() => {
    const finalInterviews = props.interviews?.filter((i: any) => i.interview_type === 3) || [];
    const scores = finalInterviews
        .map((i: any) => i.evaluation_score)
        .filter((score: any) => score !== null && score !== undefined && score !== '')
        .map((score: any) => Number(score))
        .filter((score: number) => !isNaN(score) && score >= 1 && score <= 5);

    if (scores.length === 0) return '';

    const sum = scores.reduce((acc: number, score: number) => acc + score, 0);
    return (sum / scores.length).toFixed(2);
});

// Get number of interviewers who submitted scores
const initialInterviewerCount = computed(() => {
    const initialInterviews = props.interviews?.filter((i: any) => i.interview_type === 2) || [];
    return initialInterviews.filter((i: any) =>
        i.evaluation_score !== null && i.evaluation_score !== undefined && i.evaluation_score !== ''
    ).length;
});

const finalInterviewerCount = computed(() => {
    const finalInterviews = props.interviews?.filter((i: any) => i.interview_type === 3) || [];
    return finalInterviews.filter((i: any) =>
        i.evaluation_score !== null && i.evaluation_score !== undefined && i.evaluation_score !== ''
    ).length;
});

// Exam criteria
const examCriteriaDisplay = computed(() => ({
    passed: { atpp: 60, tech_exam: 30 },
    p2: { atpp: 55, tech_exam: 20 }
}));

// Selected applicant display
const selectedApplicantLabel = computed(() => {
    const a = props.application.applicant;
    if (!a) return 'N/A';
    return `${a.last_name}, ${a.first_name} ${a.middle_name || ''}`.trim();
});

// Form
const form = useForm({
    resource_schedule_id: props.application.resource_schedule_id || '',
    position: props.application.position || '',

    upload_resume: props.application.upload_resume || '',
    upload_pic: props.application.upload_pic || '',

    answer_q1: props.application.answer_q1 !== null && props.application.answer_q1 !== undefined
        ? Number(props.application.answer_q1)
        : null,
    answer_q2: props.application.answer_q2 !== null && props.application.answer_q2 !== undefined
        ? Number(props.application.answer_q2)
        : null,
    answer_q3: props.application.answer_q3 !== null && props.application.answer_q3 !== undefined
        ? Number(props.application.answer_q3)
        : null,
    answer_q4: props.application.answer_q4 !== null && props.application.answer_q4 !== undefined
        ? Number(props.application.answer_q4)
        : null,

    availability_date: props.application.availability_date || '',
    desired_salary_range: props.application.desired_salary_range || '',
    work_preference: props.application.work_preference || '',

    basic_pay: props.application.basic_pay || '',
    bonuses: props.application.bonuses || '',
    hmo: props.application.hmo || '',
    leaves: props.application.leaves || '',
    allowances: props.application.allowances || '',
    other_benefits: props.application.other_benefits || '',
    targeted_company: props.application.targeted_company || '',
    industry_experience: props.application.industry_experience || '',

    exam_plan_date: formatDateForInput(props.application.exam_plan_date),
    exam_actual_date: formatDateForInput(props.application.exam_actual_date),
    exam_venue: props.application.exam_venue || '',
    exam_atpp_part1_correct: props.application.exam_atpp_part1_correct || '',
    exam_atpp_part1_wrong: props.application.exam_atpp_part1_wrong || '',
    exam_atpp_part2_correct: props.application.exam_atpp_part2_correct || '',
    exam_atpp_part2_wrong: props.application.exam_atpp_part2_wrong || '',
    exam_atpp_part3_correct: props.application.exam_atpp_part3_correct || '',
    exam_atpp_part3_wrong: props.application.exam_atpp_part3_wrong || '',
    exam_atpp_result: props.application.exam_atpp_result || '',
    exam_tech_result: props.application.exam_tech_result || '',
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
    final_interview_final: props.application.final_interview_final || '',
    final_interview_result: props.application.final_interview_result || '',
    final_interview_application_status: props.application.final_interview_application_status || '',
    final_interview_remarks: props.application.final_interview_remarks || '',

    job_offer_schedule: formatDateForInput(props.application.job_offer_schedule),
    job_offer_status: props.application.job_offer_status || '',
    job_offer_remarks: props.application.job_offer_remarks || '',

    remarks: props.application.remarks || '',

    initial_interview_id: null as number | null,
    initial_evaluation_score: '' as string | number,
    initial_evaluation_result: '' as string | number,
    initial_evaluation_remarks: '',

    final_interview_id: null as number | null,
    final_evaluation_score: '' as string | number,
    final_evaluation_result: '' as string | number,
    final_evaluation_remarks: '',
});

const isExamStatusManuallySet = ref(
    !!props.application.exam_application_status &&
    props.application.exam_application_status !== ''
);
const isInitialStatusManuallySet = ref(
    !!props.application.initial_interview_application_status &&
    props.application.initial_interview_application_status !== ''
);

const isFinalStatusManuallySet = ref(
    !!props.application.final_interview_application_status &&
    props.application.final_interview_application_status !== ''
);


// Track previous values to detect manual changes
const previousExamStatus = ref<string | number>(props.application.exam_application_status || '');
const previousInitialStatus = ref<string | number>(props.application.initial_interview_application_status || '');
const previousFinalStatus = ref<string | number>(props.application.final_interview_application_status || '');

if (currentUserInitialInterview.value) {
    form.initial_interview_id = currentUserInitialInterview.value.id;
    form.initial_evaluation_score = currentUserInitialInterview.value.evaluation_score || '';
    form.initial_evaluation_result = currentUserInitialInterview.value.evaluation_results || '';
    form.initial_evaluation_remarks = currentUserInitialInterview.value.evaluation_remarks || '';
}

if (currentUserFinalInterview.value) {
    form.final_interview_id = currentUserFinalInterview.value.id;
    form.final_evaluation_score = currentUserFinalInterview.value.evaluation_score || '';
    form.final_evaluation_result = currentUserFinalInterview.value.evaluation_results || '';
    form.final_evaluation_remarks = currentUserFinalInterview.value.evaluation_remarks || '';
}

// Exam result label
const examResultLabel = computed(() => {
    const results: Record<number, string> = { 1: 'Pending', 2: 'Passed', 3: 'Failed' };
    return results[Number(form.exam_result)] || 'Pending';
});

// Evaluation result label helper
const getEvaluationResultLabel = (result: number | null) => {
    const results: Record<number, string> = { 1: 'Pending', 2: 'Passed', 3: 'Failed' };
    if (!result) return '-';
    return results[result] || '-';
};

// Venue label helper
const getVenueLabel = (venue: number | string | null) => {
    if (!venue) return '-';
    const found = examVenuesList.value.find(v => v.value === Number(venue));
    return found?.label || '-';
};

// File upload handlers
const handleResumeUpload = (event: Event) => {
    if (isEarlySectionsLocked.value) return;

    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        if (!allowedTypes.includes(file.type)) {
            form.setError('upload_resume', 'Please upload a PDF or Word document');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            form.setError('upload_resume', 'File size must be less than 5MB');
            return;
        }

        resumeFile.value = file;
        form.upload_resume = file.name;
        if (resumePreview.value) URL.revokeObjectURL(resumePreview.value);
        if (file.type === 'application/pdf') {
            resumePreview.value = URL.createObjectURL(file);
        }
        form.clearErrors('upload_resume');
    }
};

const handlePictureUpload = (event: Event) => {
    if (isEarlySectionsLocked.value) return;

    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!allowedTypes.includes(file.type)) {
            form.setError('upload_pic', 'Please upload a JPG or PNG image');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            form.setError('upload_pic', 'File size must be less than 2MB');
            return;
        }

        pictureFile.value = file;
        form.upload_pic = file.name;
        if (picturePreview.value) URL.revokeObjectURL(picturePreview.value);
        picturePreview.value = URL.createObjectURL(file);
        form.clearErrors('upload_pic');
    }
};

const removeFile = (type: 'resume' | 'picture') => {
    if (isEarlySectionsLocked.value) return;

    if (type === 'resume') {
        resumeFile.value = null;
        form.upload_resume = '';
        if (resumePreview.value) {
            URL.revokeObjectURL(resumePreview.value);
            resumePreview.value = null;
        }
    } else {
        pictureFile.value = null;
        form.upload_pic = '';
        if (picturePreview.value) {
            URL.revokeObjectURL(picturePreview.value);
            picturePreview.value = null;
        }
    }
};

// Score clamping
function clampScore(obj: any, field: string, max: number) {
    let value = Number(obj[field] || 0);
    if (Number.isNaN(value)) value = 0;
    if (value < 0) value = 0;
    if (value > max) value = max;
    obj[field] = value;
}

function clampAtppPair(obj: any, correctField: string, wrongField: string, max: number) {
    let correct = Number(obj[correctField] || 0);
    let wrong = Number(obj[wrongField] || 0);
    if (Number.isNaN(correct)) correct = 0;
    if (Number.isNaN(wrong)) wrong = 0;
    if (correct < 0) correct = 0;
    if (wrong < 0) wrong = 0;
    if (correct > max) correct = max;
    if (wrong > max) wrong = max;
    if (correct + wrong > max) {
        const excess = correct + wrong - max;
        if ((document.activeElement as HTMLInputElement | null)?.name === correctField) {
            wrong = Math.max(0, wrong - excess);
        } else {
            correct = Math.max(0, correct - excess);
        }
    }
    obj[correctField] = correct;
    obj[wrongField] = wrong;
}

// Date minimums
const nowDateTime = (): string => {
    const now = new Date();
    return formatDateTimeLocal(now);
};

function formatDateTimeLocal(value: Date) {
    const year = value.getFullYear();
    const month = String(value.getMonth() + 1).padStart(2, '0');
    const day = String(value.getDate()).padStart(2, '0');
    const hours = String(value.getHours()).padStart(2, '0');
    const minutes = String(value.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

const formatDateTime = (dateString: string | null) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return String(dateString);
    return date.toLocaleString('en-US', {
        month: 'long', day: 'numeric', year: 'numeric',
        hour: 'numeric', minute: 'numeric', hour12: true
    });
};

const examPlanMin = computed(() => isExamPlanDateLocked.value ? undefined : nowDateTime());
const examActualMin = computed(() => form.exam_plan_date || nowDateTime());
const initialInterviewPlanMin = computed(() => isInitialInterviewPlanDateLocked.value ? undefined : nowDateTime());
const initialInterviewActualMin = computed(() => form.initial_interview_plan_date || nowDateTime());
const finalInterviewMin = computed(() => nowDateTime());
const jobOfferScheduleMin = computed(() => nowDateTime());

// Submit
const normalizeDateTimeForSubmit = (value: unknown) => {
    if (typeof value !== 'string' || !value) return value;
    if (/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/.test(value)) {
        return value.replace('T', ' ') + ':00';
    }
    return value;
};

function submit() {
    form.clearErrors();

    form.transform((data) => {
        const formData = new FormData();

        Object.keys(data).forEach((key) => {
            const value = data[key as keyof typeof data];

            if (key === 'upload_resume') {
                if (resumeFile.value) formData.append('upload_resume', resumeFile.value);
                return;
            }
            if (key === 'upload_pic') {
                if (pictureFile.value) formData.append('upload_pic', pictureFile.value);
                return;
            }

            if (value !== null && value !== undefined && value !== '') {
                const normalizedValue = normalizeDateTimeForSubmit(value);
                formData.append(key, String(normalizedValue));
            }
        });

        formData.append('_method', 'PUT');
        return formData as any;
    });

    form.post(`/intermediate/applications/${props.application.id}`, {
        preserveState: true,
        preserveScroll: true,
    });
}

// Watch computed ATPP result
watch(computedAtppResult, (value) => {
    form.exam_atpp_result = value;
}, { immediate: true });

// Auto exam status - only if not manually overridden
watch([() => form.exam_atpp_result, () => form.exam_tech_result], ([atpp, tech]) => {
    // Skip auto-calculation if manually set
    if (isExamStatusManuallySet.value) return;

    const hasAllScores = !!atpp && !!tech;
    if (!hasAllScores) {
        form.exam_application_status = '';
        return;
    }
    const atppNum = Number(atpp);
    const techNum = Number(tech);
    if (Number.isNaN(atppNum) || Number.isNaN(techNum)) {
        form.exam_application_status = '';
        return;
    }

    if (atppNum >= 60 && techNum >= 30) {
        form.exam_application_status = '3';
    } else if (atppNum >= 55 && techNum >= 20) {
        form.exam_application_status = '4';
    } else {
        form.exam_application_status = '5';
    }
}, { immediate: true });

// Detect manual changes to Exam Application Status
watch(() => form.exam_application_status, (newVal, oldVal) => {
    // Skip if this is the initial load
    if (oldVal === undefined) return;

    // Mark as manually set when user changes the dropdown
    if (newVal !== previousExamStatus.value) {
        isExamStatusManuallySet.value = true;
        previousExamStatus.value = newVal;
    }
});

watch(() => form.exam_application_status, (status) => {
    if (isExamStatusManuallySet.value) return;

    const statusNum = Number(status);
    if (statusNum === 3 || statusNum === 4) {
        form.exam_result = 2;
    } else if (statusNum === 5) {
        form.exam_result = 3;
    } else {
        form.exam_result = 1;
    }
}, { immediate: true });

// Reset manual override when scores are cleared
watch([() => form.exam_atpp_result, () => form.exam_tech_result], ([atpp, tech]) => {
    // If both scores are cleared, reset manual override
    if (!atpp && !tech) {
        isExamStatusManuallySet.value = false;
    }
});

watch(() => form.initial_interview_final, (score) => {
    // If score is cleared, reset manual override
    if (!score) {
        isInitialStatusManuallySet.value = false;
    }
});

watch(() => form.final_interview_final, (score) => {
    // If score is cleared, reset manual override
    if (!score) {
        isFinalStatusManuallySet.value = false;
    }
});

// Interview score mapping
function mapInterviewScore(score: any) {
    const num = Number(score);

    if (!score || Number.isNaN(num) || num === 0) {
        return { result: 1, status: '1' };
    }

    if (num >= 1.00 && num <= 2.00) {
        return { result: 2, status: '3' };
    }

    if (num >= 2.01 && num <= 3.00) {
        return { result: 2, status: '4' };
    }

    if (num >= 3.01 && num <= 5.00) {
        return { result: 3, status: '5' };
    }

    return { result: 1, status: '1' };
}

// Auto-correlate Initial Interview - only if not manually overridden
watch(() => form.initial_interview_final, (score) => {
    if (isInitialStatusManuallySet.value) return;

    const { result, status } = mapInterviewScore(score);
    form.initial_interview_result = result;
    form.initial_interview_application_status = status;
}, { immediate: true });

// Auto-correlate Final Interview - only if not manually overridden
watch(() => form.final_interview_final, (score) => {
    if (isFinalStatusManuallySet.value) return;

    const { result, status } = mapInterviewScore(score);
    form.final_interview_result = result;
    form.final_interview_application_status = status;
}, { immediate: true });

// Watch for changes in interviews prop and update form
watch(() => props.interviews, () => {
    if (computedInitialAverageScore.value) {
        form.initial_interview_final = computedInitialAverageScore.value;
    }
    if (computedFinalAverageScore.value) {
        form.final_interview_final = computedFinalAverageScore.value;
    }
}, { deep: true });

watch(computedInitialAverageScore, (value) => {
    if (value) {
        form.initial_interview_final = value;
    }
});

watch(computedFinalAverageScore, (value) => {
    if (value) {
        form.final_interview_final = value;
    }
});

// Validate interview score
function validateInterviewScore(field: string, value: string | number) {
    if (value === '' || value === null || value === undefined) {
        form.clearErrors(field);
        return;
    }
    const num = Number(value);
    if (Number.isNaN(num)) {
        form.setError(field, 'Score must be a valid number.');
        return;
    }
    if (num < 1.00 || num > 5.00) {
        form.setError(field, 'Score must be between 1.00 and 5.00.');
        return;
    }
    form.clearErrors(field);
}

// Clamp interview score
function clampInterviewScore(obj: any, field: string) {
    let value = obj[field];
    if (value === '' || value === null || value === undefined) return;
    let num = Number(value);
    if (Number.isNaN(num)) {
        obj[field] = '';
        return;
    }
    if (num < 1.00) num = 1.00;
    if (num > 5.00) num = 5.00;
    obj[field] = Math.round(num * 100) / 100;
}

// Detect manual changes to Initial Interview Application Status
watch(() => form.initial_interview_application_status, (newVal, oldVal) => {
    if (oldVal === undefined) return;

    if (newVal !== previousInitialStatus.value) {
        isInitialStatusManuallySet.value = true;
        previousInitialStatus.value = newVal;
    }
});

// Detect manual changes to Final Interview Application Status
watch(() => form.final_interview_application_status, (newVal, oldVal) => {
    if (oldVal === undefined) return;

    if (newVal !== previousFinalStatus.value) {
        isFinalStatusManuallySet.value = true;
        previousFinalStatus.value = newVal;
    }
});



// Validate scores
watch(() => form.initial_interview_final, (value) => {
    validateInterviewScore('initial_interview_final', value);
});
watch(() => form.final_interview_final, (value) => {
    validateInterviewScore('final_interview_final', value);
});
watch(() => form.initial_evaluation_score, (value) => {
    validateInterviewScore('initial_evaluation_score', value);
});
watch(() => form.final_evaluation_score, (value) => {
    validateInterviewScore('final_evaluation_score', value);
});

// Initialize options
onMounted(() => {
    if (props.examVenues) {
        examVenuesList.value = Object.entries(props.examVenues).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }));
    } else {
        examVenuesList.value = [
            { value: 1, label: 'Online' },
            { value: 2, label: 'Face to Face' }
        ];
    }

    if (props.examStatuses) {
        examStatusesList.value = Object.entries(props.examStatuses).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }));
    }

    if (props.interviewStatuses) {
        interviewStatusesList.value = Object.entries(props.interviewStatuses).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }));
    }

    if (props.sourceProjects) {
        sourceProjectsList.value = props.sourceProjects;
    }
});

// Watch for flash messages
watch(errorMessage, (val) => {
    if (val) {
        showError.value = true;
        setTimeout(() => (showError.value = false), 5000);
    }
});

watch(successMessage, (val) => {
    if (val) {
        showSuccess.value = true;
        setTimeout(() => (showSuccess.value = false), 5000);
    }
});

// Salary input filters
const filterSalaryInput = (e: Event) => {
    if (isEarlySectionsLocked.value) return;
    const target = e.target as HTMLInputElement;
    target.value = target.value.replace(/[^0-9,\-]/g, '');
    form.desired_salary_range = target.value;
};

const filterBasicPay = (e: Event) => {
    if (isEarlySectionsLocked.value) return;
    const target = e.target as HTMLInputElement;
    target.value = target.value.replace(/[^0-9,]/g, '');
    form.basic_pay = target.value;
};

// Check if exam plan date is locked (has a value)
const isExamPlanDateLocked = computed(() => {
    return !!props.application.exam_plan_date;
});

// Check if initial interview plan date is locked (has a value)
const isInitialInterviewPlanDateLocked = computed(() => {
    return !!props.application.initial_interview_plan_date;
});



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
                <h2 class="page-title">Edit Intermediate Application</h2>
            </div>

            <div class="form-wrapper">
                <div class="form-card">
                    <form @submit.prevent="submit">
                        <!-- Basic Information -->
                        <div class="form-section" :class="{ 'disabled-section': isEarlySectionsLocked }">
                            <div class="section-header">
                                <h3>Basic Information</h3>
                                <div v-if="isPaperScreeningCompleted" class="section-badge">
                                    <span class="badge badge-locked">Locked - Paper Screening Completed</span>
                                </div>
                                <div v-if="isPaperScreeningFailed" class="section-badge">
                                    <span class="badge badge-failed">Locked - Application Failed</span>
                                </div>
                            </div>
                            <div class="form-grid grid-2 mb-6">
                                <div class="form-field">
                                    <label class="field-label-required required">Intermediate Applicant</label>
                                    <input type="text" :value="selectedApplicantLabel" class="form-input" disabled />
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Request Requisition Forms</label>
                                    <select v-model="form.resource_schedule_id" class="form-select"
                                        :disabled="isEarlySectionsLocked">
                                        <option value="">Select Requisition Form</option>
                                        <option v-for="project in sourceProjectsList" :key="project.value"
                                            :value="project.value">
                                            {{ project.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Documents -->
                        <div class="form-section" :class="{ 'disabled-section': isEarlySectionsLocked }">
                            <div class="section-header">
                                <h3>Upload Documents</h3>
                                <div v-if="isPaperScreeningCompleted" class="section-badge">
                                    <span class="badge badge-locked">Locked - Paper Screening Completed</span>
                                </div>
                                <div v-if="isPaperScreeningFailed" class="section-badge">
                                    <span class="badge badge-failed">Locked - Application Failed</span>
                                </div>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Upload Resume</label>
                                    <input type="file" @change="handleResumeUpload" accept=".pdf,.doc,.docx"
                                        class="file-input-btn w-full"
                                        :disabled="form.processing || isEarlySectionsLocked" />
                                    <div v-if="form.upload_resume" class="file-info">
                                        <span class="file-name">{{ form.upload_resume }}</span>
                                        <button type="button" @click="removeFile('resume')" class="remove-file"
                                            :disabled="isEarlySectionsLocked">×</button>
                                    </div>
                                    <span v-if="form.errors.upload_resume" class="error-message">{{
                                        form.errors.upload_resume }}</span>
                                    <div v-if="resumePreview" class="file-preview">
                                        <a :href="resumePreview" target="_blank" class="preview-link">Preview PDF</a>
                                    </div>
                                    <div v-else-if="existingResumeUrl && !resumeFile" class="file-preview">
                                        <a :href="existingResumeUrl" target="_blank" class="preview-link">View current
                                            resume</a>
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Upload Picture</label>
                                    <input type="file" @change="handlePictureUpload"
                                        accept="image/jpeg,image/png,image/jpg" class="file-input-btn w-full"
                                        :disabled="form.processing || isEarlySectionsLocked" />
                                    <div v-if="form.upload_pic" class="file-info">
                                        <span class="file-name">{{ form.upload_pic }}</span>
                                        <button type="button" @click="removeFile('picture')" class="remove-file"
                                            :disabled="isEarlySectionsLocked">×</button>
                                    </div>
                                    <span v-if="form.errors.upload_pic" class="error-message">{{ form.errors.upload_pic
                                        }}</span>
                                    <div v-if="picturePreview" class="picture-preview">
                                        <img :src="picturePreview" alt="Picture preview" class="preview-image" />
                                    </div>
                                    <div v-else-if="existingPictureUrl && !pictureFile" class="picture-preview">
                                        <img :src="existingPictureUrl" alt="Current picture" class="preview-image" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Screening Questions & Preferences -->
                        <div class="form-section" :class="{ 'disabled-section': isEarlySectionsLocked }">
                            <div class="section-header">
                                <h3>Screening Questions & Preferences</h3>
                                <div v-if="isPaperScreeningCompleted" class="section-badge">
                                    <span class="badge badge-locked">Locked - Paper Screening Completed</span>
                                </div>
                                <div v-if="isPaperScreeningFailed" class="section-badge">
                                    <span class="badge badge-failed">Locked - Application Failed</span>
                                </div>
                            </div>

                            <div class="position-preferences-grid mb-6">
                                <div class="form-field">
                                    <label class="field-label position-label">Position</label>
                                    <input type="text" v-model="form.position" class="form-input position-input"
                                        placeholder="Enter aspiring position" :disabled="isEarlySectionsLocked" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 position-label">When are you available to
                                        start?</label>
                                    <input type="text" v-model="form.availability_date"
                                        class="form-input position-input" placeholder="ex: 2024-12-01 or ASAP"
                                        :disabled="isEarlySectionsLocked" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 position-label">Desired Salary</label>
                                    <input type="text" v-model="form.desired_salary_range"
                                        class="form-input position-input" placeholder="ex: 50,000 - 70,000"
                                        @input="filterSalaryInput" :disabled="isEarlySectionsLocked" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 position-label">Work Preference</label>
                                    <input type="text" v-model="form.work_preference" class="form-input position-input"
                                        placeholder="ex: Regular/Part-time" :disabled="isEarlySectionsLocked" />
                                </div>
                            </div>

                            <div class="form-grid grid-2 mb-8 gap-4">
                                <div class="form-field">
                                    <label class="field-label text-sm">1. Have you ever filed an application in AWS,
                                        Inc. before?</label>
                                    <select v-model.number="form.answer_q1" class="form-select"
                                        :disabled="isEarlySectionsLocked">
                                        <option :value="null">Select</option>
                                        <option :value="1">Yes</option>
                                        <option :value="0">No</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label text-sm">2. Do any of your friends or relatives, other
                                        than a spouse, work in AWS?</label>
                                    <select v-model.number="form.answer_q2" class="form-select"
                                        :disabled="isEarlySectionsLocked">
                                        <option :value="null">Select</option>
                                        <option :value="1">Yes</option>
                                        <option :value="0">No</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label text-sm">3. Have you worked in AWS before?</label>
                                    <select v-model.number="form.answer_q3" class="form-select"
                                        :disabled="isEarlySectionsLocked">
                                        <option :value="null">Select</option>
                                        <option :value="1">Yes</option>
                                        <option :value="0">No</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label class="field-label text-sm">4. Will you travel if the job requires
                                        it?</label>
                                    <select v-model.number="form.answer_q4" class="form-select"
                                        :disabled="isEarlySectionsLocked">
                                        <option :value="null">Select</option>
                                        <option :value="1">Yes</option>
                                        <option :value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="compensation-grid">
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Basic Pay</label>
                                    <input type="text" v-model="form.basic_pay" class="form-input compensation-input"
                                        placeholder="ex: 50,000" @input="filterBasicPay"
                                        :disabled="isEarlySectionsLocked" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Bonuses</label>
                                    <input type="text" v-model="form.bonuses" class="form-input compensation-input"
                                        placeholder="ex: 13-month bonus" :disabled="isEarlySectionsLocked" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">HMO</label>
                                    <input type="text" v-model="form.hmo" class="form-input compensation-input"
                                        placeholder="ex: Healthcare" :disabled="isEarlySectionsLocked" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Leaves</label>
                                    <input type="text" v-model="form.leaves" class="form-input compensation-input"
                                        placeholder="ex: 15 VL" :disabled="isEarlySectionsLocked" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Allowance</label>
                                    <input type="text" v-model="form.allowances" class="form-input compensation-input"
                                        placeholder="ex: Transportation - 5000" :disabled="isEarlySectionsLocked" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Other Benefits</label>
                                    <input type="text" v-model="form.other_benefits"
                                        class="form-input compensation-input" placeholder="ex: Wi-fi"
                                        :disabled="isEarlySectionsLocked" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Target Company</label>
                                    <input type="text" v-model="form.targeted_company"
                                        class="form-input compensation-input" placeholder="ex: AWS"
                                        :disabled="isEarlySectionsLocked" />
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500 compensation-label">Industry
                                        Experience</label>
                                    <input type="text" v-model="form.industry_experience"
                                        class="form-input compensation-input" placeholder="ex: 2 years"
                                        :disabled="isEarlySectionsLocked" />
                                </div>
                            </div>
                        </div>

                        <!-- Exam Section -->
                        <div class="form-section" :class="{ 'disabled-section': !canEditExamSection }">
                            <div class="text-gray-500 field-label pb-6">
                                (You may leave the following fields empty if the applicant is not yet finished with the
                                screening process.)
                            </div>
                            <div class="section-header">
                                <h3>Exam Details</h3>
                                <div v-if="!isExamApplicable" class="section-badge">
                                    <span class="badge badge-failed">Not Applicable - Application Failed</span>
                                </div>
                                <div v-else-if="!canEditExamSection && hasAnyApprovedInterview" class="section-badge">
                                    <span class="badge badge-locked">You are not assigned to this stage</span>
                                </div>
                            </div>

                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Exam Plan Date</label>
                                    <input type="datetime-local" v-model="form.exam_plan_date" class="form-input"
                                        :min="nowDateTime()" :disabled="!canEditExamSection || isExamPlanDateLocked" />
                                    <small v-if="isExamPlanDateLocked" class="helper-text text-amber-600">
                                        ⚠️ Plan date cannot be changed once set
                                    </small>
                                    <span v-if="form.errors.exam_plan_date" class="error-message">{{
                                        form.errors.exam_plan_date }}</span>
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Exam Actual Date</label>
                                    <input type="datetime-local" v-model="form.exam_actual_date" class="form-input"
                                        :disabled="!form.exam_plan_date || !canEditExamSection" :min="examActualMin" />
                                    <small class="helper-text">Cannot select past dates</small>
                                    <span v-if="form.errors.exam_actual_date" class="error-message">{{
                                        form.errors.exam_actual_date }}</span>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="field-label !text-gray-500">Exam Venue</label>
                                <select v-model="form.exam_venue" class="form-select" :disabled="!canEditExamSection">
                                    <option value="">Select Venue</option>
                                    <option v-for="venue in examVenuesList" :key="venue.value" :value="venue.value">
                                        {{ venue.label }}
                                    </option>
                                </select>
                                <span v-if="form.errors.exam_venue" class="error-message">{{ form.errors.exam_venue
                                    }}</span>
                            </div>

                            <div class="exam-section-layout">
                                <div class="exam-form-column">
                                    <div class="atpp-stack">
                                        <div class="atpp-card">
                                            <div class="atpp-card-title">ATPP Part I (Sequence / Pattern Analysis)</div>
                                            <div class="form-grid grid-2">
                                                <div class="form-field">
                                                    <label class="field-label">Correct</label>
                                                    <input type="number" step="1" min="0" max="40"
                                                        v-model="form.exam_atpp_part1_correct" placeholder="0"
                                                        class="form-input" :disabled="!canEditExamSection"
                                                        @input="clampAtppPair(form, 'exam_atpp_part1_correct', 'exam_atpp_part1_wrong', 40)" />
                                                    <span v-if="form.errors.exam_atpp_part1_correct"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part1_correct }}
                                                    </span>
                                                </div>
                                                <div class="form-field">
                                                    <label class="field-label">Wrong</label>
                                                    <input type="number" step="1" min="0" max="40"
                                                        v-model="form.exam_atpp_part1_wrong" placeholder="0"
                                                        class="form-input" :disabled="!canEditExamSection"
                                                        @input="clampAtppPair(form, 'exam_atpp_part1_correct', 'exam_atpp_part1_wrong', 40)" />
                                                    <span v-if="form.errors.exam_atpp_part1_wrong"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part1_wrong }}
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
                                                        class="form-input" :disabled="!canEditExamSection"
                                                        @input="clampAtppPair(form, 'exam_atpp_part2_correct', 'exam_atpp_part2_wrong', 30)" />
                                                    <span v-if="form.errors.exam_atpp_part2_correct"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part2_correct }}
                                                    </span>
                                                </div>
                                                <div class="form-field">
                                                    <label class="field-label">Wrong</label>
                                                    <input type="number" step="1" min="0" max="30"
                                                        v-model="form.exam_atpp_part2_wrong" placeholder="0"
                                                        class="form-input" :disabled="!canEditExamSection"
                                                        @input="clampAtppPair(form, 'exam_atpp_part2_correct', 'exam_atpp_part2_wrong', 30)" />
                                                    <span v-if="form.errors.exam_atpp_part2_wrong"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part2_wrong }}
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
                                                        class="form-input" :disabled="!canEditExamSection"
                                                        @input="clampAtppPair(form, 'exam_atpp_part3_correct', 'exam_atpp_part3_wrong', 25)" />
                                                    <span v-if="form.errors.exam_atpp_part3_correct"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part3_correct }}
                                                    </span>
                                                </div>
                                                <div class="form-field">
                                                    <label class="field-label">Wrong</label>
                                                    <input type="number" step="1" min="0" max="25"
                                                        v-model="form.exam_atpp_part3_wrong" placeholder="0"
                                                        class="form-input" :disabled="!canEditExamSection"
                                                        @input="clampAtppPair(form, 'exam_atpp_part3_correct', 'exam_atpp_part3_wrong', 25)" />
                                                    <span v-if="form.errors.exam_atpp_part3_wrong"
                                                        class="error-message">
                                                        {{ form.errors.exam_atpp_part3_wrong }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="exam-criteria-column">
                                    <div class="criteria-panel">
                                        <template v-if="examCriteriaDisplay">
                                            <div class="criteria-panel-header">
                                                <div class="criteria-panel-title">Exam Criteria</div>
                                            </div>
                                            <div class="criteria-rule passed">
                                                <div class="criteria-rule-title">Passed (Priority 1)</div>
                                                <ul class="criteria-list">
                                                    <li>ATPP: ≥ {{ examCriteriaDisplay.passed.atpp }}</li>
                                                    <li>Technical: ≥ {{ examCriteriaDisplay.passed.tech_exam }}</li>
                                                </ul>
                                            </div>
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
                                    <input type="number" step="0.01" v-model="form.exam_atpp_result" placeholder="0.00"
                                        class="form-input" readonly />
                                    <small class="helper-text">ATPP Final Result = Total Correct - (Total Wrong /
                                        4)</small>
                                </div>
                                <div class="form-field">
                                    <label class="field-label !text-gray-500">Technical Exam Result</label>
                                    <input type="number" step="0.01" min="0" max="80"
                                        @input="clampScore(form, 'exam_tech_result', 80)"
                                        v-model="form.exam_tech_result" placeholder="0.00" class="form-input"
                                        :disabled="!canEditExamSection" />
                                    <span v-if="form.errors.exam_tech_result" class="error-message">{{
                                        form.errors.exam_tech_result }}</span>
                                </div>
                            </div>

                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label pt-5 !text-gray-500">Exam Result</label>
                                    <input type="text" class="form-input" :value="examResultLabel || 'Pending'"
                                        readonly />
                                </div>
                                <div class="form-field">
                                    <label class="field-label pt-5 !text-gray-500">Exam Application Status</label>
                                    <select v-model="form.exam_application_status" class="form-select"
                                        :disabled="!canEditExamSection">
                                        <option value="">Select Status</option>
                                        <option v-for="status in examStatusesList" :key="status.value"
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
                                    class="form-textarea" :disabled="!canEditExamSection"></textarea>
                                <span v-if="form.errors.exam_remarks" class="error-message">{{ form.errors.exam_remarks
                                    }}</span>
                            </div>
                        </div>

                        <!-- Initial Interview -->
                        <div class="form-section" :class="{ 'disabled-section': !canEditInitialSection }">
                            <div class="section-header">
                                <h3>Initial Interview</h3>
                                <div v-if="!isInitialInterviewApplicable" class="section-badge">
                                    <span class="badge badge-failed">Not Applicable - Application Failed</span>
                                </div>
                                <div v-else-if="!canEditInitialSection && hasAnyApprovedInterview"
                                    class="section-badge">
                                    <span class="badge badge-locked">You are not assigned to this stage</span>
                                </div>
                            </div>

                            <!-- HR/Admin sees all fields -->
                            <div v-if="canManageInterviewers">
                                <div class="exam-section-layout">
                                    <div class="exam-form-column">
                                        <div class="form-grid grid-2 mb-6">
                                            <div class="form-field">
                                                <label class="field-label">Initial Interview Plan Date</label>
                                                <input type="datetime-local" v-model="form.initial_interview_plan_date"
                                                    class="form-input" :min="nowDateTime()"
                                                    :disabled="!canEditInitialSection || isInitialInterviewPlanDateLocked" />
                                                <small v-if="isInitialInterviewPlanDateLocked"
                                                    class="helper-text text-amber-600">
                                                    ⚠️ Plan date cannot be changed once set
                                                </small>
                                            </div>
                                            <div class="form-field">
                                                <label class="field-label">Initial Interview Actual Date</label>
                                                <input type="datetime-local"
                                                    v-model="form.initial_interview_actual_date" class="form-input"
                                                    :disabled="!form.initial_interview_plan_date || !canEditInitialSection"
                                                    :min="initialInterviewActualMin" />
                                                <small class="helper-text">Cannot select past dates</small>
                                                <span v-if="form.errors.initial_interview_actual_date"
                                                    class="error-message">
                                                    {{ form.errors.initial_interview_actual_date }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-field mb-6">
                                            <label class="field-label">Initial Interview Venue</label>
                                            <select v-model="form.initial_interview_venue" class="form-select"
                                                :disabled="!canEditInitialSection">
                                                <option value="">Select Venue</option>
                                                <option v-for="venue in examVenuesList" :key="venue.value"
                                                    :value="venue.value">
                                                    {{ venue.label }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="form-grid grid-2 mb-6">
                                            <div class="form-field">
                                                <label class="field-label">Final Score (Average)</label>
                                                <input type="number" step="0.01" min="1.00" max="5.00"
                                                    v-model="form.initial_interview_final" class="form-input"
                                                    placeholder="1.00 - 5.00" :disabled="!canEditInitialSection" />
                                                <small class="helper-text">
                                                    Average of {{ initialInterviewerCount }} interviewer(s):
                                                    {{ computedInitialAverageScore || 'No scores yet' }}
                                                </small>
                                                <span v-if="form.errors.initial_interview_final" class="error-message">
                                                    {{ form.errors.initial_interview_final }}
                                                </span>
                                            </div>
                                            <div class="form-field">
                                                <label class="field-label">Application Status</label>
                                                <select v-model="form.initial_interview_application_status"
                                                    class="form-select" :disabled="!canEditInitialSection">
                                                    <option value="">Select Status</option>
                                                    <option v-for="status in interviewStatusesList" :key="status.value"
                                                        :value="status.value">
                                                        {{ status.label }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-field">
                                            <label class="field-label">Initial Interview Remarks</label>
                                            <textarea v-model="form.initial_interview_remarks" class="form-textarea"
                                                rows="3" :disabled="!canEditInitialSection"></textarea>
                                        </div>
                                    </div>

                                    <div class="exam-criteria-column">
                                        <div class="criteria-panel compact">
                                            <div class="criteria-panel-title">Initial Interview Criteria</div>
                                            <div class="criteria-rule passed">
                                                <div class="criteria-rule-title">1.00 - 2.00 → Passed</div>
                                            </div>
                                            <div class="criteria-rule p2">
                                                <div class="criteria-rule-title">2.01 - 3.00 → P2</div>
                                            </div>
                                            <div class="criteria-rule failed">
                                                <div class="criteria-rule-title">3.01 - 5.00 → Failed</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Interviewer sees only their evaluation -->
                            <div v-else-if="isApprovedForInitial">
                                <div
                                    class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/30">
                                    <h4 class="mb-3 text-sm font-semibold text-blue-800 dark:text-blue-300">Your
                                        Interview Evaluation</h4>

                                    <div v-if="currentUserInitialInterview?.evaluation_score" class="mb-4 text-sm">
                                        <p>Current Score: <strong>{{ currentUserInitialInterview.evaluation_score || '-'
                                                }}</strong></p>
                                        <p>Current Result: <strong>{{
                                            getEvaluationResultLabel(currentUserInitialInterview.evaluation_results)
                                                }}</strong></p>
                                    </div>

                                    <div class="form-grid grid-2">
                                        <div class="form-field">
                                            <label class="field-label">Your Score (1.00 - 5.00)</label>
                                            <input type="number" step="0.01" min="1.00" max="5.00"
                                                v-model="form.initial_evaluation_score"
                                                @input="clampInterviewScore(form, 'initial_evaluation_score')"
                                                class="form-input" placeholder="1.00 - 5.00" />
                                        </div>
                                        <div class="form-field">
                                            <label class="field-label">Your Evaluation Result</label>
                                            <select v-model="form.initial_evaluation_result" class="form-select">
                                                <option value="">Select Result</option>
                                                <option value="1">Pending</option>
                                                <option value="2">Passed</option>
                                                <option value="3">Failed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-field mt-3">
                                        <label class="field-label">Your Remarks</label>
                                        <textarea v-model="form.initial_evaluation_remarks" rows="3"
                                            class="form-textarea"
                                            placeholder="Enter your evaluation remarks..."></textarea>
                                    </div>
                                </div>

                                <div class="mt-4 text-sm text-gray-500">
                                    <p><strong>Plan Date:</strong> {{ formatDateTime(form.initial_interview_plan_date)
                                        }}</p>
                                    <p><strong>Venue:</strong> {{ getVenueLabel(form.initial_interview_venue) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Final Interview -->
                        <div class="form-section" :class="{ 'disabled-section': !canEditFinalSection }">
                            <div class="section-header">
                                <h3>Final Interview</h3>
                                <div v-if="!isFinalInterviewApplicable" class="section-badge">
                                    <span class="badge badge-failed">Not Applicable - Previous stage failed</span>
                                </div>
                                <div v-else-if="!canEditFinalSection && hasAnyApprovedInterview" class="section-badge">
                                    <span class="badge badge-locked">You are not assigned to this stage</span>
                                </div>
                            </div>

                            <!-- HR/Admin sees all fields -->
                            <div v-if="canManageInterviewers">
                                <div class="exam-section-layout">
                                    <div class="exam-form-column">
                                        <div class="form-field mb-6">
                                            <label class="field-label">Final Interview Date</label>
                                            <input type="datetime-local" v-model="form.final_interview_date"
                                                class="form-input" :disabled="!canEditFinalSection"
                                                :min="nowDateTime()" />
                                            <small class="helper-text">Cannot select past dates</small>
                                        </div>

                                        <div class="form-grid grid-2 mb-6">
                                            <div class="form-field">
                                                <label class="field-label">Final Score (Average)</label>
                                                <input type="number" step="0.01" min="1.00" max="5.00"
                                                    v-model="form.final_interview_final" class="form-input"
                                                    placeholder="1.00 - 5.00" :disabled="!canEditFinalSection" />
                                                <small class="helper-text">
                                                    Average of {{ finalInterviewerCount }} interviewer(s):
                                                    {{ computedFinalAverageScore || 'No scores yet' }}
                                                </small>
                                                <span v-if="form.errors.final_interview_final" class="error-message">
                                                    {{ form.errors.final_interview_final }}
                                                </span>
                                            </div>
                                            <div class="form-field">
                                                <label class="field-label">Application Status</label>
                                                <select v-model="form.final_interview_application_status"
                                                    class="form-select" :disabled="!canEditFinalSection">
                                                    <option value="">Select Status</option>
                                                    <option v-for="status in interviewStatusesList" :key="status.value"
                                                        :value="status.value">
                                                        {{ status.label }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-field">
                                            <label class="field-label">Final Interview Remarks</label>
                                            <textarea v-model="form.final_interview_remarks" class="form-textarea"
                                                rows="3" :disabled="!canEditFinalSection"></textarea>
                                        </div>
                                    </div>

                                    <div class="exam-criteria-column">
                                        <div class="criteria-panel compact">
                                            <div class="criteria-panel-title">Final Interview Criteria</div>
                                            <div class="criteria-rule passed">
                                                <div class="criteria-rule-title">1.00 - 2.00 → Passed</div>
                                            </div>
                                            <div class="criteria-rule p2">
                                                <div class="criteria-rule-title">2.01 - 3.00 → P2</div>
                                            </div>
                                            <div class="criteria-rule failed">
                                                <div class="criteria-rule-title">3.01 - 5.00 → Failed</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Interviewer sees only their evaluation -->
                            <div v-else-if="isApprovedForFinal">
                                <div
                                    class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/30">
                                    <h4 class="mb-3 text-sm font-semibold text-blue-800 dark:text-blue-300">Your
                                        Interview Evaluation</h4>

                                    <div v-if="currentUserFinalInterview?.evaluation_score" class="mb-4 text-sm">
                                        <p>Current Score: <strong>{{ currentUserFinalInterview.evaluation_score || '-'
                                                }}</strong></p>
                                        <p>Current Result: <strong>{{
                                            getEvaluationResultLabel(currentUserFinalInterview.evaluation_results)
                                                }}</strong></p>
                                    </div>

                                    <div class="form-grid grid-2">
                                        <div class="form-field">
                                            <label class="field-label">Your Score (1.00 - 5.00)</label>
                                            <input type="number" step="0.01" min="1.00" max="5.00"
                                                v-model="form.final_evaluation_score"
                                                @input="clampInterviewScore(form, 'final_evaluation_score')"
                                                class="form-input" placeholder="1.00 - 5.00" />
                                        </div>
                                        <div class="form-field">
                                            <label class="field-label">Your Evaluation Result</label>
                                            <select v-model="form.final_evaluation_result" class="form-select">
                                                <option value="">Select Result</option>
                                                <option value="1">Pending</option>
                                                <option value="2">Passed</option>
                                                <option value="3">Failed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-field mt-3">
                                        <label class="field-label">Your Remarks</label>
                                        <textarea v-model="form.final_evaluation_remarks" rows="3" class="form-textarea"
                                            placeholder="Enter your evaluation remarks..."></textarea>
                                    </div>
                                </div>

                                <div class="mt-4 text-sm text-gray-500">
                                    <p><strong>Date:</strong> {{ formatDateTime(form.final_interview_date) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Job Offer -->
                        <div class="form-section" :class="{ 'disabled-section': !canEditJobOfferSection }">
                            <div class="section-header">
                                <h3>Job Offer</h3>
                                <div v-if="!isJobOfferApplicable" class="section-badge">
                                    <span class="badge badge-failed">Not Applicable - Previous stage failed</span>
                                </div>
                                <div v-else-if="!canEditJobOfferSection && hasAnyApprovedInterview"
                                    class="section-badge">
                                    <span class="badge badge-locked">Only HR/Admin can edit</span>
                                </div>
                            </div>
                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label">Job Offer Schedule</label>
                                    <input type="datetime-local" v-model="form.job_offer_schedule" class="form-input"
                                        :disabled="!canEditJobOfferSection" :min="nowDateTime()" />
                                    <small class="helper-text">Cannot select past dates</small>
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Job Offer Status</label>
                                    <select v-model="form.job_offer_status" class="form-select"
                                        :disabled="!canEditJobOfferSection">
                                        <option value="">Select Status</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Done</option>
                                        <option value="3">Accept</option>
                                        <option value="4">Decline</option>
                                        <option value="5">Withdraw</option>
                                        <option value="6">Retracted</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field mt-3">
                                <label class="field-label">Job Offer Remarks</label>
                                <textarea v-model="form.job_offer_remarks" class="form-textarea" rows="3"
                                    :disabled="!canEditJobOfferSection"></textarea>
                            </div>
                        </div>

                        <!-- General Remarks -->
                        <div class="form-section" :class="{ 'disabled-section': !canEditGeneralRemarks }">
                            <div class="section-header">
                                <h3>General Remarks</h3>
                                <div v-if="!canEditGeneralRemarks && hasAnyApprovedInterview" class="section-badge">
                                    <span class="badge badge-locked">Only HR/Admin can edit</span>
                                </div>
                            </div>
                            <div class="form-field">
                                <label class="field-label !text-gray-500">Overall Remarks</label>
                                <textarea v-model="form.remarks" class="form-textarea" rows="4"
                                    placeholder="Any additional notes or comments..."
                                    :disabled="!canEditGeneralRemarks"></textarea>
                                <span v-if="form.errors.remarks" class="error-message">{{ form.errors.remarks }}</span>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <Link :href="`/intermediate/applications/${application.id}`" class="btn btn-secondary">
                                Cancel</Link>
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">
                                {{ form.processing ? 'Updating…' : 'Update Application' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.disabled-section {
    opacity: 0.7;
    pointer-events: none;
    position: relative;
}

.disabled-section::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.3);
    pointer-events: none;
    border-radius: 0.5rem;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.section-badge {
    margin-left: 1rem;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 9999px;
}

.badge-locked {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.badge-failed {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

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

.form-wrapper {
    width: 100%;
}

.form-card {
    overflow: hidden;
    background: #ffffff;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.form-card form {
    padding: 2rem;
}

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

.section-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
}

.form-grid {
    display: grid;
    gap: 1rem 1.25rem;
}

.grid-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.grid-3 {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

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

.form-input,
.form-select,
.form-textarea,
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
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    box-sizing: border-box;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-input:disabled,
.form-select:disabled,
.form-textarea:disabled,
.file-input-btn:disabled {
    background: #f3f4f6;
    border-color: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
}

.form-textarea {
    min-height: 96px;
    resize: vertical;
}

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

.file-preview,
.picture-preview {
    margin-top: 0.25rem;
}

.preview-link {
    font-size: 0.8125rem;
    color: #2563eb;
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

.error-message {
    margin-top: 0.125rem;
    font-size: 0.75rem;
    line-height: 1.4;
    color: #ef4444;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.75rem;
    margin-top: 2rem;
    padding-top: 0.5rem;
}

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
    background: #2563eb;
    border: none;
}

.btn-primary:hover:not(:disabled) {
    background: #1d4ed8;
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-secondary {
    color: var(--ats-text);
    background: #ffffff;
    border: 1px solid var(--ats-border);
}

.btn-secondary:hover {
    background: var(--ats-bg-subtle);
    border-color: #9ca3af;
}

.position-preferences-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
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

.compensation-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.5rem;
    margin-top: 1rem;
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

.mb-6 {
    margin-bottom: 1.5rem;
}

.mb-8 {
    margin-bottom: 2rem;
}

.pt-5 {
    padding-top: 1.25rem;
}

.mt-3 {
    margin-top: 0.75rem;
}

.mt-4 {
    margin-top: 1rem;
}

.gap-4 {
    gap: 1rem;
}

.w-full {
    width: 100%;
}

.text-sm {
    font-size: 0.875rem;
}

.\!text-gray-500 {
    color: #6b7280 !important;
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

@media (max-width: 768px) {
    .page-container {
        padding: 1rem;
    }

    .form-card form {
        padding: 1.25rem;
    }

    .grid-2,
    .grid-3 {
        grid-template-columns: 1fr;
    }

    .position-preferences-grid {
        grid-template-columns: 1fr;
    }

    .compensation-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }
}
</style>