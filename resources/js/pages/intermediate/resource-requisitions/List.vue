<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'

const page = usePage<any>()

const requisitions = computed(() => page.props.requisitions)
const filters = computed(() => page.props.filters)
const userPermissions = computed(() => Number(page.props.user_permissions))
const search = ref(filters.value.search || '')

const doSearch = debounce((value: string) => {
  router.get(
    '/intermediate/resource-requisitions',
    { search: value, page: 1 },  
    { preserveState: true, replace: true }
  )
}, 300) 

watch(search, (value: string) => {
  doSearch(value)
})

const currentPage = computed(() => requisitions.value.current_page)
const lastPage = computed(() => requisitions.value.last_page)
const requisitionsTotal = computed(() => page.props.requisitions_total)

const blockSize = 5
const currentBlock = computed(() => Math.ceil(currentPage.value / blockSize))
const startPage = computed(() => (currentBlock.value - 1) * blockSize + 1)
const endPage = computed(() => Math.min(startPage.value + blockSize - 1, lastPage.value))

const pageNumbers = computed(() => {
  const pages = []
  for (let i = startPage.value; i <= endPage.value; i++) pages.push(i)
  return pages
})

function goToPage(pageNumber: number) {
  router.get(
    '/intermediate/resource-requisitions',
    { page: pageNumber, search: search.value },  
    { preserveState: true }
  )
}

const locationMap: Record<number, string> = {
  1: 'Alabang',
  2: 'Makati',
  3: 'Cebu',
  4: 'Japan',
  5: 'China',
  6: 'Other'
}

function prevBlock() {
  if (startPage.value > 1) goToPage(startPage.value - 1)
}

function nextBlock() {
  if (endPage.value < lastPage.value) goToPage(endPage.value + 1)
}

const shouldShowPagination = computed(() => requisitionsTotal.value > 20)

function formatDate(dateString: string) {
  if (!dateString) return ''

  const date = new Date(dateString)

  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: '2-digit'
  })
}


</script>

<template>
  <AppLayout>
    <div class="page-content">

      <!-- PAGE HEADER -->
      <div class="page-header">
        <h2 class="page-title">Resource Requisition List</h2>
        <Link v-if="userPermissions === 1 || userPermissions === 5" :href="`/intermediate/resource-requisitions/register`"
          class="!bg-[#1C7BA5] btn-primary">
          Create Resource Requisition
        </Link>
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
    <input v-model="search" type="text" placeholder="Search by Project Name, Resources, Location Assignment, Start Date, and Requester"
      class="w-full pl-10 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700" />
  </div>
</div>

      <!-- CARD WRAPPER -->
      <div class="card">
        <!-- COUNT -->
        <div class="mb-2 text-xs text-gray-600">
          Showing {{ requisitions.data.length > 0 ? requisitions.from : 0 }}–{{ requisitions.data.length > 0 ? requisitions.to : 0 }}
          out of {{ requisitionsTotal }} items
        </div>

        <!-- TABLE -->
        <div class="table-wrapper">
          <table class="ats-table w-full table-auto border-collapse border text-sm">
            <thead class="bg-zinc-100 dark:bg-zinc-800 text-left">
              <tr>
                <th class="border px-3 py-2">Project Name</th>
                <th class="border p3 py-2">Project Description</th>
                <th class="borderx-3 py-2">Resources Title</th>
                <th class="borderx-3 py-2">Location Assignment</th>
                <th class="border px-3 py-2">Start Date</th>
                <th class="border p-3 py-2">Date Requested</th>
                <th class="border px-3 py-2">Requested By</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-900">
              <tr v-if="requisitions.data.length === 0">
                <td colspan="6" class="text-center p-6 text-zinc-500">
                  No records found
                </td>
              </tr>

              <tr v-for="requisition in requisitions.data" :key="requisition.id">
                <td class="border px-3 py-2">
                  <Link :href="`/intermediate/requisitions/${requisition.id}`" class="table-link">
                    {{ requisition.project?.project_name }}
                  </Link>
                </td>
                <td class="border px-3 py-2">{{ requisition.project_description }}</td>
                <td class="border px-3 py-2">
                  <div class="flex flex-wrap gap-1">
                    <span
                      v-for="(item, index) in requisition.resource?.split(',')"
                      :key="index"
                      class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-black-800 dark:text-blue-200"
                    >
                      {{ item.trim() }}
                    </span>
                  </div>
                </td>
                <td class="border px-3 py-2">{{ locationMap[requisition.location_assignment] }}</td>
                <td class="border px-3 py-2">{{ formatDate(requisition.start_date) }}</td>
                <td class="border px-3 py-2">{{ formatDate(requisition.created_time) }}</td>
                <td class="border px-3 py-2">
                  {{ requisition.requested_by?.first_name }} {{ requisition.requested_by?.last_name }}
                </td>
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
/* CARD */
.card {
  background: var(--ats-card, white);
  padding: 1rem;
  border-radius: 0.5rem;
  box-shadow: var(--ats-shadow, 0 1px 3px rgba(0, 0, 0, 0.1));
}

/* CARD */
.card {
  background: var(--ats-card, white);
  padding: 1rem;
  border-radius: 0.5rem;
  box-shadow: var(--ats-shadow, 0 1px 3px rgba(0, 0, 0, 0.1));
}

/* TABLE WRAPPER */
.table-wrapper {
  width: 100%; 
  overflow-x: hidden;
  display: block;
}

/* TABLE STYLES */
.ats-table {
  width: 100%; 
  table-layout: auto; 
}

.ats-table th,
.ats-table td {
  padding-left: 10px;
  padding-right: 10px;
  word-wrap: break-word; 
  text-overflow: ellipsis; 
  white-space: normal; 
}

.ats-table th:nth-child(1),
.ats-table td:nth-child(1) {
  min-width: 150px; 
}

.ats-table th:nth-child(2),
.ats-table td:nth-child(2) {
  min-width: 200px;
}

.ats-table th:nth-child(3),
.ats-table td:nth-child(3) {
  min-width: 170px;
}

.ats-table th:nth-child(4),
.ats-table td:nth-child(4) {
  min-width: 100px;
}

.ats-table th:nth-child(5),
.ats-table td:nth-child(5) {
  min-width: 150px;
}

.ats-table th:nth-child(6),
.ats-table td:nth-child(6) {
  min-width:130px;
}
.ats-table th:nth-child(7),
.ats-table td:nth-child(7) {
  min-width: 240px;
}

.ats-table td:nth-child(7) {
  white-space: normal; 
  word-wrap: break-word;
}

.table-wrapper {
  width: 100%;
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

.ats-table td {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 20ch;
  position: relative;
}

.ats-table td:hover {
max-width: none;
overflow: visible;
white-space: normal;
z-index: 10;
}
 
.ats-table td > div {
display: flex;
flex-wrap: nowrap;
overflow: hidden;
max-width: 100%;
}
 
.ats-table td:hover > div {
flex-wrap: wrap;
overflow: visible;
}

</style>