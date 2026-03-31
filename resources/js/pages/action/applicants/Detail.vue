<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const props = defineProps<{
    users: {
        id: number
        name: string
        email: string
        created_at: string
    }[]
    flash?: {
        success?: string
        error?: string
    }
}>()

// Reactive state for toast
const showToast = ref(false)
const toastMessage = ref<string | null>(null)
const toastType = ref<'success' | 'error'>('success')

onMounted(() => {
    if (props.flash?.success) {
        toastMessage.value = props.flash.success
        toastType.value = 'success'
        showToast.value = true
    } else if (props.flash?.error) {
        toastMessage.value = props.flash.error
        toastType.value = 'error'
        showToast.value = true
    }

    // Auto-hide after 10 seconds
    if (showToast.value) {
        setTimeout(() => {
            showToast.value = false
        }, 10000)
    }
})
</script>

<template>
    <AppLayout>
        <!-- Toast -->
        <div v-if="showToast" class="full-width-alert">
            <div :class="['alert-banner', toastType === 'success' ? 'alert-success-banner' : 'alert-error-banner']">
                <div class="alert-body">{{ toastMessage }}</div>
                <button type="button" class="close-btn" @click="showToast = false">×</button>
            </div>
        </div>

        <!-- PAGE HEADER -->
        <div class="page-content">
            <div class="page-header">
                <h2 class="page-title">Detail ACTION Applicant</h2>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* =========================
   OVERRIDE GLOBAL ATS TABLE
   (Users page only)
   ========================= */

/* Disable forced horizontal scrolling */
.table-wrapper {
    overflow-x: hidden;
}

/* Remove global min-width */
.ats-table {
    min-width: 0 !important;
    table-layout: fixed;
}

/* Allow content to wrap naturally */
.ats-table th,
.ats-table td {
    white-space: normal;
    word-break: break-word;
}

/* =========================
   PAGE HEADER
   ========================= */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}

.page-title {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--ats-text);
}

/* =========================
   PRIMARY BUTTON
   ========================= */
.btn-primary {
    background: var(--ats-primary);
    color: #ffffff;
    padding: 0.55rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.15s ease;
}

.btn-primary:hover {
    background: var(--ats-accent);
}

/* =========================
   TABLE EXTRAS
   ========================= */
.actions-col {
    width: 120px;
    text-align: left;
}

.table-link {
    color: var(--ats-accent);
    font-weight: 500;
    text-decoration: none;
}

.table-link:hover {
    text-decoration: underline;
}

/* =========================
   EMPTY STATE
   ========================= */
.empty-state {
    text-align: center;
    padding: 1.25rem;
    font-style: italic;
    color: var(--ats-muted);
}

/* =========================
   PAGE CONTAINER
   ========================= */
.page-content {
    max-width: 1175px;
    margin: 0 auto;
    padding: 0 1.5rem;
}
</style>