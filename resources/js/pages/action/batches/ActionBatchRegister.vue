<script setup lang="ts">
import { ref } from 'vue'
import { router, usePage, Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
 
const page = usePage<any>()
const loading = ref(false)
 
const form = ref({
  action_batch: page.props.batchOptions?.[0] || '',
  target_trainees: '',
  target_date: '',
  remarks: '',
  processing: false,
})
 
const submit = () => {
  form.value.processing = true
 
  router.post('/action/batches', form.value, {
    onFinish: () => {
      form.value.processing = false
      loading.value = false
    }
  })
}
</script>
 
<template>
  <Head title="ACTION Batch Register" />
 
  <AppLayout :errors="page.props.errors">
    <div class="flex justify-between items-center mx-5 mb-3">
      <h2 class="text-xl font-bold">Create ACTION Batch</h2>
    </div>
 
    <!-- Form -->
    <div class="text-xs overflow-x-auto mt-6 mr-4 p-6 bg-white shadow-lg rounded-lg border ml-5">
 
      <!-- ACTION Batch Dropdown -->
      <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">ACTION Batch</label>
 
          <select v-model="form.action_batch" class="border p-2 rounded w-full">
            <option disabled value="">Select ACTION Batch</option>
            <option
              v-for="option in page.props.batchOptions"
              :key="option"
              :value="option"
            >
              {{ option }}
            </option>
          </select>
 
          <span v-if="page.props.errors?.action_batch" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.action_batch }}
          </span>
        </div>
      </div>
 
      <!-- Target Trainees -->
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
 
      <!-- Target Date -->
      <div class="grid grid-cols-2 gap-4 mt-5 w-40">
        <div class="flex flex-col col-span-2">
          <label class="text-xs font-semibold mb-1">Target Start Date</label>
 
          <input
            v-model="form.target_date"
            type="month"
            class="border p-2 rounded w-full"
          />
 
          <span v-if="page.props.errors?.target_date" class="text-red-600 text-xs mt-1">
            {{ page.props.errors.target_date }}
          </span>
        </div>
      </div>
 
      <!-- Remarks -->
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
 
      <!-- Buttons -->
      <div class="form-actions">
        <button
          class="btn btn-secondary cursor-pointer"
          @click="$inertia.get('/action/batches')"
        >
          Cancel
      </button>
 
        <button
            type="button"
            class="btn btn-primary"
            @click="submit"
            :disabled="form.processing"
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