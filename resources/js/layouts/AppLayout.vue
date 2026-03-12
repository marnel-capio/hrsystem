<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue'
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'
import { computed, ref, watchEffect } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage<any>()
const flash = computed(() => page.props.flash)

const showError = ref(false)
const showSuccess = ref(false)

watchEffect(() => {
  if (page.props.errors?.error) {
    showError.value = true
    setTimeout(() => {
      showError.value = false
    }, 5000)
  }
})

watchEffect(() => {
  if (page.props.success?.success) {
    showSuccess.value = true
    setTimeout(() => {
      showSuccess.value = false
    }, 5000)
  }
})
</script>

<template>
  <div class="app-shell">
    <div v-if="showSuccess && page.props.success?.success" class="success-message">
      {{ page.props.success.success }}
    </div>

    <div v-if="showError && page.props.errors?.error" class="error-message">
      {{ page.props.errors.error }}
    </div>

    <AppSidebar />
    <div class="app-main">
      <AppHeader />
      <main class="main-content">
        <slot />
      </main>
      <AppFooter />
    </div>
  </div>
</template>

<style scoped>
.app-shell {
  display: flex;
  width: 100%;
  min-height: 100vh;
  overflow-x: hidden;
}

.app-main {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  min-width: 0;
  overflow-x: hidden;
}

.error-message,
.success-message {
  font-size: 14px;
  padding: 10px 20px;
  text-align: left;
  width: 100%;
  position: fixed;
  left: 0;
  z-index: 1000;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  color: #fff;
  animation: slide-down 0.5s ease-out;
}

.error-message {
  background-color: #e11212;
}

.success-message {
  background-color: #4CAF50; /* Green color for success */
  top: 40px; /* To avoid covering the header if needed */
}

/* Slide down animation */
@keyframes slide-down {
  from {
    top: -60px;
    opacity: 0;
  }
  to {
    top: 0;
    opacity: 1;
  }
}
</style>