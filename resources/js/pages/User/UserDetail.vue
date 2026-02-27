<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const props = defineProps<{
    user: any
    flash?: {
        success?: string
    }
}>()

// Toast state
const showSuccess = ref(false)
const successMessage = ref<string | null>(null)

// Show toast if flash.success exists
onMounted(() => {
    if (props.flash?.success) {
        successMessage.value = props.flash.success
        showSuccess.value = true

        setTimeout(() => {
            showSuccess.value = false
        }, 500000)
    }
})
</script>

<template>
    <AppLayout>
        <!-- Success Toast -->
        <div v-if="showSuccess" class="full-width-alert">
            <div class="alert-success-banner">
                <div class="alert-body">{{ successMessage }}</div>
                <button type="button" class="close-btn" @click="showSuccess = false">×</button>
            </div>
        </div>
        <div class="dashboard-wrapper">
            <h1 class="dashboard-title">
                Hi, this is the User Detail Page.
            </h1>
        </div>
    </AppLayout>
</template>

<style scoped>
.page-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.card {
    width: 100%;
    max-width: 700px;
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.card-header h2 {
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    font-weight: 600;
}

.detail-row {
    margin-bottom: 1rem;
}

.detail-row label {
    font-size: 0.85rem;
    color: #6b7280;
    display: block;
}

.detail-row p {
    font-size: 1rem;
    margin-top: 0.25rem;
}

.badge-active {
    background: #dcfce7;
    color: #166534;
    padding: 0.3rem 0.6rem;
    border-radius: 999px;
    font-size: 0.8rem;
}

.badge-inactive {
    background: #fee2e2;
    color: #991b1b;
    padding: 0.3rem 0.6rem;
    border-radius: 999px;
    font-size: 0.8rem;
}

.card-footer {
    margin-top: 2rem;
    display: flex;
    justify-content: flex-end;
}

.btn-secondary {
    padding: 0.5rem 1.2rem;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 5px;
    text-decoration: none;
    color: #374151;
}

.btn-secondary:hover {
    background: #e5e7eb;
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

.alert-success-banner {
    background-color: #28a745;
    color: #fff;
    padding: 0.4rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
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