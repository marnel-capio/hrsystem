<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
 
const form = ref({
  action_batch: '',
  status: 1, // default active
  remarks: ''
})
 
const submit = () => {
  router.post('/action/batches/store', form.value)
}
</script>
 
<template>
  <Head title="Action Batch Register"/>
  <AppLayout>
    <div class="flex justify-between items-center mx-5 mb-3">
      <h2 class="text-xl font-bold">Action Batch Register</h2>
    </div>
 
    <div class="text-xs overflow-x-auto ml-15 mt-6 mr-4 p-6 bg-white shadow-lg rounded-lg border ml-5">
      <h2 class="text-lg font-bold mb-5">Register Action Batch</h2>
 
      <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col">
          <label class="text-xs font-semibold mb-1" for="name">Action Batch</label>
          <input v-model="form.action_batch" id="name" placeholder="Name" class="border p-2 rounded" />
        </div>
        <div class="flex flex-col">
          <label class="text-xs font-semibold mb-1" for="status">Status</label>
          <select v-model="form.status" id="status" class="border p-2 rounded">
            <option :value="1">New</option>
            <option :value="0">Completed</option>
          </select>
        </div>
      </div>
 
      <div class="grid grid-cols-1 gap-4 mt-5">
        <div class="flex flex-col">
          <label class="text-xs font-semibold mb-1" for="remarks">Remarks</label>
          <textarea v-model="form.remarks" id="remarks" rows="6" placeholder="Remarks" class="border p-2 rounded" />
        </div>
      </div>
 
      <div class="mt-10 w-full flex justify-end space-x-2">
        <span
          class="px-4 cursor-pointer border border-black-2 py-2 text-black rounded hover:bg-gray-200"
          @click="$inertia.get(`/action/batches/list`)">
          Cancel
        </span>
        <span
          class="px-4 cursor-pointer py-2 bg-[#2176ff] text-white rounded hover:bg-blue-400"
          @click="submit">
          Create
        </span>
      </div>
    </div>
  </AppLayout>
</template>