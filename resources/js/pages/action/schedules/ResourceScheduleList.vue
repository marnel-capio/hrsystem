<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Resource Schedule', href: '#' },
];

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

// Search input bound to backend
const searchQuery = ref(props.filters.search || '');
watch(searchQuery, (newVal) => {
  router.get('/action/schedules', { search: newVal }, {
    preserveState: true,
    replace: true,
  });
});

// Format deployment date
function formatDeploymentDate(dateStr: string) {
  if (!dateStr) return '';
  
  const date = new Date(dateStr); // Use the string directly
  if (isNaN(date.getTime())) return dateStr; // fallback if invalid
  
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
}

// ---------------- Pagination Setup ----------------
const currentPage = ref(1);
const perPage = 20; // items per page
const blockSize = 5; // number of page buttons

// Filtered schedules based on search
const filteredSchedules = computed(() => {
  const q = searchQuery.value.toLowerCase();
  if (!q) return props.schedules;
return props.schedules.filter((rs) => {
  const batch = rs.action_batch.toLowerCase();
  const locLabel = formatLocation(rs.target_location).toLowerCase();
  const locNumber = String(rs.target_location); // allow search "1" or "2"
  const deployment = formatDeploymentDate(rs.deployment_date).toLowerCase();

  return (
    batch.includes(q) ||
    locLabel.includes(q) ||    // match Manila, Cebu
    locNumber.includes(q) ||   // match 1, 2
    deployment.includes(q)
  );
});
});

// Total pages
const totalPages = computed(() =>
  Math.ceil(filteredSchedules.value.length / perPage)
);

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
function prevBlock() {
  if (startPage.value > 1) goToPage(startPage.value - 1);
}
function nextBlock() {
  if (endPage.value < totalPages.value) goToPage(endPage.value + 1);
}
</script>

<template>
  <Head title="Resource Schedule List" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-8 bg-zinc-50/50 dark:bg-zinc-950 min-h-screen">

      <!-- Header with Create Button -->
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">Resource Schedule List</h1>

        <a
          v-if="props.userPermissions != 3"
          href="/action/schedules/create"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold"
          style="background-color: #1C7BA5;"
        >
          Create Resource Schedule
        </a>
      </div>

      <!-- Search Input -->
      <div class="flex gap-4 mb-4">
        <div class="relative w-full">
          <span class="absolute inset-y-0 left-3 flex items-center text-zinc-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35m0 0A7 7 0 1010.3 3a7 7 0 006.35 13.65z" />
            </svg>
          </span>

          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search by Batch Name, Deployment, or Location"
            class="w-full pl-10 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700"
          />
        </div>
      </div>

      <!-- Resource Schedule Table -->
      <table class="w-full table-auto border-collapse border text-sm">
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
              <a :href="`/action/schedules/${rs.id}`" class="text-blue-600 hover:underline">
                {{ rs.action_batch }}
              </a>
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

      <!-- Block Pagination -->
      <div class="flex justify-center mt-3 gap-2 text-xs" v-if="filteredSchedules.length > 0">
        <span
          @click="prevBlock"
          class="px-3 py-2 border rounded cursor-pointer"
          :class="{ 'opacity-50 cursor-not-allowed': startPage === 1 }"
        >
          Prev
        </span>

        <span
          v-for="pageNumber in pageNumbers"
          :key="pageNumber"
          @click="goToPage(pageNumber)"
          class="px-3 py-2 border rounded cursor-pointer"
          :class="pageNumber === currentPage ? 'bg-blue-600 text-white' : ''"
        >
          {{ pageNumber }}
        </span>

        <span
          @click="nextBlock"
          class="px-3 py-2 border rounded cursor-pointer"
          :class="{ 'opacity-50 cursor-not-allowed': endPage === totalPages }"
        >
          Next
        </span>
      </div>

    </div>
  </AppLayout>
</template>

<style scoped>
.fade-in { animation: fadeIn 0.3s ease-out; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>