<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps<{
    users: {
        id: number
        name: string
        email: string
        created_at: string
    }[]
}>()
</script>

<template>
    <AppLayout>
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

</style>
