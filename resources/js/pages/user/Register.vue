<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

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

// Submit handler
function submit() {
    form.post('/user')
}
</script>

<template>
    <AppLayout>
        <div class="page-header">
            <h2 class="page-title">Register User</h2>
        </div>

        <div class="form-center">
            <div class="card create-user-card">
                <form @submit.prevent="submit">

                    <!-- Name fields -->
                    <div class="form-group">
                        <label>First Name</label>
                        <input v-model="form.first_name" type="text" placeholder="Enter first name" />
                        <span v-if="form.errors.first_name" class="error">{{ form.errors.first_name }}</span>
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <input v-model="form.last_name" type="text" placeholder="Enter last name" />
                        <span v-if="form.errors.last_name" class="error">{{ form.errors.last_name }}</span>
                    </div>

                    <div class="form-group">
                        <label>Middle Name</label>
                        <input v-model="form.middle_name" type="text" placeholder="Enter middle name (optional)" />
                        <span v-if="form.errors.middle_name" class="error">{{ form.errors.middle_name }}</span>
                    </div>

                    <!-- Contact -->
                    <div class="form-group">
                        <label>Address</label>
                        <input v-model="form.address" type="text" placeholder="Enter address" />
                        <span v-if="form.errors.address" class="error">{{ form.errors.address }}</span>
                    </div>

                    <div class="form-group">
                        <label>Contact Number</label>
                        <input v-model="form.contact_no" type="text" placeholder="09XXXXXXXXX" />
                        <span v-if="form.errors.contact_no" class="error">{{ form.errors.contact_no }}</span>
                    </div>

                    <!-- Email & password -->
                    <div class="form-group">
                        <label>Email Address</label>
                        <input v-model="form.email_address" type="text" placeholder="Enter email address" />
                        <span v-if="form.errors.email_address" class="error">{{ form.errors.email_address }}</span>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input v-model="form.password" type="password" placeholder="Enter password" />
                        <span v-if="form.errors.password" class="error">{{ form.errors.password }}</span>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input v-model="form.password_confirmation" type="password" placeholder="Confirm password" />
                        <span v-if="form.errors.password_confirmation" class="error">
                            {{ form.errors.password_confirmation }}
                        </span>
                    </div>

                    <!-- Position -->
                    <div class="form-group">
                        <label>Position</label>
                        <select v-model="form.position">
                            <option disabled value="">Select Position</option>
                            <option
                                v-for="pos in positionOptions"
                                :key="pos.value"
                                :value="pos.value"
                            >
                                {{ pos.label }}
                            </option>
                        </select>
                        <span v-if="form.errors.position" class="error">
                            {{ form.errors.position }}
                        </span>
                    </div>

                    <!-- Permissions -->
                    <div class="form-group">
                        <label>Permissions</label>
                        <select v-model="form.permissions">
                            <option disabled value="">Select Role</option>
                            <option
                                v-for="perm in permissionLevels"
                                :key="perm.value"
                                :value="perm.value"
                            >
                                {{ perm.label }}
                            </option>
                        </select>
                        <span v-if="form.errors.permissions" class="error">
                            {{ form.errors.permissions }}
                        </span>
                    </div>

                    <!-- Active -->
                    <div class="form-group">
                        <label>
                            <input
                                type="checkbox"
                                v-model="form.active_status"
                                :true-value="1"
                                :false-value="0"
                            />
                            Active User
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="form-actions">
                        <Link href="/user" class="btn btn-secondary">
                            Cancel
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="btn btn-primary"
                        >
                            {{ form.processing ? 'Creating…' : 'Register' }}
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
</style>