<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
 
const page = usePage<any>()
const batch = page.props.batch
const flash = page.props.flash || {}
</script>
 
<template>
  <Head title="Action Batch Detail" />
  <AppLayout>
    <div class="flex justify-between items-center mx-5 mb-3">
      <h2 class="text-xl font-bold">Action Batch Details</h2>
    </div>
 
    <!-- Success message -->
    <div v-if="flash.success" class="bg-green-100 text-green-700 p-3 mb-4 rounded text-xs">
      {{ flash.success }}
    </div>
 
    <div class="text-xs overflow-x-auto ml-15 mt-2 mr-4 p-6 bg-white shadow-lg rounded-lg border ml-5">
      <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">Action Batch Name</label>
          <span class="p-2 border rounded bg-gray-50">{{ batch.action_batch }}</span>
        </div>
      </div>
 
      <div class="grid grid-cols-2 gap-4 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">Remarks</label>
          <span class="p-2 border rounded bg-gray-50">{{ batch.remarks || 'No remarks' }}</span>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
 