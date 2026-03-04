<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
  schedule: any;
}>();

const page = usePage();

// Success notification
const successMessage = computed(() => (page.props.flash as any)?.success || '');
const showSuccess = ref(false);

onMounted(() => {
  if (successMessage.value) {
    showSuccess.value = true;
    setTimeout(() => showSuccess.value = false, 5000);
  }
});

function closeSuccess() {
  showSuccess.value = false;
}
</script>

<template>
  <Head title="Resource Schedule Details" />
  
  <AppLayout>
    <!-- Success Notification -->
    <div 
      v-if="showSuccess"
      class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-full px-4"
    >
      <div 
        class="relative bg-green-500 border-green-200 rounded-lg shadow-md p-4 flex items-center justify-between gap-4 animate-slide-down"
      >
        <!-- Success Message -->
        <p class="text-white text-m font-medium text-left flex-1">
          {{ successMessage }}
        </p>

        <!-- Close Button -->
        <button 
          style="all: unset; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background-color: rgba(0, 0, 0, 0.3); color: white; font-weight: bold; font-size: 1rem;"
          @click="closeSuccess"
        >
          X
        </button>
      </div>
    </div>

    <!-- details page content -->
    <div class="max-w-5xl mx-auto w-full space-y-10 p-8">
      <h1 class="text-3xl font-bold mb-6">Resource Schedule Details</h1>
    </div>
  </AppLayout>
</template>

<style scoped>
@keyframes slide-down {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-slide-down {
  animation: slide-down 0.3s ease-out;
}
</style>