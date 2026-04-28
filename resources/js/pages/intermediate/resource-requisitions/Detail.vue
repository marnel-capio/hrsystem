<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { Users } from 'lucide-vue-next';
import { ref, computed, onMounted, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const page = usePage();

const successMessage = ref((page.props.flash as any)?.success || '');
const showSuccess = ref(!!successMessage.value);

const errorMessage = ref((page.props.flash as any)?.error || '');
const showError = ref(!!errorMessage.value);

const deleteForm = useForm({});
const showDeleteModal = ref(false);
const deleting = ref(false);

function confirmDelete() {
  showDeleteModal.value = true;
}

function deleteRequisition() {
  deleting.value = true;

  deleteForm.delete(`/intermediate/resource-requisitions/${props.requisition.id}`, {
    preserveScroll: false,
    preserveState: false,
    onSuccess: () => {
      showDeleteModal.value = false;
    },
    onError: () => {
      errorMessage.value = "Failed to delete the record.";
      showError.value = true;
      setTimeout(() => (showError.value = false), 5000);
    },
    onFinish: () => {
      deleting.value = false;
    },
  });
}

const notificationSent = ref(false);
const sendingNotification = ref(false);
const notificationForm = useForm({});

function sendNotification() {
  sendingNotification.value = true;

  notificationForm.post(
    `/intermediate/resource-requisitions/${props.requisition.id}/send-notification`,
    {
      onSuccess: () => {
        sendingNotification.value = false;
        notificationSent.value = true;

        const page = usePage();

        successMessage.value =
          (page.props.flash as any)?.success ||
          "Notification emails sent to all active HR recruiters successfully.";

        showSuccess.value = true;

        setTimeout(() => (showSuccess.value = false), 5000);
      },

      onError: () => {
        sendingNotification.value = false;

        errorMessage.value =
          "An error occurred while sending the email/s. Please try again.";

        showError.value = true;
        setTimeout(() => (showError.value = false), 5000);
      },
    }
  );
}

function resolve(map: Record<number, string>, value: number | null | undefined) {
  if (!value) return '';
  return map[value] ?? 'Unknown';
}

const props = defineProps<{
  flash?: {
    success?: string;
    error?: string;
  };
  user_permissions: number;
  requisition: {
  id: number;
  project_name: string;
  business_unit: string;

  engagement_type: string;
  engagement_type_label?: string;

  sourcing_type?: string;
  sourcing_type_label?: string;

  request_type: string;
  request_type_label?: string;

  replacement_due_to?: string;
  replacement_due_to_label?: string;

  person_to_replace?: string;
  
  location_assignment: string | null;
  location_assignment_label: string | null;
  custom_location: string | null;

  project_id?: number;
  project_description?: string;
  business_unit_id?: number;
  resource?: string;
  practice?: string;

  no_resources_needed?: number;
  start_date?: string;
  duration_project_engagement?: string;

  required_skills?: string;
  preferred_skills?: string;
  role?: string;
  expected_salary_range?: string;

  remarks?: string;

  updated_by_name?: string;
  updated_time?: string;
};
  userPermissions: number;
}>();

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Resource Requisition', href: '/resource-requisitions' },
  { title: props.requisition.project_name, href: '#' },
];

watch(
  successMessage,
  (newVal) => {
    if (newVal) {
      showSuccess.value = true;
      setTimeout(() => {
        showSuccess.value = false;
        successMessage.value = '';
      }, 5000);
    }
  },
  { immediate: true }
);

watch(
  errorMessage,
  (newVal) => {
    if (newVal) {
      showError.value = true;
      setTimeout(() => {
        showError.value = false;
        errorMessage.value = '';
      }, 5000);
    }
  },
  { immediate: true }
);

const formatDateTime = (dateString: string | null) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const options: Intl.DateTimeFormatOptions = {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
    hour12: true,
  };
  return date.toLocaleString('en-US', options);
};

const formatDate = (dateString: string | null) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const options: Intl.DateTimeFormatOptions = {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
  };
  return date.toLocaleString('en-US', options);
};

console.log('User Permissions in Vue:', props.user_permissions);

const HR_ADMIN_PERMISSION = 1;
const BU_MANAGER_PERMISSION = 5; 

const isAllowedToManage = computed(() => {
  console.log('Checking permissions...');
  return props.user_permissions === HR_ADMIN_PERMISSION || props.user_permissions === BU_MANAGER_PERMISSION;
});

console.log('Is Allowed to Manage:', isAllowedToManage.value);

</script>

<template>
  <Head :title="`${requisition.project_name} - Resource Requisition`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <!-- SUCCESS ALERT -->
    <div v-if="showSuccess" class="full-width-alert">
      <div class="alert-banner alert-success-banner">
        <div class="alert-body">{{ successMessage }}</div>
        <button class="close-btn" @click="showSuccess = false">×</button>
      </div>
    </div>

    <!-- ERROR ALERT -->
    <div v-if="showError" class="full-width-alert">
      <div class="alert-banner alert-error-banner">
        <div class="alert-body">{{ errorMessage }}</div>
        <button class="close-btn" @click="showError = false">×</button>
      </div>
    </div>

    <div class="flex flex-1 flex-col gap-6 p-8 bg-zinc-50/50 dark:bg-zinc-950 min-h-screen">

      <!-- Header -->
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100">
          Resource Requisition Detail
        </h1>

        <div class="flex gap-3">
          <button
            v-if="isAllowedToManage"
            @click.prevent="sendNotification"
            :disabled="sendingNotification || notificationSent"
            class="btn-send"
          >
            <span v-if="!sendingNotification && !notificationSent">Send Notification</span>
            <span v-else-if="sendingNotification">Sending...</span>
            <span v-else>Sent</span>
          </button>

          <a
            v-if="isAllowedToManage"
            :href="`/intermediate/resource-requisitions/${props.requisition.id}/edit`"
            class="btn-edit"
          >
            Edit
          </a>

          <button
            v-if="isAllowedToManage"
            @click="confirmDelete"
            class="btn-delete"
          >
            Delete
          </button>
        </div>
      </div>

      <!-- DELETE MODAL -->
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showDeleteModal = false"></div>

        <div class="relative bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
          <h3 class="text-xl font-bold text-center text-red-600 mb-2">
            Delete Resource Requisition?
          </h3>

          <p class="text-center mb-6">
            Are you sure you want to delete a Resource Requisition for Project <strong>"{{ requisition.project_name }}"</strong>?<br>
            This action cannot be undone.
          </p>

          <div class="flex gap-3">
            <button @click="showDeleteModal = false" :disabled="deleting" class="btn-primary !border !border-gray-300 !text-black !bg-secondary">
              Cancel
            </button>

            <button @click="deleteRequisition" :disabled="deleting" class="btn-primary">
              <span v-if="!deleting">Delete</span>
              <span v-else>Deleting...</span>
            </button>
          </div>
        </div>
      </div>

      <!-- BODY -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="flex flex-col gap-4 ">

          <!-- Project Card -->
          <div
            class="flex-1 text-white px-5 py-4 rounded-xl shadow-md flex flex-col items-center justify-center text-center bg-[#2F359E]"
          >
            <h2 class="text-2xl font-extrabold tracking-wide drop-shadow">
              {{ requisition.project_name }}
            </h2>
            <p class="text-sm opacity-90 mt-1">
              Project Resource Requisition Overview
            </p>
          </div>

          <div class="bg-white rounded-xl p-4 shadow border -grow">
            <table class="min-w-full">
              <tbody>
              <tr class="mt-5">
                  <th class="px-2 py-2 text-left font-bold">
                    Business Unit
                  </th>
                  <td class=" px-2">{{ requisition.business_unit.business_unit }}</td>
              </tr>
              <tr class="mt-5">
                  <th class="px-2 py-2 text-left font-bold mt-5">Location Assignment</th>
                  <td class=" px-2">
                    <!-- Check if location_assignment is 1 to 5 -->
                    <span v-if="requisition.location_assignment && requisition.location_assignment >= '1' && requisition.location_assignment <= '5'">
                      {{ requisition.location_assignment_label}}
                    </span>
                    <!-- If "Other" is selected, show the custom location -->
                    <span v-else>
                      {{ requisition.custom_location || 'No custom location provided' }}
                    </span>
                  </td>
                </tr>
            </tbody></table>
          </div>
        </div>

        <div class="md:col-span-2 bg-white dark:bg-zinc-900 p-6 rounded-xl border shadow h-full">
          <table class="min-w-full table-auto">
            <tbody>
              <tr>
                <td class="font-semibold px-3 py-2 border-y border-x-0">Engagement Type</td>
                <td class="px-3 py-2 border-y border-x-0">{{ requisition.engagement_type_label }}</td>
              </tr>
              <tr>
                <td class="font-semibold px-3 py-2 border-y border-x-0">Sourcing Type</td>
                <td class="px-3 py-2 border-y border-x-0">{{ requisition.sourcing_type_label }}</td>
              </tr>
              <tr>
                <td class="font-semibold px-3 py-2 border-y border-x-0">Request Type</td>
                <td class="px-3 py-2 border-y border-x-0">{{ requisition.request_type_label }}</td>
              </tr>
              <tr>
                <td class="font-semibold px-3 py-2 border-y border-x-0">Replacement Reason</td>
                <td class="px-3 py-2 border-y border-x-0">{{ requisition.replacement_due_to_label}}</td>
              </tr>
              <tr>
                <td class="font-semibold px-3 py-2 border-y border-x-0">Person to Replace</td>
                <td class="px-3 py-2 border-y border-x-0">{{ requisition.person_to_replace || '-'}}</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
      <div class="md:col-span-2 bg-white dark:bg-zinc-900 p-6 rounded-xl border shadow h-full">
        <table class="min-w-full table-auto">
          <tbody>
            <tr>
              <td class="font-semibold px-3 py-2 border-y border-x-0">Resource</td>
              <td class="px-3 py-2 border-y border-x-0">
                {{ requisition.resource || '-' }}
              </td>
            </tr>
            <tr>
              <td class="font-semibold px-3 py-2 border-y border-x-0">Practice</td>
              <td class="px-3 py-2 border-y border-x-0">
                {{ requisition.practice || '-' }}
              </td>
            </tr>
            <tr>
              <td class="font-semibold px-3 py-2 border-y border-x-0">No. of Resources Needed</td>
              <td class="px-3 py-2 border-y border-x-0">{{ requisition.no_resources_needed || '-'}}</td>
            </tr>
            <tr>
              <td class="font-semibold px-3 py-2 border-y border-x-0">Start Date</td>
              <td class="px-3 py-2 border-y border-x-0">{{ formatDate(requisition.start_date) || '-'}}</td>
            </tr>
            <tr>
              <td class="font-semibold px-3 py-2 border-y border-x-0">Duration of Project Engagement</td>
              <td class="px-3 py-2 border-y border-x-0">{{ requisition.duration_project_engagement || '-'}}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div>
            <label class="text-sm font-semibold">Project Description</label>
            <textarea
              class="w-full bg-zinc-50 border rounded-lg p-2.5"
              rows="4"
              readonly
              :value="requisition.project_description"
            />
          </div>

      <!-- Text Areas -->
      <div class="space-y-4 mt-6">
        <div>
          <label class="text-sm font-semibold">Required Skills/Experience</label>
          <textarea
            class="w-full bg-zinc-50 border rounded-lg p-2.5"
            rows="4"
            readonly
            :value="requisition.required_skills"
          />
        </div>

        <div>
          <label class="text-sm font-semibold">Preferred Skills/Experience</label>
          <textarea
            class="w-full bg-zinc-50 border rounded-lg p-2.5"
            rows="4"
            readonly
            :value="requisition.preferred_skills"
          />
        </div>

        <div>
          <label class="text-sm font-semibold">Role/Job Description</label>
          <textarea
            class="w-full bg-zinc-50 border rounded-lg p-2.5"
            rows="4"
            readonly
            :value="requisition.role"
          />
        </div>
        <div>
          <label class="text-sm font-semibold">Expected Salary/Billing Range</label>
          <input class="w-full bg-zinc-50 border rounded-lg p-2.5" readonly
            :value="requisition.expected_salary_range"
          />
        </div>
        <div>
          <label class="text-sm font-semibold">Remarks</label>
          <textarea
            class="w-full bg-zinc-50 border rounded-lg p-2.5"
            rows="4"
            readonly
            :value="requisition.remarks"
          />
        </div>
      </div>

      <!-- Updated Info -->
      <div class="text-xs text-zinc-500 mt-4">
        <span>Last updated by: {{ requisition.updated_by_name }}</span>
        <span class="ml-8">Last updated at: {{ formatDateTime(requisition.updated_time) }}</span>
      </div>

    </div>
  </AppLayout>
</template>