<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue'
import AppHeader from '@/components/AppHeader.vue'
import AppFooter from '@/components/AppFooter.vue'
import '../../css/ats.css'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
 
const page = usePage<any>()
const flash = computed(() => page.props.flash)
</script>

<template>
  <div class="app-shell">
    <AppSidebar />

    <div class="app-main">
      <AppHeader />
      <div v-if="flash.error"
          class="bg-red-100 text-red-700 p-3 mb-4 rounded text-xs align-center">
          {{ flash.error }}
      </div>
      
      <div v-if="flash.success"
          class="bg-green-100 text-green-700 p-3 mb-4 rounded text-xs">
          {{ flash.success }}
      </div>
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
  min-width: 0;      /* 🔑 THIS LINE FIXES THE OVERFLOW */
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  min-width: 0;
  overflow-x: hidden;
}
</style>
