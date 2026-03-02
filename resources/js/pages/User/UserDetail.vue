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
        }, 5000) // 5 seconds is more reasonable
    }
})
</script>

<template>
    <AppLayout>
        <!-- Success Toast -->
        <div v-if="showSuccess" class="full-width-alert">
            <div class="alert-banner alert-success-banner">
                <div class="alert-body">{{ successMessage }}</div>
                <button type="button" class="close-btn" @click="showSuccess = false">×</button>
            </div>
        </div>

        <div class="dashboard-wrapper">
            <h1 class="dashboard-title"> Hi, this is the User Detail Page. </h1>
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
</style>