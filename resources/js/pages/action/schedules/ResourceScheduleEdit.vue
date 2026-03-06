<template>
  <AppLayout>
    <div class="max-w-4xl mx-auto py-8">
      <h1 class="text-2xl font-bold mb-6">Edit Resource Schedule</h1>

      <!-- Success/Error flash messages -->
      <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 p-4 mb-4 rounded">
        {{ $page.props.flash.success }}
      </div>
      <div v-if="$page.props.flash?.error" class="bg-red-100 border border-red-400 text-red-700 p-4 mb-4 rounded">
        {{ $page.props.flash.error }}
      </div>

      <!-- Form -->
      <form @submit.prevent="submitForm" class="space-y-4">
        <!-- Action Batch -->
        <div>
          <label class="block font-semibold mb-1">Action Batch</label>
          <select v-model="form.action_batch_id" class="w-full border rounded p-2">
            <option disabled value="">Select Action Batch</option>
            <option v-for="(batch, id) in newBatches" :key="id" :value="id">
              {{ batch }}
            </option>
          </select>
          <p v-if="form.errors.action_batch_id" class="text-red-500 text-sm mt-1">{{ form.errors.action_batch_id }}</p>
        </div>

        <!-- Previous Batch -->
        <div>
          <label class="block font-semibold mb-1">Previous Batch</label>
          <select v-model="form.prev_batch_id" class="w-full border rounded p-2">
            <option disabled value="">Select Previous Batch</option>
            <option v-for="(batch, id) in prevBatches" :key="id" :value="id">
              {{ batch }}
            </option>
          </select>
          <p v-if="form.errors.prev_batch_id" class="text-red-500 text-sm mt-1">{{ form.errors.prev_batch_id }}</p>
        </div>

        <!-- Target Location -->
        <div>
          <label class="block font-semibold mb-1">Target Location</label>
          <select v-model="form.target_location" class="w-full border rounded p-2">
            <option disabled value="">Select Location</option>
            <option value="1">Manila</option>
            <option value="2">Cebu</option>
          </select>
          <p v-if="form.errors.target_location" class="text-red-500 text-sm mt-1">{{ form.errors.target_location }}</p>
        </div>

        <!-- Start Week -->
        <div>
          <label class="block font-semibold mb-1">Start Week</label>
          <input
            type="text"
            v-model="form.start_week"
            placeholder="YYYY-WW"
            class="w-full border rounded p-2"
          />
          <p v-if="form.errors.start_week" class="text-red-500 text-sm mt-1">{{ form.errors.start_week }}</p>
        </div>

        <!-- End Week -->
        <div>
          <label class="block font-semibold mb-1">End Week</label>
          <input
            type="text"
            v-model="form.end_week"
            placeholder="YYYY-WW"
            class="w-full border rounded p-2"
          />
          <p v-if="form.errors.end_week" class="text-red-500 text-sm mt-1">{{ form.errors.end_week }}</p>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-2 mt-4">
          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold"
          >
            Save
          </button>

          <Link
            :href="`/action/schedules/${schedule.id}`"
            class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 font-semibold"
          >
            Cancel
          </Link>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps<{
  schedule: any,
  newBatches: Record<number, string>,
  prevBatches: Record<number, string>,
  errorMessages: Record<string, string>,
}>()

// Initialize form with prefilled schedule data
const form = useForm({
  action_batch_id: props.schedule.action_batch_id || '',
  prev_batch_id: props.schedule.prev_batch_id || '',
  target_location: props.schedule.target_location || '',
  start_week: props.schedule.start_week || '',
  end_week: props.schedule.end_week || '',
})

// Submit form via PUT to update route
function submitForm() {
  form.put(`/action/schedules/${props.schedule.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      console.log('Update successful!')
    },
  })
}
</script>

<style scoped>
/* Optional: add a small slide-down animation for flash messages */
</style>