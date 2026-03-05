<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const props = defineProps<{
    user: any
    flash?: {
        success?: string
        error?: string
    }
    positions: Record<number, string>
    permissions: Record<number, string>
}>()

// Toast state
const showSuccess = ref(false)
const showError = ref(false)
const successMessage = ref<string | null>(null)
const errorMessage = ref<string | null>(null)

onMounted(() => {
    if (props.flash?.success) {
        successMessage.value = props.flash.success
        showSuccess.value = true
        setTimeout(() => (showSuccess.value = false), 5000)
    }
    if (props.flash?.error) {
        errorMessage.value = props.flash.error
        showError.value = true
        setTimeout(() => (showError.value = false), 10000)
    }
})

// Form handling using Inertia
const form = useForm({
    first_name: props.user.first_name,
    middle_name: props.user.middle_name ?? '',
    last_name: props.user.last_name,
    email_address: props.user.email_address,
    contact_no: props.user.contact_no,
    address: props.user.address,
    position: props.user.position,
    permissions: props.user.permissions,
    active_status: props.user.active_status ? 1 : 0,
    password: '',                // ✅ New password field
    password_confirmation: ''    // ✅ Confirm password
})

const submit = () => {
    form.put(`/user/${props.user.id}`, {
        preserveScroll: true,
        onSuccess: () => { },
    })
}
</script>

<template>
    <AppLayout>
        <!-- Toasts -->
        <div v-if="showSuccess" class="full-width-alert">
            <div class="alert-banner alert-success-banner">
                <div class="alert-body">{{ successMessage }}</div>
                <button type="button" class="close-btn" @click="showSuccess = false">×</button>
            </div>
        </div>
        <div v-if="showError" class="full-width-alert">
            <div class="alert-banner alert-error-banner">
                <div class="alert-body">{{ errorMessage }}</div>
                <button type="button" class="close-btn" @click="showError = false">×</button>
            </div>
        </div>

        <div class="page-content">
            <!-- PAGE HEADER -->
            <div class="page-header">
                <h2 class="page-title">Edit User Details</h2>
            </div>

            <!-- EDIT FORM CARD -->
            <div class="detail-card">
                <form @submit.prevent="submit">

                    <!-- Existing fields -->
                    <div class="detail-row">
                        <label>First Name</label>
                        <input type="text" v-model="form.first_name" class="input-field" />
                    </div>

                    <div class="detail-row">
                        <label>Middle Name</label>
                        <input type="text" v-model="form.middle_name" class="input-field" />
                    </div>

                    <div class="detail-row">
                        <label>Last Name</label>
                        <input type="text" v-model="form.last_name" class="input-field" />
                    </div>

                    <div class="detail-row">
                        <label>Email Address</label>
                        <input type="email" v-model="form.email_address" class="input-field" />
                    </div>

                    <div class="detail-row">
                        <label>Contact Number</label>
                        <input type="text" v-model="form.contact_no" class="input-field" />
                    </div>

                    <div class="detail-row">
                        <label>Address</label>
                        <input type="text" v-model="form.address" class="input-field" />
                    </div>

                    <div class="detail-row">
                        <label>Position</label>
                        <select v-model="form.position" class="input-field">
                            <option v-for="(label, key) in props.positions" :key="key" :value="key">{{ label }}</option>
                        </select>
                    </div>

                    <div class="detail-row">
                        <label>Permissions</label>
                        <select v-model="form.permissions" class="input-field">
                            <option v-for="(label, key) in props.permissions" :key="key" :value="key">{{ label }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" v-model="form.active_status" :true-value="1" :false-value="0" />
                            Active User
                        </label>
                    </div>

                    <!-- NEW PASSWORD FIELDS -->
                    <div class="detail-row">
                        <label>Password <span class="text-muted">(Leave blank to keep current)</span></label>
                        <input type="password" v-model="form.password" class="input-field" />
                    </div>

                    <div class="detail-row">
                        <label>Confirm Password</label>
                        <input type="password" v-model="form.password_confirmation" class="input-field" />
                    </div>

                    <div class="form-actions">
                       <Link :href="`/user/${props.user.id}`" class="btn-secondary">Cancel</Link>
                       <button type="submit" :disabled="form.processing" class="btn btn-primary">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.page-content {
    max-width: 1175px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

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

.detail-row {
    margin-bottom: 1.25rem;
}

.detail-row label {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--ats-muted);
    display: block;
    margin-bottom: 0.25rem;
}

.input-field {
    width: 100%;
    padding: 0.55rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.95rem;
    color: var(--ats-text);
    outline: none;
    transition: border-color 0.15s ease;
}

.input-field:focus {
    border-color: #1C7BA5;
}

.text-muted {
    font-size: 0.75rem;
    color: #6b7280;
    margin-left: 0.25rem;
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

/* =========================
   FORM ACTION BUTTONS (Save / Cancel)
   ========================= */
/* Form actions */
.form-actions {
    margin-top: 2rem;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.5rem;
}

.form-actions button,
.form-actions a {
    flex: 0 0 auto;
    width: auto;
}

/* Buttons */
button[type="submit"],
.btn-secondary {
    padding: 0.5rem 1.2rem;
    font-size: 0.85rem;
    border-radius: 5px;
    font-weight: 500;
    white-space: nowrap;
    transition: background 0.15s ease;
}

button[type="submit"] {
    border: none;
    background: var(--ats-primary);
    color: #fff;
    cursor: pointer;
}

button[type="submit"]:hover:not(:disabled) {
    background: var(--ats-accent);
}

button[type="submit"]:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-secondary {
    border: 1px solid #d1d5db;
    background: #f3f4f6;
    color: #374151;
    text-decoration: none;
}

.btn-secondary:hover {
    background: #e5e7eb;
}
</style>