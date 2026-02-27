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
        error?: string
    }
}>()

const showError = ref(false)
const errorMessage = ref<string | null>(null)

onMounted(() => {
    if (props.flash?.error) {
        errorMessage.value = props.flash.error
        showError.value = true

        setTimeout(() => {
            showError.value = false
        }, 10000)
    }
})

</script>

<template>
    <AppLayout>
        <!-- Error Toast -->
        <div v-if="showError" class="full-width-alert">
            <div class="alert-error-banner">
                <div class="alert-body">
                    {{ errorMessage }}
                </div>
                <button type="button" class="close-btn" @click="showError = false">
                    ×
                </button>
            </div>
        </div>

        <!-- PAGE HEADER -->
        <div class="page-content">
            <div class="page-header">
                <h2 class="page-title">Users</h2>

                <Link href="/user/register" class="btn-primary">
                    Register User
                </Link>
            </div>

            <!-- USERS TABLE -->
            <!-- <div class="card">
                <div class="table-wrapper">
                    <table class="ats-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created</th>
                                <th class="actions-col">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-if="users.length === 0">
                                <td colspan="4" class="empty-state">
                                    No users found.
                                </td>
                            </tr>

                            <tr v-for="user in users" :key="user.id">
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ new Date(user.created_at).toLocaleDateString() }}</td>
                                <td class="actions-col">
                                    <Link :href="`/user/${user.id}/edit`" class="table-link">
                                        Edit
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> -->
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
/* Full-width alert container */
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

/* Red banner */
.alert-error-banner {
    background-color: #dc3545;
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
    text-align: center;
    font-weight: 500;
}

/* Slim close button */
.close-btn {
    background-color: rgba(255, 255, 255, 0.2);
    border: none;
    color: #fff;
    font-size: 0.95rem;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, transform 0.2s;
}

.close-btn:hover {
    background-color: rgba(255, 255, 255, 0.35);
    transform: scale(1.08);
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
