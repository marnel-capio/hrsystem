<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

// Map trainees_from to location label
function formatLocation(loc: number) {
  return loc === 1 ? 'Manila' : loc === 2 ? 'Cebu' : 'Unknown';
}

// Props from backend
const props = defineProps<{
  applications: Array<{
    id: number;
    action_applicant_id: number;
    action_batch_id: number;
    first_name: string;
    last_name: string;
    action_batch: string;
    trainees_from: number;
  }>;
  filters: { search: string };
  userPermissions: number;
}>();

const searchQuery = ref(props.filters.search || '');
watch(searchQuery, () => currentPage.value = 1);

const filteredApplications = computed(() => {
  const q = searchQuery.value.toLowerCase();
  let data = props.applications;

  if (q) {
    data = data.filter((app) => {
      const fullName = `${app.first_name} ${app.last_name}`.toLowerCase();
      const batch = app.action_batch.toLowerCase();
      const locLabel = formatLocation(app.trainees_from).toLowerCase();
      const locNumber = String(app.trainees_from);

      return fullName.includes(q)
             || batch.includes(q)
             || locLabel.includes(q)
             || locNumber.includes(q)
             || String(app.id).includes(q);
    });
  }

  return [...data].sort((a, b) => b.id - a.id);
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
</script>

<template>
  <AppLayout>
    <div class="page-content">

      <!-- PAGE HEADER -->
      <div class="page-header">
        <h2 class="page-title">ACTION Application List</h2>
        <div class="flex gap-2">
  <Link v-if="userPermissions === 1 || userPermissions === 2"
    href="/action/applications/register"
    class="!bg-[#1C7BA5] btn-primary">
    Create ACTION Application
  </Link>

  <Link v-if="userPermissions === 1 || userPermissions === 2"
    href="/action/applications/register"
    class="btn-primary">
    Upload Application from Google Forms
  </Link>
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
      <th class="border px-3 py-2">Application ID</th>
      <th class="border px-3 py-2">Applicant Name</th>
      <th class="border px-3 py-2">Batch Name</th>
      <th class="border px-3 py-2">Location</th>
    </tr>
  </thead>
<tbody>
  <tr v-for="app in paginatedSchedules" :key="app.id">
    <!-- Application ID as clickable link -->
    <td class="border px-3 py-2">
      <Link :href="`/action/applications/${app.id}`" class="table-link">
        {{ app.id }}
      </Link>
    </td>

    <!-- Applicant Name -->
    <td class="border px-3 py-2">{{ app.first_name }} {{ app.last_name }}</td>

    <!-- Batch Name -->
    <td class="border px-3 py-2">{{ app.action_batch }}</td>

    <!-- Trainee From -->
    <td class="border px-3 py-2">Manila</td>
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