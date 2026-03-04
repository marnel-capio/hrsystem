<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, onMounted } from 'vue'

const props = defineProps<{
  flash?: {
    error?: string
  }
}>()

// Toast state
const showError = ref(false)
const errorMessage = ref<string | null>(null)

// Show toast if flash.error exists
onMounted(() => {
  if (props.flash?.error) {
    errorMessage.value = props.flash.error
    showError.value = true

    // Auto hide after 5 seconds
    setTimeout(() => {
      showError.value = false
    }, 5000)
  }
})
</script>

<template>
  <AppLayout>
    <!-- Error Toast -->
    <div v-if="showError" class="full-width-alert">
      <div class="alert-banner alert-error-banner">
        <div class="alert-body">{{ errorMessage }}</div>
        <button type="button" class="close-btn" @click="showError = false">×</button>
      </div>
    </div>

    <div class="dashboard-wrapper">
      <h1 class="dashboard-title">
        Hi, this is the HR Dashboard Page.
      </h1>
    </div>
  </AppLayout>
</template>

<style scoped>
.dashboard-wrapper {
  min-height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.dashboard-title {
  font-size: 1.6rem;
  font-weight: 600;
  color: var(--ats-text);
}
</style>