<script setup lang="ts">
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const page = usePage<any>()
const loading = ref(false)

const form = ref({
  id: page.props.project?.id ?? 0,
  project_name: page.props.project?.project_name ?? '',
  remarks: page.props.project?.remarks ?? '',
  processing: false,
})


const submit = () => {
  form.value.processing = true
 
  router.post(`/intermediate/projects/${form.value.id}/update`, form.value, {
    onFinish: () => {
      form.value.processing = false
      loading.value = false
    }
  })
}
console.log(form.value);

</script>

<template>
  <Head title="Project Register" />
  <AppLayout :errors="page.props.errors">
    <div class="flex justify-between items-center mx-5 mb-3">
      <h2 class="text-xl font-bold">Edit Project</h2>
    </div>

    <!-- Form Fields -->
    <div class="text-xs overflow-x-auto mt-6 mr-4 p-6 bg-white shadow-lg rounded-lg border ml-5">
      <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">Project Name</label>
          <input
            v-model="form.project_name"
            readonly
            class="border p-2 rounded w-full bg-gray-100 cursor-not-allowed"
          />
          <span v-if="page.props.errors?.project_name" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.project_name }}
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
          class="px-4 text-xs !h-10 !border !border-gray-300 py-2 rounded 
                !bg-secondary !hover:bg-gray-700 !text-black
                !w-fit inline-flex items-center justify-center shrink-0"
          @click="$inertia.get(`/intermediate/projects/${form.id}`)"
        >
          Cancel
        </button>


        <button
          type="button"
          class="px-4 text-xs !h-10 py-2 bg-[#2F359E] text-white rounded 
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