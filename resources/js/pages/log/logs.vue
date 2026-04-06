<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Logs', href: '#' },
];

const page = usePage();

const props = defineProps<{
  logs: Array<{
    id: number;
    module: string;
    activity: string;
    ip_address: string;
    created_by_name: string;
    create_time: string;
  }>;
  filters: {
    search: string;
  };
  userPermissions: number;
  users: Array<{ created_by: number, name: string }>; // Pass the list of users here
}>();

const selectedDate = ref<string>('');
const showDateFilter = ref<boolean>(false); 

const selectedModule = ref<string>(''); 
const showModuleFilter = ref<boolean>(false);  
const selectedUser = ref<string>(''); 
const showUserFilter = ref<boolean>(false); 

function formatTableDate(dateStr: string) {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  if (isNaN(date.getTime())) return dateStr;
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: '2-digit',
  });
}

function formatDetailDate(dateStr: string) {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  if (isNaN(date.getTime())) return dateStr;
  return date.toLocaleString('en-US', {
    year: 'numeric',
    month: 'long',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: true,
  });
}

// Pagination setup
const currentPage = ref(1);
const perPage = 20;
const blockSize = 5;
const searchQuery = ref(''); 

const selectedLog = ref<null | {
  id: number;
  module: string;
  activity: string;
  ip_address: string;
  created_by_name: string;
  create_time: string;
}>(null);

watch(searchQuery, () => {
  selectedLog.value = null; 
});

watch(selectedDate, (newDate) => {
  if (newDate) selectedLog.value = null; 
});

watch(selectedModule, (newModule) => {
  if (newModule) selectedLog.value = null; 
});

watch(selectedUser, (newUser) => {
  if (newUser) selectedLog.value = null; 
});

const detailsRef = ref<HTMLElement | null>(null);

function showDetails(log: any) {
  if (selectedLog.value?.id === log.id) {
    selectedLog.value = null; 
  } else {
    selectedLog.value = log; 
  }
  setTimeout(() => {
    detailsRef.value?.scrollIntoView({ behavior: 'smooth' });
  }, 100);
}

function highlightText(text: string): string {
  if (!searchQuery.value.trim()) return text; 
  const regex = new RegExp(`(${searchQuery.value.trim()})`, 'gi'); 
  return text.replace(regex, '<span class="highlight">$1</span>'); 
}

const filteredLogs = computed(() => {
  const date = selectedDate.value;
  const module = selectedModule.value?.trim().toLowerCase();
  const created_by = selectedUser.value?.trim().toLowerCase();
  const search = searchQuery.value?.trim().toLowerCase();

  let data = props.logs;

  // Apply the Date filter if it's enabled
  if (showDateFilter.value && date) {
    data = data.filter((log) => {
      const logDate = new Date(log.create_time).toLocaleDateString();
      return logDate === new Date(date).toLocaleDateString();
    });
  }

  // Apply the Module filter if it's enabled
  if (showModuleFilter.value && module) {
    data = data.filter(log => log.module?.toLowerCase().trim() === module);  
  }

  if (showUserFilter.value && created_by) {
    data = data.filter(log => log.created_by_name?.toLowerCase().trim() === created_by);  
  }

  // Apply the Search filter
  if (search) {
    data = data.filter(log =>
      log.activity?.toLowerCase().includes(search) ||
      log.created_by_name?.toLowerCase().includes(search)
    );
  }

  return [...data].sort((a, b) => b.id - a.id);
});

const totalPages = computed(() => Math.ceil(filteredLogs.value.length / perPage));
const paginatedLogs = computed(() => {
  const start = (currentPage.value - 1) * perPage;
  return filteredLogs.value.slice(start, start + perPage);
});

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

const showingFrom = computed(() =>
  filteredLogs.value.length === 0 ? 0 : (currentPage.value - 1) * perPage + 1
);

const showingTo = computed(() => {
  const end = currentPage.value * perPage;
  return end > filteredLogs.value.length ? filteredLogs.value.length : end;
});

function formatActivitySummary(activity: string) {
  const detailsIndex = activity.indexOf('Details:');
  return detailsIndex === -1 ? activity : activity.substring(0, detailsIndex).trim();
}

</script>

<template>
  <Head title="Log List" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-6 p-8 bg-zinc-50/50 dark:bg-zinc-950 min-h-screen">
      <div class="page-content">
        <!-- HEADER -->
        <div class="page-header">
          <h2 class="page-title">Logs List</h2>
        </div>

        <!-- SEARCH --> 
        <div class="flex gap-4 mb-4"> 
          <div class="relative w-full"> 
            <input 
              v-model="searchQuery"
              type="text" 
              placeholder="Search by activity or creator" 
              class="w-full pl-3 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700" 
            /> 
          </div>
        </div>

        <!-- FILTER BY -->
        <div class="flex gap-4 mb-4">
          <label class="text-sm font-semibold">Filter by:</label>
          
          <!-- Date Filter Checkbox -->
          <div class="flex items-center gap-2">
            <input
              v-model="showDateFilter"
              type="checkbox"
              id="date-filter-checkbox"
              class="cursor-pointer"
            />
            <label for="date-filter-checkbox" class="text-sm">Date</label>
          </div>

          <!-- User Filter Checkbox -->
          <div class="flex items-center gap-2">
            <input
              v-model="showUserFilter"
              type="checkbox"
              id="module-filter-checkbox"
              class="cursor-pointer"
            />
            <label for="module-filter-checkbox" class="text-sm">User</label>
          </div>

          <!-- Module Filter Checkbox -->
          <div class="flex items-center gap-2">
            <input
              v-model="showModuleFilter"
              type="checkbox"
              id="module-filter-checkbox"
              class="cursor-pointer"
            />
            <label for="module-filter-checkbox" class="text-sm">Module</label>
          </div>
        </div>

        <div v-if="showDateFilter" class="flex gap-4 mb-4">
          <div class="relative w-full">
            <input
              v-model="selectedDate"
              type="date"
              class="w-42 pl-3 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700"
            />
          </div>
        </div>

        <div v-if="showUserFilter" class="flex gap-4 mb-4">
          <div class="relative w-full">
            <select
              v-model="selectedUser"
              class="w-42 pl-3 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700"
            >
              <option value="" class="readonly">All Users</option>
              <option v-for="user in props.users" :key="user.created_by" :value="user.full_name">
                {{ user.full_name }}
              </option>
            </select>
          </div>
        </div>

        <div v-if="showModuleFilter" class="flex gap-4 mb-4">
          <div class="relative w-full">
            <select
              v-model="selectedModule"
              class="w-42 pl-3 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700"
            >
              <option value="" class="readonly">All Modules</option>
              <option v-for="module in props.modules" :key="module" :value="module">
                {{ module }}
              </option>
            </select>
          </div>
        </div>

        <!-- TABLE -->
        <div class="card">
          <div class="mb-2 text-xs text-gray-600">
            Showing {{ showingFrom }}–{{ showingTo }} out of {{ filteredLogs.length }} items
          </div>

          <div class="table-wrapper">
            <table class="ats-table w-full table-auto border-collapse border text-sm">
              <thead class="bg-zinc-100 dark:bg-zinc-800 text-left">
                <tr>
                  <th class="border px-3 py-2 w-40">Timestamp</th>
                  <th class="border px-3 py-2 w-100 break-all">Activity Summary</th>
                  <th class="border px-3 py-2">User</th>
                  <th class="border px-3 py-2">IP Address</th>
                </tr>
              </thead>

              <tbody class="bg-white dark:bg-zinc-900">
                <tr
                  v-for="log in paginatedLogs"
                  :key="log.id"
                  :class="[ selectedLog?.id === log.id ? 'bg-blue-50 dark:bg-zinc-800' : '', 'hover:bg-blue-100 dark:hover:bg-zinc-700' ]"
                >
                  <td class="border px-3 py-2">{{ formatTableDate(log.create_time) }}</td>
                  <td
                    class="border px-3 py-2 cursor-pointer"
                    @click="showDetails(log)">
                    {{ formatActivitySummary(log.activity) }}
                  </td>

                  <td class="border px-3 py-2" v-html="highlightText(log.created_by_name)"></td>
                  <td class="border px-3 py-2">{{ formatTableDate(log.ip_address) }}</td>
                </tr>

                <tr v-if="paginatedLogs.length === 0">
                  <td colspan="4" class="text-center p-6 text-zinc-500">
                    No logs found.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PAGINATION -->
        <div
          class="flex justify-center mt-3 gap-2 text-xs"
          v-if="filteredLogs.length > perPage"
        >
          <span @click="prevBlock" class="px-3 py-2 border rounded cursor-pointer">
            Prev
          </span>

          <span
            v-for="pageNumber in pageNumbers"
            :key="pageNumber"
            @click="goToPage(pageNumber)"
            class="px-3 py-2 border rounded cursor-pointer"
            :class="pageNumber === currentPage ? 'bg-blue-600 text-white' : ''">{{ pageNumber }} </span>

      <span @click="nextBlock" class="px-3 py-2 border rounded cursor-pointer">
        Next
      </span>
    </div>

    <!-- DETAILS -->
    <div v-if="selectedLog" ref="detailsRef" class="card mt-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Log Details</h3>

        <button
          @click="selectedLog = null"
          class="!px-4 !py-2 !text-xs !bg-[#1C7BA5] text-white !rounded !hover:bg-[#1C7BA5]-200 !flex items-center !justify-center !w-15 !h-7"
        >
          Close
        </button>
      </div>

      <div class="log-details-container">
        <div class="log-details-left">
          <div><strong>Creation Date</strong></div>
          <div>{{ formatDetailDate(selectedLog.create_time) }}</div>
          <div><strong>Created by</strong></div>
          <div>{{ selectedLog.created_by_name }}</div>

          <div><strong>Module</strong></div>
          <div>{{ selectedLog.module }}</div>
          <div><strong>IP address</strong></div>
          <div>{{ selectedLog.ip_address }}</div>
        </div>

        <div class="activity-wrapper">
          <div class="activity-title">Activity</div>
          <div class="activity-content">
            {{ selectedLog.activity }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

  </AppLayout>
</template>


<style scoped>
.card {
padding: 1rem;
border-radius: 0.5rem;
box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
 
.table-link {
color: #1C7BA5;
font-weight: 500;
}

.highlight {
  background-color: yellow;
  padding: 0 3px;
  border-radius: 3px;
}

.page-title {
font-size: 1.4rem;
font-weight: 600;
}
 
.log-details-container {
display: flex;
gap: 6rem;
}
 
.log-details-left {
display: grid;
grid-template-columns: 120px 1fr;
gap: 10px;
}
 
.activity-wrapper {
width: 700px;
border: 1px solid #e5e7eb;
border-radius: 12px;
padding: 1rem;
}
 
.activity-title {
text-align: center;
font-weight: 600;
margin-bottom: 0.5rem;
}
 

.ats-table td {
  max-width: 250px;
}
.activity-content {
font-size: 0.85rem;
white-space: pre-wrap;
}
 
.close-btn {
background-color: #1C7BA5;
color: white;
font-size: 0.75rem;
padding: 4px 12px;
border-radius: 6px;
border: none;
cursor: pointer;
}

.ats-table td:nth-child(2) {
  max-width: 400px;
  white-space: normal;
  word-break: break-word;
  cursor: pointer;
}
</style>