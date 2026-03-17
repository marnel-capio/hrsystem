<script setup lang="ts">
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const page = usePage<any>()
const loading = ref(false)

const batch = ref({
  id: page.props.batch?.id ?? 0,
  action_batch: page.props.batch?.action_batch ?? '',
  target_trainees: page.props.batch?.target_trainees ?? 0,
  target_date: page.props.batch?.target_date ?? '',
  remarks: page.props.batch?.remarks ?? '',
})

const formatToUppercase = () => {
  batch.value.action_batch = batch.value.action_batch.toUpperCase()
}

const submit = () => {
    loading.value = true
 
    router.post(`/action/batches/${batch.value.id}/update`, batch.value, {
        onFinish: () => {
            loading.value = false
        }
    })
}
</script>

<template>
  <Head title="Action Batch Register" />
  <AppLayout :errors="page.props.errors">
    <div class="flex justify-between items-center mx-5 mb-3">
      <h2 class="text-xl font-bold">Edit Action Batch</h2>
    </div>

    <!-- Form Fields -->
    <div class="text-xs overflow-x-auto mt-6 mr-4 p-6 bg-white shadow-lg rounded-lg border ml-5">
      <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">Action Batch</label>
          <input
            v-model="batch.action_batch"
            @input="formatToUppercase"
            placeholder="Action batch"
            class="border p-2 rounded w-full"
          />
          <span v-if="page.props.errors?.action_batch" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.action_batch }}
          </span>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">Target Trainees</label>
          <input
            v-model="batch.target_trainees"
            placeholder="Target Trainees"
            type="number"
            class="border p-2 rounded w-full"
          />
          <span v-if="page.props.errors?.target_trainees" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.target_trainees }}
          </span>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">Target Start Date</label>
          <input
            v-model="batch.target_date"
            type="month"
            placeholder="Target Start Date"
            class="border p-2 rounded w-full"
          />
          <span v-if="page.props.errors?.target_date" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.target_date }}
          </span>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">Remarks</label>
          <textarea
            v-model="batch.remarks"
            rows="6"
            placeholder="Remarks"
            class="border p-2 rounded w-full"
          />
          <span v-if="page.props.errors?.remarks" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.remarks }}
          </span>
        </div>
      </div>

      <div class="mt-10 w-full flex justify-end space-x-2">
        <span
          class="px-4 text-xs cursor-pointer border py-2 rounded hover:bg-gray-200"
          @click="$inertia.get(`/action/batches/${batch.id}`)"
        >
          Cancel
        </span>

        <span
          class="px-4 text-xs cursor-pointer py-2 bg-[#2F359E] text-white rounded hover:bg-blue-400"
          @click="submit"
        >
          Update
        </span>
      </div>
    </div>
  </AppLayout>
</template>