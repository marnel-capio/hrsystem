<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
 
const form = ref({
  action_batch: '',
  remarks: ''
})
 
const errors = ref({
  action_batch: ''
})
 
// Validate action batch format: "ACTION <number>"
const validate = () => {
  errors.value.action_batch = ''
  if (!form.value.action_batch.trim()) {
    errors.value.action_batch = 'This field is required.'
    return false
  }
  const regex = /^ACTION \d+$/i
  if (!regex.test(form.value.action_batch.trim())) {
    errors.value.action_batch = 'Format must be "ACTION (Number)" like ACTION 26.'
    return false
  }
  return true
}
 
const submit = () => {
  if (!validate()) return
 
  router.post('/action/batches/store', form.value, {
    onError: () => {
      errors.value.action_batch = 'An error occurred while creating record, please try again.'
    },
    onSuccess: (page) => {
      // Redirect to detail page if server returns created id
      if (page.props.newBatchId) {
        router.visit(`/action/batches/${page.props.newBatchId}`)
      }
    }
  })
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
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1" for="name">Action Batch</label>
          <input
            id="name"
            v-model="form.action_batch"
            placeholder="Name"
            class="border p-2 rounded w-full"
          />
          <span v-if="errors.action_batch" class="text-red-600 text-xs mt-1">{{ errors.action_batch }}</span>
        </div>
      </div>
 
      <div class="grid grid-cols-2 gap-4 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1" for="remarks">Remarks</label>
          <textarea
            id="remarks"
            v-model="form.remarks"
            rows="6"
            placeholder="Remarks"
            class="border p-2 rounded w-full"
          />
        </div>
      </div>
 
      <div class="mt-10 w-full flex justify-end space-x-2">
        <span
          class="px-4 text-xs cursor-pointer border border-black-2 py-2 text-black rounded hover:bg-gray-200"
          @click="$inertia.get(`/action/batches/list`)"
        >
          Cancel
        </span>
        <span
          class="px-4 text-xs cursor-pointer py-2 bg-[#2176ff] text-white rounded hover:bg-blue-400"
          @click="submit"
        >
          Create
        </span>
      </div>
    </div>
  </AppLayout>
</template>