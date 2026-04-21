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
        addSkillNameError.value = 'Skill name is required';
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
        editSkillNameError.value = 'Skill name is required';
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
});
</script>

<template>
    <Head title="INTERMEDIATE Applicant Detail" />
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
                    <h4 class="mb-2 text-left text-xs font-bold">CHILDREN</h4>
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
                            Stage
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            FY Week
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Position
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Paper Screening
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Exam Status
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            HR Interview Status
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            BU Interview Status
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Final Interview Status
                        </th>
                        <th class="px-2 py-2 font-semibold text-gray-600">
                            Job Offer Status
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
                            {{ stageLabel(app.application_stage) }}
                        </td>
                        <td class="px-2 py-2 text-center">
                            {{ app.fy_week || '-' }}
                        </td>
                        <td class="px-2 py-2 text-center">
                            {{ app.position || '-' }}
                        </td>
                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getPaperScreeningBadgeClass(
                                        app.paper_screening_status,
                                    )
                                "
                            >
                                {{
                                    paperScreeningLabel(
                                        app.paper_screening_status,
                                    )
                                }}
                            </span>
                        </td>
                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="getResultBadgeClass(app.exam_status)"
                            >
                                {{ applicationStatusLabel(app.exam_status) }}
                            </span>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getResultBadgeClass(app.hr_interview_status)
                                "
                            >
                                {{
                                    applicationStatusLabel(
                                        app.hr_interview_status,
                                    )
                                }}
                            </span>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getResultBadgeClass(app.bu_interview_status)
                                "
                            >
                                {{
                                    applicationStatusLabel(
                                        app.bu_interview_status,
                                    )
                                }}
                            </span>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <span
                                class="rounded-full border px-2 py-1 text-xs"
                                :class="
                                    getResultBadgeClass(
                                        app.final_interview_status,
                                    )
                                "
                            >
                                {{
                                    applicationStatusLabel(
                                        app.final_interview_status,
                                    )
                                }}
                            </span>
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
                        <td class="max-w-[250px] px-2 py-2 text-center">
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
                            colspan="12"
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
</style>
```
