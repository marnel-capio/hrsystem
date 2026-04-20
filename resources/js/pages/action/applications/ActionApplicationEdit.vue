<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { Link, usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const page = usePage();

const props = defineProps<{
    application: any;
    examVenues?: Record<number, string>;
    examResults?: Record<number, string>;
    examStatuses?: Record<number, string>;
    interviewResults?: Record<number, string>;
    interviewAppStatuses?: Record<number, string>;
    jobOfferStatuses?: Record<number, string>;
    applicationResultMap?: {
        exam?: Record<number, number>;
        initial_interview?: Record<number, number>;
        final_interview?: Record<number, number>;
    };
    applicationScoreRules?: {
        exam?: Record<string, any>;
        initial_interview?: Record<string, number>;
    };
    editableStages?: {
        exam: boolean;
        initial_interview: boolean;
        final_interview: boolean;
        job_offer: boolean;
        general: boolean;
        documents: boolean;
    };
    finalInterviewAssignments?: Array<{
        id: number;
        interviewer_id: number;
        name: string;
        role_label: string;
        score?: number | null;
        evaluation_result?: number | null;
        evaluation_remarks?: string | null;
    }>;
    canEditFinalInterviewDecision?: boolean;
    user_permissions?: number;
    user_id?: number;
    flash?: { error?: string; success?: string };
}>();

const editableStages = computed(
    () =>
        props.editableStages || {
            exam: false,
            initial_interview: false,
            final_interview: false,
            job_offer: false,
            general: false,
            documents: false,
        },
);

const canEditAnything = computed(() =>
    Object.values(editableStages.value).some(Boolean),
);
const canEditExamPlanDate = computed(
    () => editableStages.value.exam && !props.application.exam_plan_date,
);

const canEditInitialInterviewPlanDate = computed(
    () =>
        editableStages.value.initial_interview &&
        !props.application.initial_interview_plan_date,
);

const canEditFinalInterviewPlanDate = computed(
    () =>
        editableStages.value.final_interview &&
        !props.application.final_interview_date,
);

const examVenues = ref<Array<{ value: number; label: string }>>([]);
const examResults = ref<Array<{ value: number; label: string }>>([]);
const examStatuses = ref<Array<{ value: number; label: string }>>([]);
const interviewResults = ref<Array<{ value: number; label: string }>>([]);
const interviewAppStatuses = ref<Array<{ value: number; label: string }>>([]);
const jobOfferStatuses = ref<Array<{ value: number; label: string }>>([]);

const resumeFile = ref<File | null>(null);
const torFile = ref<File | null>(null);
const pictureFile = ref<File | null>(null);

const formatDateForInput = (dateString: string | null) => {
    if (!dateString) return '';
    return String(dateString).slice(0, 16).replace(' ', 'T');
};

function formatDateTimeLocal(value: Date) {
    const year = value.getFullYear();
    const month = String(value.getMonth() + 1).padStart(2, '0');
    const day = String(value.getDate()).padStart(2, '0');
    const hours = String(value.getHours()).padStart(2, '0');
    const minutes = String(value.getMinutes()).padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

function addMinutes(value: string, minutes: number) {
    if (!value) return '';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '';
    date.setMinutes(date.getMinutes() + minutes);
    return formatDateTimeLocal(date);
}

function getTomorrowStart() {
    const now = new Date();
    now.setHours(0, 0, 0, 0);
    now.setDate(now.getDate() + 1);
    return formatDateTimeLocal(now);
}

function isBefore(a: string, b: string) {
    if (!a || !b) return false;
    const first = new Date(a);
    const second = new Date(b);

    if (Number.isNaN(first.getTime()) || Number.isNaN(second.getTime())) {
        return false;
    }

    return first.getTime() < second.getTime();
}

const finalScoreManuallyEdited = ref(false);

function parseScore(value: string | number | null | undefined): number {
    if (value === '' || value === null || value === undefined) return 0;
    const num = Number(value);
    return Number.isNaN(num) ? 0 : num;
}

function getInterviewerEvaluationResultFromScore(
    score: string | number | null | undefined,
): string {
    if (score === '' || score === null || score === undefined) return '';

    const num = Number(score);
    if (Number.isNaN(num)) return '';

    if (num === 0) return '1'; // Pending
    if (num > 0 && num <= 2.0) return '2'; // Passed
    if (num > 2.0 && num <= 5.0) return '3'; // Failed

    return '';
}

function handleFinalScoreManualInput() {
    finalScoreManuallyEdited.value = true;
}

const form = useForm({
    upload_resume: props.application.upload_resume || '',
    upload_tor: props.application.upload_tor || '',
    upload_pic: props.application.upload_pic || '',
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
    exam_git_result: props.application.exam_git_result || '',
    exam_prg_result: props.application.exam_prg_result || '',
    exam_result: props.application.exam_result || '',
    exam_application_status: props.application.exam_application_status || '',
    exam_remarks: props.application.exam_remarks || '',

    initial_interview_plan_date: formatDateForInput(
        props.application.initial_interview_plan_date,
    ),
    initial_interview_actual_date: formatDateForInput(
        props.application.initial_interview_actual_date,
    ),
    initial_interview_venue: props.application.initial_interview_venue || '',
    initial_interview_final: props.application.initial_interview_final || '',
    initial_interview_result: props.application.initial_interview_result || '',
    initial_interview_application_status:
        props.application.initial_interview_application_status || '',
    initial_interview_remarks:
        props.application.initial_interview_remarks || '',

    final_interview_date: formatDateForInput(
        props.application.final_interview_date,
    ),
    final_interview_assignments: (props.finalInterviewAssignments || []).map(
        (row) => ({
            id: row.id,
            interviewer_id: row.interviewer_id,
            name: row.name,
            role_label: row.role_label,
            score: row.score ?? '',
            evaluation_result: row.evaluation_result ?? '',
            evaluation_remarks: row.evaluation_remarks ?? '',
        }),
    ),
    final_interview_final: props.application.final_interview_final || '',
    final_interview_result: props.application.final_interview_result || '',
    final_interview_application_status:
        props.application.final_interview_application_status || '',
    final_interview_remarks: props.application.final_interview_remarks || '',

    job_offer_schedule: formatDateForInput(
        props.application.job_offer_schedule,
    ),
    job_offer_status: props.application.job_offer_status || '',
    job_offer_remarks: props.application.job_offer_remarks || '',
    remarks: props.application.remarks || '',
});

const currentApplicant = computed(() => {
    return props.application?.applicant || null;
});

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

const canEditFinalInterviewDecision = computed(
    () => !!props.canEditFinalInterviewDecision,
);

const isHrDecisionEditor = computed(
    () => !!props.canEditFinalInterviewDecision,
);

const visibleFinalInterviewAssignments = computed(() => {
    const rows = form.final_interview_assignments || [];

    if (isHrDecisionEditor.value) {
        return rows;
    }

    return rows.filter(
        (row: any) => Number(row.interviewer_id) === Number(props.user_id || 0),
    );
});

const finalInterviewEvaluatedRows = computed(() => {
    return (form.final_interview_assignments || []).filter((row: any) =>
        [2, 3].includes(Number(row.evaluation_result)),
    );
});

const allFinalInterviewersPassed = computed(() => {
    const rows = finalInterviewEvaluatedRows.value;
    return (
        rows.length > 0 &&
        rows.every((row: any) => Number(row.evaluation_result) === 2)
    );
});

const allFinalInterviewersFailed = computed(() => {
    const rows = finalInterviewEvaluatedRows.value;
    return (
        rows.length > 0 &&
        rows.every((row: any) => Number(row.evaluation_result) === 3)
    );
});

const hasMixedFinalInterviewResults = computed(() => {
    const rows = finalInterviewEvaluatedRows.value;
    const hasPassed = rows.some(
        (row: any) => Number(row.evaluation_result) === 2,
    );
    const hasFailed = rows.some(
        (row: any) => Number(row.evaluation_result) === 3,
    );
    return hasPassed && hasFailed;
});

watch(computedAtppResult, (value) => {
    form.exam_atpp_result = value;
});

const examResultLabel = computed(() => {
    const selected = examResults.value.find(
        (result) => result.value === Number(form.exam_result),
    );
    return selected ? selected.label : '';
});

const initialInterviewResultLabel = computed(() => {
    const selected = interviewResults.value.find(
        (result) => result.value === Number(form.initial_interview_result),
    );
    return selected ? selected.label : '';
});

const finalInterviewResultLabel = computed(() => {
    const selected = interviewResults.value.find(
        (result) => result.value === Number(form.final_interview_result),
    );
    return selected ? selected.label : '';
});

const selectedApplicantLabel = computed(() => {
    if (props.application.applicant) {
        return `${props.application.applicant.last_name}, ${props.application.applicant.first_name} ${props.application.applicant.middle_name || ''}`;
    }
    return 'Select Applicant';
});

const batchName = computed(
    () => props.application.batch?.action_batch || 'N/A',
);

const getFileUrl = (filename: string | null) => {
    if (!filename) return null;
    return `/storage/${filename}`;
};

const existingResumeUrl = computed(() =>
    getFileUrl(props.application.upload_resume || null),
);
const existingTorUrl = computed(() =>
    getFileUrl(props.application.upload_tor || null),
);
const existingPictureUrl = computed(() =>
    getFileUrl(props.application.upload_pic || null),
);

const originalFiles = {
    resume: props.application.upload_resume,
    tor: props.application.upload_tor,
    picture: props.application.upload_pic,
};

const examPlanMin = computed(() => getTomorrowStart());
const examActualMin = computed(() => form.exam_plan_date || '');
const initialInterviewPlanMin = computed(() =>
    form.exam_plan_date ? addMinutes(form.exam_plan_date, 1) : '',
);
const initialInterviewActualMin = computed(
    () => form.initial_interview_plan_date || '',
);
const finalInterviewMin = computed(
    () => form.initial_interview_plan_date || '',
);
const jobOfferScheduleMin = computed(() => form.final_interview_date || '');

watch(
    () => form.exam_plan_date,
    (newValue) => {
        if (
            form.exam_actual_date &&
            newValue &&
            isBefore(form.exam_actual_date, newValue)
        ) {
            form.exam_actual_date = '';
        }

        const nextInitialPlanMin = newValue ? addMinutes(newValue, 1) : '';
        if (
            form.initial_interview_plan_date &&
            nextInitialPlanMin &&
            isBefore(form.initial_interview_plan_date, nextInitialPlanMin)
        ) {
            form.initial_interview_plan_date = '';
        }
    },
);

watch(
    () => form.initial_interview_plan_date,
    (newValue) => {
        if (
            form.initial_interview_actual_date &&
            newValue &&
            isBefore(form.initial_interview_actual_date, newValue)
        ) {
            form.initial_interview_actual_date = '';
        }

        if (
            form.final_interview_date &&
            newValue &&
            isBefore(form.final_interview_date, newValue)
        ) {
            form.final_interview_date = '';
        }
    },
);

watch(
    () => form.final_interview_date,
    (newValue) => {
        if (
            form.job_offer_schedule &&
            newValue &&
            isBefore(form.job_offer_schedule, newValue)
        ) {
            form.job_offer_schedule = '';
        }
    },
);

const errorMessage = computed(() => (page.props.flash as any)?.error || '');
const showError = ref(false);
const successMessage = computed(() => (page.props.flash as any)?.success || '');
const showSuccess = ref(false);

function normalizeDegree(degree: string): string {
    return String(degree || '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s]/g, ' ')
        .replace(/\s+/g, ' ');
}

function isTechDegree(degree: string): boolean {
    const normalizedDegree = normalizeDegree(degree);

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
    ];

    for (const pattern of patterns) {
        const normalizedPattern = normalizeDegree(pattern);

        if (!normalizedPattern) continue;

        if (
            ['it', 'cs', 'cpe', 'bsit', 'bscs', 'bscpe'].includes(
                normalizedPattern,
            )
        ) {
            const regex = new RegExp(
                `\\b${normalizedPattern.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}\\b`,
            );
            if (regex.test(normalizedDegree)) {
                return true;
            }
            continue;
        }

        if (normalizedDegree.includes(normalizedPattern)) {
            return true;
        }
    }

    return false;
}

function getExamCategory(applicant: any): 'young_it' | 'young_other' | 'adult' {
    if (!applicant) return 'young_other';

    const age = Number(applicant.age || 0);
    const rawDegree =
        applicant.others_degree || applicant.degree || applicant.course || '';

    if (age >= 25) {
        return 'adult';
    }

    return isTechDegree(rawDegree) ? 'young_it' : 'young_other';
}

function getExamApplicationStatus(
    attp: number,
    git: number,
    prg: number,
    category: 'young_it' | 'young_other' | 'adult',
): string {
    const rules = props.applicationScoreRules?.exam?.[category];

    if (!rules) return '';

    const passed = rules.passed;
    const p2 = rules.p2;

    if (
        passed &&
        attp >= Number(passed.attp) &&
        git >= Number(passed.git) &&
        prg >= Number(passed.prg)
    ) {
        return '5';
    }

    if (
        p2 &&
        attp >= Number(p2.attp) &&
        git >= Number(p2.git) &&
        prg >= Number(p2.prg)
    ) {
        return '3';
    }

    return '6';
}
function getInitialInterviewApplicationStatus(score: number): string {
    const rules = props.applicationScoreRules?.initial_interview;

    const passedMax = Number(rules?.passed_min ?? 2.0);
    const p2Max = Number(rules?.p2_min ?? 2.5);
    const failedMax = 5.0;

    if (score === 0) return '1';
    if (score > 0 && score <= passedMax) return '3';
    if (score > passedMax && score <= p2Max) return '4';
    if (score > p2Max && score <= failedMax) return '5';

    return '1';
}

function validateScoreField(
    field: string,
    label: string,
    value: string | number,
) {
    if (value === '' || value === null || value === undefined) {
        if ((form.errors as any)[field]?.includes(`${label} must`)) {
            form.clearErrors(field as any);
        }
        return;
    }

    const num = Number(value);

    if (Number.isNaN(num)) {
        form.setError(field as any, `${label} must be a valid number.`);
        return;
    }

    if (num < 0 || num > 999.99) {
        form.setError(field as any, `${label} must be between 0 and 999.99.`);
        return;
    }

    if ((form.errors as any)[field]?.includes(`${label} must`)) {
        form.clearErrors(field as any);
    }
}

watch(
    () => form.exam_atpp_result,
    (value) => {
        validateScoreField('exam_atpp_result', 'ATPP Result', value);
    },
);

watch(
    () => form.exam_git_result,
    (value) => {
        validateScoreField('exam_git_result', 'GIT Result', value);
    },
);

watch(
    () => form.exam_prg_result,
    (value) => {
        validateScoreField('exam_prg_result', 'PRG Result', value);
    },
);

watch(
    () => form.initial_interview_final,
    (value) => {
        validateScoreField(
            'initial_interview_final',
            'Initial Interview Final Score',
            value,
        );
    },
);

watch(
    () => form.final_interview_final,
    (value) => {
        validateScoreField('final_interview_final', 'Final Score', value);
    },
);

watch(
    () => form.final_interview_assignments,
    (rows) => {
        const list = rows || [];

        const numericScores = list
            .map((row: any) => Number(row.score))
            .filter((value: number) => !Number.isNaN(value));

        if (numericScores.length === 0) {
            if (!finalScoreManuallyEdited.value) {
                form.final_interview_final = '';
            }
        } else if (!finalScoreManuallyEdited.value) {
            const average =
                numericScores.reduce(
                    (sum: number, value: number) => sum + value,
                    0,
                ) / numericScores.length;

            form.final_interview_final = average.toFixed(2);
        }

        (list || []).forEach((row: any, index: number) => {
            validateScoreField(
                `final_interview_assignments.${index}.score`,
                `${row.name || 'Interviewer'} Score`,
                row.score,
            );
        });

        if (allFinalInterviewersPassed.value) {
            form.final_interview_result = '2';
            form.final_interview_application_status = '3';
            return;
        }

        if (allFinalInterviewersFailed.value) {
            form.final_interview_result = '3';
            form.final_interview_application_status = '5';
            return;
        }

        if (hasMixedFinalInterviewResults.value) {
            form.final_interview_application_status = '2';
            if (!isHrDecisionEditor.value) {
                form.final_interview_result = '';
            }
            return;
        }

        if (form.final_interview_date) {
            form.final_interview_application_status = '1';
        } else {
            form.final_interview_application_status = '';
            form.final_interview_result = '';
        }
    },
    { deep: true },
);

onMounted(() => {
    if (props.examVenues) {
        examVenues.value = Object.entries(props.examVenues).map(
            ([value, label]) => ({
                value: Number(value),
                label: String(label),
            }),
        );
    }

    if (props.examResults) {
        examResults.value = Object.entries(props.examResults).map(
            ([value, label]) => ({
                value: Number(value),
                label: String(label),
            }),
        );
    }

    if (props.examStatuses) {
        examStatuses.value = Object.entries(props.examStatuses).map(
            ([value, label]) => ({
                value: Number(value),
                label: String(label),
            }),
        );
    }

    if (props.interviewResults) {
        interviewResults.value = Object.entries(props.interviewResults).map(
            ([value, label]) => ({
                value: Number(value),
                label: String(label),
            }),
        );
    }

    if (props.interviewAppStatuses) {
        interviewAppStatuses.value = Object.entries(
            props.interviewAppStatuses,
        ).map(([value, label]) => ({
            value: Number(value),
            label: String(label),
        }));
    }

    if (props.jobOfferStatuses) {
        jobOfferStatuses.value = Object.entries(props.jobOfferStatuses).map(
            ([value, label]) => ({
                value: Number(value),
                label: String(label),
            }),
        );
    }
});

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

watch(
    () => form.exam_plan_date,
    (newPlanDate) => {
        if (
            newPlanDate &&
            !form.exam_atpp_result &&
            !form.exam_git_result &&
            !form.exam_prg_result
        ) {
            form.exam_application_status = '1';
        }

        if (
            !newPlanDate &&
            !form.exam_atpp_result &&
            !form.exam_git_result &&
            !form.exam_prg_result
        ) {
            form.exam_application_status = '';
        }
    },
);

watch(
    [
        () => form.exam_atpp_result,
        () => form.exam_git_result,
        () => form.exam_prg_result,
        () => currentApplicant.value,
        () => form.exam_plan_date,
    ],
    ([attp, git, prg, applicant, planDate]) => {
        const hasAllScores = !!attp && !!git && !!prg;

        if (!hasAllScores) {
            form.exam_application_status = planDate ? '1' : '';
            return;
        }

        if (!applicant) {
            form.exam_application_status = planDate ? '1' : '';
            return;
        }

        const attpNum = Number(attp);
        const gitNum = Number(git);
        const prgNum = Number(prg);

        if (
            Number.isNaN(attpNum) ||
            Number.isNaN(gitNum) ||
            Number.isNaN(prgNum)
        ) {
            form.exam_application_status = planDate ? '1' : '';
            return;
        }

        const category = getExamCategory(applicant);
        form.exam_application_status = getExamApplicationStatus(
            attpNum,
            gitNum,
            prgNum,
            category,
        );
    },
);

watch(
    () => form.initial_interview_plan_date,
    (newPlanDate) => {
        if (newPlanDate && !form.initial_interview_final) {
            form.initial_interview_application_status = '1';
        }

        if (!newPlanDate && !form.initial_interview_final) {
            form.initial_interview_application_status = '';
        }
    },
);

watch(
    [
        () => form.initial_interview_final,
        () => form.initial_interview_plan_date,
    ],
    ([score, planDate]) => {
        if (!score) {
            form.initial_interview_application_status = planDate ? '1' : '';
            return;
        }

        const numericScore = Number(score);

        if (Number.isNaN(numericScore)) {
            form.initial_interview_application_status = planDate ? '1' : '';
            return;
        }

        form.initial_interview_application_status =
            getInitialInterviewApplicationStatus(numericScore);
    },
);

watch(
    () => form.final_interview_date,
    (newPlanDate) => {
        if (newPlanDate && !form.final_interview_application_status) {
            form.final_interview_application_status = '1';
        }

        if (!newPlanDate && form.final_interview_application_status === '1') {
            form.final_interview_application_status = '';
        }
    },
);

watch(
    () => form.job_offer_schedule,
    (newSchedule) => {
        if (newSchedule && !form.job_offer_status) {
            form.job_offer_status = '1';
        }

        if (!newSchedule && form.job_offer_status === '1') {
            form.job_offer_status = '';
        }
    },
);

watch(
    () => form.exam_application_status,
    (newStatus) => {
        if (!newStatus) {
            form.exam_result = '';
            return;
        }

        const mappedResult =
            props.applicationResultMap?.exam?.[Number(newStatus)];
        form.exam_result = mappedResult ? String(mappedResult) : '';
    },
);

watch(
    () => form.initial_interview_application_status,
    (newStatus) => {
        if (!newStatus) {
            form.initial_interview_result = '';
            return;
        }

        const mappedResult =
            props.applicationResultMap?.initial_interview?.[Number(newStatus)];
        form.initial_interview_result = mappedResult
            ? String(mappedResult)
            : '';
    },
);

watch(
    () => form.final_interview_application_status,
    (newStatus) => {
        if (!newStatus) {
            form.final_interview_result = '';
            return;
        }

        const mappedResult =
            props.applicationResultMap?.final_interview?.[Number(newStatus)];
        form.final_interview_result = mappedResult ? String(mappedResult) : '';
    },
);

watch(
    () => form.exam_atpp_part1_correct,
    (value) => {
        validateScoreField(
            'exam_atpp_part1_correct',
            'ATPP Part I Correct',
            value,
        );
    },
);

watch(
    () => form.exam_atpp_part1_wrong,
    (value) => {
        validateScoreField('exam_atpp_part1_wrong', 'ATPP Part I Wrong', value);
    },
);

watch(
    () => form.exam_atpp_part2_correct,
    (value) => {
        validateScoreField(
            'exam_atpp_part2_correct',
            'ATPP Part II Correct',
            value,
        );
    },
);

watch(
    () => form.exam_atpp_part2_wrong,
    (value) => {
        validateScoreField(
            'exam_atpp_part2_wrong',
            'ATPP Part II Wrong',
            value,
        );
    },
);

watch(
    () => form.exam_atpp_part3_correct,
    (value) => {
        validateScoreField(
            'exam_atpp_part3_correct',
            'ATPP Part III Correct',
            value,
        );
    },
);

watch(
    () => form.exam_atpp_part3_wrong,
    (value) => {
        validateScoreField(
            'exam_atpp_part3_wrong',
            'ATPP Part III Wrong',
            value,
        );
    },
);

watch(
    () => form.final_interview_assignments,
    (rows) => {
        (rows || []).forEach((row: any) => {
            if (
                row.score === '' ||
                row.score === null ||
                row.score === undefined
            ) {
                row.evaluation_result = '';
                return;
            }

            const computedResult = getInterviewerEvaluationResultFromScore(
                row.score,
            );

            if (
                !canEditFinalInterviewDecision.value ||
                !row.evaluation_result
            ) {
                row.evaluation_result = computedResult;
            }
        });
    },
    { deep: true },
);

const handleResumeUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        if (!allowedTypes.includes(file.type)) {
            form.setError(
                'upload_resume',
                'Please upload a PDF or Word document',
            );
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            form.setError('upload_resume', 'File size must be less than 5MB');
            return;
        }

        resumeFile.value = file;
        form.upload_resume = file.name;
        form.clearErrors('upload_resume');
    }
};

const isInitialBlocked = computed(
    () => Number(props.application.exam_result) === 3,
);

const isFinalBlocked = computed(
    () =>
        Number(props.application.exam_result) === 3 ||
        Number(props.application.initial_interview_result) === 3,
);

const isJobOfferBlocked = computed(
    () =>
        Number(props.application.exam_result) === 3 ||
        Number(props.application.initial_interview_result) === 3 ||
        Number(props.application.final_interview_result) === 3,
);

const handleTorUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
        ];

        if (!allowedTypes.includes(file.type)) {
            form.setError(
                'upload_tor',
                'Please upload a PDF, Word document, or image',
            );
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            form.setError('upload_tor', 'File size must be less than 5MB');
            return;
        }

        torFile.value = file;
        form.upload_tor = file.name;
        form.clearErrors('upload_tor');
    }
};

const handlePictureUpload = (event: Event) => {
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
        form.clearErrors('upload_pic');
    }
};

const removeFile = (type: 'resume' | 'tor' | 'picture') => {
    switch (type) {
        case 'resume':
            resumeFile.value = null;
            form.upload_resume = '';
            break;
        case 'tor':
            torFile.value = null;
            form.upload_tor = '';
            break;
        case 'picture':
            pictureFile.value = null;
            form.upload_pic = '';
            break;
    }
};

const normalizeDateTimeForSubmit = (value: unknown) => {
    if (typeof value !== 'string' || !value) return value;

    if (/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/.test(value)) {
        return value.replace('T', ' ') + ':00';
    }

    return value;
};

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

function getFinalInterviewApplicationStatus(score: number): string {
    const rules = props.applicationScoreRules?.initial_interview;

    const passedMax = Number(rules?.passed_min ?? 2.0);
    const p2Max = Number(rules?.p2_min ?? 2.5);
    const failedMax = 5.0;

    if (score === 0) return '1';
    if (score > 0 && score <= passedMax) return '3';
    if (score > passedMax && score <= p2Max) return '4';
    if (score > p2Max && score <= failedMax) return '5';

    return '1';
}

watch(
    [() => form.final_interview_final, () => form.final_interview_date],
    ([score, finalDate]) => {
        if (!score) {
            form.final_interview_application_status = finalDate ? '1' : '';
            form.final_interview_result = finalDate ? '1' : '';
            return;
        }

        const numericScore = Number(score);

        if (Number.isNaN(numericScore)) {
            form.final_interview_application_status = finalDate ? '1' : '';
            form.final_interview_result = finalDate ? '1' : '';
            return;
        }

        form.final_interview_application_status =
            getFinalInterviewApplicationStatus(numericScore);
    },
);

function submit() {
    form.clearErrors();

    form.transform((data) => {
        const formData = new FormData();

        Object.keys(data).forEach((key) => {
            const value = data[key as keyof typeof data];

            if (key === 'upload_resume') {
                if (resumeFile.value) {
                    formData.append('upload_resume', resumeFile.value);
                } else if (value === '' && originalFiles.resume) {
                    formData.append('upload_resume', '');
                }
                return;
            }

            if (key === 'upload_tor') {
                if (torFile.value) {
                    formData.append('upload_tor', torFile.value);
                } else if (value === '' && originalFiles.tor) {
                    formData.append('upload_tor', '');
                }
                return;
            }

            if (key === 'upload_pic') {
                if (pictureFile.value) {
                    formData.append('upload_pic', pictureFile.value);
                } else if (value === '' && originalFiles.picture) {
                    formData.append('upload_pic', '');
                }
                return;
            }

            if (key === 'final_interview_assignments') {
                ((value as any[]) || []).forEach((row: any, index: number) => {
                    if (row.id !== null && row.id !== undefined) {
                        formData.append(
                            `final_interview_assignments[${index}][id]`,
                            String(row.id),
                        );
                    }

                    formData.append(
                        `final_interview_assignments[${index}][score]`,
                        row.score !== null && row.score !== undefined
                            ? String(row.score)
                            : '',
                    );

                    formData.append(
                        `final_interview_assignments[${index}][evaluation_result]`,
                        row.evaluation_result !== null &&
                            row.evaluation_result !== undefined
                            ? String(row.evaluation_result)
                            : '',
                    );

                    formData.append(
                        `final_interview_assignments[${index}][evaluation_remarks]`,
                        row.evaluation_remarks ?? '',
                    );
                });
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

    form.post(`/action/applications/${props.application.id}`, {
        preserveState: true,
        preserveScroll: true,
    });
}
const examCriteriaDisplay = computed(() => {
    if (!currentApplicant.value) return null;

    const category = getExamCategory(currentApplicant.value);
    const rules = props.applicationScoreRules?.exam?.[category];

    if (!rules) return null;

    return {
        category,
        passed: rules.passed,
        p2: rules.p2,
    };
});
</script>

<template>
    <AppLayout>
        <div v-if="showSuccess" class="full-width-alert">
            <div class="alert-banner alert-success-banner">
                <div class="alert-body">{{ successMessage }}</div>
                <button
                    type="button"
                    class="close-btn"
                    @click="showSuccess = false"
                >
                    ×
                </button>
            </div>
        </div>

        <div v-if="showError" class="full-width-alert">
            <div class="alert-banner alert-error-banner">
                <div class="alert-body">{{ errorMessage }}</div>
                <button
                    type="button"
                    class="close-btn"
                    @click="showError = false"
                >
                    ×
                </button>
            </div>
        </div>

        <div class="page-container">
            <div class="page-header">
                <h2 class="page-title">Edit ACTION Application</h2>
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
                                    <label class="field-label-required required"
                                        >ACTION Batch</label
                                    >
                                    <input
                                        type="text"
                                        :value="batchName"
                                        class="form-input"
                                        disabled
                                    />
                                </div>

                                <div class="form-field">
                                    <label class="field-label-required required"
                                        >ACTION Applicant</label
                                    >
                                    <input
                                        type="text"
                                        :value="selectedApplicantLabel"
                                        class="form-input"
                                        disabled
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-header">
                                <h3>Upload Documents</h3>
                            </div>

                            <div class="form-grid grid-3">
                                <div class="form-field">
                                    <label class="field-label"
                                        >Upload Resume</label
                                    >

                                    <div
                                        v-if="form.upload_resume && !resumeFile"
                                        class="file-info"
                                    >
                                        <span class="file-name">{{
                                            form.upload_resume
                                        }}</span>
                                        <button
                                            type="button"
                                            @click="removeFile('resume')"
                                            class="remove-file"
                                            :disabled="
                                                !editableStages.documents
                                            "
                                        >
                                            ×
                                        </button>
                                    </div>

                                    <div v-if="resumeFile" class="file-info">
                                        <span class="file-name">{{
                                            form.upload_resume
                                        }}</span>
                                        <button
                                            type="button"
                                            @click="removeFile('resume')"
                                            class="remove-file"
                                            :disabled="
                                                !editableStages.documents
                                            "
                                        >
                                            ×
                                        </button>
                                    </div>

                                    <input
                                        type="file"
                                        @change="handleResumeUpload"
                                        accept=".pdf,.doc,.docx"
                                        class="file-input-btn w-full"
                                        :disabled="
                                            form.processing ||
                                            !editableStages.documents
                                        "
                                    />

                                    <span
                                        v-if="form.errors.upload_resume"
                                        class="error-message"
                                    >
                                        {{ form.errors.upload_resume }}
                                    </span>

                                    <div
                                        v-if="existingResumeUrl && !resumeFile"
                                        class="file-preview"
                                    >
                                        <a
                                            :href="existingResumeUrl"
                                            target="_blank"
                                            class="preview-link"
                                            >Open current resume</a
                                        >
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label class="field-label"
                                        >Upload TOR</label
                                    >

                                    <div
                                        v-if="form.upload_tor && !torFile"
                                        class="file-info"
                                    >
                                        <span class="file-name">{{
                                            form.upload_tor
                                        }}</span>
                                        <button
                                            type="button"
                                            @click="removeFile('tor')"
                                            class="remove-file"
                                            :disabled="
                                                !editableStages.documents
                                            "
                                        >
                                            ×
                                        </button>
                                    </div>

                                    <div v-if="torFile" class="file-info">
                                        <span class="file-name">{{
                                            form.upload_tor
                                        }}</span>
                                        <button
                                            type="button"
                                            @click="removeFile('tor')"
                                            class="remove-file"
                                            :disabled="
                                                !editableStages.documents
                                            "
                                        >
                                            ×
                                        </button>
                                    </div>

                                    <input
                                        type="file"
                                        @change="handleTorUpload"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                        class="file-input-btn w-full"
                                        :disabled="
                                            form.processing ||
                                            !editableStages.documents
                                        "
                                    />

                                    <span
                                        v-if="form.errors.upload_tor"
                                        class="error-message"
                                    >
                                        {{ form.errors.upload_tor }}
                                    </span>

                                    <div
                                        v-if="existingTorUrl && !torFile"
                                        class="file-preview"
                                    >
                                        <a
                                            :href="existingTorUrl"
                                            target="_blank"
                                            class="preview-link"
                                            >Open current TOR</a
                                        >
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label class="field-label"
                                        >Upload 2x2 Pic</label
                                    >

                                    <div
                                        v-if="form.upload_pic && !pictureFile"
                                        class="file-info"
                                    >
                                        <span class="file-name">{{
                                            form.upload_pic
                                        }}</span>
                                        <button
                                            type="button"
                                            @click="removeFile('picture')"
                                            class="remove-file"
                                            :disabled="
                                                !editableStages.documents
                                            "
                                        >
                                            ×
                                        </button>
                                    </div>

                                    <div v-if="pictureFile" class="file-info">
                                        <span class="file-name">{{
                                            form.upload_pic
                                        }}</span>
                                        <button
                                            type="button"
                                            @click="removeFile('picture')"
                                            class="remove-file"
                                            :disabled="
                                                !editableStages.documents
                                            "
                                        >
                                            ×
                                        </button>
                                    </div>

                                    <input
                                        type="file"
                                        @change="handlePictureUpload"
                                        accept="image/jpeg,image/png,image/jpg"
                                        class="file-input-btn w-full"
                                        :disabled="
                                            form.processing ||
                                            !editableStages.documents
                                        "
                                    />

                                    <span
                                        v-if="form.errors.upload_pic"
                                        class="error-message"
                                    >
                                        {{ form.errors.upload_pic }}
                                    </span>

                                    <div
                                        v-if="
                                            existingPictureUrl && !pictureFile
                                        "
                                        class="picture-preview"
                                    >
                                        <img
                                            :src="existingPictureUrl"
                                            alt="Current picture"
                                            class="preview-image"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-header">
                                <h3>Exam Details</h3>
                            </div>

                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label"
                                        >Exam Plan Date</label
                                    >
                                    <input
                                        type="datetime-local"
                                        v-model="form.exam_plan_date"
                                        class="form-input"
                                        :min="examPlanMin || undefined"
                                        :disabled="!canEditExamPlanDate"
                                    />
                                    <span
                                        v-if="form.errors.exam_plan_date"
                                        class="error-message"
                                    >
                                        {{ form.errors.exam_plan_date }}
                                    </span>
                                </div>

                                <div class="form-field">
                                    <label class="field-label"
                                        >Exam Actual Date</label
                                    >
                                    <input
                                        type="datetime-local"
                                        v-model="form.exam_actual_date"
                                        class="form-input"
                                        :min="examActualMin || undefined"
                                        :disabled="!editableStages.exam"
                                    />
                                    <span
                                        v-if="form.errors.exam_actual_date"
                                        class="error-message"
                                    >
                                        {{ form.errors.exam_actual_date }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="field-label">Exam Venue</label>
                                <select
                                    v-model="form.exam_venue"
                                    class="form-select"
                                    :disabled="!editableStages.exam"
                                >
                                    <option value="">Select Venue</option>
                                    <option
                                        v-for="venue in examVenues"
                                        :key="venue.value"
                                        :value="venue.value"
                                    >
                                        {{ venue.label }}
                                    </option>
                                </select>
                                <span
                                    v-if="form.errors.exam_venue"
                                    class="error-message"
                                >
                                    {{ form.errors.exam_venue }}
                                </span>
                            </div>

                            <div class="exam-section-layout">
                                <div class="exam-form-column">
                                    <div class="atpp-stack">
                                        <div class="atpp-card">
                                            <div class="atpp-card-title">
                                                ATPP Part I (Sequence / Pattern
                                                Analysis)
                                            </div>
                                            <div class="form-grid grid-2">
                                                <div class="form-field">
                                                    <label class="field-label"
                                                        >Correct</label
                                                    >
                                                    <input
                                                        type="number"
                                                        step="1"
                                                        min="0"
                                                        max="40"
                                                        @input="
                                                            clampScore(
                                                                form,
                                                                'exam_atpp_part1_correct',
                                                                40,
                                                            )
                                                        "
                                                        v-model="
                                                            form.exam_atpp_part1_correct
                                                        "
                                                        class="form-input"
                                                        :disabled="
                                                            !editableStages.exam
                                                        "
                                                    />
                                                    <span
                                                        v-if="
                                                            form.errors
                                                                .exam_atpp_part1_correct
                                                        "
                                                        class="error-message"
                                                    >
                                                        {{
                                                            form.errors
                                                                .exam_atpp_part1_correct
                                                        }}
                                                    </span>
                                                </div>

                                                <div class="form-field">
                                                    <label class="field-label"
                                                        >Wrong</label
                                                    >
                                                    <input
                                                        type="number"
                                                        step="1"
                                                        min="0"
                                                        max="40"
                                                        @input="
                                                            clampScore(
                                                                form,
                                                                'exam_atpp_part1_wrong',
                                                                40,
                                                            )
                                                        "
                                                        v-model="
                                                            form.exam_atpp_part1_wrong
                                                        "
                                                        class="form-input"
                                                        :disabled="
                                                            !editableStages.exam
                                                        "
                                                    />
                                                    <span
                                                        v-if="
                                                            form.errors
                                                                .exam_atpp_part1_wrong
                                                        "
                                                        class="error-message"
                                                    >
                                                        {{
                                                            form.errors
                                                                .exam_atpp_part1_wrong
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="atpp-card">
                                            <div class="atpp-card-title">
                                                ATPP Part II (Abstract
                                                Reasoning)
                                            </div>
                                            <div class="form-grid grid-2">
                                                <div class="form-field">
                                                    <label class="field-label"
                                                        >Correct</label
                                                    >
                                                    <input
                                                        type="number"
                                                        step="1"
                                                        min="0"
                                                        max="30"
                                                        @input="
                                                            clampScore(
                                                                form,
                                                                'exam_atpp_part2_correct',
                                                                30,
                                                            )
                                                        "
                                                        v-model="
                                                            form.exam_atpp_part2_correct
                                                        "
                                                        class="form-input"
                                                        :disabled="
                                                            !editableStages.exam
                                                        "
                                                    />
                                                    <span
                                                        v-if="
                                                            form.errors
                                                                .exam_atpp_part2_correct
                                                        "
                                                        class="error-message"
                                                    >
                                                        {{
                                                            form.errors
                                                                .exam_atpp_part2_correct
                                                        }}
                                                    </span>
                                                </div>

                                                <div class="form-field">
                                                    <label class="field-label"
                                                        >Wrong</label
                                                    >
                                                    <input
                                                        type="number"
                                                        step="1"
                                                        min="0"
                                                                                                                max="30"
                                                        @input="
                                                            clampScore(
                                                                form,
                                                                'exam_atpp_part2_wrong',
                                                                30,
                                                            )
                                                        "

                                                        v-model="
                                                            form.exam_atpp_part2_wrong
                                                        "
                                                        class="form-input"
                                                        :disabled="
                                                            !editableStages.exam
                                                        "
                                                    />
                                                    <span
                                                        v-if="
                                                            form.errors
                                                                .exam_atpp_part2_wrong
                                                        "
                                                        class="error-message"
                                                    >
                                                        {{
                                                            form.errors
                                                                .exam_atpp_part2_wrong
                                                        }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="atpp-card">
                                            <div class="atpp-card-title">
                                                ATPP Part III (Problem Solving)
                                            </div>
                                            <div class="form-grid grid-2">
                                                <div class="form-field">
                                                    <label class="field-label"
                                                        >Correct</label
                                                    >
                                                    <input
                                                        type="number"
                                                        step="1"
                                                        min="0"
                                                        max="25"
                                                        @input="
                                                            clampScore(
                                                                form,
                                                                'exam_atpp_part3_correct',
                                                                25,
                                                            )
                                                        "
                                                        v-model="
                                                            form.exam_atpp_part3_correct
                                                        "
                                                        class="form-input"
                                                        :disabled="
                                                            !editableStages.exam
                                                        "
                                                    />
                                                    <span
                                                        v-if="
                                                            form.errors
                                                                .exam_atpp_part3_correct
                                                        "
                                                        class="error-message"
                                                    >
                                                        {{
                                                            form.errors
                                                                .exam_atpp_part3_correct
                                                        }}
                                                    </span>
                                                </div>

                                                <div class="form-field">
                                                    <label class="field-label"
                                                        >Wrong</label
                                                    >
                                                    <input
                                                        type="number"
                                                        step="1"
                                                        min="0"
                                                                                                                max="25"
                                                        @input="
                                                            clampScore(
                                                                form,
                                                                'exam_atpp_part3_wrong',
                                                                25,
                                                            )
                                                        "

                                                        v-model="
                                                            form.exam_atpp_part3_wrong
                                                        "
                                                        class="form-input"
                                                        :disabled="
                                                            !editableStages.exam
                                                        "
                                                    />
                                                    <span
                                                        v-if="
                                                            form.errors
                                                                .exam_atpp_part3_wrong
                                                        "
                                                        class="error-message"
                                                    >
                                                        {{
                                                            form.errors
                                                                .exam_atpp_part3_wrong
                                                        }}
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
                                                <div
                                                    class="criteria-panel-title"
                                                >
                                                    Exam Criteria
                                                </div>
                                            </div>

                                            <div class="criteria-rule passed">
                                                <div
                                                    class="criteria-rule-title"
                                                >
                                                    PASSED
                                                </div>
                                                <ul class="criteria-list">
                                                    <li>
                                                        ATPP must be at least
                                                        {{
                                                            examCriteriaDisplay
                                                                .passed.attp
                                                        }}
                                                    </li>
                                                    <li>
                                                        GIT must be at least
                                                        {{
                                                            examCriteriaDisplay
                                                                .passed.git
                                                        }}
                                                    </li>
                                                    <li>
                                                        PRG must be at least
                                                        {{
                                                            examCriteriaDisplay
                                                                .passed.prg
                                                        }}
                                                    </li>
                                                </ul>
                                            </div>

                                            <div
                                                v-if="examCriteriaDisplay.p2"
                                                class="criteria-rule p2"
                                            >
                                                <div
                                                    class="criteria-rule-title"
                                                >
                                                    P2
                                                </div>
                                                <ul class="criteria-list">
                                                    <li>
                                                        ATPP must be at least
                                                        {{
                                                            examCriteriaDisplay
                                                                .p2.attp
                                                        }}
                                                    </li>
                                                    <li>
                                                        GIT must be at least
                                                        {{
                                                            examCriteriaDisplay
                                                                .p2.git
                                                        }}
                                                    </li>
                                                    <li>
                                                        PRG must be at least
                                                        {{
                                                            examCriteriaDisplay
                                                                .p2.prg
                                                        }}
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="criteria-rule failed">
                                                <div
                                                    class="criteria-rule-title"
                                                >
                                                    FAILED
                                                </div>
                                                <ul class="criteria-list">
                                                    <li>
                                                        ATPP is below
                                                        {{
                                                            examCriteriaDisplay
                                                                .p2?.attp ??
                                                            examCriteriaDisplay
                                                                .passed.attp
                                                        }}
                                                    </li>
                                                    <li>
                                                        or GIT is below
                                                        {{
                                                            examCriteriaDisplay
                                                                .p2?.git ??
                                                            examCriteriaDisplay
                                                                .passed.git
                                                        }}
                                                    </li>
                                                    <li>
                                                        or PRG is below
                                                        {{
                                                            examCriteriaDisplay
                                                                .p2?.prg ??
                                                            examCriteriaDisplay
                                                                .passed.prg
                                                        }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </template>

                                        <template v-else>
                                            <div class="criteria-panel-header">
                                                <div
                                                    class="criteria-panel-title"
                                                >
                                                    Exam Criteria
                                                </div>
                                                <div
                                                    class="criteria-panel-subtitle"
                                                >
                                                    Applicant category
                                                    unavailable
                                                </div>
                                            </div>

                                            <div class="criteria-empty">
                                                The applicable criteria depends
                                                on the applicant's age and
                                                degree category.
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-grid grid-3 pt-5">
                                <div class="form-field">
                                    <label class="field-label"
                                        >ATPP Final Result</label
                                    >
                                    <input
                                        type="number"
                                        step="0.01"
                                        :value="computedAtppResult"
                                        class="form-input"
                                        readonly
                                    />
                                    <small class="helper-text">
                                        ATPP Final Result = Total Correct -
                                        (Total Wrong / 4)
                                    </small>
                                    <span
                                        v-if="form.errors.exam_atpp_result"
                                        class="error-message"
                                    >
                                        {{ form.errors.exam_atpp_result }}
                                    </span>
                                </div>

                                <div class="form-field">
                                    <label class="field-label"
                                        >GIT Result</label
                                    >
                                    <input
                                        type="number"
                                        step="0.01"
                                        v-model="form.exam_git_result"
                                         min="0"
                                        max="12"
                                        class="form-input"
                                        @input="clampScore(form, 'exam_git_result', 12)"
                                        :disabled="!editableStages.exam"
                                    />
                                    <span
                                        v-if="form.errors.exam_git_result"
                                        class="error-message"
                                    >
                                        {{ form.errors.exam_git_result }}
                                    </span>
                                </div>

                                <div class="form-field">
                                    <label class="field-label"
                                        >PRG Result</label
                                    >
                                    <input
                                        type="number"
                                        step="0.01"
                                        v-model="form.exam_prg_result"
                                        min="0"
                                        max="80"
                                        class="form-input"
                                        :disabled="!editableStages.exam"
                                        @input="clampScore(form, 'exam_prg_result', 80)"
                                    />
                                    <span
                                        v-if="form.errors.exam_prg_result"
                                        class="error-message"
                                    >
                                        {{ form.errors.exam_prg_result }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label"
                                        >Exam Result</label
                                    >
                                    <input
                                        type="text"
                                        class="form-input"
                                        :value="
                                            examResultLabel ||
                                            (!form.exam_application_status
                                                ? 'Auto-filled from application status'
                                                : '')
                                        "
                                        readonly
                                        :disabled="!editableStages.exam"
                                    />
                                    <span
                                        v-if="form.errors.exam_result"
                                        class="error-message"
                                        >{{ form.errors.exam_result }}</span
                                    >
                                </div>

                                <div class="form-field">
                                    <label class="field-label"
                                        >Exam Application Status</label
                                    >
                                    <select
                                        v-model="form.exam_application_status"
                                        class="form-select"
                                        :disabled="!editableStages.exam"
                                    >
                                        <option value="">Select Status</option>
                                        <option
                                            v-for="status in examStatuses"
                                            :key="status.value"
                                            :value="status.value"
                                        >
                                            {{ status.label }}
                                        </option>
                                    </select>
                                    <span
                                        v-if="
                                            form.errors.exam_application_status
                                        "
                                        class="error-message"
                                    >
                                        {{
                                            form.errors.exam_application_status
                                        }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="field-label">Exam Comments</label>
                                <textarea
                                    v-model="form.exam_remarks"
                                    rows="3"
                                    class="form-textarea"
                                    :disabled="!editableStages.exam"
                                ></textarea>
                                <span
                                    v-if="form.errors.exam_remarks"
                                    class="error-message"
                                >
                                    {{ form.errors.exam_remarks }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="form-section"
                            :class="{
                                'pointer-events-none opacity-50':
                                    isInitialBlocked,
                            }"
                        >
                            <div class="section-header">
                                <h3>Initial Interview</h3>
                                <div
                                    v-if="isInitialBlocked"
                                    class="mb-2 text-sm text-red-500"
                                >
                                    Initial Interview is disabled because
                                    applicant failed previous stage.
                                </div>
                            </div>

                            <div class="exam-section-layout">
                                <div class="exam-form-column">
                                    <div class="form-grid grid-2">
                                        <div class="form-field">
                                            <label class="field-label"
                                                >Plan Date</label
                                            >
                                            <input
                                                type="datetime-local"
                                                v-model="
                                                    form.initial_interview_plan_date
                                                "
                                                class="form-input"
                                                :min="
                                                    initialInterviewPlanMin ||
                                                    undefined
                                                "
                                                :disabled="
                                                    !canEditInitialInterviewPlanDate
                                                "
                                            />
                                            <span
                                                v-if="
                                                    form.errors
                                                        .initial_interview_plan_date
                                                "
                                                class="error-message"
                                            >
                                                {{
                                                    form.errors
                                                        .initial_interview_plan_date
                                                }}
                                            </span>
                                        </div>

                                        <div class="form-field">
                                            <label class="field-label"
                                                >Actual Date</label
                                            >
                                            <input
                                                type="datetime-local"
                                                v-model="
                                                    form.initial_interview_actual_date
                                                "
                                                class="form-input"
                                                :min="
                                                    initialInterviewActualMin ||
                                                    undefined
                                                "
                                                :disabled="
                                                    !editableStages.initial_interview ||
                                                    isInitialBlocked
                                                "
                                            />
                                            <span
                                                v-if="
                                                    form.errors
                                                        .initial_interview_actual_date
                                                "
                                                class="error-message"
                                            >
                                                {{
                                                    form.errors
                                                        .initial_interview_actual_date
                                                }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-field">
                                        <label class="field-label">Venue</label>
                                        <select
                                            v-model="
                                                form.initial_interview_venue
                                            "
                                            class="form-select"
                                            :disabled="
                                                !editableStages.initial_interview ||
                                                isInitialBlocked
                                            "
                                        >
                                            <option value="">
                                                Select Venue
                                            </option>
                                            <option
                                                v-for="venue in examVenues"
                                                :key="venue.value"
                                                :value="venue.value"
                                            >
                                                {{ venue.label }}
                                            </option>
                                        </select>
                                        <span
                                            v-if="
                                                form.errors
                                                    .initial_interview_venue
                                            "
                                            class="error-message"
                                        >
                                            {{
                                                form.errors
                                                    .initial_interview_venue
                                            }}
                                        </span>
                                    </div>

                                    <div class="form-field">
                                        <label class="field-label"
                                            >Initial Interview Final
                                            Score</label
                                        >
                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="5"
                                            v-model="
                                                form.initial_interview_final
                                            "
                                            class="form-input"
                                            @input="
                                                clampScore(
                                                    form,
                                                    'initial_interview_final',
                                                    5
                                                )
                                            "
                                            :disabled="
                                                !editableStages.initial_interview ||
                                                isInitialBlocked
                                            "
                                        />
                                        <span
                                            v-if="
                                                form.errors
                                                    .initial_interview_final
                                            "
                                            class="error-message"
                                        >
                                            {{
                                                form.errors
                                                    .initial_interview_final
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <div class="exam-criteria-column">
                                    <div class="criteria-panel compact">
                                        <div class="criteria-panel-title">
                                            Initial Interview Criteria
                                        </div>

                                        <div class="criteria-rule passed">
                                            <div class="criteria-rule-title">
                                                1 - Highly Recommended
                                            </div>
                                        </div>

                                        <div class="criteria-rule passed">
                                            <div class="criteria-rule-title">
                                                2 - Recommended
                                            </div>
                                        </div>

                                        <div class="criteria-rule p2">
                                            <div class="criteria-rule-title">
                                                3 - Average
                                            </div>
                                        </div>

                                        <div class="criteria-rule failed">
                                            <div class="criteria-rule-title">
                                                4 - Not Recommended
                                            </div>
                                        </div>

                                        <div class="criteria-rule failed">
                                            <div class="criteria-rule-title">
                                                5 - Never Recommended
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label">Result</label>
                                    <input
                                        type="text"
                                        class="form-input"
                                        :value="
                                            initialInterviewResultLabel ||
                                            (!form.initial_interview_application_status
                                                ? 'Auto-filled from application status'
                                                : '')
                                        "
                                        readonly
                                        :disabled="
                                            !editableStages.initial_interview ||
                                            isInitialBlocked
                                        "
                                    />
                                </div>

                                <div class="form-field">
                                    <label class="field-label"
                                        >Application Status</label
                                    >
                                    <select
                                        v-model="
                                            form.initial_interview_application_status
                                        "
                                        class="form-select"
                                        :disabled="
                                            !editableStages.initial_interview ||
                                            isInitialBlocked
                                        "
                                    >
                                        <option value="">Select Status</option>
                                        <option
                                            v-for="status in interviewAppStatuses"
                                            :key="status.value"
                                            :value="status.value"
                                        >
                                            {{ status.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-field mt-3">
                                <label class="field-label"
                                    >Initial Interview Comments</label
                                >
                                <textarea
                                    v-model="form.initial_interview_remarks"
                                    rows="3"
                                    class="form-textarea"
                                    :disabled="
                                        !editableStages.initial_interview ||
                                        isInitialBlocked
                                    "
                                ></textarea>
                                <span
                                    v-if="form.errors.initial_interview_remarks"
                                    class="error-message"
                                >
                                    {{ form.errors.initial_interview_remarks }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="form-section"
                            :class="{
                                'pointer-events-none opacity-50':
                                    isFinalBlocked,
                            }"
                        >
                            <div class="section-header">
                                <h3>Final Interview</h3>
                                <div
                                    v-if="isFinalBlocked"
                                    class="mb-2 text-sm text-red-500"
                                >
                                    Final Interview is disabled because
                                    applicant failed previous stage.
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="field-label">Date</label>
                                <input
                                    type="datetime-local"
                                    v-model="form.final_interview_date"
                                    class="form-input"
                                    :min="finalInterviewMin || undefined"
                                    :disabled="!editableStages.final_interview"
                                />
                                <span
                                    v-if="form.errors.final_interview_date"
                                    class="error-message"
                                >
                                    {{ form.errors.final_interview_date }}
                                </span>
                            </div>

                            <div class="exam-section-layout">
                                <div class="exam-form-column">
                                    <div
                                        v-if="
                                            visibleFinalInterviewAssignments.length ===
                                            0
                                        "
                                        class="criteria-empty"
                                    >
                                        No final interviewers approved yet.
                                    </div>

                                    <div v-else class="atpp-stack">
                                        <div
                                            v-for="(
                                                assignment, index
                                            ) in visibleFinalInterviewAssignments"
                                            :key="assignment.id"
                                            class="atpp-card"
                                        >
                                            <div class="atpp-card-title">
                                                {{ assignment.name }}
                                                <span
                                                    class="criteria-panel-subtitle"
                                                    >({{
                                                        assignment.role_label
                                                    }})</span
                                                >
                                            </div>

                                            <div class="form-grid grid-2">
                                                <div class="form-field">
                                                    <label class="field-label"
                                                        >Score</label
                                                    >
                                                    <input
                                                        type="number"
                                                        v-model="
                                                            assignment.score
                                                        "
                                                        min="0"
                                                        max="5"
                                                        step="0.01"
                                                        class="form-input"
                                                        @input="
                                                            clampScore(
                                                                assignment,
                                                                'score',
                                                                5
                                                            )
                                                        "
                                                    />
                                                </div>

                                                <div class="form-field">
                                                    <label class="field-label"
                                                        >Result</label
                                                    >
                                                    <select
                                                        v-model="
                                                            assignment.evaluation_result
                                                        "
                                                        class="form-select"
                                                        :disabled="
                                                            !editableStages.final_interview
                                                        "
                                                    >
                                                        <option value="">
                                                            Select Result
                                                        </option>
                                                        <option value="1">
                                                            Pending
                                                        </option>
                                                        <option value="2">
                                                            Passed
                                                        </option>
                                                        <option value="3">
                                                            Failed
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-field">
                                                <label class="field-label"
                                                    >Interviewer Remarks</label
                                                >
                                                <textarea
                                                    v-model="
                                                        assignment.evaluation_remarks
                                                    "
                                                    rows="2"
                                                    class="form-textarea"
                                                    :disabled="
                                                        !editableStages.final_interview
                                                    "
                                                ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="exam-criteria-column">
                                    <div class="criteria-panel compact">
                                        <div class="criteria-panel-title">
                                            Final Interview Criteria
                                        </div>

                                        <div class="criteria-rule passed">
                                            <div class="criteria-rule-title">
                                                1 - Highly Recommended
                                            </div>
                                        </div>

                                        <div class="criteria-rule passed">
                                            <div class="criteria-rule-title">
                                                2 - Recommended
                                            </div>
                                        </div>

                                        <div class="criteria-rule p2">
                                            <div class="criteria-rule-title">
                                                3 - Average
                                            </div>
                                        </div>

                                        <div class="criteria-rule failed">
                                            <div class="criteria-rule-title">
                                                4 - Not Recommended
                                            </div>
                                        </div>

                                        <div class="criteria-rule failed">
                                            <div class="criteria-rule-title">
                                                5 - Never Recommended
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-grid grid-3 mt-3">
                                <div class="form-field">
                                    <label class="field-label"
                                        >Final Score</label
                                    >
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="5"
                                        v-model="form.final_interview_final"
                                        @input="
                                            handleFinalScoreManualInput();
                                            clampScore(
                                                form,
                                                'final_interview_final',
                                                5
                                            );
                                        "
                                        class="form-input"
                                        :disabled="
                                            !editableStages.final_interview
                                        "
                                    />
                                </div>

                                <div class="form-field">
                                    <label class="field-label">Result</label>
                                    <input
                                        v-if="
                                            allFinalInterviewersPassed ||
                                            allFinalInterviewersFailed
                                        "
                                        type="text"
                                        class="form-input"
                                        :value="
                                            finalInterviewResultLabel ||
                                            (!form.final_interview_application_status
                                                ? 'Auto-filled from application status'
                                                : '')
                                        "
                                        readonly
                                        :disabled="
                                            !editableStages.final_interview
                                        "
                                    />

                                    <select
                                        v-else-if="
                                            hasMixedFinalInterviewResults &&
                                            canEditFinalInterviewDecision
                                        "
                                        v-model="form.final_interview_result"
                                        class="form-select"
                                        :disabled="
                                            !editableStages.final_interview
                                        "
                                    >
                                        <option value="">
                                            Select Final Result
                                        </option>
                                        <option value="2">Passed</option>
                                        <option value="3">Failed</option>
                                    </select>

                                    <input
                                        v-else
                                        type="text"
                                        class="form-input"
                                        :value="
                                            finalInterviewResultLabel ||
                                            'For HR deliberation'
                                        "
                                        readonly
                                        :disabled="
                                            !editableStages.final_interview
                                        "
                                    />
                                </div>

                                <div class="form-field">
                                    <label class="field-label"
                                        >Application Status</label
                                    >
                                    <input
                                        type="text"
                                        class="form-input"
                                        :value="
                                            interviewAppStatuses.find(
                                                (s) =>
                                                    String(s.value) ===
                                                    String(
                                                        form.final_interview_application_status,
                                                    ),
                                            )?.label || ''
                                        "
                                        readonly
                                        :disabled="
                                            !editableStages.final_interview
                                        "
                                    />
                                </div>
                            </div>

                            <div class="form-field mt-3">
                                <label class="field-label"
                                    >Final Interview Comments</label
                                >
                                <textarea
                                    v-model="form.final_interview_remarks"
                                    rows="3"
                                    class="form-textarea"
                                    :disabled="!editableStages.final_interview"
                                ></textarea>
                                <span
                                    v-if="form.errors.final_interview_remarks"
                                    class="error-message"
                                >
                                    {{ form.errors.final_interview_remarks }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="form-section"
                            :class="{
                                'pointer-events-none opacity-50':
                                    isJobOfferBlocked,
                            }"
                        >
                            <div class="section-header">
                                <h3>Job Offer</h3>
                                <div
                                    v-if="isJobOfferBlocked"
                                    class="mb-2 text-sm text-red-500"
                                >
                                    Job Offer is disabled because applicant
                                    failed previous stage.
                                </div>
                            </div>

                            <div class="form-grid grid-2">
                                <div class="form-field">
                                    <label class="field-label">Schedule</label>
                                    <input
                                        type="datetime-local"
                                        v-model="form.job_offer_schedule"
                                        class="form-input"
                                        :min="jobOfferScheduleMin || undefined"
                                        :disabled="!editableStages.job_offer"
                                    />
                                    <span
                                        v-if="form.errors.job_offer_schedule"
                                        class="error-message"
                                        >{{
                                            form.errors.job_offer_schedule
                                        }}</span
                                    >
                                </div>
                                <div class="form-field">
                                    <label class="field-label">Status</label>
                                    <select
                                        v-model="form.job_offer_status"
                                        class="form-select"
                                        :disabled="!editableStages.job_offer"
                                    >
                                        <option value="">Select Status</option>
                                        <option
                                            v-for="status in jobOfferStatuses"
                                            :key="status.value"
                                            :value="status.value"
                                        >
                                            {{ status.label }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="field-label"
                                    >Job Offer Comments</label
                                >
                                <textarea
                                    v-model="form.job_offer_remarks"
                                    rows="3"
                                    class="form-textarea"
                                    :disabled="!editableStages.job_offer"
                                ></textarea>
                                <span
                                    v-if="form.errors.job_offer_remarks"
                                    class="error-message"
                                >
                                    {{ form.errors.job_offer_remarks }}
                                </span>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-header">
                                <h3>Additional Information</h3>
                            </div>

                            <div class="form-field">
                                <label class="field-label"
                                    >General Remarks</label
                                >
                                <textarea
                                    v-model="form.remarks"
                                    rows="3"
                                    class="form-textarea"
                                    :disabled="!editableStages.general"
                                ></textarea>
                                <span
                                    v-if="form.errors.remarks"
                                    class="error-message"
                                >
                                    {{ form.errors.remarks }}
                                </span>
                            </div>
                        </div>

                        <div class="form-actions">
                            <Link
                                :href="`/action/applications/${application.id}`"
                                class="btn btn-secondary"
                                >Cancel</Link
                            >
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
    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease,
        background-color 0.2s ease;
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
