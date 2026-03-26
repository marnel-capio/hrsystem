<script setup lang="ts">
import { Head, usePage, Link } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted } from 'vue'
import axios from 'axios'
import { Pen, Trash } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue'

// Inertia page props
const page = usePage<any>()
const applicant = computed(() => page.props.applicant)
const userPermissions = computed(() => Number(page.props.user_permissions))
const languages = ref<any[]>(page.props.languages || []);

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

const selectedLanguages = ref<number[]>([])
const newLanguage = ref('')

// Fetch languages for this applicant
const fetchLanguages = async () => {
    const res = await axios.get(`/action/applicants/${applicant.value.id}/languages`)
    languages.value = res.data
}

// Add new language
const addLanguage = async () => {
    if (!newLanguage.value.trim()) return;

    try {
        await axios.post(`/action/applicants/${applicant.value.id}/languages`, {
            program_language: newLanguage.value.trim()
        });

        newLanguage.value = '';
        fetchLanguages();
    } catch (error) {
        console.error('Add language failed', error);
        alert('Failed to add language. Check console for details.');
    }
}

// Edit language
const editLanguage = (lang: any) => {
    const updated = prompt('Edit language', lang.program_language)
    if (updated && updated.trim() !== lang.program_language) {
        axios.put(`/action/applicants/${applicant.value.id}/languages/${lang.id}`, {
            program_language: updated.trim()
        }).then(fetchLanguages)
    }
}

// Delete single
const deleteLanguage = async (id: number) => {
    if (!confirm('Delete this language?')) return
    await axios.delete(`/action/applicants/${applicant.value.id}/languages/${id}`)
        .then(fetchLanguages)
}

// Bulk delete
const bulkDelete = async () => {
    if (!selectedLanguages.value.length) return;

    if (!confirm('Delete selected languages?')) return;

    try {
        await axios.post(`/action/applicants/${applicant.value.id}/languages/bulk-delete`, {
            ids: selectedLanguages.value
        });

        // Clear selected checkboxes
        selectedLanguages.value = [];

        // Refresh the list
        fetchLanguages();
    } catch (error) {
        console.error('Bulk delete failed', error);
        alert('Failed to delete selected languages. Check console for details.');
    }
}

// Toggle all checkboxes
const toggleAllLanguages = (e: Event) => {
    const target = e.target as HTMLInputElement
    selectedLanguages.value = target.checked ? languages.value.map(l => l.id) : []
}

onMounted(fetchLanguages)

// Modal state
const editModalVisible = ref(false)
const languageBeingEdited = ref<{ id: number, program_language: string, remarks: string | null } | null>(null)
const editedLanguage = ref('')
const editedRemarks = ref('') // <-- new state for remarks

// Open modal
const openEditModal = (lang: any) => {
    languageBeingEdited.value = { ...lang }
    editedLanguage.value = lang.program_language
    editedRemarks.value = lang.remarks || ''
    editModalVisible.value = true
}

// Save changes
const saveLanguageEdit = async () => {
    if (!languageBeingEdited.value) return

    if (!editedLanguage.value.trim()) {
        alert("Language cannot be empty")
        return
    }

    try {
        await axios.put(`/action/applicants/${applicant.value.id}/languages/${languageBeingEdited.value.id}`, {
            program_language: editedLanguage.value.trim(),
            remarks: editedRemarks.value.trim() || null // send remarks
        })
        fetchLanguages()
        editModalVisible.value = false
    } catch (error) {
        console.error('Edit failed', error)
        alert('Failed to update language. Check console.')
    }
}

// Close modal
const closeEditModal = () => {
    editModalVisible.value = false
    languageBeingEdited.value = null
    editedLanguage.value = ''
    editedRemarks.value = '' // reset remarks
}

// Add Modal state
const addModalVisible = ref(false)
const newLanguageName = ref('')
const newLanguageRemarks = ref('')

// Open add modal
const openAddModal = () => {
    newLanguageName.value = ''
    newLanguageRemarks.value = ''
    addModalVisible.value = true
}

// Close add modal
const closeAddModal = () => {
    addModalVisible.value = false
    newLanguageName.value = ''
    newLanguageRemarks.value = ''
}

// Save new language
const saveNewLanguage = async () => {
    if (!newLanguageName.value.trim()) {
        alert("Programming language cannot be empty")
        return
    }

    try {
        await axios.post(`/action/applicants/${applicant.value.id}/languages`, {
            program_language: newLanguageName.value.trim(),
            remarks: newLanguageRemarks.value.trim() || null
        })

        // Refresh list
        fetchLanguages()
        closeAddModal()
    } catch (error) {
        console.error('Failed to add language', error)
        alert('Failed to add language. Check console for details.')
    }
}

// Delete confirmation modal
const deleteModalVisible = ref(false)
const deleteTargetId = ref<number | null>(null) // for single delete
const isBulkDelete = ref(false)

// Single delete
const confirmDeleteLanguage = (id: number) => {
    deleteTargetId.value = id
    isBulkDelete.value = false
    deleteModalVisible.value = true
}

// Bulk delete
const confirmBulkDelete = () => {
    if (!selectedLanguages.value.length) return;
    isBulkDelete.value = true
    deleteTargetId.value = null
    deleteModalVisible.value = true
}

const performDelete = async () => {
    try {
        if (isBulkDelete.value) {
            await axios.post(`/action/applicants/${applicant.value.id}/languages/bulk-delete`, {
                ids: selectedLanguages.value
            });
            selectedLanguages.value = []
        } else if (deleteTargetId.value !== null) {
            await axios.delete(`/action/applicants/${applicant.value.id}/languages/${deleteTargetId.value}`)
        }
        fetchLanguages()
    } catch (error) {
        console.error('Delete failed', error)
        alert('Failed to delete language(s). Check console.')
    } finally {
        closeDeleteModal()
    }
}

// Close modal
const closeDeleteModal = () => {
    deleteModalVisible.value = false
    deleteTargetId.value = null
    isBulkDelete.value = false
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

        <!-- Edit Language Modal -->
        <div v-if="editModalVisible" class="modal-overlay">
            <div class="modal-content">
                <h3 class="modal-title">
                    Edit {{ applicant.first_name }}'s Programming Language
                </h3>
                <input v-model="editedLanguage" class="modal-input" placeholder="Programming language" />
                <textarea v-model="editedRemarks" class="modal-textarea" placeholder="Remarks (optional)"></textarea>
                <div class="modal-actions">
                    <button class="btn-red" @click="closeEditModal">Cancel</button>
                    <button class="btn-primary" @click="saveLanguageEdit">Save</button>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="deleteModalVisible" class="modal-overlay">
            <div class="modal-content">
                <h3 class="modal-title text-red-500">
                    Confirm Deletion
                </h3>
                <p class="text-center mb-4">
                    Are you sure you want to delete
                    <strong>
                        {{ isBulkDelete ? selectedLanguages.length + ' selected language(s)' : 'this language' }}
                    </strong>?
                </p>
                <div class="modal-actions justify-center">
                    <button class="btn-red" @click="closeDeleteModal">Cancel</button>
                    <button class="btn-primary" @click="performDelete">Delete</button>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="flex justify-between mx-5 mb-3">
            <h2 class="text-xl font-bold">ACTION Applicant's Details</h2>
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
                                <td class="px-2">{{ applicant.degree }} {{
                                    applicant.others_degree ? `(${applicant.others_degree})` : '' }}</td>
                            </tr>
                            <tr v-if="applicant.expected_graduation">
                                <th class="px-2 py-2 font-semibold text-gray-600">Expected Graduation</th>
                                <td class="px-2">{{ applicant.expected_graduation }}</td>
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

        <!-- Programming Languages Section -->
        <div class="languages-section-wrapper">
            <div class="languages-section">
                <h3>Programming Languages</h3>

                <!-- Add / Bulk Delete -->
                <div class="languages-actions justify-end">
                    <div class="languages-buttons">
                        <button @click="openAddModal" class="btn-primary btn-small">Add</button>
                        <button @click="confirmBulkDelete" class="btn-red btn-small"
                            :disabled="!selectedLanguages.length">
                            Delete Selected
                        </button>
                    </div>
                </div>

                <!-- Add Language Modal -->
                <div v-if="addModalVisible" class="modal-overlay">
                    <div class="modal-content">
                        <h3 class="modal-title">
                            Add Programming Language to
                            {{ [applicant.first_name].filter(Boolean).join('') }}
                        </h3>
                        <input v-model="newLanguageName" class="modal-input" placeholder="Programming Language" />
                        <textarea v-model="newLanguageRemarks" class="modal-textarea"
                            placeholder="Remarks (optional)"></textarea>
                        <div class="modal-actions">
                            <button class="btn-red" @click="closeAddModal">Cancel</button>
                            <button class="btn-primary" @click="saveNewLanguage">Add</button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <table class="languages-table">
                    <thead>
                        <tr>
                            <th class="w-10"><input type="checkbox" @change="toggleAllLanguages($event)" /></th>
                            <th class="text-left">Language</th>
                            <th class="text-left">Remarks</th> <!-- New column -->
                            <th class="w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="lang in languages" :key="lang.id">
                            <td><input type="checkbox" :value="lang.id" v-model="selectedLanguages" /></td>
                            <td>{{ lang.program_language }}</td>
                            <td>{{ lang.remarks || '-' }}</td> <!-- Display remarks -->
                            <td class="flex gap-2 justify-center">
                                <Pen class="w-5 h-5 text-blue-500 cursor-pointer" @click="openEditModal(lang)"
                                    title="Edit" />
                                <Trash class="w-5 h-5 text-red-500 cursor-pointer"
                                    @click="confirmDeleteLanguage(lang.id)" title="Delete" />
                            </td>
                        </tr>
                        <tr v-if="!languages.length">
                            <td colspan="4" class="empty-state">No programming languages found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* =========================
   EDIT LANGUAGE MODAL
   ========================= */
.modal-overlay {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.1);
    /* very low opacity for subtle gray */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
}

.modal-content {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    /* more padding */
    width: 450px;
    /* wider modal */
    max-width: 95%;
    /* less restrictive on smaller screens */
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    /* slightly stronger shadow */
}

.modal-title {
    text-align: center;
    font-size: 1.25rem;
    /* slightly bigger */
    font-weight: 600;
    margin-bottom: 1.5rem;
    /* more space below title */
}


.modal-input,
.modal-textarea {
    width: 100%;
    padding: 0.5rem 0.75rem;
    /* slightly bigger input */
    border: 1px solid #d1d5db;
    border-radius: 6px;
    margin-bottom: 1rem;
    font-size: 0.95rem;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn-red {
    background-color: #e53e3e;
    color: white;
    padding: 0.45rem 1rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: background 0.15s ease;
}

.btn-red:hover {
    background-color: #c53030;
}

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

.languages-section-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.languages-section {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    /* slightly smaller padding */
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    width: 600px;
    /* smaller card width */
    max-width: 90%;
    /* responsive for small screens */
}

.languages-section h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
    text-align: center;
    /* center heading */
}

.languages-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.languages-input {
    flex: 1;
    padding: 0.35rem 0.75rem;
    /* slightly smaller input */
    border: 1px solid #d1d5db;
    border-radius: 6px;
    text-align: left;
}

.languages-buttons {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.languages-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
    /* slightly smaller font */
}

.languages-table th,
.languages-table td {
    padding: 0.4rem 0.6rem;
    /* smaller padding */
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}

.languages-table thead {
    background-color: #f9fafb;
    font-weight: 600;
    color: #374151;
}

.languages-table tr:hover {
    background-color: #f3f4f6;
}

.languages-table td svg {
    transition: all 0.15s ease;
}

.languages-table td svg:hover {
    opacity: 0.8;
    transform: scale(1.1);
}

.empty-state {
    text-align: center;
    padding: 0.5rem;
    font-style: italic;
    color: #6b7280;
}

.btn-small {
    padding: 0.35rem 0.7rem;
    font-size: 0.75rem;
}

.languages-actions.justify-end {
    justify-content: flex-end;
    /* align buttons to right */
}
</style>