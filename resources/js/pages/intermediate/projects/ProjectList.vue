<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const page = usePage<any>()

const projects = computed(() => page.props.projects)
const filters = computed(() => page.props.filters)
const userPermissions = computed(() => Number(page.props.user_permissions))
const search = ref(filters.value.search || '')

watch(search, (value: string) => {
  router.get(
    '/intermediate/projects',
    { search: value },
    { preserveState: true, replace: true }
  )
})

const currentPage = computed(() => projects.value.current_page)
const lastPage = computed(() => projects.value.last_page)

const blockSize = 5
const currentBlock = computed(() => Math.ceil(currentPage.value / blockSize))
const startPage = computed(() => (currentBlock.value - 1) * blockSize + 1)
const endPage = computed(() => Math.min(startPage.value + blockSize - 1, lastPage.value))

const projectsTotal = computed(() => page.props.projects_total)

const pageNumbers = computed(() => {
  const pages = []
  for (let i = startPage.value; i <= endPage.value; i++) {
    pages.push(i)
  }
  return pages
})

function goToPage(pageNumber: number) {
  router.get(
    '/intermediate/projects',
    { page: pageNumber, search: search.value },
    { preserveState: true }
  )
}

function prevBlock() {
  if (startPage.value > 1) {
    goToPage(startPage.value - 1)
  }
}

function nextBlock() {
  if (endPage.value < lastPage.value) {
    goToPage(endPage.value + 1)
  }
}

const shouldShowPagination = computed(() => projectsTotal.value > 20)
</script>
 
<template>
  <AppLayout>
    <div class="page-content">
 
      <div class="page-header flex justify-between items-center mb-6">
          <h2 class="text-lg font-semibold">Project List</h2>
          <Link
            v-if="userPermissions === 1 || userPermissions === 5"
            :href="`/intermediate/projects/register`"
            class="text-xs bg-[#1C7BA5] text-white px-4 py-2 rounded">
            Create Project
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
          <input v-model="search" type="text" placeholder="Search by Project Name"
            class="w-full pl-10 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700" />
        </div>
      </div>
      
      <div class="card bg-white p-6 rounded shadow">
        <!-- CARD WRAPPER -->
      <div class="card">
        <!-- COUNT -->
        <div class="mb-2 text-xs text-gray-600">
          Showing {{ projects.data.length > 0 ? projects.to : 0 }}–{{ projects.data.length > 0 ? projects.to : 0 }}
          out of {{ projectsTotal }} items
        </div>
 
        <!-- TABLE -->
        <div class="overflow-x-auto">
          <table class="min-w-full border text-xs">
            <thead class="bg-gray-100">
              <tr>
                <th class="p-3 border text-left w-1/4">Project Name</th>
                <th class="p-3 border text-left w-2/3">Remarks</th>
              </tr>
            </thead>
 
            <tbody>
              <tr
                v-for="project in projects.data"
                :key="project.id"
                class="hover:bg-gray-50"
              >
                <td class="p-3 border-b  text-blue-600 hover:underline cursor-pointer">
                  <Link
                  :href="`/intermediate/projects/${project.id}`">
                  {{ project.project_name }}
                </Link>
                </td>
                <td class="p-3 border">
                  {{ project.remarks }}
                </td>
              </tr>
 
              <tr v-if="projects.data.length === 0">
                <td colspan="4" class="text-center p-6 text-gray-500">
                  No records found
                </td>
              </tr>
            </tbody>
          </table>
        </div>
 
        <!-- PAGINATION -->
        <div v-if="shouldShowPagination" class="flex justify-center mt-3 gap-2 text-xs">
          <span
            @click="startPage !== 1 && prevBlock()"
            class="px-3 py-2 border rounded cursor-pointer"
            :class="{ 'opacity-50 cursor-not-allowed': startPage === 1 }"
          >
            Prev
          </span>
 
          <span
            v-for="pageNumber in pageNumbers"
            :key="pageNumber"
            @click="goToPage(pageNumber)"
            class="text-xs px-3 py-2 border rounded cursor-pointer"
            :class="pageNumber === currentPage ? 'bg-blue-600 text-white' : ''"
          >
            {{ pageNumber }}
          </span>
 
          <span
            @click="endPage !== lastPage && nextBlock()"
            class="px-3 py-2 border rounded cursor-pointer"
            :class="{ 'opacity-50 cursor-not-allowed': endPage === lastPage }"
          >
            Next
          </span>
        </div>
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
 