<script setup lang="ts">
import { ref } from 'vue'
import { router, usePage, Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const page = usePage<any>()
const loading = ref(false)

const form = ref({
  project_name: '',
  remarks: '',
  processing: false,
})

const maxProjectNameLength = 20
const maxRemarksLength = 1024


const projectNameError = ref('')
const remarksError = ref('')

const validateProjectName = () => {
  projectNameError.value = form.value.project_name.length > maxProjectNameLength
    ? 'This field exceeds the maximum allowed length.'
    : ''
}

const validateRemarks = () => {
  remarksError.value = form.value.remarks.length > maxRemarksLength
    ? 'This field exceeds the maximum allowed length.'
    : ''
}

const submit = () => {
  form.value.processing = true

  router.post('/intermediate/projects', form.value, {
    onFinish: () => {
      form.value.processing = false
      loading.value = false
    }
  })
}
</script>
 
<template>
  <Head title="Project Register" />

  <AppLayout :errors="page.props.errors">
    <div class="flex justify-between items-center mx-5 mb-3">
      <h2 class="text-xl font-bold">Create Project</h2>
    </div>

    <!-- Form -->
    <div class="text-xs overflow-x-auto mt-6 mr-4 p-6 bg-white shadow-lg rounded-lg border ml-5">

      <!-- Project Dropdown -->
      <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">Project Name</label>
          <input
            v-model="form.project_name"
            @input="validateProjectName"
            placeholder="Project Name"
            class="border p-2 rounded w-full"
          />

          <span v-if="projectNameError" class="text-red-600 text-xs mt-1">
            {{ projectNameError }}
          </span>
          <span v-if="page.props.errors?.project_name" class="text-red-600 text-xs mt-1"> {{ page.props.errors.project_name }} </span>
        </div>
      </div>

      <!-- Remarks -->
      <div class="grid grid-cols-2 gap-4 mt-5">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">Remarks</label>

          <textarea
            v-model="form.remarks"
            @input="validateRemarks"
            rows="6"
            placeholder="Remarks"
            class="border p-2 rounded w-full"
          />

          <span v-if="remarksError" class="text-red-600 text-xs mt-1">
            {{ remarksError }}
          </span>
          <span v-if="page.props.errors?.remarks" class="text-red-600 text-xs mt-1"> {{ page.props.errors.remarks }} </span>
        </div>
      </div>

      <!-- Buttons -->
      <div class="form-actions">
        <button
          class="btn btn-secondary cursor-pointer"
          @click="$inertia.get('/intermediate/projects')"
        >
          Cancel
        </button>

        <button
  type="button"
  class="btn btn-primary"
  @click="submit"
  :disabled="form.processing || projectNameError || remarksError || page.props.errors?.project_name || page.props.errors?.remarks"
>
  {{ form.processing ? 'Creating…' : 'Create' }}
</button>
      </div>

    </div>
  </AppLayout>
</template>
 
<style scoped>
.form-actions {
  margin-top: 2rem;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 0.5rem;
}
 
.form-actions button,
.form-actions a {
  flex: 0 0 auto;
  width: auto;
}

button[type="submit"]:hover:not(:disabled) {
  background: var(--ats-accent);
}
 
button[type="submit"],
.btn-secondary {
  padding: 0.5rem 1.2rem;
  font-size: 0.85rem;
  border-radius: 5px;
  font-weight: 500;
  white-space: nowrap;
  transition: background 0.15s ease;
}
 
button[type="submit"] {
  border: none;
  background: var(--ats-primary);
  color: #fff;
  cursor: pointer;
}
 
button[type="submit"]:hover:not(:disabled) {
  background: var(--ats-accent);
}
 
button[type="submit"]:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
 
.btn-secondary {
  border: 1px solid #d1d5db;
  background: #f3f4f6;
  color: #374151;
}
 
.btn-secondary:hover {
  background: #e5e7eb;
}
</style>