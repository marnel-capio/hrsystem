<script setup lang="ts">
import { Link, useForm, router, usePage } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'

const page = usePage<any>()

// ✅ FLASH MESSAGES
const flashMessages = ref({
  success: '',
  error: '',
  info: ''
})

const hasFlash = computed(() =>
  !!flashMessages.value.success || !!flashMessages.value.error || !!flashMessages.value.info
)

watch(() => page.props.flash as any, (flash) => {
  if (flash) {
    flashMessages.value = {
      success: (flash as any).success || '',
      error: (flash as any).error || '',
      info: (flash as any).info || ''
    }
  }
}, { immediate: true, deep: true })

const clearFlash = () => {
  flashMessages.value = { success: '', error: '', info: '' }
}

const closeSuccess = () => {
  flashMessages.value.success = '';
};

const closeError = () => {
  flashMessages.value.error = '';
};

onMounted(() => {
  if (hasFlash.value) setTimeout(clearFlash, 10000)
})
//UPLOAD FUNCTIONS
const fileInput = ref<HTMLInputElement | null>(null);

const form = useForm({
  action_batch_id: null as number | null,
});

// Modal state
const showImportModal = ref(false);
const importFile = ref<File | null>(null);

// Open modal
const openImportModal = () => {
  showImportModal.value = true;
};

// Close modal + reset
const closeImportModal = () => {
  showImportModal.value = false;
  importFile.value = null;
  form.action_batch_id = null; // Reset form
  form.clearErrors(); // Clear any validation errors
};

// Handle file selection
const onImportFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (!target.files?.length) return;

  const file = target.files[0];
  importFile.value = file;

  console.log('File selected:', file.name); // Debug
};

const processing = ref(false);

// Submit import - FIXED
const submitImport = () => {
  if (!form.action_batch_id) {
    alert("Please select a batch.");
    return;
  }
  if (!importFile.value) {
    alert("Please select a file (.xlsx or .csv).");
    return;
  }

  const formData = new FormData();
  formData.append("file", importFile.value);
  formData.append("batch_id", String(form.action_batch_id));

  processing.value = true; // Start uploading

  router.post("/applications/import", formData, {
    forceFormData: true,
    preserveState: true,
    preserveScroll: true,
    onSuccess: (page) => {
      closeImportModal();
      router.reload({ only: ['applications'] });
    },
    onError: (errors) => {
      alert('Import failed: ' + Object.values(errors).join(', '));
    },
    onFinish: () => {
      processing.value = false; // Done uploading
    }
  });
};
//END OF UPLOAD FUNCTIONS


// Map trainees_from to location label
function formatLocation(loc: number | null) {
  if (loc === 1) return 'Manila';
  if (loc === 2) return 'Cebu';
  return 'Unknown';
}


// Props from backend
const props = defineProps<{
  applications: Array<{
    target_location: number
    id: number;
    action_applicant_id: number;
    action_batch_id: number;
    first_name: string;
    last_name: string;
    action_batch: string;
    }>;
  filters: { search: string };
  userPermissions: number;
  actionBatches: Array<{
    id: number;
    action_batch: string;
    target_trainees: number;
    target_date: string;
  }>;
}>();

const actionBatches = props.actionBatches;

const searchQuery = ref(props.filters.search || '');
watch(searchQuery, () => currentPage.value = 1);

const filteredApplications = computed(() => {
  const q = searchQuery.value.toLowerCase();
  return props.applications.filter((app) => {
    const fullName = `${app.first_name} ${app.last_name}`.toLowerCase();
    const batch = app.action_batch.toLowerCase();

    // Map trainees_from to string for search
    const locLabel = formatLocation(app.target_location).toLowerCase();
    const locNumber = app.target_location !== null ? String(app.target_location) : '';

    return fullName.includes(q)
           || batch.includes(q)
           || locLabel.includes(q)      // this allows "unknown" to match
           || locNumber.includes(q)
           || String(app.id).includes(q);
  });
});

// ---------------- Pagination ----------------
const currentPage = ref(1);
const perPage = 20;
const blockSize = 5;

const totalPages = computed(() => Math.ceil(filteredApplications.value.length / perPage));
const paginatedSchedules = computed(() => {
  const start = (currentPage.value - 1) * perPage;
  return filteredApplications.value.slice(start, start + perPage);
});

const currentBlock = computed(() => Math.ceil(currentPage.value / blockSize));
const startPage = computed(() => (currentBlock.value - 1) * blockSize + 1);
const endPage = computed(() => Math.min(startPage.value + blockSize - 1, totalPages.value));
const pageNumbers = computed(() => {
  const pages = [];
  for (let i = startPage.value; i <= endPage.value; i++) pages.push(i);
  return pages;
});

function goToPage(page: number) { if (page >= 1 && page <= totalPages.value) currentPage.value = page; }
function prevBlock() { if (startPage.value > 1) goToPage(startPage.value - 1); }
function nextBlock() { if (endPage.value < totalPages.value) goToPage(endPage.value + 1); }

const showingFrom = computed(() => filteredApplications.value.length === 0 ? 0 : (currentPage.value - 1) * perPage + 1);
const showingTo = computed(() => Math.min(currentPage.value * perPage, filteredApplications.value.length));
const batchesTotal = computed(() => filteredApplications.value.length);
const shouldShowPagination = computed(() => filteredApplications.value.length > perPage);
const lastPage = computed(() => totalPages.value);

console.log('Received batches:', props.actionBatches);

</script>

<template>
  <AppLayout>
<!-- TOP-RIGHT TOASTS - Smooth animations -->
<div class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-sm w-full sm:w-96">
  <!-- SUCCESS TOAST -->
  <TransitionGroup name="toast" tag="div">
    <div
      v-if="flashMessages.success"
      key="success"
      class="bg-green-100 border border-green-400 text-green-700  p-4 rounded-xl shadow-2xl backdrop-blur-sm border  max-h-80 overflow-y-auto animate-in slide-in-from-top-2 fade-in duration-300"
    >
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 mt-0.5">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <pre class="whitespace-pre-wrap text-sm leading-relaxed font-medium">{{ flashMessages.success }}</pre>
        </div>
            <button
@click="closeSuccess"
      style="all: unset; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background-color: rgba(0,0,0,0.3); color:white; font-weight:bold; font-size:1rem;"
    >
      X
    </button>
      </div>
    </div>
  </TransitionGroup>

  <!-- ERROR TOAST -->
  <TransitionGroup name="toast" tag="div">
    <div
      v-if="flashMessages.error"
      key="error"
      class="bg-red-100 border-red-400 text-red-700 p-4 rounded-xl shadow-2xl backdrop-blur-sm border max-h-80 overflow-y-auto animate-in slide-in-from-top-2 fade-in duration-300"
    >
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 mt-0.5">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <pre class="whitespace-pre-wrap text-sm leading-relaxed font-medium">{{ flashMessages.error }}</pre>
        </div>
            <button
@click="closeError"
      style="all: unset; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background-color: rgba(0,0,0,0.3); color:white; font-weight:bold; font-size:1rem;"
    >
      X
    </button>
      </div>
    </div>

  </TransitionGroup>
</div>
    <!-- IMPORT MODAL -->
<div v-if="showImportModal" class="fixed inset-0 bg-black/40  flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-[500px] shadow-lg">

    <!-- Title -->
    <h2 class="text-lg font-semibold mb-4">
      Upload Applications from Google Forms
    </h2>

    <!-- Batch Dropdown -->
    <label class="block text-sm font-medium mb-1">Select ACTION Batch</label>
<select v-model="form.action_batch_id" class="border rounded px-2 py-1 w-full">
    <option value="">Select</option>
    <option v-for="batch in actionBatches" :key="batch.id" :value="batch.id">
      {{ batch.action_batch }}
    </option>
  </select>

    <!-- File Input -->
    <label class="block text-sm font-medium mb-1 mt-6">Choose File (.xlsx or .csv)</label>
  <input
    type="file"
    accept=".xlsx,.csv"
    @change="onImportFileChange"
    class="file-input-btn w-full mb-4"
  />

    <!-- Buttons -->
    <div class="flex justify-end gap-2 mt-4">
      <button
        @click="closeImportModal"
        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
      >
        Cancel
      </button>
      <button
        @click="submitImport"
        :disabled="processing"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        {{ processing ? 'Uploading...' : 'Upload' }}
      </button>
    </div>
  </div>
</div>
    <div class="page-content">

      <!-- PAGE HEADER -->
      <div class="page-header">
        <h2 class="page-title">ACTION Application List</h2>
<div class="flex gap-2 flex-nowrap">
  <Link
    v-if="props.userPermissions != 5 && props.userPermissions != 6"
    href="/action/applications/register"
    class="!bg-[#1C7BA5] btn-primary whitespace-nowrap"
  >
    Create ACTION Application
  </Link>
  <input
    type="file"
    ref="fileInput"
    class="hidden"
    accept=".xlsx,.csv"
  />
<button
  v-if="props.userPermissions != 5 && props.userPermissions != 6"
  type="button"
  @click="openImportModal"
  class="btn-primary whitespace-nowrap"
>
  Upload Applications from Google Forms
</button>
</div>
      </div>

      <!-- SEARCH -->
      <div class="flex gap-4 mb-4">
        <div class="relative w-full">
          <span class="absolute inset-y-0 left-3 flex items-center text-zinc-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35m0 0A7 7 0 1010.3 3a7 7 0 006.35 13.65z" />
            </svg>
          </span>
          <input v-model="searchQuery" type="text" placeholder="Search by Applicant, Batch Name, or Location"
            class="w-full pl-10 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700" />
        </div>
      </div>

      <!-- TABLE CARD -->
      <div class="card">
        <div class="mb-2 text-xs text-gray-600">
          Showing {{ showingFrom }}–{{ showingTo }} of {{ batchesTotal }} items
        </div>

        <div class="table-wrapper">
          <table class="ats-table w-full table-auto border-collapse border text-sm">
            <thead>
              <tr>
                <th class="border px-3 py-2">Applicant Name</th>
                <th class="border px-3 py-2">Batch Name</th>
                <th class="border px-3 py-2">Location</th>
              </tr>
            </thead>
          <tbody>
            <tr v-for="app in paginatedSchedules" :key="app.id">
              <!-- Application ID as clickable link -->
<td class="border px-3 py-2">
  <Link :href="`/action/applications/${app.id}`" class="text-blue-600 hover:underline">
    {{ app.first_name }} {{ app.last_name }}
  </Link>
</td>

              <!-- Batch Name -->
              <td class="border px-3 py-2">{{ app.action_batch }}</td>

              <!-- Trainee From -->
<td class="border px-3 py-2">
  {{ app.target_location !== null ? formatLocation(app.target_location) : 'Unknown' }}
</td>
            </tr>

            <tr v-if="paginatedSchedules.length === 0">
              <td colspan="4" class="text-center p-6 text-zinc-500">No applications found.</td>
            </tr>
          </tbody>
          </table>
        </div>

        <!-- PAGINATION -->
        <div v-if="shouldShowPagination" class="flex justify-center mt-3 gap-2 text-xs">
          <span @click="prevBlock" class="px-3 py-2 border rounded cursor-pointer"
            :class="{ 'opacity-50 cursor-not-allowed': startPage === 1 }">Prev</span>

          <span v-for="pageNumber in pageNumbers" :key="pageNumber" @click="goToPage(pageNumber)"
            class="px-3 py-2 border rounded cursor-pointer"
            :class="pageNumber === currentPage ? 'bg-blue-600 text-white' : ''">{{ pageNumber }}</span>

          <span @click="nextBlock" class="px-3 py-2 border rounded cursor-pointer"
            :class="{ 'opacity-50 cursor-not-allowed': endPage === lastPage }">Next</span>
        </div>

      </div>
    </div>
  </AppLayout>
</template>
<style scoped>
/* CARD */
.card {
  background: var(--ats-card, white);
  padding: 1rem;
  border-radius: 0.5rem;
  box-shadow: var(--ats-shadow, 0 1px 3px rgba(0, 0, 0, 0.1));
}

/* TABLE WRAPPER */
.table-wrapper {
  overflow-x: hidden;
  /* remove horizontal scroll */
}

.ats-table th,
.ats-table td {
  padding-left: 10px;
  padding-right: 70px;
}

/* TABLE LINK */
.table-link {
  color: var(--ats-accent, #1C7BA5);
  font-weight: 500;
  text-decoration: none;
}

.table-link:hover {
  text-decoration: underline;
}

/* PAGE HEADER */
.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.25rem;
}

.page-title {
  font-size: 1.4rem;
  font-weight: 600;
  color: var(--ats-text);
}

/* BUTTON */
.btn-primary {
  background: var(--ats-primary, #1C7BA5);
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

/* PAGE CONTENT */
.page-content {
  max-width: 1175px;
  margin: 0 auto;
  padding: 0 1.5rem;
}
</style>
