<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps<{
    applicants?: Array<{
        id: number
        first_name: string
        middle_name: string
        last_name: string
        email_address: string
        contact_no: string
        school_graduated_from: string
        course: string
        year_attended: string
        remarks: string
        skills?: Array<{
            id: number
            skill: string
            remarks: string
        }>
    }>
    flash?: { error?: string }
    userPermissions: number
}>()

const applicants = ref(props.applicants ?? [])

// Error Toast
const showError = ref(false)
const errorMessage = ref<string | null>(null)

onMounted(() => {
    if (props.flash?.error) {
        errorMessage.value = props.flash.error
        showError.value = true
        setTimeout(() => (showError.value = false), 10000)
    }
})

// Search
const searchQuery = ref('')
const currentPage = ref(1)
watch(searchQuery, () => {
    currentPage.value = 1
})

// Permissions
const canCreateApplicant = computed(() => {
    return [1, 2, 3].includes(props.userPermissions)
})

const canViewSkills = computed(() => {
    return [1, 2, 3].includes(props.userPermissions)
})

// Pagination
const perPage = 20
const blockSize = 5

const filteredApplicants = computed(() => {
    const q = searchQuery.value.toLowerCase().trim()
    if (!q) return applicants.value

    return applicants.value.filter(a => {
        const name = `${a.first_name ?? ''} ${a.middle_name ?? ''} ${a.last_name ?? ''}`.toLowerCase()
        const email = (a.email_address ?? '').toLowerCase()
        const school = (a.school_graduated_from ?? '').toLowerCase()
        const course = (a.course ?? '').toLowerCase()
        const year = (a.year_attended ?? '').toLowerCase()
        const remarks = (a.remarks ?? '').toLowerCase()

        let skills = ''
        if (canViewSkills.value) {
            skills = (a.skills ?? []).map(s => s.skill.toLowerCase()).join(' ')
        }

        return (
            name.includes(q) ||
            email.includes(q) ||
            school.includes(q) ||
            course.includes(q) ||
            year.includes(q) ||
            remarks.includes(q) ||
            skills.includes(q)
        )
    })
})

const totalColumns = computed(() => {
    let cols = 6 // Name, Email, School, Course, Year Attended, Remarks
    if (canViewSkills.value) cols += 1
    return cols
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
    for (let i = startPage.value; i <= endPage.value; i++) {
        pages.push(i)
    }
    return pages
})

function goToPage(page: number) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
    }
}

function prevBlock() {
    if (startPage.value > 1) {
        goToPage(startPage.value - 1)
    }
}

function nextBlock() {
    if (endPage.value < totalPages.value) {
        goToPage(endPage.value + 1)
    }
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

const searchPlaceholder = computed(() => {
    if (canViewSkills.value) {
        return 'Search by name, email, school, course, year, or skill'
    }
    return 'Search by name, email, school, course, or year'
})
</script>

<template>
    <AppLayout>
        <div v-if="showError" class="full-width-alert">
            <div class="alert-banner alert-error-banner">
                <div class="alert-body">{{ errorMessage }}</div>
                <button type="button" class="close-btn" @click="showError = false">×</button>
            </div>
        </div>

        <div class="page-content">
            <div class="page-header">
                <h2 class="page-title">Intermediate Applicants List</h2>
                <Link
                    v-if="canCreateApplicant"
                    href="/intermediate/applicants/register"
                    class="!bg-[#1C7BA5] btn-primary"
                >
                    Create Intermediate Applicant
                </Link>
            </div>

            <div class="flex gap-4 mb-4">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center text-zinc-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7 7 0 1010.3 3a7 7 0 006.35 13.65z"
                            />
                        </svg>
                    </span>

                    <input
                        v-model="searchQuery"
                        type="text"
                        :placeholder="searchPlaceholder"
                        class="w-full pl-10 pr-3 py-2 rounded-lg border bg-white dark:bg-zinc-900 dark:border-zinc-700"
                    />
                </div>
            </div>

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
                                <th class="border px-3 py-2">School Graduated From</th>
                                <th class="border px-3 py-2">Course</th>
                                <th class="border px-3 py-2">Year Attended</th>
                                <th v-if="canViewSkills" class="border px-3 py-2">Skills</th>
                                <th class="border px-3 py-2">Remarks</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="a in paginatedApplicants" :key="a.id">
                                <td class="border px-3 py-2">
                                    <Link :href="`/intermediate/applicants/${a.id}`" class="table-link">
                                        {{ a.first_name }} {{ a.middle_name }} {{ a.last_name }}
                                    </Link>
                                </td>
                                <td class="border px-3 py-2">{{ a.email_address || '—' }}</td>
                                <td class="border px-3 py-2">{{ a.school_graduated_from || '—' }}</td>
                                <td class="border px-3 py-2">{{ a.course || '—' }}</td>
                                <td class="border px-3 py-2">{{ a.year_attended || '—' }}</td>

                                <td v-if="canViewSkills" class="border px-3 py-2">
                                    <div class="remarks-clamp" :title="a.skills?.map(s => s.skill).join(', ')">
                                        {{ a.skills?.map(s => s.skill).join(', ') || '—' }}
                                    </div>
                                </td>

                                <td class="border px-3 py-2">
                                    <div class="remarks-clamp" :title="a.remarks">
                                        {{ a.remarks || '—' }}
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="paginatedApplicants.length === 0">
                                <td :colspan="totalColumns" class="text-center p-6 text-zinc-500">
                                    No applicants found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-center mt-3 gap-2 text-xs" v-if="filteredApplicants.length > perPage">
                <span
                    @click="prevBlock"
                    class="px-3 py-2 border rounded cursor-pointer"
                    :class="{ 'opacity-50 cursor-not-allowed': startPage === 1 }"
                >
                    Prev
                </span>

                <span
                    v-for="pageNumber in pageNumbers"
                    :key="pageNumber"
                    @click="goToPage(pageNumber)"
                    class="px-3 py-2 border rounded cursor-pointer"
                    :class="pageNumber === currentPage ? 'bg-blue-600 text-white' : ''"
                >
                    {{ pageNumber }}
                </span>

                <span
                    @click="nextBlock"
                    class="px-3 py-2 border rounded cursor-pointer"
                    :class="{ 'opacity-50 cursor-not-allowed': endPage === totalPages }"
                >
                    Next
                </span>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.table-wrapper {
    overflow-x: hidden;
}

.ats-table {
    min-width: 0 !important;
    table-layout: fixed;
}

.ats-table th,
.ats-table td {
    white-space: normal;
    word-break: break-word;
}

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

.table-link {
    color: var(--ats-accent);
    font-weight: 500;
    text-decoration: none;
}

.table-link:hover {
    text-decoration: underline;
}

.page-content {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.remarks-clamp {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
    word-break: break-word;
}
</style>
