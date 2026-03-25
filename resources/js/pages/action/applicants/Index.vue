<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted } from 'vue'

// Props
const props = defineProps<{
    applicants?: Array<{
        id: number
        first_name: string
        middle_name: string
        last_name: string
        email_address: string
        school: string
        degree: string
        expected_graduation: string
        remarks: string
    }>
    flash?: { error?: string }
     userPermissions: number
}>()

const applicants = ref(props.applicants ?? [])

// ========================
// Error Toast
// ========================
const showError = ref(false)
const errorMessage = ref<string | null>(null)

onMounted(() => {
    if (props.flash?.error) {
        errorMessage.value = props.flash.error
        showError.value = true
        setTimeout(() => (showError.value = false), 10000)
    }
})

// ========================
// Search
// ========================
const searchQuery = ref('')
const currentPage = ref(1)
watch(searchQuery, () => currentPage.value = 1)

// ========================
// Pagination
// ========================
const perPage = 20
const blockSize = 5

const filteredApplicants = computed(() => {
    const q = searchQuery.value.toLowerCase()
    if (!q) return applicants.value

    return applicants.value.filter(a => {
        return (
            `${a.first_name} ${a.middle_name} ${a.last_name}`.toLowerCase().includes(q) || 
            a.email_address.toLowerCase().includes(q) ||                                   
            a.school.toLowerCase().includes(q) ||                                          
            a.degree.toLowerCase().includes(q)                                            
        )
    })
})

const totalPages = computed(() => Math.ceil(filteredApplicants.value.length / perPage))
const paginatedApplicants = computed(() => {
    const start = (currentPage.value - 1) * perPage
    return filteredApplicants.value.slice(start, start + perPage)
})

const currentBlock = computed(() => Math.ceil(currentPage.value / blockSize))
const startPage = computed(() => (currentBlock.value - 1) * blockSize + 1)
const endPage = computed(() => Math.min(startPage.value + blockSize - 1, totalPages.value))

const pageNumbers = computed(() => {
    const pages: number[] = []
    for (let i = startPage.value; i <= endPage.value; i++) pages.push(i)
    return pages
})

function goToPage(page: number) {
    if (page >= 1 && page <= totalPages.value) currentPage.value = page
}

function prevBlock() {
    if (startPage.value > 1) goToPage(startPage.value - 1)
}

function nextBlock() {
    if (endPage.value < totalPages.value) goToPage(endPage.value + 1)
}

const showingFrom = computed(() => {
    if (filteredApplicants.value.length === 0) return 0
    return (currentPage.value - 1) * perPage + 1
})

const showingTo = computed(() => {
    const end = currentPage.value * perPage
    const total = filteredApplicants.value.length
    return end > total ? total : end
})

const canCreateApplicant = computed(() => {
    // Only show for permissions 1, 2, 3
    return [1, 2, 3].includes(props.userPermissions);
})
</script>

<template>
<AppLayout>
    <!-- Error Toast -->
    <div v-if="showError" class="full-width-alert">
        <div class="alert-banner alert-error-banner">
            <div class="alert-body">{{ errorMessage }}</div>
            <button type="button" class="close-btn" @click="showError = false">×</button>
        </div>
    </div>

    <div class="page-content">
        <!-- HEADER -->
        <div class="page-header">
            <h2 class="page-title">ACTION Applicants List</h2>
            <Link 
        v-if="canCreateApplicant" 
        href="/action/applicants/register" 
        class="!bg-[#1C7BA5] btn-primary">
        Create ACTION Applicant
    </Link>
        </div>

        <!-- SEARCH -->
        <div class="flex gap-4 mb-4">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center text-zinc-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7 7 0 1010.3 3a7 7 0 006.35 13.65z" />
                        </svg>
                    </span>
                    <input v-model="searchQuery" type="text" placeholder="Search by name, email, school, degree"
                        class="w-full pl-10 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700" />
                </div>
            </div>

        <!-- TABLE -->
        <div class="card">
            <div class="mb-2 text-xs text-gray-600">
                Showing {{ showingFrom }}–{{ showingTo }} out of {{ filteredApplicants.length }} items
            </div>

            <div class="table-wrapper">
                <table class="ats-table w-full table-auto border-collapse border text-sm">
                    <thead class="bg-zinc-100 dark:bg-zinc-800 text-left">
                        <tr>
                            <th class="border px-3 py-2">Name</th>
                            <th class="border px-3 py-2">Email</th>
                            <th class="border px-3 py-2">School</th>
                            <th class="border px-3 py-2">Degree</th>
                            <th class="border px-3 py-2">Expected Graduation</th>
                            <th class="border px-3 py-2">Remarks</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="a in paginatedApplicants" :key="a.id">
                            <td class="border px-3 py-2">
                                <Link :href="`/action/applicants/${a.id}`" class="table-link">
                                    {{ a.first_name }} {{ a.middle_name }} {{ a.last_name }}
                                </Link>
                            </td>
                            <td class="border px-3 py-2">{{ a.email_address }}</td>
                            <td class="border px-3 py-2">{{ a.school }}</td>
                            <td class="border px-3 py-2">{{ a.degree }}</td>
                            <td class="border px-3 py-2">{{ a.expected_graduation }}</td>
                            <td class="border px-3 py-2">{{ a.remarks }}</td>
                        </tr>

                        <tr v-if="paginatedApplicants.length === 0">
                            <td colspan="6" class="text-center p-6 text-zinc-500">
                                No applicants found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION -->
        <div class="flex justify-center mt-3 gap-2 text-xs"
            v-if="filteredApplicants.length > perPage">

            <span @click="prevBlock"
                class="px-3 py-2 border rounded cursor-pointer"
                :class="{ 'opacity-50 cursor-not-allowed': startPage === 1 }">
                Prev
            </span>

            <span v-for="pageNumber in pageNumbers"
                :key="pageNumber"
                @click="goToPage(pageNumber)"
                class="px-3 py-2 border rounded cursor-pointer"
                :class="pageNumber === currentPage ? 'bg-blue-600 text-white' : ''">
                {{ pageNumber }}
            </span>

            <span @click="nextBlock"
                class="px-3 py-2 border rounded cursor-pointer"
                :class="{ 'opacity-50 cursor-not-allowed': endPage === totalPages }">
                Next
            </span>
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