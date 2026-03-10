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

const showSuccess = ref(false)
const successMessage = ref<string | null>(null)

onMounted(() => {
    if (props.flash?.success) {
        successMessage.value = props.flash.success
        showSuccess.value = true

        setTimeout(() => {
            showSuccess.value = false
        }, 5000)
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

        <div class="page-content">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <h2 class="page-title">User Details</h2>

                <Link :href="`/user/${user.id}/edit`" class="btn-primary">
                    Edit
                </Link>
            </div>

            <!-- CARD -->
            <div class="detail-card">

                <div class="detail-row">
                    <label>Full Name</label>
                    <p>{{ user.first_name }} {{ user.middle_name ?? '' }} {{ user.last_name }}</p>
                </div>

                <div class="detail-row">
                    <label>Email Address</label>
                    <p>{{ user.email_address }}</p>
                </div>

                <div class="detail-row">
                    <label>Contact Number</label>
                    <p>{{ user.contact_no }}</p>
                </div>

                <div class="detail-row">
                    <label>Address</label>
                    <p>{{ user.address }}</p>
                </div>

                <div class="detail-row">
                    <label>Position</label>
                    <p>{{ user.position_label }}</p>
                </div>

                <div v-if="user.permissions === 1 || user.permissions === 2" class="detail-row">
                    <label>Permissions</label>
                    <p>{{ user.permission_label }}</p>
                </div>

                <div class="detail-row">
                    <label>Status</label>
                    <p>
                        <span :class="user.active_status ? 'badge-active' : 'badge-inactive'">
                            {{ user.active_status ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* =========================
   PAGE CONTAINER
   ========================= */
.page-content {
    max-width: 1175px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* =========================
   PAGE HEADER
   ========================= */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 700px;
    margin: 0 auto 1.5rem;
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
    background: #1C7BA5;
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
   DETAIL CARD
   ========================= */
.detail-card {
    background: #ffffff;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    max-width: 700px;
    margin: 0 auto;
}

/* =========================
   DETAIL ROWS
   ========================= */
.detail-row {
    margin-bottom: 1.25rem;
}

.detail-row label {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--ats-muted);
    display: block;
}

.detail-row p {
    font-size: 0.95rem;
    margin-top: 0.35rem;
    color: var(--ats-text);
}

/* =========================
   STATUS BADGES
   ========================= */
.badge-active {
    background: #dcfce7;
    color: #166534;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-inactive {
    background: #fee2e2;
    color: #991b1b;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 500;
}
</style>