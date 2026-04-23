index application

<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const page = usePage<any>();

interface Application {
    id: number;
    first_name: string;
    last_name: string;
    project_name?: string;
    position?: string;
    application_stage: number;
    remarks?: string;
}

interface Props {
    applications: Application[];
    filters: { search: string };
    userPermissions: number;
    errorsConfig: Record<string, string>;
}

const props = defineProps<Props>();

// FLASH MESSAGES
const flashMessages = ref({ success: '', error: '', info: '' });

const hasFlash = computed(
    () =>
        !!flashMessages.value.success ||
        !!flashMessages.value.error ||
        !!flashMessages.value.info,
);

watch(
    () => page.props.flash as any,
    (flash) => {
        if (flash) {
            flashMessages.value = {
                success: flash?.success || '',
                error: flash?.error || '',
                info: flash?.info || '',
            };
        }
    },
    { immediate: true, deep: true },
);

const clearFlash = () =>
    (flashMessages.value = { success: '', error: '', info: '' });

const closeSuccess = () => (flashMessages.value.success = '');
const closeError = () => (flashMessages.value.error = '');

onMounted(() => {
    if (hasFlash.value) setTimeout(clearFlash, 10000);
});

// ------------------- IMPORT MODAL -------------------
const showImportModal = ref(false);
const importFile = ref<File | null>(null);
const processing = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);
const importError = ref('');

const openImportModal = () => {
    showImportModal.value = true;
    importFile.value = null;
    importError.value = '';
    if (fileInput.value) fileInput.value.value = '';
};

const closeImportModal = () => {
    showImportModal.value = false;
    importFile.value = null;
    importError.value = '';
    if (fileInput.value) fileInput.value.value = '';
};

const onImportFileChange = (event: Event) => {
    importError.value = '';
    const target = event.target as HTMLInputElement;
    if (!target.files?.length) return;

    const file = target.files[0];
    const maxSize = 10 * 1024 * 1024;

    if (file.size > maxSize) {
        flashMessages.value.error =
            props.errorsConfig?.file_too_large ||
            'File is too large (max 10MB)';
        target.value = '';
        importFile.value = null;
        return;
    }

    const allowedExtensions = ['xlsx', 'csv'];
    const fileExtension = file.name.split('.').pop()?.toLowerCase();

    if (!allowedExtensions.includes(fileExtension || '')) {
        flashMessages.value.error = 'Please upload only .xlsx or .csv files';
        target.value = '';
        importFile.value = null;
        return;
    }

    importFile.value = file;
};

const submitImport = () => {
    importError.value = '';

    if (!importFile.value) {
        importError.value =
            props.errorsConfig?.field_required ||
            'Please select a file to upload';
        return;
    }

    const formData = new FormData();
    formData.append('file', importFile.value);

    processing.value = true;

    router.post('/intermediate/applications/import', formData, {
        forceFormData: true,
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            closeImportModal();
            router.reload({ only: ['applications'] });
        },
        onError: (errors) => {
            if (errors?.file) {
                flashMessages.value.error = Array.isArray(errors.file)
                    ? errors.file[0]
                    : errors.file;
            }
            importFile.value = null;
            if (fileInput.value) fileInput.value.value = '';
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};

// ------------------- SEARCH & PAGINATION -------------------
const searchQuery = ref(props.filters.search || '');
const currentPage = ref(1);

const perPage = 20;
const blockSize = 5;

// RESET PAGE ON SEARCH (IMPORTANT)
watch(searchQuery, () => {
    currentPage.value = 1;
});

const filteredApplications = computed(() => {
    const q = searchQuery.value.toLowerCase().trim();
    if (!q) return props.applications;

    return props.applications.filter((app) => {
        const fullName = `${app.first_name} ${app.last_name}`.toLowerCase();

        // Convert stage number → label for searching
        const stageLabel = getStageLabel(app.application_stage).toLowerCase();

        return (
            fullName.includes(q) ||
            String(app.id).includes(q) ||
            (app.position?.toLowerCase().includes(q) ?? false) ||
            (app.project_name?.toLowerCase().includes(q) ?? false) ||
            (app.remarks?.toLowerCase().includes(q) ?? false) ||
            stageLabel.includes(q) // ✅ ADDED THIS
        );
    });
});

const totalPages = computed(() =>
    Math.ceil(filteredApplications.value.length / perPage),
);

const paginatedApplications = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    return filteredApplications.value.slice(start, start + perPage);
});

// BLOCK PAGINATION (5 pages per block)
const currentBlock = computed(() => Math.ceil(currentPage.value / blockSize));

const startPage = computed(() => (currentBlock.value - 1) * blockSize + 1);

const endPage = computed(() =>
    Math.min(startPage.value + blockSize - 1, totalPages.value),
);

const pageNumbers = computed(() => {
    const pages: number[] = [];
    for (let i = startPage.value; i <= endPage.value; i++) {
        pages.push(i);
    }
    return pages;
});

function goToPage(page: number) {
    currentPage.value = Math.max(1, Math.min(page, totalPages.value));
}

function prevBlock() {
    if (startPage.value > 1) {
        goToPage(startPage.value - 1);
    }
}

function nextBlock() {
    if (endPage.value < totalPages.value) {
        goToPage(endPage.value + 1);
    }
}

const showingFrom = computed(() =>
    filteredApplications.value.length === 0
        ? 0
        : (currentPage.value - 1) * perPage + 1,
);

const showingTo = computed(() =>
    Math.min(currentPage.value * perPage, filteredApplications.value.length),
);

const getStageLabel = (stage: number) => {
    const labels: Record<number, string> = {
        1: 'New',
        2: 'For Exam',
        3: 'For Initial Interview',
        4: 'For Final Interview',
        5: 'For Job Offer',
        6: 'Failed',
    };
    return labels[stage] || 'Unknown';
};

const canCreateOrImport = computed(() => {
    return ![5, 6].includes(props.userPermissions);
});
</script>
<template>
    <AppLayout>
        <!-- TOASTS (MATCHED FRIEND STYLE) -->
        <div
            class="fixed top-4 right-4 z-50 flex w-full max-w-sm flex-col gap-2 sm:w-96"
        >
            <!-- SUCCESS -->
            <TransitionGroup name="toast" tag="div">
                <div
                    v-if="flashMessages.success"
                    key="success"
                    class="max-h-80 animate-in overflow-y-auto rounded-xl border border-green-400 bg-green-100 p-4 text-green-700 shadow-2xl backdrop-blur-sm duration-300 fade-in slide-in-from-top-2"
                >
                    <div class="flex items-start gap-3">
                        <svg
                            class="mt-0.5 h-5 w-5 flex-shrink-0"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div class="min-w-0 flex-1">
                            <pre
                                class="text-sm font-medium whitespace-pre-wrap"
                                >{{ flashMessages.success }}</pre
                            >
                        </div>
                        <button
                            @click="closeSuccess"
                            style="
                                all: unset;
                                cursor: pointer;
                                width: 28px;
                                height: 28px;
                                border-radius: 50%;
                                background: rgba(0, 0, 0, 0.3);
                                color: #fff;
                                font-weight: bold;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            "
                        >
                            X
                        </button>
                    </div>
                </div>
            </TransitionGroup>

            <!-- ERROR -->
            <TransitionGroup name="toast" tag="div">
                <div
                    v-if="flashMessages.error"
                    key="error"
                    class="max-h-80 animate-in overflow-y-auto rounded-xl border border-red-400 bg-red-100 p-4 text-red-700 shadow-2xl backdrop-blur-sm duration-300 fade-in slide-in-from-top-2"
                >
                    <div class="flex items-start gap-3">
                        <svg
                            class="mt-0.5 h-5 w-5 flex-shrink-0"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div class="min-w-0 flex-1">
                            <pre
                                class="text-sm font-medium whitespace-pre-wrap"
                                >{{ flashMessages.error }}</pre
                            >
                        </div>
                        <button
                            @click="closeError"
                            style="
                                all: unset;
                                cursor: pointer;
                                width: 28px;
                                height: 28px;
                                border-radius: 50%;
                                background: rgba(0, 0, 0, 0.3);
                                color: #fff;
                                font-weight: bold;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            "
                        >
                            X
                        </button>
                    </div>
                </div>
            </TransitionGroup>
        </div>

        <!-- IMPORT MODAL -->
        <transition name="fade">
            <div
                v-if="showImportModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
            >
                <div class="w-96 rounded-xl bg-white p-6 shadow-lg">
                    <h3 class="mb-4 text-lg font-semibold">
                        Upload Applications from Google Forms
                    </h3>
                    <!-- File Input -->
                    <label class="mt-6 mb-1 block text-sm font-medium"
                        >Choose File (.xlsx or .csv)</label
                    >
                    <input
                        ref="fileInput"
                        type="file"
                        accept=".xlsx,.csv"
                        @change="onImportFileChange"
                        class="file-input-btn mb-1 w-full"
                    />
                    <p v-if="importError" class="mt-1 text-xs text-red-600">
                        {{ importError }}
                    </p>

                    <!-- BUTTONS -->
                    <div class="mt-4 flex justify-end gap-2">
                        <button
                            @click="closeImportModal"
                            class="btn-primary bg-gray-400 hover:bg-gray-500"
                        >
                            Cancel
                        </button>
                        <button
                            @click="submitImport"
                            :disabled="processing"
                            class="btn-primary flex items-center gap-2"
                        >
                            <svg
                                v-if="processing"
                                class="h-4 w-4 animate-spin text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                                ></path>
                            </svg>
                            <span>{{
                                processing ? 'Uploading...' : 'Upload File'
                            }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <div class="page-content space-y-8">
            <!-- HEADER -->
            <div class="flex items-center justify-between">
                <h2 class="page-title">Intermediate Application List</h2>
                <div v-if="canCreateOrImport" class="flex flex-nowrap gap-2">
                    <Link
                        href="/intermediate/applications/register"
                        class="btn-primary !bg-[#1C7BA5] whitespace-nowrap"
                        >Create Intermediate Application</Link
                    >
                    <button
                        @click="showImportModal = true"
                        class="btn-primary whitespace-nowrap"
                    >
                        Upload Applications from Google Forms
                    </button>
                </div>
            </div>

            <!-- SEARCH -->
            <div class="mb-4 flex gap-4">
                <div class="relative w-full">
                    <span
                        class="absolute inset-y-0 left-3 flex items-center text-zinc-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7 7 0 1010.3 3a7 7 0 006.35 13.65z"
                            />
                        </svg>
                    </span>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search by Applicant Name, Project, Position, Application Stage, or Remarks"
                        class="w-full rounded-lg border bg-white py-2 pr-3 pl-10 dark:border-zinc-700 dark:bg-zinc-900"
                    />
                </div>
            </div>

            <!-- TABLE -->
            <div class="card">
                <div class="mb-2 text-xs text-gray-600">
                    Showing {{ showingFrom }}–{{ showingTo }} of
                    {{ filteredApplications.length }} items
                </div>

                <div class="table-wrapper">
                    <table
                        class="ats-table w-full table-auto border-collapse border text-sm"
                    >
                        <thead class="bg-zinc-100 text-left dark:bg-zinc-800">
                            <tr>
                                <th class="border px-3 py-2">Applicant Name</th>
                                <th class="border px-3 py-2">Project</th>
                                <th class="border px-3 py-2">Position</th>
                                <th class="border px-3 py-2">
                                    Application Stage
                                </th>
                                <th class="border px-3 py-2">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="app in paginatedApplications"
                                :key="app.id"
                            >
                                <td class="border px-3 py-2">
                                    <Link
                                        :href="`/intermediate/applications/${app.id}`"
                                        class="table-link"
                                    >
                                        {{ app.first_name }} {{ app.last_name }}
                                    </Link>
                                </td>
                                <td class="border px-3 py-2">
                                    {{ app.project_name || '—' }}
                                </td>
                                <td class="border px-3 py-2">
                                    {{ app.position || '—' }}
                                </td>
                                <td class="border px-3 py-2">
                                    <span
                                        :class="[
                                            'inline-flex rounded-full px-3 py-1 text-sm font-semibold',
                                            app.application_stage === 1
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : app.application_stage === 2
                                                  ? 'bg-orange-100 text-orange-800'
                                                  : app.application_stage === 3
                                                    ? 'bg-blue-100 text-blue-800'
                                                    : app.application_stage ===
                                                        4
                                                      ? 'bg-purple-100 text-purple-800'
                                                      : app.application_stage ===
                                                          5
                                                        ? 'bg-green-100 text-green-800'
                                                        : app.application_stage ===
                                                            6
                                                          ? 'bg-red-100 text-red-800'
                                                          : 'bg-gray-100 text-gray-500',
                                        ]"
                                        >{{
                                            getStageLabel(app.application_stage)
                                        }}</span
                                    >
                                </td>
                                <td class="border px-3 py-2">
                                    <div
                                        class="remarks-clamp"
                                        :title="app.remarks"
                                    >
                                        {{ app.remarks || '—' }}
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!paginatedApplications.length">
                                <td
                                    colspan="5"
                                    class="p-6 text-center text-zinc-500"
                                >
                                    No applications found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div
                    class="mt-3 flex justify-center gap-2 text-xs"
                    v-if="filteredApplications.length > perPage"
                >
                    <span
                        @click="prevBlock"
                        class="cursor-pointer rounded border px-3 py-2"
                        :class="{
                            'cursor-not-allowed opacity-50': startPage === 1,
                        }"
                    >
                        Prev
                    </span>

                    <span
                        v-for="pageNumber in pageNumbers"
                        :key="pageNumber"
                        @click="goToPage(pageNumber)"
                        class="cursor-pointer rounded border px-3 py-2"
                        :class="
                            pageNumber === currentPage
                                ? 'bg-blue-600 text-white'
                                : ''
                        "
                    >
                        {{ pageNumber }}
                    </span>

                    <span
                        @click="nextBlock"
                        class="cursor-pointer rounded border px-3 py-2"
                        :class="{
                            'cursor-not-allowed opacity-50':
                                endPage === totalPages,
                        }"
                    >
                        Next
                    </span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
/* =========================
   OVERRIDE GLOBAL ATS TABLE
   ========================= */
.table-wrapper {
    overflow-x: hidden;
    /* Prevents horizontal scrolling */
}

.ats-table {
    min-width: 0 !important;
    table-layout: fixed;
    /* Equal column distribution */
}

/* Allow content to wrap naturally */
.ats-table th,
.ats-table td {
    white-space: normal;
    word-break: break-word;
}

/* =========================
   REMARKS CLAMP (2-line limit)
   ========================= */
.remarks-clamp {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
    word-break: break-word;
}

/* =========================
   TABLE LINK
   ========================= */
.table-link {
    color: var(--ats-accent, #1c7ba5);
    font-weight: 500;
    text-decoration: none;
}

.table-link:hover {
    text-decoration: underline;
}

/* =========================
   EXISTING STYLES (Keep these)
   ========================= */
.card {
    background: var(--ats-card, white);
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: var(--ats-shadow, 0 1px 3px rgba(0, 0, 0, 0.1));
}

.page-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--ats-text);
}

.btn-primary {
    background: var(--ats-primary, #1c7ba5);
    color: #fff;
    padding: 0.55rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.15s ease;
}

.btn-primary:hover {
    background: var(--ats-accent, #165a80);
}

.page-content {
    max-width: 1175px;
    margin: 0 auto;
    padding: 0 1.5rem;
}
</style>
