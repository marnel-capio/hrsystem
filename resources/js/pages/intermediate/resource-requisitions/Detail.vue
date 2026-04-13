<script setup lang="ts">
import { Head, router, usePage, Link } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'

const page = usePage<any>()

// Success message state
const showSuccessMessage = ref(!!page.props.flash?.success)

// Computed property for success message
const successMessage = computed(() => page.props.flash?.success)

// Method to close the success modal
const closeModal = () => {
  showSuccessMessage.value = false
}
</script>

<template>
  <Head title="Action Batch Detail"/>

  <AppLayout>
    <!-- Success Toast -->
    <div v-if="showSuccessMessage" class="full-width-alert">
      <div class="alert-banner alert-success-banner">
        <div class="alert-body">{{ successMessage }}</div>
        <button type="button" class="close-btn" @click="closeModal">×</button>
      </div>
    </div>

    <h2 class="text-xl font-bold">Resource Requisition Detail</h2>
  </AppLayout>
</template>

<style scoped>
/* Optional styling for the close button */
.close-btn {
  font-size: 20px;
  font-weight: bold;
  background: none;
  border: none;
  cursor: pointer;
}
</style>