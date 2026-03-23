<script setup lang="ts">
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const page = usePage<any>()
const loading = ref(false)

const form = ref({
  id: page.props.batch?.id ?? 0,
  action_batch: page.props.batch?.action_batch ?? '',
  target_trainees: page.props.batch?.target_trainees ?? 0,
  target_date: page.props.batch?.target_date ?? '',
  remarks: page.props.batch?.remarks ?? '',
  processing: false,
})


const submit = () => {
  form.value.processing = true
 
  router.post(`/action/batches/${form.value.id}/update`, form.value, {
    onFinish: () => {
      form.value.processing = false
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
            v-model="form.action_batch"
            readonly
            class="border p-2 rounded w-full bg-gray-100 cursor-not-allowed"
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
            v-model="form.target_trainees"
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
        <div class="flex flex-col col-span-2 w-40">
          <label class="text-xs font-semibold mb-1">Target Start Date</label>
          <input
            v-model="form.target_date"
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
            v-model="form.remarks"
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
        <button
          type="button"
          class="px-4 text-xs !border !border-gray-300 py-2 rounded 
                !bg-secondary !hover:bg-gray-700 !text-black
                !w-fit inline-flex items-center justify-center shrink-0"
          @click="$inertia.get(`/action/batches/${form.id}`)"
        >
          Cancel
        </button>


        <button
          type="button"
          class="px-4 text-xs py-2 bg-[#2F359E] text-white rounded 
                hover:bg-blue-400 
                disabled:opacity-60 disabled:cursor-not-allowed
                !w-fit inline-flex items-center justify-center"
          @click="submit"
          :disabled="form.processing"
        >
          {{ form.processing ? 'Updating...' : 'Update' }}
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.btn-primary.disabled-btn {
  opacity: 0.6;
  cursor: not-allowed;
  pointer-events: none;
}

.btn-primary:not(.disabled-btn):hover {
  background: #3b82f6;
  width: 50px;
}
</style>