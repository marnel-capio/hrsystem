<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

// Props
const props = defineProps<{
    user: any
    positions: Record<number, string>
    permissions: Record<number, string>
}>()

const showPassword = ref(false)
const showConfirmPassword = ref(false)

// ----- Toasts -----
const page = usePage()
const showSuccess = ref(false)
const showError = ref(false)
const successMessage = ref<string | null>(null)
const errorMessage = ref<string | null>(null)
const passwordError = ref<string | null>(null)

// Watch for flash messages and display toast
watch(
    () => (page.props as any).flash,
    (flash) => {
        if (flash?.success) {
            successMessage.value = flash.success
            showSuccess.value = true
            setTimeout(() => (showSuccess.value = false), 5000)
        }
        if (flash?.error) {
            errorMessage.value = flash.error
            showError.value = true
            setTimeout(() => (showError.value = false), 10000)
        }
    },
    { immediate: true }
)

// ----- Logged-in user -----
const loggedInUser = (page.props as any).auth.user
const isOwnAccount = loggedInUser.id === props.user.id
const loggedInPermissions = Number(loggedInUser.permissions)

// ----- Permissions logic -----
const canEditAll = (page.props as any).full_edit_permissions.includes(loggedInPermissions)
const limitedEdit = (page.props as any).limited_edit_permissions.includes(loggedInPermissions)

// ----- Form -----
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
    password: '',
    password_confirmation: ''
})

watch(
    () => [form.password, form.password_confirmation],
    ([password, confirm]) => {

        if (!password) {
            passwordError.value = null
            return
        }

        // Password complexity checks
        if (password.length < 8) {
            passwordError.value = "Password must be at least 8 characters."
        }
        else if (password.length > 64) {
            passwordError.value = "Password must be at most 64 characters."
        }
        else if (!/[A-Z]/.test(password)) {
            passwordError.value = "Password must contain at least one uppercase letter."
        }
        else if (!/[a-z]/.test(password)) {
            passwordError.value = "Password must contain at least one lowercase letter."
        }
        else if (!/[0-9]/.test(password)) {
            passwordError.value = "Password must contain at least one number."
        }
        else if (!/[!@#$%&*_]/.test(password)) {
            passwordError.value = "Password must contain at least one special character (!@#$%&*_)."
        }
        else if (password !== confirm) {
            passwordError.value = "Passwords do not match."
        }
        else {
            passwordError.value = null
        }

    },
    { immediate: true }
)

const submit = () => {
    form.put(`/user/${props.user.id}/update`, { preserveScroll: true })
}

// ----- Field readonly helpers -----
const passwordReadonly = () => !isOwnAccount

const fieldReadonly = (field: 'email' | 'position' | 'permissions' | 'status') => {
    if (canEditAll) return false
    if (limitedEdit) return true
    return false
}

const personalFieldReadonly = () => {
    if (canEditAll) return false
    if (limitedEdit) return !isOwnAccount
    return true
}

const firstNameError = ref<string | null>(null)
const middleNameError = ref<string | null>(null)
const lastNameError = ref<string | null>(null)
const emailError = ref<string | null>(null)
const contactError = ref<string | null>(null)
const addressError = ref<string | null>(null)
const positionError = ref<string | null>(null)
const permissionsError = ref<string | null>(null)
const statusError = ref<string | null>(null)

// Validation helpers
const validateAlphaSpaceDash = (value: string) => /^[A-Za-z\s-]+$/.test(value)
const validateEmail = (value: string) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
const validateAWSDomain = (value: string) => value.endsWith('@awsys-i.com')
const validateContact = (value: string) => /^\d{11}$/.test(value)

// Watchers for dynamic validation
watch(() => form.first_name, (value) => {
    if (!value) firstNameError.value = "This is a required field."
    else if (value.length > 80) firstNameError.value = "This field exceeds the maximum allowed length."
    else if (!validateAlphaSpaceDash(value)) firstNameError.value = "Only letters, spaces, and hyphens are allowed."
    else firstNameError.value = null
})

watch(() => form.middle_name, (value) => {
    if (value && value.length > 80) middleNameError.value = "This field exceeds the maximum allowed length."
    else if (value && !validateAlphaSpaceDash(value)) middleNameError.value = "Only letters, spaces, and hyphens are allowed."
    else middleNameError.value = null
})

watch(() => form.last_name, (value) => {
    if (!value) lastNameError.value = "This is a required field."
    else if (value.length > 80) lastNameError.value = "This field exceeds the maximum allowed length."
    else if (!validateAlphaSpaceDash(value)) lastNameError.value = "Only letters, spaces, and hyphens are allowed."
    else lastNameError.value = null
})

watch(() => form.email_address, (value) => {
    if (!value) emailError.value = "This is a required field."
    else if (value.length > 80) emailError.value = "This field exceeds the maximum allowed length."
    else if (!validateEmail(value)) emailError.value = "Invalid email format."
    else if (!validateAWSDomain(value)) emailError.value = "The email address must be your AWS email address."
    else emailError.value = null
})

watch(() => form.contact_no, (value) => {
    if (!value) contactError.value = "This is a required field."
    else if (!/^\d+$/.test(value)) contactError.value = "The contact number must contain only numbers."
    else if (!/^\d{11}$/.test(value)) contactError.value = "The contact number must be exactly 11 digits."
    else contactError.value = null
})

watch(() => form.address, (value) => {
    if (!value) addressError.value = "This is a required field."
    else if (value.length > 1024) addressError.value = "This field exceeds the maximum allowed length."
    else addressError.value = null
})

watch(() => form.position, (value) => {
    if (value === null || value === undefined) positionError.value = "This is a required field."
    else positionError.value = null
})

watch(() => form.permissions, (value) => {
    if (value === null || value === undefined) permissionsError.value = "This is a required field."
    else permissionsError.value = null
})

watch(() => form.active_status, (value) => {
    if (value !== 0 && value !== 1) statusError.value = "This is a required field."
    else statusError.value = null
})

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

        <!-- EDIT FORM -->
        <div class="page-content">
            <div class="page-header">
                <h2 class="page-title">Edit User Details</h2>
            </div>

            <div class="detail-card">
                <form @submit.prevent="submit">
                    <!-- First Name -->
                    <div class="detail-row">
                        <label>First Name</label>
                        <input type="text" v-model="form.first_name" class="input-field"
                            :readonly="personalFieldReadonly()" />
                        <span v-if="firstNameError || form.errors.first_name" class="error">
                            {{ firstNameError ?? form.errors.first_name }}
                        </span>
                    </div>

                    <!-- Middle Name -->
                    <div class="detail-row">
                        <label>Middle Name</label>
                        <input type="text" v-model="form.middle_name" class="input-field"
                            :readonly="personalFieldReadonly()" />
                        <span v-if="middleNameError || form.errors.middle_name" class="error">{{ middleNameError ??
                            form.errors.middle_name }}</span>
                    </div>

                    <!-- Last Name -->
                    <div class="detail-row">
                        <label>Last Name</label>
                        <input type="text" v-model="form.last_name" class="input-field"
                            :readonly="personalFieldReadonly()" />
                        <span v-if="lastNameError || form.errors.last_name" class="error">{{ lastNameError ??
                            form.errors.last_name }}</span>
                    </div>

                    <!-- Email -->
                    <div class="detail-row">
                        <label>Email Address</label>
                        <input type="text" v-model="form.email_address" class="input-field"
                            :readonly="fieldReadonly('email')" />
                        <span v-if="emailError || form.errors.email_address" class="error">{{ emailError ?? form.errors.email_address }}</span>
                    </div>

                    <!-- Contact & Address -->
                    <div class="detail-row">
                        <label>Contact Number</label>
                        <input type="text" v-model="form.contact_no" class="input-field"
                            :readonly="personalFieldReadonly()" />
                        <span v-if="contactError || form.errors.contact_no" class="error">{{ contactError ?? form.errors.contact_no }}</span>
                    </div>

                    <div class="detail-row">
                        <label>Address</label>
                        <input type="text" v-model="form.address" class="input-field"
                            :readonly="personalFieldReadonly()" />
                        <span v-if="addressError || form.errors.address" class="error">{{ addressError ?? form.errors.address }}</span>
                    </div>

                    <!-- Position & Permissions -->
                    <div class="detail-row">
                        <label>Position</label>
                        <select v-model="form.position" class="input-field" :disabled="fieldReadonly('position')">
                            <option v-for="(label, key) in props.positions" :key="key" :value="key">{{ label }}</option>
                        </select>
                    </div>

                    <div v-if="loggedInPermissions === 1 || loggedInPermissions === 2" class="detail-row">
                        <label>Permissions</label>
                        <select v-model="form.permissions" class="input-field" :disabled="fieldReadonly('permissions')">
                            <option v-for="(label, key) in props.permissions" :key="key" :value="key">{{ label }}
                            </option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="detail-row">
                        <label>Status</label>
                        <select v-model="form.active_status" class="input-field" :disabled="fieldReadonly('status')">
                            <option :value="1">Active</option>
                            <option :value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div v-if="isOwnAccount" class="detail-row">
                        <label>Password <span class="text-muted">(Leave blank to keep current)</span></label>

                        <div class="password-wrapper">
                            <input v-model="form.password" :type="showPassword ? 'text' : 'password'"
                                class="input-field" placeholder="Enter new password" />

                            <div class="toggle">
                                <input type="checkbox" v-model="showPassword" id="show-password" />
                                <label for="show-password">Show</label>
                            </div>
                        </div>

                        <span v-if="passwordError || form.errors.password" class="error">
                            {{ passwordError ?? form.errors.password }}
                        </span>
                    </div>

                    <!-- Confirm Password -->
                    <div v-if="isOwnAccount" class="detail-row">
                        <label>Confirm Password</label>

                        <div class="password-wrapper">
                            <input v-model="form.password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'" class="input-field"
                                placeholder="Confirm new password" />

                            <div class="toggle">
                                <input type="checkbox" v-model="showConfirmPassword" id="show-confirm-password" />
                                <label for="show-confirm-password">Show</label>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <Link :href="`/user/${props.user.id}`" class="btn-secondary">Cancel</Link>
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">Update</button>
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

.error {
    color: #ff4d4f;
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

.password-wrapper {
    position: relative;
    width: 100%;
}

.password-wrapper input {
    width: 100%;
    padding-right: 100px;
    /* extra space for checkbox + label */
}

/* Align checkbox and label perfectly */
.toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    align-items: center;
    /* <-- aligns checkbox and text vertically */
    gap: 0.25rem;
    font-size: 0.75rem;
    color: #555;
    cursor: pointer;
}

.toggle input[type="checkbox"] {
    margin: 0;
    /* removes default checkbox spacing */
}

.toggle label {
    margin: 0;
    padding: 0;
    line-height: 1;
    cursor: pointer;
}

.input-field:disabled,
.input-field[readonly] {
    background-color: #e5e7eb;
    /* darker gray than before */
    color: #6b7280;
    /* muted text */
    cursor: not-allowed;
    /* indicates non-editable */
    border-color: #d1d5db;
    /* border remains the same */
}

/* Make select dropdowns match disabled style */
select:disabled {
    background-color: #e5e7eb;
    color: #6b7280;
    cursor: not-allowed;
    border-color: #d1d5db;
}
</style>