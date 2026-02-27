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
            <div class="alert-error-banner">
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

/* Full-width top alert */
.full-width-alert {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    z-index: 1055;
    display: flex;
    justify-content: center;
    pointer-events: none;
}

.alert-error-banner {
    background-color: #dc2626; /* red for error */
    color: #fff;
    padding: 0.4rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    pointer-events: auto;
    animation: slideDown 0.4s ease-out;
    font-size: 0.95rem;
}

.alert-body {
    flex: 1;
    font-weight: 500;
    text-align: center;
}

.close-btn {
    background-color: rgba(255, 255, 255, 0.2);
    border: none;
    color: #fff;
    font-size: 1rem;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, transform 0.2s;
}

.close-btn:hover {
    background-color: rgba(255, 255, 255, 0.35);
    transform: scale(1.1);
}

@keyframes slideDown {
    0% {
        transform: translateY(-100%);
        opacity: 0;
    }

    100% {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>