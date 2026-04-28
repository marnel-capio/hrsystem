<script setup lang="ts">
import { computed, ref, watch, onMounted } from 'vue'
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
const canCreateRR = computed(() => {
  return [1, 5].includes(userPermissions.value);
})

const successMessage = computed(() => (page.props.flash as any)?.success || '');
const errorMessage = computed(() => (page.props.flash as any)?.error || '');

const showSuccess = ref(successMessage.value);
const showError = ref(false);

onMounted(() => {
  if (successMessage.value) {
    showSuccess.value = true;

    setTimeout(() => {
      showSuccess.value = false;
    }, 3000); 
  }

  if (errorMessage.value) {
    showError.value = true;
  }
});

onMounted(() => {
  console.log('Requisition data:', requisitions.value);
});

onMounted(() => {
  console.log('Requisitions:', requisitions.value); // Check if businessUnit is loaded
});

</script>

<template>
  <AppLayout>
    <div v-if="showSuccess" class="full-width-alert">
      <div class="alert-banner alert-success-banner">
        <div class="alert-body">{{ successMessage }}</div>
        <button class="close-btn" @click="showSuccess = false">×</button>
      </div>
    </div>
    <div v-if="showError" class="full-width-alert">
      <div class="alert-banner alert-error-banner">
        <div class="alert-body">{{ errorMessage }}</div>
        <button class="close-btn" @click="showError = false">×</button>
      </div>
    </div>
    <div class="page-content">

      <!-- PAGE HEADER -->
      <div class="page-header">
        <h2 class="page-title">Resource Requisition List</h2>
        <Link v-if="canCreateRR" :href="`/intermediate/resource-requisitions/register`"
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
          <input v-model="search" type="text" placeholder="Search by Project Name, Business Unit, Location Assignment, Start Date, and Required Skills"
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
                <th class="borderx-3 py-2">No. of Resources Needed</th>
                <th class="border p-3 py-2">Business Unit</th>
                <th class="borderx-3 py-2">Location Assignment</th>
                <th class="border px-3 py-2">Start Date</th>
                <th class="border px-3 py-2">Required Skills</th>
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
                  <Link :href="`/intermediate/resource-requisitions/${requisition.id}`" class="table-link">
                    {{ requisition.project?.project_name }}
                  </Link>
                </td>
                <td class="border px-3 py-2">{{ requisition.no_resources_needed }}</td>
                <td class="border px-3 py-2">
  {{ requisition.business_unit?.business_unit }}
</td>
                
                <td class="border px-3 py-2">
                  {{ (requisition.location_assignment === '6' || requisition.location_assignment === 6) && requisition.custom_location
                    ? requisition.custom_location 
                    : locationMap[Number(requisition.location_assignment)] || 'No location specified' }}
                </td>
                <td class="border px-3 py-2">{{ formatDate(requisition.start_date) }}</td>
                <td class="border px-3 py-2 truncate">{{ requisition.required_skills }}</td>
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
.truncate {
  max-width: 30ch;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.ats-table {
    min-width: 0 !important;
    table-layout: fixed;
}


.ats-table td:hover {
  max-width: none;
  overflow: visible;
  white-space: normal;
  z-index: 10;
}

</style>