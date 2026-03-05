<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
 
const page = usePage<any>()
const batch = computed(() => page.props.batch)
 
const form = ref({
    name: batch.value.action_batch,
    remarks: batch.value.remarks
})
 
const showModal = ref(false)
const showSuccess = ref(false)  
 
const submit = () => {
    router.post(`/action/batches/update/${batch.value.id}`, form.value)
    showModal.value = false
}
 
const cancel = () => {
    showModal.value = false
}

const successMessage = computed(() => page.props.flash?.success) 
const closeModal = () => {
  showSuccess.value = false
}

watch(successMessage, (val) => {
  if (val) {
    showSuccess.value = true
  }
}, { immediate: true })
</script>
<template>
  <Head title="Action Batch Detail"/>
 
  <AppLayout>
    <div v-if="showSuccess" 
         class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-full px-4">

      <div class="relative bg-green-500 border border-green-200 rounded-lg shadow-md p-4 flex items-left gap-4 animate-slide-down"> 

        <div class="flex-1 flex justify-start items-left gap-3"> 
          <span class="text-white text-xl"> </span> 
          <p class="text-white text-m font-medium text-left">
            {{ successMessage }} 
          </p>
        </div>

        <button
          style="all: unset; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background-color: rgba(0, 0, 0, 0.3); color: white; font-weight: bold; font-size: 1rem;"
          @click="closeModal">
          X
        </button>

      </div>  
    </div>
 
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
            />
          </div>

          <div class="mt-4 flex flex-col">
            <label class="text-xs font-semibold mb-1 block">Remarks</label>
            <textarea
              v-model="form.remarks"
              rows="5"
              class="border p-2 rounded w-full text-sm"
            ></textarea>
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