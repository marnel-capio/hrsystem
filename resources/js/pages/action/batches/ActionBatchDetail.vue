<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
 
const page = usePage<any>()
const batch = computed(() => page.props.batch)
 
const form = ref({
    name: batch.value.action_batch,
    remarks: batch.value.remarks
})
 
const showModal = ref(false)
 
const submit = () => {
    router.post(`/action/batches/update/${batch.value.id}`, form.value)
    showModal.value = false
}
 
const cancel = () => {
    showModal.value = false
}
</script>
 
<template>
  <Head title="Action Batch Detail"/>
 
  <AppLayout>
 
    <!-- Header -->
    <div class="flex justify-between mx-5 mb-3">
      <h2 class="text-xl font-bold">Action Batch Detail</h2>
 
      <span
        @click="showModal = true"
        class="bg-[#1C7BA5] text-white px-4 py-2 rounded shadow hover:bg-blue-500 text-xs"
      >
        Edit
    </span>
    </div>
 
    <!-- Main Content -->
    <div class="mx-5 mt-6 grid grid-cols-3 gap-6">
 
      <!-- LEFT -->
      <div class="col-span-1 space-y-4">
 
        <div class="bg-[#2F359E] text-white rounded-xl p-6 shadow">
          <h3 class="text-lg font-bold text-center">{{ batch.action_batch }}</h3>
          <p class="text-xs opacity-80 text-center">Action Batch Detail</p>
        </div>
 
        <div class="bg-white rounded-xl p-4 shadow border">
          <table class="min-w-full table-auto">
          <tbody>
            <tr>
              <th class="px-2 py-2 text-left font-semibold text-xs text-gray-600 w-40">
                Created by
              </th>
              <td class="text-xs px-2"></td>
            </tr>
 
            <tr>
              <th class="px-2 py-2 text-left font-semibold text-xs text-gray-600">
                Updated by
              </th>
              <td class="text-xs px-2"></td>
            </tr>
 
            <tr>
              <th class="px-2 py-2 text-left font-semibold text-xs text-gray-600">
                Created Time
              </th>
              <td class="text-xs px-2"></td>
            </tr>
 
            <tr>
              <th class="px-2 py-2 text-left font-semibold text-xs text-gray-600">
                Updated Time
              </th>
              <td class="text-xs px-2"></td>
            </tr>
          </tbody>
        </table>
        </div>
 
      </div>
 
      <!-- RIGHT -->
      <div class="col-span-2 bg-white rounded-xl shadow border p-6">
 
        <h4 class="text-xs font-bold mb-3 text-center">REMARKS</h4>
          <p class="text-xs ">{{ batch.remarks }}</p>
 
      </div>
    </div>
 
    <!-- MODAL -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    >
      <div class="bg-white w-[600px] rounded-xl shadow-lg p-6">
 
        <h3 class="text-lg font-bold mb-4">Edit Action Batch</h3>
 
        <div class="grid grid-cols-1 gap-4">
          <div class="flex flex-col">
            <label class="text-xs font-semibold mb-1">Action Batch</label>
            <input
              v-model="form.name"
              class="border p-2 rounded text-sm w-full"
            />{{ action_batch }}
          </div>

          <div class="mt-4 flex flex-col">
            <label class="text-xs font-semibold mb-1 block">Remarks</label>
            <textarea
              v-model="form.remarks"
              rows="5"
              class="border p-2 rounded w-full text-sm"
            >{{ remarks }}</textarea>
          </div>
        </div>
 
        <div class="flex justify-end mt-6 space-x-1">
          <span
            @click="cancel"
            class="px-2 py-1 border rounded cursor-pointer bg-gray-100 hover:bg-gray-300"
          >
            Cancel
          </span>
 
          <span
            @click="submit"
            class="px-2 py-1 bg-[#2176ff] cursor-pointer text-white rounded hover:bg-blue-400"
          >
            Update
          </span>
 
        </div>
 
      </div>
    </div>
 
  </AppLayout>
</template>
 