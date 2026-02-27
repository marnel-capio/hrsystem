<script setup lang="ts">
import { onMounted, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const errorMessage = ref('');

onMounted(() => {
    // Get error from URL query param
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    
    if (error) {
        errorMessage.value = decodeURIComponent(error);
        
        // Clean up URL after showing
        const url = new URL(window.location.href);
        url.searchParams.delete('error');
        window.history.replaceState({}, '', url);
    }
});

// Close modal
const closeModal = () => {
    errorMessage.value = '';
};
</script>

<template>
    <AppLayout>

    <div 
        v-if="errorMessage" 
        class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-full px-4"
    >
        <div 
            class="relative bg-red-500 border border-red-200 rounded-lg shadow-md p-4 flex items-center gap-4 animate-slide-down"
        >
            <div class="flex-1 flex justify-center items-center gap-3">
                <span class="text-red text-xl">⚠️</span>
                <p class="text-white text-m font-medium text-center">
                    {{ errorMessage }}
                </p>
            </div>
            
            <button 
                style="all: unset; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background-color: rgba(0, 0, 0, 0.3); color: white; font-weight: bold; font-size: 1rem;"
                @click="closeModal"
            >
                X
            </button>
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