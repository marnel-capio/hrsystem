<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

// Props coming from controller
const props = defineProps<{
    positions: Record<number, string>,
    permissions: Record<number, string>
}>()

// Convert config objects into dropdown arrays
const positionOptions = Object.entries(props.positions).map(
    ([value, label]) => ({
        value: Number(value),
        label
    })
)

const passwordError = ref<string | null>(null)

const permissionLevels = Object.entries(props.permissions).map(
    ([value, label]) => ({
        value: Number(value),
        label
    })
)

// Form state
const form = useForm({
    first_name: '',
    last_name: '',
    middle_name: '',
    address: '',
    contact_no: '',
    email_address: '',
    password: '',
    password_confirmation: '',
    position: '',
    permissions: '',
    active_status: 1,
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
        // Confirm password mismatch always triggers if password != confirm
        else if (password !== confirm) {
            passwordError.value = "Passwords do not match."
        }
        else {
            passwordError.value = null
        }

    },
    { immediate: true }
)

const showPassword = ref(false)
const showConfirmPassword = ref(false)

const firstNameError = ref<string | null>(null)
const middleNameError = ref<string | null>(null)
const lastNameError = ref<string | null>(null)
const emailError = ref<string | null>(null)
const contactError = ref<string | null>(null)
const addressError = ref<string | null>(null)
const positionError = ref<string | null>(null)
const permissionsError = ref<string | null>(null)
const activeStatusError = ref<string | null>(null)

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
    else if (!validateContact(value)) contactError.value = "The contact number must be exactly 11 digits."
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

// Submit handler
function submit() {
    form.post('/user')
}
</script>

<template>
    <AppLayout>
        <div class="page-header">
            <h2 class="page-title">Create User</h2>
        </div>

        <div class="form-center">
            <div class="card create-user-card">
                <form @submit.prevent="submit">

                    <!-- Name fields -->
                    <div class="form-group">
                        <label style="font-weight: bold;">First Name *</label>
                        <input v-model="form.first_name" type="text" placeholder="Enter first name" />
                        <span v-if="firstNameError || form.errors.first_name" class="error">
                            {{ firstNameError ?? form.errors.first_name }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: bold;">Last Name *</label>
                        <input v-model="form.last_name" type="text" placeholder="Enter last name" />
                        <span v-if="lastNameError || form.errors.last_name" class="error">
                            {{ lastNameError ?? form.errors.last_name }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label>Middle Name</label>
                        <input v-model="form.middle_name" type="text" placeholder="Enter middle name (optional)" />
                        <span v-if="middleNameError || form.errors.middle_name" class="error">
                            {{ middleNameError ?? form.errors.middle_name }}
                        </span>
                    </div>

                    <!-- Contact -->
                    <div class="form-group">
                        <label style="font-weight: bold;">Address *</label>
                        <input v-model="form.address" type="text" placeholder="Enter address" />
                        <span v-if="addressError || form.errors.address" class="error">
                            {{ addressError ?? form.errors.address }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: bold;">Contact Number *</label>
                        <input v-model="form.contact_no" type="text" placeholder="09XXXXXXXXX" />
                        <span v-if="contactError || form.errors.contact_no" class="error">
                            {{ contactError ?? form.errors.contact_no }}
                        </span>
                    </div>

                    <!-- Email & password -->
                    <div class="form-group">
                        <label style="font-weight: bold;">Email Address *</label>
                        <input v-model="form.email_address" type="text" placeholder="Enter email address" />
                        <span v-if="emailError || form.errors.email_address" class="error">
                            {{ emailError ?? form.errors.email_address }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: bold;">Password *</label>

                        <div class="password-wrapper">
                            <input v-model="form.password" :type="showPassword ? 'text' : 'password'"
                                placeholder="Enter password" />

                            <label class="toggle">
                                <input type="checkbox" v-model="showPassword" />
                                Show
                            </label>
                        </div>

                        <span v-if="passwordError || form.errors.password" class="error">
                            {{ passwordError ?? form.errors.password }}
                        </span>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: bold;">Confirm Password *</label>

                        <div class="password-wrapper">
                            <input v-model="form.password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'" placeholder="Confirm password" />

                            <label class="toggle">
                                <input type="checkbox" v-model="showConfirmPassword" />
                                Show
                            </label>
                        </div>
                    </div>

                    <!-- Position -->
                    <div class="form-group">
                        <label style="font-weight: bold;">Position *</label>
                        <select v-model="form.position">
                            <option disabled value="">Select Position</option>
                            <option v-for="pos in positionOptions" :key="pos.value" :value="pos.value">
                                {{ pos.label }}
                            </option>
                        </select>
                        <span v-if="positionError || form.errors.position" class="error">
                            {{ positionError ?? form.errors.position }}
                        </span>
                    </div>

                    <!-- Permissions -->
                    <div class="form-group">
                        <label style="font-weight: bold;">Permissions *</label>
                        <select v-model="form.permissions">
                            <option disabled value="">Select Role</option>
                            <option v-for="perm in permissionLevels" :key="perm.value" :value="perm.value">
                                {{ perm.label }}
                            </option>
                        </select>
                        <span v-if="permissionsError || form.errors.permissions" class="error">
                            {{ permissionsError ?? form.errors.permissions }}
                        </span>
                    </div>

                    <!-- Submit -->
                    <div class="form-actions">
                        <Link href="/user" class="btn btn-secondary">
                            Cancel
                        </Link>

                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            {{ form.processing ? 'Creating…' : 'Create' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AppLayout>
</template>


<style scoped>
/* Page header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 600px;
    margin: 0 auto 1.5rem;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
}

/* Center form card */
.form-center {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}

/* Card styling */
.card.create-user-card {
    width: 100%;
    max-width: 600px;
    padding: 2rem;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Form groups */
.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}

input,
select {
    padding: 0.6rem 1rem;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 0.95rem;
    transition: border 0.15s ease;
}

input:focus,
select:focus {
    outline: none;
    border-color: var(--ats-primary);
}

input::placeholder {
    color: #999;
    font-style: italic;
}

select {
    color: #333;
}

/* Error messages */
.error {
    color: #ff4d4f;
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

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

.password-wrapper {
    position: relative;
    width: 100%;
}

.password-wrapper input {
    width: 100%;
    padding-right: 70px;
    /* space for toggle */
}

.toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);

    font-size: 0.75rem;
    color: #555;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    cursor: pointer;
}
</style>