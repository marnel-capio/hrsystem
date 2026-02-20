<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import type { PageProps as InertiaPageProps } from '@inertiajs/core'
 
interface Batch {
  id: number
  action_batch: string
  status: number
  remarks: string
}
 
interface PageProps extends InertiaPageProps {
  batches: {
    data: Batch[]
    links: any[]
    current_page: number
    last_page: number
  }
  filters: { search?: string }
}
 
const page = usePage<PageProps>()
const batches = computed(() => page.props.batches?.data ?? [])
const links = computed(() => page.props.batches?.links ?? [])
const searchQuery = ref<string>(page.props.filters?.search ?? '')
 
watch(searchQuery, (value: string) => {
  router.get(
    '/action/batches/list',
    { search: value },
    { preserveState: true, replace: true }
  )
})
</script>
<div v-if="$page.props.flash.success" class="mb-4 p-2 bg-green-100 text-green-800 rounded">
  {{ $page.props.flash.success }}
</div>
<template>
  <AppLayout>
    <div class="page-content">
      <div class="page-header flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Action Batch List</h2>
        <Link href="/action/batches/create" class="bg-blue-600 text-white px-4 py-2 text-xs rounded">Register Action Batch</Link>
      </div>
 
      <div class="card bg-white p-6 rounded shadow">
 
        <!-- SEARCH -->
        <div class="mb-4">
          <input v-model="searchQuery" placeholder="Search Action Batch" class="p-2 border rounded w-full" />
        </div>
 
        <!-- TABLE (ALWAYS VISIBLE) -->
        <div class="overflow-x-auto">
          <table class="min-w-full border text-xs">
            <thead class="bg-gray-100">
              <tr>
                <th class="p-3 border text-left">Action Batch</th>
                <th class="p-3 border text-left">Status</th>
                <th class="p-3 border text-left">Remarks</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="batch in batches" :key="batch.id" v-if="batches.length" class="hover:bg-gray-50">
                <!-- Make Action Batch clickable to details page -->
                <td class="p-3 border text-blue-600 hover:underline cursor-pointer" @click="$inertia.get(`/action/batches/${batch.id}`)">
                  {{ batch.action_batch }}
                </td>
                <td class="p-3 border">{{ batch.status === 1 ? 'New' : 'Completed' }}</td>
                <td class="p-3 border">{{ batch.remarks ?? '—' }}</td>
              </tr>
 
              <!-- No records -->
              <tr v-if="!batches.length">
                <td colspan="3" class="text-center p-6 text-gray-500">No records found</td>
              </tr>
            </tbody>
          </table>
        </div>
 
        <!-- PAGINATION -->
        <div class="flex justify-center mt-6 gap-2 text-xs">
          <Link v-for="link in links" :key="link.label" :href="link.url ?? ''" v-html="link.label"
                class="px-3 py-1 border rounded"
                :class="{
                  'bg-blue-600 text-white': link.active,
                  'text-gray-400 pointer-events-none': !link.url
                }"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
 