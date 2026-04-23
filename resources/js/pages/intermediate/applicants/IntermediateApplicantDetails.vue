<script setup lang="ts">
import { Head, usePage, Link } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';
import { Pen, Trash, Eye } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';

const page = usePage<any>();
const applicant = computed(() => page.props.applicant);
const userPermissions = computed(() =>
    Number(page.props.user_permissions ?? page.props.userPermissions ?? 0),
);

const skills = ref<any[]>(page.props.skills || applicant.value?.skills || []);

const selectedSkills = ref<number[]>([]);

const addSkillModalVisible = ref(false);
const editSkillModalVisible = ref(false);
const deleteSkillModalVisible = ref(false);

const newSkillName = ref('');
const newSkillRemarks = ref('');

const skillBeingEdited = ref<{
    id: number;
    skill: string;
    remarks: string | null;
} | null>(null);
const editedSkillName = ref('');
const editedSkillRemarks = ref('');

const deleteSkillTargetId = ref<number | null>(null);
const isBulkDeleteSkillsModal = ref(false);

const addSkillNameError = ref<string | null>(null);
const addSkillRemarksError = ref<string | null>(null);
const editSkillNameError = ref<string | null>(null);
const editSkillRemarksError = ref<string | null>(null);

const canSeeRestrictedRemarks = computed(() =>
    [1, 2, 3].includes(userPermissions.value),
);

const workExperiences = ref<any[]>(
    page.props.workExperiences || applicant.value?.workExperiences || [],
);

const selectedWorkExperiences = ref<number[]>([]);

const addWorkModalVisible = ref(false);
const editWorkModalVisible = ref(false);
const deleteWorkModalVisible = ref(false);

const newWork = ref({
    employer: '',
    company_address: '',
    job_title: '',
    date_employed: '',
    work_description: '',
    salary: '',
    reason_for_leaving: '',
    name_supervisor: '',
    remarks: '',
});

const workBeingEdited = ref<any | null>(null);

const editedWork = ref({
    employer: '',
    company_address: '',
    job_title: '',
    date_employed: '',
    work_description: '',
    salary: '',
    reason_for_leaving: '',
    name_supervisor: '',
    remarks: '',
});

const deleteWorkTargetId = ref<number | null>(null);
const isBulkDeleteWorkModal = ref(false);

const workErrors = ref<Record<string, string | null>>({
    employer: null,
    company_address: null,
    job_title: null,
    date_employed: null,
    work_description: null,
    salary: null,
    reason_for_leaving: null,
    name_supervisor: null,
    remarks: null,
});

const editWorkErrors = ref<Record<string, string | null>>({
    employer: null,
    company_address: null,
    job_title: null,
    date_employed: null,
    work_description: null,
    salary: null,
    reason_for_leaving: null,
    name_supervisor: null,
    remarks: null,
});

const resetWorkErrors = () => {
    workErrors.value = {
        employer: null,
        company_address: null,
        job_title: null,
        date_employed: null,
        work_description: null,
        salary: null,
        reason_for_leaving: null,
        name_supervisor: null,
        remarks: null,
    };
};

const resetEditWorkErrors = () => {
    editWorkErrors.value = {
        employer: null,
        company_address: null,
        job_title: null,
        date_employed: null,
        work_description: null,
        salary: null,
        reason_for_leaving: null,
        name_supervisor: null,
        remarks: null,
    };
};

const fetchWorkExperiences = async () => {
    try {
        const res = await axios.get(
            `/intermediate/applicants/${applicant.value.id}/work-experiences`,
        );
        workExperiences.value = res.data;
    } catch {
        // keep server-provided props if route does not exist yet
    }
};

const openAddWorkModal = () => {
    newWork.value = {
        employer: '',
        company_address: '',
        job_title: '',
        date_employed: '',
        work_description: '',
        salary: '',
        reason_for_leaving: '',
        name_supervisor: '',
        remarks: '',
    };
    resetWorkErrors();
    addWorkModalVisible.value = true;
};

const closeAddWorkModal = () => {
    addWorkModalVisible.value = false;
    resetWorkErrors();
};

const saveNewWork = async () => {
    resetWorkErrors();

    try {
        await axios.post(
            `/intermediate/applicants/${applicant.value.id}/work-experiences`,
            newWork.value,
        );

        await fetchWorkExperiences();
        closeAddWorkModal();
        showToastMessage(
            messages.record_created_successfully.errorMessage,
            'success',
        );
    } catch (error: any) {
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            Object.keys(workErrors.value).forEach((key) => {
                workErrors.value[key] = errors[key]?.[0] || null;
            });
        } else {
            showToastMessage(messages.transaction_failed.errorMessage, 'error');
        }
    }
};

const openEditWorkModal = (work: any) => {
    workBeingEdited.value = { ...work };
    editedWork.value = {
        employer: work.employer || '',
        company_address: work.company_address || '',
        job_title: work.job_title || '',
        date_employed: work.date_employed || '',
        work_description: work.work_description || '',
        salary: work.salary || '',
        reason_for_leaving: work.reason_for_leaving || '',
        name_supervisor: work.name_supervisor || '',
        remarks: work.remarks || '',
    };
    resetEditWorkErrors();
    editWorkModalVisible.value = true;
};

const closeEditWorkModal = () => {
    editWorkModalVisible.value = false;
    workBeingEdited.value = null;
    resetEditWorkErrors();
};

const saveWorkEdit = async () => {
    if (!workBeingEdited.value) return;

    resetEditWorkErrors();

    try {
        await axios.put(
            `/intermediate/applicants/${applicant.value.id}/work-experiences/${workBeingEdited.value.id}`,
            editedWork.value,
        );

        await fetchWorkExperiences();
        closeEditWorkModal();
        showToastMessage(
            messages.record_updated_successfully.errorMessage,
            'success',
        );
    } catch (error: any) {
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            Object.keys(editWorkErrors.value).forEach((key) => {
                editWorkErrors.value[key] = errors[key]?.[0] || null;
            });
        } else {
            showToastMessage(messages.update_failed.errorMessage, 'error');
        }
    }
};

const confirmDeleteWork = (id: number) => {
    deleteWorkTargetId.value = id;
    isBulkDeleteWorkModal.value = false;
    deleteWorkModalVisible.value = true;
};

const confirmBulkDeleteWork = () => {
    if (!selectedWorkExperiences.value.length) return;
    isBulkDeleteWorkModal.value = true;
    deleteWorkTargetId.value = null;
    deleteWorkModalVisible.value = true;
};

const closeWorkDeleteModal = () => {
    deleteWorkModalVisible.value = false;
    deleteWorkTargetId.value = null;
    isBulkDeleteWorkModal.value = false;
};

const performWorkDelete = async () => {
    try {
        if (isBulkDeleteWorkModal.value) {
            await axios.post(
                `/intermediate/applicants/${applicant.value.id}/work-experiences/bulk-delete`,
                {
                    ids: selectedWorkExperiences.value,
                },
            );
            selectedWorkExperiences.value = [];
        } else if (deleteWorkTargetId.value !== null) {
            await axios.delete(
                `/intermediate/applicants/${applicant.value.id}/work-experiences/${deleteWorkTargetId.value}`,
            );
        }

        await fetchWorkExperiences();
        showToastMessage(
            messages.record_deleted_successfully.errorMessage,
            'success',
        );
    } catch {
        showToastMessage(messages.record_deleted_failed.errorMessage, 'error');
    } finally {
        closeWorkDeleteModal();
    }
};

const toggleAllWorkExperiences = (e: Event) => {
    const target = e.target as HTMLInputElement;
    selectedWorkExperiences.value = target.checked
        ? workExperiences.value.map((w) => w.id)
        : [];
};

const examResultLabel = (value: number | null | undefined) => {
    switch (Number(value)) {
        case 1:
            return 'Pending';
        case 2:
            return 'Passed';
        case 3:
            return 'Failed';
        default:
            return '-';
    }
};

const interviewResultLabel = (value: number | null | undefined) => {
    switch (Number(value)) {
        case 1:
            return 'Pending';
        case 2:
            return 'Passed';
        case 3:
            return 'Failed';
        default:
            return '-';
    }
};

const messages = {
    record_created_successfully: {
        errorMessage: 'Record created successfully.',
    },
    record_updated_successfully: {
        errorMessage: 'Record updated successfully.',
    },
    record_deleted_successfully: {
        errorMessage: 'Record successfully deleted.',
    },
    transaction_failed: {
        errorMessage:
            'An error occurred while creating the record. Please try again.',
    },
    update_failed: {
        errorMessage:
            'An error occurred while saving the record. Please try again.',
    },
    record_deleted_failed: {
        errorMessage:
            'An error occurred while deleting the record. Please try again.',
    },
};

const openAddSkillModal = () => {
    newSkillName.value = '';
    newSkillRemarks.value = '';
    addSkillNameError.value = null;
    addSkillRemarksError.value = null;
    addSkillModalVisible.value = true;
};

const closeAddSkillModal = () => {
    addSkillModalVisible.value = false;
    newSkillName.value = '';
    newSkillRemarks.value = '';
    addSkillNameError.value = null;
    addSkillRemarksError.value = null;
};

const saveNewSkill = async () => {
    addSkillNameError.value = null;
    addSkillRemarksError.value = null;

    if (!newSkillName.value.trim()) {
        addSkillNameError.value = 'This is a required field.';
        return;
    }

    try {
        await axios.post(
            `/intermediate/applicants/${applicant.value.id}/skills`,
            {
                skill: newSkillName.value.trim(),
                remarks: newSkillRemarks.value.trim() || null,
            },
        );

        await fetchSkills();
        closeAddSkillModal();
        showToastMessage(
            messages.record_created_successfully.errorMessage,
            'success',
        );
    } catch (error: any) {
        if (error.response?.data?.errors) {
            addSkillNameError.value =
                error.response.data.errors.skill?.[0] || null;
            addSkillRemarksError.value =
                error.response.data.errors.remarks?.[0] || null;
        } else {
            showToastMessage(messages.transaction_failed.errorMessage, 'error');
        }
    }
};

const openEditSkillModal = (skill: any) => {
    skillBeingEdited.value = { ...skill };
    editedSkillName.value = skill.skill;
    editedSkillRemarks.value = skill.remarks || '';
    editSkillNameError.value = null;
    editSkillRemarksError.value = null;
    editSkillModalVisible.value = true;
};

const closeEditSkillModal = () => {
    editSkillModalVisible.value = false;
    skillBeingEdited.value = null;
    editedSkillName.value = '';
    editedSkillRemarks.value = '';
    editSkillNameError.value = null;
    editSkillRemarksError.value = null;
};

const saveSkillEdit = async () => {
    if (!skillBeingEdited.value) return;

    editSkillNameError.value = null;
    editSkillRemarksError.value = null;

    if (!editedSkillName.value.trim()) {
        editSkillNameError.value = 'This is a required field.';
        return;
    }

    try {
        await axios.put(
            `/intermediate/applicants/${applicant.value.id}/skills/${skillBeingEdited.value.id}`,
            {
                skill: editedSkillName.value.trim(),
                remarks: editedSkillRemarks.value.trim() || null,
            },
        );

        await fetchSkills();
        closeEditSkillModal();
        showToastMessage(
            messages.record_updated_successfully.errorMessage,
            'success',
        );
    } catch (error: any) {
        if (error.response?.data?.errors) {
            editSkillNameError.value =
                error.response.data.errors.skill?.[0] || null;
            editSkillRemarksError.value =
                error.response.data.errors.remarks?.[0] || null;
        } else {
            showToastMessage(messages.update_failed.errorMessage, 'error');
        }
    }
};

const confirmDeleteSkill = (id: number) => {
    deleteSkillTargetId.value = id;
    isBulkDeleteSkillsModal.value = false;
    deleteSkillModalVisible.value = true;
};

const confirmBulkDeleteSkills = () => {
    if (!selectedSkills.value.length) return;
    isBulkDeleteSkillsModal.value = true;
    deleteSkillTargetId.value = null;
    deleteSkillModalVisible.value = true;
};

const closeSkillDeleteModal = () => {
    deleteSkillModalVisible.value = false;
    deleteSkillTargetId.value = null;
    isBulkDeleteSkillsModal.value = false;
};

const performSkillDelete = async () => {
    try {
        if (isBulkDeleteSkillsModal.value) {
            await axios.post(
                `/intermediate/applicants/${applicant.value.id}/skills/bulk-delete`,
                {
                    ids: selectedSkills.value,
                },
            );
            selectedSkills.value = [];
        } else if (deleteSkillTargetId.value !== null) {
            await axios.delete(
                `/intermediate/applicants/${applicant.value.id}/skills/${deleteSkillTargetId.value}`,
            );
        }

        await fetchSkills();
        showToastMessage(
            messages.record_deleted_successfully.errorMessage,
            'success',
        );
    } catch {
        showToastMessage(messages.record_deleted_failed.errorMessage, 'error');
    } finally {
        closeSkillDeleteModal();
    }
};

const toggleAllSkills = (e: Event) => {
    const target = e.target as HTMLInputElement;
    selectedSkills.value = target.checked ? skills.value.map((s) => s.id) : [];
};

const showToast = ref(false);
const toastMessage = ref<string | null>(null);
const toastType = ref<'success' | 'error'>('success');

const successMessage = computed(() => page.props.flash?.success);
const errorMessage = computed(() => page.props.flash?.error);

const closeToast = () => {
    showToast.value = false;
};

watch(
    successMessage,
    (val) => {
        if (val) {
            toastMessage.value = val;
            toastType.value = 'success';
            showToast.value = true;
            setTimeout(() => (showToast.value = false), 5000);
        }
    },
    { immediate: true },
);

watch(
    errorMessage,
    (val) => {
        if (val) {
            toastMessage.value = val;
            toastType.value = 'error';
            showToast.value = true;
            setTimeout(() => (showToast.value = false), 5000);
        }
    },
    { immediate: true },
);

const showToastMessage = (message: string, type: 'success' | 'error') => {
    toastMessage.value = message;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => (showToast.value = false), 5000);
};

const formatDateTime = (dateString: string | null) => {
    if (!dateString) return '-';
    const date = new Date(dateString);

    return date.toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true,
        timeZone: 'Asia/Manila',
    });
};

const sourceTypeLabel = (type: number | null | undefined) => {
    switch (Number(type)) {
        case 1:
            return 'SP (Service Provider)';
        case 2:
            return 'Recruitment Portals';
        case 3:
            return 'Employee Referral';
        case 4:
            return 'Walk-in';
        default:
            return '-';
    }
};

const sourceLabel = (
    sourceId: number | string | null | undefined,
    otherSource: string | null = null,
) => {
    const sources: Record<number, string> = {
        1: 'Mynimo',
        2: 'Indeed',
        3: 'Kalibrr',
        4: 'FoundIt',
        5: 'LinkedIn',
        6: 'Facebook',
        7: 'Jobstreet',
        8: 'AAISI',
        9: 'Primover',
        10: 'Pan Asia',
        11: 'Nityo',
        12: 'CPS',
    };

    const id = Number(sourceId);
    if (!Number.isNaN(id) && sources[id]) return sources[id];
    if (otherSource?.trim()) return otherSource;
    return '-';
};

const genderLabel = (gender: number | null | undefined) => {
    switch (Number(gender)) {
        case 1:
            return 'Male';
        case 2:
            return 'Female';
        default:
            return '-';
    }
};

const yesNoLabel = (value: number | null | undefined) => {
    if (value === 1) return 'Yes';
    if (value === 0) return 'No';
    return '-';
};

const paperScreeningLabel = (value: number | null | undefined) => {
    switch (Number(value)) {
        case 1:
            return 'Pending';
        case 2:
            return 'Passed';
        case 3:
            return 'Failed';
        default:
            return '-';
    }
};

const stageLabel = (value: number | null | undefined) => {
    switch (Number(value)) {
        case 1:
            return 'New';
        case 2:
            return 'For Exam';
        case 3:
            return 'For Initial Interview';
        case 4:
            return 'For Final Interview';
        case 5:
            return 'For Job Offer';
        default:
            return '-';
    }
};

const applicationStatusLabel = (value: number | null | undefined) => {
    switch (Number(value)) {
        case 1:
            return 'Pending';
        case 2:
            return 'Done';
        case 3:
            return 'Passed';
        case 4:
            return 'P2';
        case 5:
            return 'Failed';
        default:
            return '-';
    }
};

const jobOfferStatusLabel = (value: number | null | undefined) => {
    switch (Number(value)) {
        case 1:
            return 'Pending';
        case 2:
            return 'Done';
        case 3:
            return 'Accepted';
        case 4:
            return 'Declined';
        case 5:
            return 'Withdrawn';
        case 6:
            return 'Retracted';
        default:
            return '-';
    }
};

const applications = computed(() => applicant.value?.applications || []);
const canEdit = computed(() => ![5, 6].includes(userPermissions.value));
const canManageChildRecords = computed(
    () => ![5, 6].includes(userPermissions.value),
);

const getPaperScreeningBadgeClass = (value: number | null | undefined) => {
    switch (Number(value)) {
        case 1: // Pending
            return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300';

        case 2: // Passed
            return 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300';

        case 3: // Failed
            return 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300';

        default:
            return 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300';
    }
};

const getResultBadgeClass = (value: number | null | undefined) => {
    switch (Number(value)) {
        case 1: // Pending
            return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300';

        case 2: // Done / For deliberation
            return 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300';

        case 3: // Passed
            return 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300';

        case 4: // P2
            return 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300';

        case 5: // Failed
            return 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300';

        default:
            return 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300';
    }
};

const getJobOfferStatusClass = (value: number | null | undefined) => {
    switch (Number(value)) {
        case 1: // Pending
            return 'bg-yellow-100 text-yellow-700 border-yellow-300';

        case 2: // Done
            return 'bg-blue-100 text-blue-700 border-blue-300';

        case 3: // Accepted
            return 'bg-green-100 text-green-700 border-green-300';

        case 4: // Declined
        case 5: // Withdrawn
        case 6: // Retracted
            return 'bg-red-100 text-red-700 border-red-300';

        default:
            return 'bg-gray-100 text-gray-700 border-gray-300';
    }
};

const fetchSkills = async () => {
    try {
        const res = await axios.get(
            `/intermediate/applicants/${applicant.value.id}/skills`,
        );
        skills.value = res.data;
    } catch {
        // keep server-provided props if route does not exist yet
    }
};

const japaneseBackgrounds = computed(
    () => page.props.japaneseBackgrounds || {},
);

const japaneseLevels = computed(() => page.props.japaneseLevels || {});

const getJapaneseBackgroundLabel = (id: number | null | undefined) => {
    if (id == null) return 'N/A';
    return japaneseBackgrounds.value[id] ?? 'N/A';
};

const getJapaneseLevelLabel = (id: number | null | undefined) => {
    if (id == null) return 'N/A';
    return japaneseLevels.value[id] ?? 'N/A';
};

onMounted(() => {
    fetchSkills();
    fetchWorkExperiences();
});
</script>

<template>
    <Head title="INTERMEDIATE Applicant Detail" />

    <div v-if="addWorkModalVisible" class="modal-overlay">
        <div class="modal-content modal-large">
            <h3 class="modal-title">Add Work Experience</h3>

            <div class="modal-grid">
                <div class="modal-field">
                    <label class="font-semibold">Employer *</label>
                    <input v-model="newWork.employer" class="modal-input" />
                    <span v-if="workErrors.employer" class="modal-error">{{
                        workErrors.employer
                    }}</span>
                </div>

                <div class="modal-field">
                    <label>Company Address</label>
                    <input
                        v-model="newWork.company_address"
                        class="modal-input"
                    />
                    <span
                        v-if="workErrors.company_address"
                        class="modal-error"
                        >{{ workErrors.company_address }}</span
                    >
                </div>

                <div class="modal-field">
                    <label class="font-semibold">Job Title *</label>
                    <input v-model="newWork.job_title" class="modal-input" />
                    <span v-if="workErrors.job_title" class="modal-error">{{
                        workErrors.job_title
                    }}</span>
                </div>

                <div class="modal-field">
                    <label>Dates Employed</label>
                    <input
                        v-model="newWork.date_employed"
                        class="modal-input"
                        placeholder="(e.g. August 2018 - March 2020)"
                    />
                    <span v-if="workErrors.date_employed" class="modal-error">{{
                        workErrors.date_employed
                    }}</span>
                </div>

                <div class="modal-field">
                    <label>Salary</label>
                    <input v-model="newWork.salary" class="modal-input" />
                    <span v-if="workErrors.salary" class="modal-error">{{
                        workErrors.salary
                    }}</span>
                </div>

                <div class="modal-field">
                    <label>Supervisor</label>
                    <input
                        v-model="newWork.name_supervisor"
                        class="modal-input"
                        placeholder="(e.g Juan Dela Cruz - 0916XXXXX)"
                    />
                    <span
                        v-if="workErrors.name_supervisor"
                        class="modal-error"
                        >{{ workErrors.name_supervisor }}</span
                    >
                </div>
            </div>

            <div class="modal-field">
                <label>Work Description</label>
                <textarea
                    v-model="newWork.work_description"
                    class="modal-textarea"
                ></textarea>
                <span v-if="workErrors.work_description" class="modal-error">{{
                    workErrors.work_description
                }}</span>
            </div>

            <div class="modal-field">
                <label>Reason for Leaving</label>
                <textarea
                    v-model="newWork.reason_for_leaving"
                    class="modal-textarea"
                ></textarea>
                <span
                    v-if="workErrors.reason_for_leaving"
                    class="modal-error"
                    >{{ workErrors.reason_for_leaving }}</span
                >
            </div>

            <div class="modal-field">
                <label>Remarks</label>
                <textarea
                    v-model="newWork.remarks"
                    class="modal-textarea"
                ></textarea>
                <span v-if="workErrors.remarks" class="modal-error">{{
                    workErrors.remarks
                }}</span>
            </div>

            <div class="modal-actions">
                <button class="btn-red" @click="closeAddWorkModal">
                    Cancel
                </button>
                <button class="btn-primary" @click="saveNewWork">Add</button>
            </div>
        </div>
    </div>

    <div v-if="editWorkModalVisible" class="modal-overlay">
        <div class="modal-content modal-large">
            <h3 class="modal-title">Edit Work Experience</h3>

            <div class="modal-grid">
                <div class="modal-field">
                    <label>Employer *</label>
                    <input v-model="editedWork.employer" class="modal-input" />
                    <span v-if="editWorkErrors.employer" class="modal-error">{{
                        editWorkErrors.employer
                    }}</span>
                </div>

                <div class="modal-field">
                    <label>Company Address</label>
                    <input
                        v-model="editedWork.company_address"
                        class="modal-input"
                    />
                    <span
                        v-if="editWorkErrors.company_address"
                        class="modal-error"
                        >{{ editWorkErrors.company_address }}</span
                    >
                </div>

                <div class="modal-field">
                    <label>Job Title</label>
                    <input v-model="editedWork.job_title" class="modal-input" />
                    <span v-if="editWorkErrors.job_title" class="modal-error">{{
                        editWorkErrors.job_title
                    }}</span>
                </div>

                <div class="modal-field">
                    <label>Date Employed</label>
                    <input
                        v-model="editedWork.date_employed"
                        class="modal-input"
                    />
                    <span
                        v-if="editWorkErrors.date_employed"
                        class="modal-error"
                        >{{ editWorkErrors.date_employed }}</span
                    >
                </div>

                <div class="modal-field">
                    <label>Salary</label>
                    <input v-model="editedWork.salary" class="modal-input" />
                    <span v-if="editWorkErrors.salary" class="modal-error">{{
                        editWorkErrors.salary
                    }}</span>
                </div>

                <div class="modal-field">
                    <label>Supervisor</label>
                    <input
                        v-model="editedWork.name_supervisor"
                        class="modal-input"
                    />
                    <span
                        v-if="editWorkErrors.name_supervisor"
                        class="modal-error"
                        >{{ editWorkErrors.name_supervisor }}</span
                    >
                </div>
            </div>

            <div class="modal-field">
                <label>Work Description</label>
                <textarea
                    v-model="editedWork.work_description"
                    class="modal-textarea"
                ></textarea>
                <span
                    v-if="editWorkErrors.work_description"
                    class="modal-error"
                    >{{ editWorkErrors.work_description }}</span
                >
            </div>

            <div class="modal-field">
                <label>Reason for Leaving</label>
                <textarea
                    v-model="editedWork.reason_for_leaving"
                    class="modal-textarea"
                ></textarea>
                <span
                    v-if="editWorkErrors.reason_for_leaving"
                    class="modal-error"
                    >{{ editWorkErrors.reason_for_leaving }}</span
                >
            </div>

            <div class="modal-field">
                <label>Remarks</label>
                <textarea
                    v-model="editedWork.remarks"
                    class="modal-textarea"
                ></textarea>
                <span v-if="editWorkErrors.remarks" class="modal-error">{{
                    editWorkErrors.remarks
                }}</span>
            </div>

            <div class="modal-actions">
                <button class="btn-red" @click="closeEditWorkModal">
                    Cancel
                </button>
                <button class="btn-primary" @click="saveWorkEdit">Save</button>
            </div>
        </div>
    </div>

    <div v-if="deleteWorkModalVisible" class="modal-overlay">
        <div class="modal-content">
            <h3 class="modal-title text-red-500">Confirm Delete</h3>

            <p class="mb-4 text-center">
                Are you sure you want to delete
                <strong>
                    {{
                        isBulkDeleteWorkModal
                            ? selectedWorkExperiences.length +
                              ' selected work experience(s)'
                            : 'this work experience'
                    }} </strong
                >?
            </p>

            <div class="modal-actions">
                <button class="btn-red" @click="closeWorkDeleteModal">
                    Cancel
                </button>
                <button class="btn-primary" @click="performWorkDelete">
                    Delete
                </button>
            </div>
        </div>
    </div>
    <!-- Add Skill Modal -->
    <div v-if="addSkillModalVisible" class="modal-overlay">
        <div class="modal-content">
            <h3 class="modal-title">Add Skill</h3>

            <div class="modal-field">
                <label style="font-weight: bold">Skill Name *</label>
                <input
                    v-model="newSkillName"
                    class="modal-input"
                    placeholder="Skill"
                />
                <span v-if="addSkillNameError" class="modal-error">{{
                    addSkillNameError
                }}</span>
            </div>

            <div class="modal-field">
                <label>Remarks</label>
                <textarea
                    v-model="newSkillRemarks"
                    class="modal-textarea"
                    placeholder="Remarks (optional)"
                ></textarea>
                <span v-if="addSkillRemarksError" class="modal-error">{{
                    addSkillRemarksError
                }}</span>
            </div>

            <div class="modal-actions">
                <button class="btn-red" @click="closeAddSkillModal">
                    Cancel
                </button>
                <button class="btn-primary" @click="saveNewSkill">Add</button>
            </div>
        </div>
    </div>

    <!-- Edit Skill Modal -->
    <div v-if="editSkillModalVisible" class="modal-overlay">
        <div class="modal-content">
            <h3 class="modal-title">Edit Skill</h3>

            <div class="modal-field">
                <label style="font-weight: bold">Skill Name *</label>
                <input
                    v-model="editedSkillName"
                    class="modal-input"
                    placeholder="Skill"
                />
                <span v-if="editSkillNameError" class="modal-error">{{
                    editSkillNameError
                }}</span>
            </div>

            <div class="modal-field">
                <label>Remarks</label>
                <textarea
                    v-model="editedSkillRemarks"
                    class="modal-textarea"
                    placeholder="Remarks (optional)"
                ></textarea>
                <span v-if="editSkillRemarksError" class="modal-error">{{
                    editSkillRemarksError
                }}</span>
            </div>

            <div class="modal-actions">
                <button class="btn-red" @click="closeEditSkillModal">
                    Cancel
                </button>
                <button class="btn-primary" @click="saveSkillEdit">Save</button>
            </div>
        </div>
    </div>

    <!-- Delete Skill Modal -->
    <div v-if="deleteSkillModalVisible" class="modal-overlay">
        <div class="modal-content">
            <h3 class="modal-title text-red-500">Confirm Delete</h3>

            <p class="mb-4 text-center">
                Are you sure you want to delete
                <strong>
                    {{
                        isBulkDeleteSkillsModal
                            ? selectedSkills.length + ' selected skill(s)'
                            : 'this skill'
                    }} </strong
                >?
            </p>

            <div class="modal-actions">
                <button class="btn-red" @click="closeSkillDeleteModal">
                    Cancel
                </button>
                <button class="btn-primary" @click="performSkillDelete">
                    Delete
                </button>
            </div>
        </div>
    </div>
    <AppLayout>
        <div v-if="showToast" class="full-width-alert">
            <div
                :class="[
                    'alert-banner',
                    toastType === 'success'
                        ? 'alert-success-banner'
                        : 'alert-error-banner',
                ]"
            >
                <div class="alert-body">{{ toastMessage }}</div>
                <button type="button" class="close-btn" @click="closeToast">
                    ×
                </button>
            </div>
        </div>

        <div class="mx-5 mb-3 flex justify-between">
            <h2 class="text-xl font-bold">INTERMEDIATE Applicant's Details</h2>
            <Link
                v-if="canEdit"
                :href="`/intermediate/applicants/${applicant.id}/edit`"
                class="btn-primary !bg-[#1C7BA5]"
            >
                Edit INTERMEDIATE Applicant
            </Link>
        </div>

        <div class="mx-5 mt-6 grid grid-cols-3 gap-6">
            <div class="col-span-1 space-y-4">
                <div
                    v-if="
                        applicant.last_name ||
                        applicant.first_name ||
                        applicant.middle_name
                    "
                    class="rounded-xl bg-[#2F359E] p-6 text-white shadow"
                >
                    <h3 class="text-center text-lg font-bold">
                        {{ applicant.last_name }}, {{ applicant.first_name }}
                        {{ applicant.middle_name }}
                    </h3>
                    <p class="text-center text-xs opacity-80">Full Name</p>
                </div>

                <div class="rounded-xl border bg-white p-4 shadow">
                    <table class="min-w-full table-auto text-xs">
                        <tbody>
                            <tr>
                                <th
                                    class="w-40 px-2 py-2 text-right font-semibold text-gray-600"
                                >
                                    Email
                                </th>
                                <td class="px-2">
                                    {{ applicant.email_address || '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th
                                    class="px-2 py-2 text-right font-semibold text-gray-600"
                                >
                                    Contact No.
                                </th>
                                <td class="px-2">
                                    {{ applicant.contact_no || '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th
                                    class="px-2 py-2 text-right font-semibold text-gray-600"
                                >
                                    Gender
                                </th>
                                <td class="px-2">
                                    {{ genderLabel(applicant.gender) }}
                                </td>
                            </tr>
                            <tr>
                                <th
                                    class="px-2 py-2 text-right font-semibold text-gray-600"
                                >
                                    Birthdate
                                </th>
                                <td class="px-2">
                                    {{ applicant.birthdate || '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th
                                    class="px-2 py-2 text-right font-semibold text-gray-600"
                                >
                                    Age
                                </th>
                                <td class="px-2">{{ applicant.age || '-' }}</td>
                            </tr>
                            <tr>
                                <th
                                    class="px-2 py-2 text-right font-semibold text-gray-600"
                                >
                                    School Graduated From
                                </th>
                                <td class="px-2">
                                    {{ applicant.school_graduated_from || '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th
                                    class="px-2 py-2 text-right font-semibold text-gray-600"
                                >
                                    Course
                                </th>
                                <td class="px-2">
                                    {{ applicant.course || '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th
                                    class="px-2 py-2 text-right font-semibold text-gray-600"
                                >
                                    Year Attended
                                </th>
                                <td class="px-2">
                                    {{ applicant.year_attended || '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th
                                    class="px-2 py-2 text-right font-semibold text-gray-600"
                                >
                                    Other Degrees/Schools
                                </th>
                                <td class="px-2">
                                    {{ applicant.others || '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="rounded-xl border bg-white p-4 shadow">
                    <h4 class="mb-3 text-xs font-bold">SOURCE INFORMATION</h4>
                    <p class="text-xs leading-6 break-words">
                        <strong>Registered Date:</strong>
                        {{ formatDateTime(applicant.registered_date) }}<br />
                        <strong>Source Type:</strong>
                        {{ sourceTypeLabel(applicant.source_type) }}<br />
                        <strong>Source:</strong>
                        {{
                            sourceLabel(
                                applicant.source,
                                applicant.other_source,
                            )
                        }}
                    </p>
                </div>
            </div>

            <div
                class="col-span-2 space-y-5 rounded-xl border bg-white p-6 shadow"
            >
                <div>
                    <h4 class="mb-2 text-left text-xs font-bold">ADDRESS</h4>
                    <p class="text-xs break-words">
                        {{ applicant.address || '-' }}
                    </p>
                </div>

                <div>
                    <h4 class="mb-2 text-left text-xs font-bold">
                        SPOUSE DETAILS
                    </h4>
                    <p class="text-xs break-words">
                        {{ applicant.spouse_details || '-' }}
                    </p>
                </div>

                <div>
                    <h4 class="mb-2 text-left text-xs font-bold">
                        NUMBER OF CHILDREN
                    </h4>
                    <p class="text-xs break-words">
                        {{ applicant.children ?? '-' }}
                    </p>
                </div>

                <div>
                    <h4 class="mb-2 text-left text-xs font-bold">
                        FATHER DETAILS
                    </h4>
                    <p class="text-xs break-words">
                        {{ applicant.father_details || '-' }}
                    </p>
                </div>

                <div>
                    <h4 class="mb-2 text-left text-xs font-bold">
                        MOTHER DETAILS
                    </h4>
                    <p class="text-xs break-words">
                        {{ applicant.mother_details || '-' }}
                    </p>
                </div>

                <div>
                    <h4 class="mb-2 text-left text-xs font-bold">
                        SIBLING DETAILS
                    </h4>
                    <p class="text-xs break-words">
                        {{ applicant.sibling_details || '-' }}
                    </p>
                </div>

                <div>
                    <h4 class="mb-2 text-left text-xs font-bold">
                        EMERGENCY CONTACT
                    </h4>
                    <p class="text-xs leading-6 break-words">
                        <strong>Name:</strong>
                        {{ applicant.emergency_contact_name || '-' }}<br />
                        <strong>Number:</strong>
                        {{ applicant.emergency_contact_number || '-' }}<br />
                        <strong>Address:</strong>
                        {{ applicant.emergency_contact_address || '-' }}
                    </p>
                </div>

                <div>
                    <h4 class="mb-2 text-left text-xs font-bold">REMARKS</h4>
                    <p class="text-xs break-words">
                        {{ applicant.remarks || '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mx-5 mt-6 rounded-xl border bg-white p-6 shadow">
            <h3 class="mb-4 text-lg font-semibold">
                Japanese Language Background
            </h3>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-500">
                        Japanese Language Background
                    </p>
                    <p class="font-medium">
                        {{
                            getJapaneseBackgroundLabel(
                                applicant.japanese_background,
                            )
                        }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Japanese Language Background Remarks
                    </p>
                    <p class="font-medium">
                        {{ applicant.background_remarks ?? 'N/A' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">JLPT Level</p>
                    <p class="font-medium whitespace-pre-line">
                        {{ getJapaneseLevelLabel(applicant.japanese_level) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mx-5 mt-6 rounded-xl border bg-white p-6 shadow">
            <h3 class="mb-4 text-lg font-semibold">Application Details</h3>

            <table class="w-full table-fixed border-collapse border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            No.
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Exam Result
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Exam Status
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Exam Remarks
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Initial Interview Result
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Initial Interview Status
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Initial Interview Remarks
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Final Interview Result
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Final Interview Status
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Final Interview Remarks
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Job Offer Status
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Job Offer Remarks
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Remarks
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(app, index) in applications" :key="app.id">
                        <td
                            class="px-2 py-2 text-center font-medium text-gray-700"
                        >
                            {{ index + 1 }}
                        </td>

                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getPaperScreeningBadgeClass(app.exam_result)
                                "
                            >
                                {{ examResultLabel(app.exam_result) }}
                            </span>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getResultBadgeClass(
                                        app.exam_application_status,
                                    )
                                "
                            >
                                {{
                                    applicationStatusLabel(
                                        app.exam_application_status,
                                    )
                                }}
                            </span>
                        </td>

                        <td class="max-w-[220px] px-2 py-2 text-center">
                            <div
                                class="mx-auto w-full truncate"
                                :title="
                                    canSeeRestrictedRemarks
                                        ? app.exam_remarks || '-'
                                        : 'Restricted'
                                "
                            >
                                {{
                                    canSeeRestrictedRemarks
                                        ? app.exam_remarks || '-'
                                        : '-'
                                }}
                            </div>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getPaperScreeningBadgeClass(
                                        app.initial_interview_result,
                                    )
                                "
                            >
                                {{
                                    interviewResultLabel(
                                        app.initial_interview_result,
                                    )
                                }}
                            </span>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getResultBadgeClass(
                                        app.initial_interview_application_status,
                                    )
                                "
                            >
                                {{
                                    applicationStatusLabel(
                                        app.initial_interview_application_status,
                                    )
                                }}
                            </span>
                        </td>

                        <td class="max-w-[220px] px-2 py-2 text-center">
                            <div
                                class="mx-auto w-full truncate"
                                :title="
                                    canSeeRestrictedRemarks
                                        ? app.initial_interview_remarks || '-'
                                        : 'Restricted'
                                "
                            >
                                {{
                                    canSeeRestrictedRemarks
                                        ? app.initial_interview_remarks || '-'
                                        : '-'
                                }}
                            </div>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getPaperScreeningBadgeClass(
                                        app.final_interview_result,
                                    )
                                "
                            >
                                {{
                                    interviewResultLabel(
                                        app.final_interview_result,
                                    )
                                }}
                            </span>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getResultBadgeClass(
                                        app.final_interview_application_status,
                                    )
                                "
                            >
                                {{
                                    applicationStatusLabel(
                                        app.final_interview_application_status,
                                    )
                                }}
                            </span>
                        </td>

                        <td class="max-w-[220px] px-2 py-2 text-center">
                            <div
                                class="mx-auto w-full truncate"
                                :title="
                                    canSeeRestrictedRemarks
                                        ? app.final_interview_remarks || '-'
                                        : 'Restricted'
                                "
                            >
                                {{
                                    canSeeRestrictedRemarks
                                        ? app.final_interview_remarks || '-'
                                        : '-'
                                }}
                            </div>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getJobOfferStatusClass(app.job_offer_status)
                                "
                            >
                                {{ jobOfferStatusLabel(app.job_offer_status) }}
                            </span>
                        </td>

                        <td class="max-w-[220px] px-2 py-2 text-center">
                            <div
                                class="mx-auto w-full truncate"
                                :title="
                                    canSeeRestrictedRemarks
                                        ? app.job_offer_remarks || '-'
                                        : 'Restricted'
                                "
                            >
                                {{
                                    canSeeRestrictedRemarks
                                        ? app.job_offer_remarks || '-'
                                        : '-'
                                }}
                            </div>
                        </td>

                        <td class="max-w-[220px] px-2 py-2 text-center">
                            <div
                                class="mx-auto w-full truncate"
                                :title="app.remarks || '-'"
                            >
                                {{ app.remarks || '-' }}
                            </div>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <Link
                                v-if="app.id"
                                :href="`/intermediate/applications/${app.id}`"
                                class="inline-flex justify-center"
                            >
                                <Eye
                                    class="h-5 w-5 text-green-500 hover:text-green-600"
                                />
                            </Link>
                            <span v-else>-</span>
                        </td>
                    </tr>

                    <tr v-if="!applications.length">
                        <td
                            colspan="14"
                            class="py-4 text-center text-gray-500 italic"
                        >
                            No application records found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            class="mx-5 mt-6 rounded-xl border bg-white p-6 shadow"
            v-if="![5, 6].includes(userPermissions)"
        >
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Skills</h3>

                <div class="flex gap-2">
                    <button
                        @click="openAddSkillModal"
                        class="btn-primary btn-small"
                    >
                        Add
                    </button>
                    <button
                        @click="confirmBulkDeleteSkills"
                        class="btn-red btn-small"
                        :disabled="!selectedSkills.length"
                    >
                        Delete Selected
                    </button>
                </div>
            </div>

            <table class="unified-table table-fixed">
                <thead>
                    <tr>
                        <th class="col-checkbox">
                            <input type="checkbox" @change="toggleAllSkills" />
                        </th>
                        <th class="col-main">Skill</th>
                        <th class="col-remarks">Remarks</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="skill in skills" :key="skill.id">
                        <td class="col-checkbox">
                            <input
                                type="checkbox"
                                :value="skill.id"
                                v-model="selectedSkills"
                            />
                        </td>

                        <td>{{ skill.skill }}</td>
                        <td>{{ skill.remarks || '-' }}</td>

                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">
                                <Pen
                                    class="h-5 w-5 cursor-pointer text-blue-500"
                                    @click="openEditSkillModal(skill)"
                                />
                                <Trash
                                    class="h-5 w-5 cursor-pointer text-red-500"
                                    @click="confirmDeleteSkill(skill.id)"
                                />
                            </div>
                        </td>
                    </tr>

                    <tr v-if="!skills.length">
                        <td colspan="4" class="unified-empty">
                            No skills found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            class="mx-5 mt-6 rounded-xl border bg-white p-6 shadow"
            v-if="![5, 6].includes(userPermissions)"
        >
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold">Work Experiences</h3>

                <div class="flex gap-2">
                    <button
                        @click="openAddWorkModal"
                        class="btn-primary btn-small"
                    >
                        Add
                    </button>
                    <button
                        @click="confirmBulkDeleteWork"
                        class="btn-red btn-small"
                        :disabled="!selectedWorkExperiences.length"
                    >
                        Delete Selected
                    </button>
                </div>
            </div>

            <table class="unified-table">
                <thead>
                    <tr>
                        <th class="col-checkbox">
                            <input
                                type="checkbox"
                                @change="toggleAllWorkExperiences"
                            />
                        </th>
                        <th>Employer</th>
                        <th>Job Title</th>
                        <th>Salary</th>
                        <th>Dates Employed</th>
                        <th class="col-actions text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <template v-for="work in workExperiences" :key="work.id">
                        <!-- MAIN ROW -->
                        <tr
                            @click="work.expanded = !work.expanded"
                            class="expandable-row cursor-pointer"
                        >
                            <td class="col-checkbox">
                                <input
                                    type="checkbox"
                                    :value="work.id"
                                    v-model="selectedWorkExperiences"
                                    @click.stop
                                />
                            </td>

                            <td>
                                {{ work.employer || '-' }}
                                <span class="expand-hint">
                                    {{
                                        work.expanded
                                            ? 'Click row to collapse'
                                            : 'Click row to expand'
                                    }}</span
                                >
                            </td>
                            <td>{{ work.job_title || '-' }}</td>
                            <td>{{ work.salary || '-' }}</td>
                            <td>{{ work.date_employed || '-' }}</td>

                            <td class="text-center">
                                <div class="flex justify-center gap-2">
                                    <Pen
                                        class="h-5 w-5 cursor-pointer text-blue-500"
                                        @click.stop="openEditWorkModal(work)"
                                    />
                                    <Trash
                                        class="h-5 w-5 cursor-pointer text-red-500"
                                        @click.stop="confirmDeleteWork(work.id)"
                                    />
                                </div>
                            </td>
                        </tr>

                        <!-- EXPANDED DETAILS ROW -->
                        <tr v-if="work.expanded">
                            <td colspan="6" class="expanded-row">
                                <div class="expanded-grid-3col">
                                    <!-- row 1 -->

                                    <div class="long-text">
                                        <span class="label"
                                            >Work Description:</span
                                        >
                                        <span>{{
                                            work.work_description || '-'
                                        }}</span>
                                    </div>

                                    <div>
                                        <span class="label">Supervisor:</span>
                                        <span>{{
                                            work.name_supervisor || '-'
                                        }}</span>
                                    </div>

                                    <div class="long-text">
                                        <span class="label">Remarks:</span>
                                        <span>{{ work.remarks || '-' }}</span>
                                    </div>

                                    <!-- row 2 -->
                                    <div>
                                        <span class="label">Address:</span>
                                        <span>{{
                                            work.company_address || '-'
                                        }}</span>
                                    </div>

                                    <div class="long-text">
                                        <span class="label"
                                            >Reason for Leaving:</span
                                        >
                                        <span>{{
                                            work.reason_for_leaving || '-'
                                        }}</span>
                                    </div>

                                    <div>
                                        <span class="label" hidden>—</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr v-if="!workExperiences.length">
                        <td colspan="5" class="unified-empty">
                            No work experiences found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

<style scoped>
.unified-table input[type='checkbox'] {
    margin: 0;
    vertical-align: middle;
}

.unified-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.75rem;
    table-layout: fixed;
}

.unified-table td,
.unified-table th {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.unified-table thead {
    background-color: #f3f4f6;
}

.unified-table th {
    text-align: left;
    padding: 0.75rem 1rem;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #e5e7eb;
}

.unified-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}

.unified-table tr:hover {
    background-color: #f9fafb;
}

.col-main {
    width: 40%;
}

.col-remarks {
    width: 60%;
}

.unified-empty {
    text-align: center;
    color: #6b7280;
    font-style: italic;
    padding: 1rem;
    background-color: #fafafa;
}

.btn-primary {
    background: var(--ats-primary);
    color: #ffffff;
    padding: 0.55rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.15s ease;
}

.btn-primary:hover {
    background: var(--ats-accent);
}

.col-checkbox {
    width: 40px;
    text-align: center;
    overflow: visible !important;
    text-overflow: unset !important;
    white-space: normal !important;
}

.col-actions {
    width: 100px;
    text-align: center;
}

.btn-small {
    padding: 0.35rem 0.7rem;
    font-size: 0.75rem;
}

.btn-red {
    background-color: #e53e3e;
    color: white;
    padding: 0.45rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
}

.btn-red:hover {
    background-color: #c53030;
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
}

.modal-content {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    width: 450px;
    max-width: 95%;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.modal-title {
    text-align: center;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.modal-field {
    display: flex;
    flex-direction: column;
    margin-bottom: 0.75rem;
}

.modal-input,
.modal-textarea {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.95rem;
    box-sizing: border-box;
}

.modal-error {
    color: #e53e3e;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    min-height: 1rem;
}

.modal-actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
}

.modal-large {
    width: 800px;
    max-width: 95%;
}

.modal-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

@media (max-width: 768px) {
    .modal-grid {
        grid-template-columns: 1fr;
    }
}

.expanded-row {
    background-color: #f9fafb;
    padding: 1rem;
}

.expanded-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    font-size: 0.75rem;
    line-height: 1.4;
}

.expanded-grid strong {
    font-size: 0.7rem;
    color: #6b7280;
}

.expandable-row {
    position: relative;
}

.expand-hint {
    display: inline-block;
    margin-top: 0.35rem;
    font-size: 0.65rem;
    color: #6b7280;
    opacity: 0;
    transition: opacity 0.15s ease;
    pointer-events: none;
    white-space: nowrap;
}

.expandable-row:hover .expand-hint {
    opacity: 1;
}

.expand-hint {
    margin-left: 25px; /* adjust as needed */
}

.expanded-grid-3col {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem 1rem;
    font-size: 0.75rem;
}

.expanded-grid-3col div {
    display: flex;
    gap: 0.25rem;
    align-items: flex-start;
    line-height: 1.3;
    min-width: 0;
}

.expanded-grid-3col .label {
    font-weight: 600;
    color: #6b7280;
    min-width: 90px;
    flex-shrink: 0;
}

.expanded-grid-3col span:last-child {
    word-break: break-word;
}

.expanded-row,
.expanded-row * {
    white-space: normal !important;
    overflow: visible !important;
    text-overflow: unset !important;
}

.expanded-grid-3col {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem 1rem;
    font-size: 0.75rem;
}

.expanded-grid-3col div {
    min-width: 0;
}

.expanded-grid-3col .label {
    font-weight: 600;
    color: #6b7280;
    margin-right: 0.25rem;
}

.expanded-grid-3col .full-width {
    grid-column: 1 / -1;
}

@media (max-width: 1024px) {
    .expanded-grid-3col {
        grid-template-columns: 1fr;
    }
}
</style>
```
