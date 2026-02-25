<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
 
const page = usePage<any>()
 
const batches = computed(() => page.props.batches)
const filters = computed(() => page.props.filters)
 
const search = ref(filters.value.search || '')
 
// SEARCH (DB level)
watch(search, (value: string) => {
    router.get('/action/batches', 
        { search: value }, 
        { preserveState: true, replace: true }
    )
})
 
const currentPage = computed(() => batches.value.current_page)
const lastPage = computed(() => batches.value.last_page)
 
const blockSize = 5
 
const currentBlock = computed(() => {
    return Math.ceil(currentPage.value / blockSize)
})
 
const startPage = computed(() => {
    return (currentBlock.value - 1) * blockSize + 1
})
 
const endPage = computed(() => {
    return Math.min(startPage.value + blockSize - 1, lastPage.value)
})

const batchesTotal = computed(()=>page.props.batches_total);
 
const pageNumbers = computed(() => {
    const pages = []
    for (let i = startPage.value; i <= endPage.value; i++) {
        pages.push(i)
    }
    return pages
})
 
function goToPage(pageNumber: number) {
    router.get('/action/batches', 
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
</script>
 
<template>
<AppLayout>
<div class="page-content">
<div class="page-header flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold">Action Batch List</h2>
    <Link href="/action/batches/create"
          class="bg-[#1C7BA5] text-white px-4 py-2 text-xs rounded">
        Create Action Batch
    </Link>
</div>
 
<div class="card bg-white p-6 rounded shadow">
 
    <!-- SEARCH -->
    <div class="mb-4 text-xs">
        <input
            v-model="search"
            placeholder="Search Action Batch"
            class="p-2 border rounded w-full"
        />
    </div>
    <!-- DISPLAY ITEM COUNT -->
    <div class="mb-2 text-xs text-gray-600">
        Showing {{ batches.data.length>0? batches.to: 0 }} out of {{ batchesTotal }} items
    </div>
    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="min-w-full border text-xs">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border text-left">Action Batch</th>
                    <th class="p-3 border text-left">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="batch in batches.data" :key="batch.id"
                    class="hover:bg-gray-50">
                    
                    <td class="p-3 border text-blue-600 hover:underline cursor-pointer"
                        @click="$inertia.get(`/action/batches/${batch.id}`)">
                        {{ batch.action_batch }}
                    </td>
 
                    <td class="p-3 border">
                        {{ batch.remarks }}
                    </td>
                </tr>
 
                <tr v-if="batches.data.length === 0">
                    <td colspan="3" class="text-center p-6 text-gray-500">
                        No records found
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
 
    <div class="flex justify-center mt-3 gap-2 text-xs">
  
  <!-- PREV BLOCK -->
  <span
      @click="startPage !== 1 && prevBlock()"
      class="px-3 py-2 border rounded cursor-pointer"
      :class="{'opacity-50 cursor-not-allowed': startPage === 1}">
      Prev
  </span>

  <!-- PAGE NUMBERS -->
  <span
      v-for="pageNumber in pageNumbers"
      :key="pageNumber"
      @click="goToPage(pageNumber)"
      class="text-xs px-3 py-2 border rounded cursor-pointer"
      :class="pageNumber === currentPage ? 'bg-blue-600 text-white' : ''">
      {{ pageNumber }}
  </span>

  <!-- NEXT BLOCK -->
  <span
      @click="endPage !== lastPage && nextBlock()"
      class="px-3 py-2 border rounded cursor-pointer"
      :class="{'opacity-50 cursor-not-allowed': endPage === lastPage}">
      Next
  </span>

</div>
 
</div>
</div>
</AppLayout>
</template>
 