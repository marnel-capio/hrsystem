<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Resource Schedule', href: '#' },
];

// Define props to receive schedules from the backend
const props = defineProps<{
  schedules: Array<{
    id: number;
    batch_name: string;
    target_trainees: number;
    deployment_date: string;
    target_location: string;
  }>;
}>();

// Search input
const searchQuery = ref("");

// Use the passed schedules data
const resourceSchedules = computed(() => props.schedules);

// Filtered schedules based on search
const filteredSchedules = computed(() => {
  const q = searchQuery.value.toLowerCase();
  if (!q) return resourceSchedules.value;

  return resourceSchedules.value.filter(rs => {
    const batch = rs.batch_name.toLowerCase();
    const loc = rs.target_location.toLowerCase();

    // format the date exactly how it's displayed
    const formattedDeployment = formatDeploymentDate(rs.deployment_date).toLowerCase();

    return (
      batch.includes(q) ||
      loc.includes(q) ||
      formattedDeployment.includes(q)
    );
  });
});



// Method to format deployment date (e.g., "2026-02" -> "February 2026")
function formatDeploymentDate(dateStr: string) {
  if (!dateStr) return '';
  try {
    // Append "-01" to make it a valid date (e.g., "2026-02-01")
    const date = new Date(dateStr + '-01');
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' }); // "February 2026"
  } catch {
    return dateStr; // Fallback to original if parsing fails
  }
}
</script>

<template>
  <Head title="Resource Schedule List" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-8 bg-zinc-50/50 dark:bg-zinc-950 min-h-screen">

      <!-- Header with Create Button -->
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">Resource Schedules</h1>

        <a
          href="/action/schedules/create"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold"
          style="background-color: #1C7BA5;"
        >
          Create Resource Schedule
        </a>
      </div>

      <div class="flex gap-4 mb-4">
        <div class="relative w-full">
          <span class="absolute inset-y-0 left-3 flex items-center text-zinc-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
          <tr v-for="rs in filteredSchedules" :key="rs.id">
            <td class="border px-3 py-2">
              <a :href="`/action/schedules/${rs.id}`" class="text-blue-600 hover:underline">
                {{ rs.batch_name }}
              </a>
            </td>
            <td class="border px-3 py-2">{{ rs.target_trainees }}</td>
            <td class="border px-3 py-2">{{ formatDeploymentDate(rs.deployment_date) }}</td>  <!-- FORMATTED DATE -->
            <td class="border px-3 py-2">{{ rs.target_location }}</td>
          </tr>
        </tbody>
      </table>

      <!-- Optional: Show message if no schedules -->
      <div v-if="filteredSchedules.length === 0" class="text-center text-zinc-500 mt-4">
        No resource schedules found.
      </div>

    </div>
  </AppLayout>
</template>

<style scoped>
/* Optional fade-in effect */
.fade-in { animation: fadeIn 0.3s ease-out; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>