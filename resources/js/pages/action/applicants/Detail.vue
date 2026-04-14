<script setup lang="ts">
import { Head, usePage, Link } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted } from 'vue'
import axios from 'axios'
import { Pen, Trash, Eye } from '@lucide/vue';
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

const messages = {
    record_created_successfully: {
        errorCode: 'RECORD_CREATED_SUCCESSFULLY',
        errorMessage: 'Record created successfully.',
    },
    record_updated_successfully: {
        errorCode: 'RECORD_UPDATED_SUCCESSFULLY',
        errorMessage: 'Record updated successfully.',
    },
    record_deleted_successfully: {
        errorCode: 'RECORD_DELETED_SUCCESSFULLY',
        errorMessage: 'Record successfully deleted.',
    },
    transaction_failed: {
        errorCode: 'TRANSACTION_FAILED',
        errorMessage: 'An error occurred while creating the record. Please try again.',
    },
    update_failed: {
        errorCode: 'UPDATE_FAILED',
        errorMessage: 'An error occurred while saving the record. Please try again.',
    },
    record_deleted_failed: {
        errorCode: 'RECORD_DELETED_FAILED',
        errorMessage: 'An error occurred while deleting the record. Please try again.',
    },
}

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

    editLanguageError.value = null
    editRemarksError.value = null

    if (!editedLanguage.value.trim()) {
        editLanguageError.value = "This is a required field."
        return
    }

    try {
        await axios.put(`/action/applicants/${applicant.value.id}/languages/${languageBeingEdited.value.id}`, {
            program_language: editedLanguage.value.trim(),
            remarks: editedRemarks.value.trim() || null
        })
        fetchLanguages()
        closeEditModal()

        // Show success toast
        toastMessage.value = messages.record_updated_successfully.errorMessage
        toastType.value = 'success'
        showToast.value = true
        setTimeout(() => (showToast.value = false), 5000)

    } catch (error: any) {
        if (error.response?.data?.errors) {
            editLanguageError.value = error.response.data.errors.program_language?.[0] || null
            editRemarksError.value = error.response.data.errors.remarks?.[0] || null
        } else {
            toastMessage.value = messages.update_failed.errorMessage
            toastType.value = 'error'
            showToast.value = true
            setTimeout(() => (showToast.value = false), 5000)
        }
    }
}

// Close modal
const closeEditModal = () => {
    editModalVisible.value = false
    languageBeingEdited.value = null
    editedLanguage.value = ''
    editedRemarks.value = ''
    editLanguageError.value = null
    editRemarksError.value = null
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
    addLanguageError.value = null
    addRemarksError.value = null
}

// Save new language
const saveNewLanguage = async () => {
    addLanguageError.value = null
    addRemarksError.value = null

    if (!newLanguageName.value.trim()) {
        addLanguageError.value = "This is a required field"
        return
    }

    try {
        await axios.post(`/action/applicants/${applicant.value.id}/languages`, {
            program_language: newLanguageName.value.trim(),
            remarks: newLanguageRemarks.value.trim() || null
        })

        fetchLanguages()
        closeAddModal()

        // Show success toast
        toastMessage.value = messages.record_created_successfully.errorMessage
        toastType.value = 'success'
        showToast.value = true
        setTimeout(() => (showToast.value = false), 5000)

    } catch (error: any) {
        if (error.response?.data?.errors) {
            addLanguageError.value = error.response.data.errors.program_language?.[0] || null
            addRemarksError.value = error.response.data.errors.remarks?.[0] || null
        } else {
            toastMessage.value = messages.transaction_failed.errorMessage
            toastType.value = 'error'
            showToast.value = true
            setTimeout(() => (showToast.value = false), 5000)
        }
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

const addLanguageError = ref<string | null>(null)
const addRemarksError = ref<string | null>(null)

const editLanguageError = ref<string | null>(null)
const editRemarksError = ref<string | null>(null)


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

        // Success toast
        toastMessage.value = messages.record_deleted_successfully.errorMessage
        toastType.value = 'success'
        showToast.value = true
        setTimeout(() => (showToast.value = false), 5000)

    } catch (error) {
        console.error('Delete failed', error)

        toastMessage.value = messages.record_deleted_failed.errorMessage
        toastType.value = 'error'
        showToast.value = true
        setTimeout(() => (showToast.value = false), 5000)
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

const applications = computed(() => applicant.value.applications || []);

// Keep these mapping functions for overall exam, interviews, and job offer
const examStatusLabel = (val: number | null) => {
    switch (val) {
        case 1: return 'Pending'
        case 2: return '1st Priority (Passed)'
        case 3: return '2nd Priority (P2)'
        case 4: return 'Done'
        case 5: return 'Passed'
        case 6: return 'Failed'
        default: return '-'
    }
}

const examResultLabel = (val: number | null) => {
    switch (val) {
        case 1: return 'Pending'
        case 2: return 'Passed'
        case 3: return 'Failed'
        default: return '-'
    }
}

const initialInterviewResultLabel = (val: number | null) => {
    switch (val) {
        case 1: return 'Pending'
        case 2: return 'Passed'
        case 3: return 'Failed'
        default: return '-'
    }
}

const initialInterviewStatusLabel = (val: number | null) => {
    switch (val) {
        case 1: return 'Pending'
        case 2: return 'Done'
        case 3: return 'Passed'
        case 4: return 'P2'
        case 5: return 'Failed'
        default: return '-'
    }
}

const finalInterviewResultLabel = (val: number | null) => {
    switch (val) {
        case 1: return 'Pending'
        case 2: return 'Passed'
        case 3: return 'Failed'
        default: return '-'
    }
}

const finalInterviewStatusLabel = (val: number | null) => {
    switch (val) {
        case 1: return 'Pending'
        case 2: return 'Done'
        case 3: return 'Passed'
        case 4: return 'P2'
        case 5: return 'Failed'
        default: return '-'
    }
}

const jobOfferStatusLabel = (val: number | null) => {
    switch (val) {
        case 1: return 'Pending'
        case 2: return 'Done'
        case 3: return 'Accept'
        case 4: return 'Decline'
        case 5: return 'Withdraw'
        case 6: return 'Retracted'
        default: return '-'
    }
}

const statusBadgeColor = (type: 'examResult' | 'interviewResult' | 'jobOffer', value: number | null) => {
    if (value === null) return 'bg-gray-200 text-gray-700'

    switch (type) {
        case 'examResult':
        case 'interviewResult':
            // 1-Pending, 2-Passed, 3-Failed
            if (value === 1) return 'bg-yellow-100 text-yellow-800'
            if (value === 2) return 'bg-green-100 text-green-800'
            if (value === 3) return 'bg-red-100 text-red-800'
            return 'bg-gray-200 text-gray-700'
        case 'jobOffer':
            // 1-Pending, 2-Done, 3-Accept, 4-Decline, 5-Withdraw, 6-Retracted
            if (value === 1) return 'bg-yellow-100 text-yellow-800'
            if (value === 2) return 'bg-blue-100 text-blue-800'
            if (value === 3) return 'bg-green-100 text-green-800'
            if ([4, 5, 6].includes(value)) return 'bg-red-100 text-red-800'
            return 'bg-gray-200 text-gray-700'
    }
}

const awardsDisplay = computed(() => {
    if (!applicant.value.awards_recognition) return ''

    return applicant.value.other_examination_certificate
        ? `${applicant.value.awards_recognition} (${applicant.value.other_examination_certificate})`
        : applicant.value.awards_recognition
})

const canSeeRemarks = computed(() => [1, 2, 3].includes(userPermissions.value))

// Map source ID to readable label
const sourceLabel = (sourceId: any, otherSource: string | null = null) => {
    const sources: Record<number, string> = {
        1: 'University Career Fair',
        2: 'Partner School',
        3: 'JobStreet',
        4: 'LinkedIn',
        5: 'Referral',
        6: 'Facebook',
        7: 'Jobstreet',
    }

    const id = parseInt(sourceId)
    if (!isNaN(id) && sources[id]) {
        return sources[id]
    }

    // fallback to other_source if present
    if (otherSource && otherSource.trim()) return otherSource

    return '-'
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

        <!-- Add Language Modal -->
        <div v-if="addModalVisible" class="modal-overlay">
            <div class="modal-content">
                <h3 class="modal-title">Add Programming Language</h3>

                <!-- Language Input -->
                <div class="modal-field">
                    <label style="font-weight: bold;">Programming Language Name *</label>
                    <input v-model="newLanguageName" class="modal-input" placeholder="Programming Language" />
                    <span v-if="addLanguageError" class="modal-error">{{ addLanguageError }}</span>
                </div>

                <!-- Remarks Textarea -->
                <div class="modal-field">
                    <label>Remarks</label>
                    <textarea v-model="newLanguageRemarks" class="modal-textarea"
                        placeholder="Remarks (optional)"></textarea>
                    <span v-if="addRemarksError" class="modal-error">{{ addRemarksError }}</span>
                </div>

                <div class="modal-actions">
                    <button class="btn-red" @click="closeAddModal">Cancel</button>
                    <button class="btn-primary" @click="saveNewLanguage">Add</button>
                </div>
            </div>
        </div>

        <!-- Edit Language Modal -->
        <div v-if="editModalVisible" class="modal-overlay">
            <div class="modal-content">
                <h3 class="modal-title">Edit Programming Language</h3>

                <!-- Language Input -->
                <div class="modal-field">
                    <label style="font-weight: bold;">Programming Language Name *</label>
                    <input v-model="editedLanguage" class="modal-input" placeholder="Programming Language" />
                    <span v-if="editLanguageError" class="modal-error">{{ editLanguageError }}</span>
                </div>

                <!-- Remarks Textarea -->
                <div class="modal-field">
                    <label>Remarks</label>
                    <textarea v-model="editedRemarks" class="modal-textarea"
                        placeholder="Remarks (optional)"></textarea>
                    <span v-if="editRemarksError" class="modal-error">{{ editRemarksError }}</span>
                </div>

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
                <div class="modal-actions">
                    <button class="btn-red" @click="closeDeleteModal">Cancel</button>
                    <button class="btn-primary" @click="performDelete">Delete</button>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="flex justify-between mx-5 mb-3">
            <h2 class="text-xl font-bold">ACTION Applicant's Details</h2>
            <Link v-if="![5, 6].includes(userPermissions)" :href="`/action/applicants/${applicant.id}/edit`"
                class="btn-primary !bg-[#1C7BA5]">
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
                                <th class="px-2 py-2 font-semibold text-gray-600 w-40 text-right">Email</th>
                                <td class="px-2">{{ applicant.email_address }}</td>
                            </tr>
                            <tr v-if="applicant.gender !== null && applicant.gender !== undefined">
                                <th class="px-2 py-2 font-semibold text-gray-600 text-right">Gender</th>
                                <td class="px-2">{{ genderLabel(applicant.gender) }}</td>
                            </tr>
                            <tr v-if="applicant.age">
                                <th class="px-2 py-2 font-semibold text-gray-600 text-right">Age</th>
                                <td class="px-2">{{ applicant.age }}</td>
                            </tr>
                            <tr v-if="applicant.school">
                                <th class="px-2 py-2 font-semibold text-gray-600 text-right">School</th>
                                <td class="px-2">{{ applicant.school }}</td>
                            </tr>
                            <tr v-if="applicant.degree || applicant.others_degree">
                                <th class="px-2 py-2 font-semibold text-gray-600 text-right">Degree</th>
                                <td class="px-2">{{ applicant.degree }} {{
                                    applicant.others_degree ? `(${applicant.others_degree})` : '' }}</td>
                            </tr>
                            <tr v-if="applicant.expected_graduation">
                                <th class="px-2 py-2 font-semibold text-gray-600 text-right">Expected Graduation</th>
                                <td class="px-2">{{ applicant.expected_graduation }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RIGHT: Remarks, Awards, Thesis, Extra Curricular -->
            <div class="col-span-2 bg-white rounded-xl shadow border p-6 space-y-4">
                <!-- Source Information -->
                <div v-if="applicant.source_type || applicant.source || applicant.other_source"
                    class="bg-white rounded-xl shadow border p-4 mb-4">
                    <h4 class="text-xs font-bold mb-2">SOURCE INFORMATION</h4>
                    <p class="text-xs break-words">
                        <strong>Source Type:</strong> {{ sourceTypeLabel(applicant.source_type) || '-' }}<br>
                        <strong>Source:</strong>
                        {{ applicant.source
                            ? sourceLabel(applicant.source)
                            : applicant.other_source
                                ? applicant.other_source
                                : '-'
                        }}
                    </p>
                </div>
                <div v-if="applicant.awards_recognition">
                    <h4 class="text-xs font-bold mb-2 text-left">AWARDS / RECOGNITION</h4>
                    <p class="text-xs break-words">{{ awardsDisplay }}</p>
                </div>
                <div v-if="applicant.thesis_project">
                    <h4 class="text-xs font-bold mb-2 text-left">THESIS / PROJECT</h4>
                    <p class="text-xs break-words">{{ applicant.thesis_project }}</p>
                </div>
                <div v-if="applicant.extra_curricular">
                    <h4 class="text-xs font-bold mb-2 text-left">EXTRA CURRICULAR</h4>
                    <p class="text-xs break-words">{{ applicant.extra_curricular }}</p>
                </div>
                <div v-if="applicant.remarks">
                    <h4 class="text-xs font-bold mb-2 text-left">REMARKS</h4>
                    <p class="text-xs break-words">{{ applicant.remarks }}</p>
                </div>
            </div>
        </div>

        <!-- Application Details Table -->
        <div class="mx-5 mt-6 bg-white rounded-xl shadow border p-6">
            <h3 class="text-lg font-semibold mb-4">Application Details</h3>

            <table class="w-full table-fixed border-collapse border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 py-2 font-semibold text-gray-600">No.</th>
                        <th class="px-2 py-2 font-semibold text-gray-600">Exam Result</th>
                        <th class="px-2 py-2 font-semibold text-gray-600">Exam Status</th>
                        <th v-if="canSeeRemarks">Exam Remarks</th>
                        <th class="px-2 py-2 font-semibold text-gray-600">Initial Interview Result</th>
                        <th class="px-2 py-2 font-semibold text-gray-600">Initial Interview Status</th>
                        <th v-if="canSeeRemarks">Initial Interview Remarks</th>
                        <th class="px-2 py-2 font-semibold text-gray-600">Final Interview Result</th>
                        <th class="px-2 py-2 font-semibold text-gray-600">Final Interview Status</th>
                        <th v-if="canSeeRemarks">Final Interview Remarks</th>
                        <th class="px-2 py-2 font-semibold text-gray-600">Job Offer Status</th>
                        <th v-if="canSeeRemarks">Job Offer Remarks</th>
                        <th class="px-2 py-2 font-semibold text-gray-600">Remarks</th>
                        <th class="px-2 py-2 font-semibold text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(app, index) in applications" :key="app.id">

                        <td class="px-2 py-2 text-center font-medium text-gray-700">
                            {{ Number(index) + 1 }}
                        </td>

                        <td class="px-2 py-2 text-center">
                            {{ examResultLabel(app.exam_result) }}
                        </td>

                        <td class="px-2 py-2 text-center">
                            {{ examStatusLabel(app.exam_application_status) }}
                        </td>

                        <td v-if="canSeeRemarks" class="px-2 py-2 max-w-[200px] text-center">
                            <div class="truncate w-full mx-auto" :title="app.exam_remarks || '-'">
                                {{ app.exam_remarks || '-' }}
                            </div>
                        </td>

                        <td class="px-2 py-2 text-center">
                            {{ initialInterviewResultLabel(app.initial_interview_result) }}
                        </td>

                        <td class="px-2 py-2 text-center">
                            {{ initialInterviewStatusLabel(app.initial_interview_application_status) }}
                        </td>

                        <td v-if="canSeeRemarks" class="px-2 py-2 max-w-[200px] text-center">
                            <div class="truncate w-full mx-auto" :title="app.initial_interview_remarks || '-'">
                                {{ app.initial_interview_remarks || '-' }}
                            </div>
                        </td>

                        <td class="px-2 py-2 text-center">
                            {{ finalInterviewResultLabel(app.final_interview_result) }}
                        </td>

                        <td class="px-2 py-2 text-center">
                            {{ finalInterviewStatusLabel(app.final_interview_application_status) }}
                        </td>

                        <td v-if="canSeeRemarks" class="px-2 py-2 max-w-[200px] text-center">
                            <div class="truncate w-full mx-auto" :title="app.final_interview_remarks || '-'">
                                {{ app.final_interview_remarks || '-' }}
                            </div>
                        </td>

                        <td class="px-2 py-2 text-center">
                            {{ jobOfferStatusLabel(app.job_offer_status) }}
                        </td>

                        <td v-if="canSeeRemarks" class="px-2 py-2 max-w-[200px] text-center">
                            <div class="truncate w-full mx-auto" :title="app.job_offer_remarks || '-'">
                                {{ app.job_offer_remarks || '-' }}
                            </div>
                        </td>

                        <td class="px-2 py-2 max-w-[250px] text-center">
                            <div class="truncate w-full mx-auto" :title="app.remarks || '-'">
                                {{ app.remarks || '-' }}
                            </div>
                        </td>

                        <td class="px-2 py-2 text-center">
                            <Link :href="`/action/applications/${app.id}`" class="inline-flex justify-center">
                                <Eye class="w-5 h-5 text-green-500 hover:text-green-600" />
                            </Link>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Programming Languages Section -->
        <div class="mx-5 mt-6 bg-white rounded-xl shadow border p-6" v-if="![5, 6].includes(userPermissions)">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Programming Languages</h3>
                <div class="flex gap-2">
                    <button @click="openAddModal" class="btn-primary btn-small">Add</button>
                    <button @click="confirmBulkDelete" class="btn-red btn-small" :disabled="!selectedLanguages.length">
                        Delete Selected
                    </button>
                </div>
            </div>

            <!-- Languages Table -->
            <table class="min-w-full table-auto text-xs border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="w-10 text-left px-3 py-2 font-semibold text-gray-700 ">
                            <input type="checkbox" @change="toggleAllLanguages($event)" />
                        </th>
                        <th class="text-left px-3 py-2 font-semibold text-gray-700 ">Language</th>
                        <th class="text-left px-3 py-2 font-semibold text-gray-700 ">Remarks</th>
                        <th class="w-24 px-3 py-2 font-semibold text-gray-700 ">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="lang in languages" :key="lang.id" class="hover:bg-gray-50">
                        <td class="px-3 py-2"><input type="checkbox" :value="lang.id" v-model="selectedLanguages" />
                        </td>
                        <td class="px-3 py-2">{{ lang.program_language }}</td>
                        <td class="px-3 py-2">{{ lang.remarks || '-' }}</td>
                        <td class="flex gap-2 justify-center px-3 py-2">
                            <Pen class="w-5 h-5 text-blue-500 cursor-pointer" @click="openEditModal(lang)"
                                title="Edit" />
                            <Trash class="w-5 h-5 text-red-500 cursor-pointer" @click="confirmDeleteLanguage(lang.id)"
                                title="Delete" />
                        </td>
                    </tr>
                    <tr v-if="!languages.length">
                        <td colspan="4" class="text-center text-gray-500 py-2 italic">No programming languages found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Modal fields wrapper for consistent spacing */
.modal-field {
    display: flex;
    flex-direction: column;
    margin-bottom: 0.75rem;
}

/* Error messages */
.modal-error {
    color: #e53e3e;
    font-size: 0.75rem;
    margin-top: 0.25rem;
    min-height: 1rem;
    /* Reserve space so modal doesn't jump */
}

/* Inputs and textareas */
.modal-input,
.modal-textarea {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.95rem;
    box-sizing: border-box;
}

/* Override global button styles only inside modals */
.modal-actions button {
    width: 20%;
    /* remove 100% width from global CSS */
    padding: 0.50rem 0.75rem;
    /* tight padding around text */
    font-size: 0.875rem;
    /* adjust font size */
    border-radius: 0.5rem;
    /* keep slightly rounded corners */
    white-space: nowrap;
    /* prevent text from wrapping */
}

/* Keep original colors */
.modal-actions .btn-primary {
    background-color: #1C7BA5;
    color: white;
}

.modal-actions .btn-red {
    background-color: #E53E3E;
    color: white;
}

span.text-red-500 {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.75rem;
}

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
    justify-content: center;
    /* align buttons to bottom-left */
    gap: 0.5rem;
    margin-top: 1rem;
    /* slight spacing above buttons */
}

.btn-red,
.btn-primary {
    padding: 0.35rem 0.7rem;
    /* slightly smaller */
    font-size: 0.75rem;
    /* slightly smaller font */
    border-radius: 6px;
    font-weight: 500;
    transition: background 0.15s ease;
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


.languages-header {
    display: flex;
    justify-content: space-between;
    /* title left, buttons right */
    align-items: center;
    margin-bottom: 1rem;
}

.languages-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    text-align: left;
    /* left align */
    margin: 0;
}

.languages-section-wrapper {
    display: block;
    /* instead of flex center */
    margin-top: 2rem;
    width: 100%;
    /* take full width */
}

.languages-section {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    width: 100%;
    /* full width */
    max-width: 100%;
    /* override previous max-width */
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