<script setup lang="ts">
import { Head, usePage, Link } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'

// Inertia page props
const page = usePage<any>()
const applicant = computed(() => page.props.applicant)
const userPermissions = computed(() => Number(page.props.user_permissions))

// Toast state
const showToast = ref(false)
const toastMessage = ref<string | null>(null)
const toastType = ref<'success' | 'error'>('success')

// Format dates
const formatDate = (dateString: string | null) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
}

const formatDateTime = (dateString: string | null) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
    })
}

// Watch flash messages
const successMessage = computed(() => page.props.flash?.success)
const closeToast = () => { showToast.value = false }

watch(successMessage, (val) => {
    if (val) {
        toastMessage.value = val
        toastType.value = 'success'
        showToast.value = true
        setTimeout(() => (showToast.value = false), 5000)
    }
}, { immediate: true })

// Map gender
const genderLabel = (gender: number) => gender === 1 ? 'Male' : 'Female'

// Map source type
const sourceTypeLabel = (type: number) => {
    switch (type) {
        case 1: return 'Campus Recruitment'
        case 2: return 'Academe Partner'
        case 3: return 'Recruitment Portal'
        case 4: return 'Employee Referral'
        case 5: return 'Walk-in'
        default: return 'Other'
    }
}
</script>

<template>

    <Head title="ACTION Applicant Detail" />

    <AppLayout>
        <!-- Toast -->
        <div v-if="showToast" class="full-width-alert">
            <div :class="['alert-banner', toastType === 'success' ? 'alert-success-banner' : 'alert-error-banner']">
                <div class="alert-body">{{ toastMessage }}</div>
                <button type="button" class="close-btn" @click="closeToast">×</button>
            </div>
        </div>

        <!-- Header -->
        <div class="flex justify-between mx-5 mb-3">
            <h2 class="text-xl font-bold">Detail ACTION Applicant</h2>
            <Link :href="`/action/applicants/${applicant.id}/edit`" class="btn-primary !bg-[#1C7BA5]">
                Edit ACTION Applicant
            </Link>
        </div>

        <!-- Main Content -->
        <div class="mx-5 mt-6 grid grid-cols-3 gap-6">
            <!-- LEFT: Personal & Academic Info -->
            <div class="col-span-1 space-y-4">
                <!-- Name Card -->
                <div v-if="applicant.last_name || applicant.first_name || applicant.middle_name"
                    class="bg-[#2F359E] text-white rounded-xl p-6 shadow">
                    <h3 class="text-lg font-bold text-center">{{ applicant.last_name }}, {{ applicant.first_name }} {{
                        applicant.middle_name }}</h3>
                    <p class="text-xs opacity-80 text-center">Full Name</p>
                </div>

                <!-- Basic Info Table -->
                <div class="bg-white rounded-xl p-4 shadow border">
                    <table class="min-w-full table-auto text-xs">
                        <tbody>
                            <tr v-if="applicant.email_address">
                                <th class="px-2 py-2 font-semibold text-gray-600 w-40">Email</th>
                                <td class="px-2">{{ applicant.email_address }}</td>
                            </tr>
                            <tr v-if="applicant.gender !== null && applicant.gender !== undefined">
                                <th class="px-2 py-2 font-semibold text-gray-600">Gender</th>
                                <td class="px-2">{{ genderLabel(applicant.gender) }}</td>
                            </tr>
                            <tr v-if="applicant.age">
                                <th class="px-2 py-2 font-semibold text-gray-600">Age</th>
                                <td class="px-2">{{ applicant.age }}</td>
                            </tr>
                            <tr v-if="applicant.school">
                                <th class="px-2 py-2 font-semibold text-gray-600">School</th>
                                <td class="px-2">{{ applicant.school }}</td>
                            </tr>
                            <tr v-if="applicant.degree || applicant.others_degree">
                                <th class="px-2 py-2 font-semibold text-gray-600">Degree</th>
                                <td class="px-2">{{ applicant.degree }}
                                    {{ applicant.others_degree ? `(${applicant.others_degree})` : '' }}</td>
                            </tr>
                            <tr v-if="applicant.expected_graduation">
                                <th class="px-2 py-2 font-semibold text-gray-600">Expected Graduation</th>
                                <td class="px-2">{{ applicant.expected_graduation }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Source Info Table -->
                <div v-if="applicant.source_type || applicant.source || applicant.other_source"
                    class="bg-white rounded-xl p-4 shadow border">
                    <table class="min-w-full table-auto text-xs">
                        <tbody>
                            <!-- Source Type -->
                            <tr v-if="applicant.source_type">
                                <th class="px-2 py-2 font-semibold text-gray-600">Source Type</th>
                                <td class="px-2">{{ sourceTypeLabel(applicant.source_type) }}</td>
                            </tr>

                            <!-- Source (sub-type, e.g., portal name) -->
                            <tr v-if="applicant.source_label">
                                <th class="px-2 py-2 font-semibold text-gray-600">Source</th>
                                <td class="px-2">{{ applicant.source_label }}</td>
                            </tr>

                            <!-- Other Source (free-text) -->
                            <tr v-if="applicant.other_source">
                                <th class="px-2 py-2 font-semibold text-gray-600">Other Source</th>
                                <td class="px-2">{{ applicant.other_source }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Metadata Table -->
                <div v-if="applicant.updated_by_name" class="bg-white rounded-xl p-4 shadow border">
                    <table class="min-w-full table-auto text-xs">
                        <tbody>
                            <tr>
                                <th class="px-2 py-2 font-semibold text-gray-600">Updated By</th>
                                <td class="px-2">{{ applicant.updated_by_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT: Remarks, Awards, Thesis, Extra Curricular -->
            <div class="col-span-2 bg-white rounded-xl shadow border p-6 space-y-4">
                <div v-if="applicant.remarks">
                    <h4 class="text-xs font-bold mb-2 text-center">REMARKS</h4>
                    <p class="text-xs break-words">{{ applicant.remarks }}</p>
                </div>
                <div v-if="applicant.awards_recognition">
                    <h4 class="text-xs font-bold mb-2 text-center">AWARDS / RECOGNITION</h4>
                    <p class="text-xs break-words">{{ applicant.awards_recognition }}</p>
                </div>
                <div v-if="applicant.thesis_project">
                    <h4 class="text-xs font-bold mb-2 text-center">THESIS / PROJECT</h4>
                    <p class="text-xs break-words">{{ applicant.thesis_project }}</p>
                </div>
                <div v-if="applicant.extra_curricular">
                    <h4 class="text-xs font-bold mb-2 text-center">EXTRA CURRICULAR</h4>
                    <p class="text-xs break-words">{{ applicant.extra_curricular }}</p>
                </div>
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