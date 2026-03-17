<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Resource Schedule', href: '#' },
];

const page = usePage();

// Flash messages
const successMessage = computed(() => (page.props.flash as any)?.success || '');
const errorMessage = computed(() => (page.props.flash as any)?.error || '');

const showSuccess = ref(successMessage.value);
const showError = ref(false);

onMounted(() => {
  if (successMessage.value) {
    showSuccess.value = true;
  }
  if (errorMessage.value) {
    showError.value = true;
  }
});

// Props from backend
const props = defineProps<{
  schedules: Array<{
    id: number;
    action_batch: string;
    target_trainees: number;
    deployment_date: string;
    target_location: number;
  }>;
  filters: {
    search: string;
  };
  userPermissions: number;
}>();

// Map location number to string
function formatLocation(loc: number) {
  return loc === 1 ? 'Manila' : loc === 2 ? 'Cebu' : 'Unknown';
}

// Search input
const searchQuery = ref(props.filters.search || '');
watch(searchQuery, () => currentPage.value = 1);

// Format deployment date
function formatDeploymentDate(dateStr: string) {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  if (isNaN(date.getTime())) return dateStr;
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
}


// ---------------- Pagination Setup ----------------
const currentPage = ref(1);
const perPage = 20;
const blockSize = 5;

// Filtered schedules
const filteredSchedules = computed(() => {
  const q = searchQuery.value.toLowerCase();
  if (!q) return props.schedules;
  return props.schedules.filter((rs) => {
    const batch = rs.action_batch.toLowerCase();
    const locLabel = formatLocation(rs.target_location).toLowerCase();
    const locNumber = String(rs.target_location);
    const deployment = formatDeploymentDate(rs.deployment_date).toLowerCase();

    return batch.includes(q) || locLabel.includes(q) || locNumber.includes(q) || deployment.includes(q);
  });
});

// Total pages
const totalPages = computed(() => Math.ceil(filteredSchedules.value.length / perPage));

// Paginated schedules
const paginatedSchedules = computed(() => {
  const start = (currentPage.value - 1) * perPage;
  return filteredSchedules.value.slice(start, start + perPage);
});

// Block pagination
const currentBlock = computed(() => Math.ceil(currentPage.value / blockSize));
const startPage = computed(() => (currentBlock.value - 1) * blockSize + 1);
const endPage = computed(() => Math.min(startPage.value + blockSize - 1, totalPages.value));
const pageNumbers = computed(() => {
  const pages = [];
  for (let i = startPage.value; i <= endPage.value; i++) pages.push(i);
  return pages;
});

function goToPage(page: number) {
  if (page >= 1 && page <= totalPages.value) currentPage.value = page;
}
function prevBlock() { if (startPage.value > 1) goToPage(startPage.value - 1); }
function nextBlock() { if (endPage.value < totalPages.value) goToPage(endPage.value + 1); }

// Showing count
const showingFrom = computed(() => filteredSchedules.value.length === 0 ? 0 : (currentPage.value - 1) * perPage + 1);
const showingTo = computed(() => {
  const end = currentPage.value * perPage;
  return end > filteredSchedules.value.length ? filteredSchedules.value.length : end;
});
</script>

<template>

  <Head title="Resource Schedule List" />

  <AppLayout :breadcrumbs="breadcrumbs">

<!-- Success Notification -->
<div 
  v-if="showSuccess"
  class="full-width-alert"
>
  <div 
    class="alert-banner alert-success-banner"
  >
      <p class="text-white text-m font-medium text-left">{{ successMessage }}</p>
    <button 
      @click="showSuccess = false"
      style="all: unset; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background-color: rgba(0,0,0,0.3); color:white; font-weight:bold; font-size:1rem;"
    >
      X
    </button>
  </div>
</div>

<!-- Error Notification -->
<div 
  v-if="showError"
  class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-full px-4"
>
  <div 
    class="relative bg-red-500 border-red-200 rounded-lg shadow-md p-4 flex items-center gap-4 animate-slide-down"
  >
    <div class="flex-1 flex justify-start items-center gap-3">
      <p class="text-white text-m font-medium text-left">{{ errorMessage }}</p>
    </div>
    <button 
      @click="showError = false"
      style="all: unset; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background-color: rgba(0,0,0,0.3); color:white; font-weight:bold; font-size:1rem;"
    >
      X
    </button>
  </div>
</div>

    <div class="flex flex-col gap-6 p-8 bg-zinc-50/50 dark:bg-zinc-950 min-h-screen">
    <div class="page-content">

      <!-- PAGE HEADER -->
      <div class="page-header">
        <h2 class="page-title">Resource Schedule List</h2>
        <a v-if="props.userPermissions != 3" href="/action/schedules/register" class="!bg-[#1C7BA5] btn-primary">
          Create Resource Schedule
        </a>
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
          <input v-model="searchQuery" type="text" placeholder="Search by Batch Name, Deployment, or Location"
            class="w-full pl-10 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700" />
        </div>
      </div>

      <!-- RESOURCE SCHEDULE TABLE -->
      <div class="card">
        <!-- COUNT -->
        <div class="mb-2 text-xs text-gray-600">
          Showing {{ showingFrom }}–{{ showingTo }} out of {{ filteredSchedules.length }} items
        </div>

        <div class="table-wrapper">
          <table class="ats-table w-full table-auto border-collapse border text-sm">
            <thead class="bg-zinc-100 dark:bg-zinc-800 text-left">
              <tr>
                <th class="border px-3 py-2">Batch Name</th>
                <th class="border px-3 py-2">Target Trainees</th>
                <th class="border px-3 py-2">Deployment</th>
                <th class="border px-3 py-2">Location</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-900">
              <tr v-for="rs in paginatedSchedules" :key="rs.id">
                <td class="border px-3 py-2">
                  <a :href="`/action/schedules/${rs.id}`" class="table-link">{{ rs.action_batch }}</a>
                </td>
                <td class="border px-3 py-2">{{ rs.target_trainees }}</td>
                <td class="border px-3 py-2">{{ formatDeploymentDate(rs.deployment_date) }}</td>
                <td class="border px-3 py-2">{{ formatLocation(rs.target_location) }}</td>
              </tr>
              <tr v-if="paginatedSchedules.length === 0">
                <td colspan="4" class="text-center p-6 text-zinc-500">
                  No resource schedules found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- PAGINATION -->
      <div class="flex justify-center mt-3 gap-2 text-xs" v-if="filteredSchedules.length > perPage">
        <span @click="prevBlock" class="px-3 py-2 border rounded cursor-pointer"
          :class="{ 'opacity-50 cursor-not-allowed': startPage === 1 }">Prev</span>

        <span v-for="pageNumber in pageNumbers" :key="pageNumber" @click="goToPage(pageNumber)"
          class="px-3 py-2 border rounded cursor-pointer"
          :class="pageNumber === currentPage ? 'bg-blue-600 text-white' : ''">{{ pageNumber }}</span>

        <span @click="nextBlock" class="px-3 py-2 border rounded cursor-pointer"
          :class="{ 'opacity-50 cursor-not-allowed': endPage === totalPages }">Next</span>
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
  /* prevents scroll */
}

.ats-table th,
.ats-table td {
  white-space: normal;
  word-break: break-word;
}

/* LINK STYLE */
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