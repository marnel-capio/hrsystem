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

     <!--Modal for error validations-->
<div 
    v-if="errorMessage" 
    class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-auto max-w-4xl px-4"
>
    <div 
        class="relative bg-white border border-red-300 rounded-lg shadow-xl p-4 animate-slide-down"
    >

        <!-- Message -->
        <p class="text-black-700 text-lg">
            ⚠️ {{ errorMessage }}
        </p>

                <!-- Close X -->
        <button 
            @click="closeModal"
            class=""
        >
            OK
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
</style>