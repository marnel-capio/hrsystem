<script setup lang="ts">
import { Head, usePage, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Users } from 'lucide-vue-next';

// Props from backend
const props = defineProps<{
  flash?: { success?: string; error?: string };
  application: {
    id: number;
    applicant_name: string;
    email: string;
    gender: string;
    age: number;
    school: string;
    degree: string;
    expected_graduation: string;
    remarks?: string;
    created_by?: string;
    created_time?: string;
    updated_by?: string;
    updated_time?: string;
  };
  userPermissions: number; // 1=admin, 2=editor, 3=viewer
}>();

// Success & error banners
const page = usePage();
const successMessage = ref((page.props.flash as any)?.success || '');
const showSuccess = ref(!!successMessage.value);

const errorMessage = ref((page.props.flash as any)?.error || '');
const showError = ref(!!errorMessage.value);

// Delete form
const deleteForm = useForm({});
const showDeleteModal = ref(false);
const deleting = ref(false);

function confirmDelete() {
  showDeleteModal.value = true;
}

function deleteApplication() {
  deleting.value = true;
  deleteForm.delete(`/action/applications/${props.application.id}`, {
    onSuccess: () => {
      window.location.href = '/action/applications';
    },
    onError: () => {
      errorMessage.value = "Failed to delete the application.";
      showError.value = true;
      setTimeout(() => (showError.value = false), 5000);
      deleting.value = false;
    },
  });
}

// Auto-hide banners
onMounted(() => {
  if (showSuccess.value) setTimeout(() => (showSuccess.value = false), 5000);
  if (showError.value) setTimeout(() => (showError.value = false), 5000);
});

function closeSuccess() {
  showSuccess.value = false;
}

function closeError() {
  showError.value = false;
}
</script>

<template>
  <Head :title="`${application.applicant_name} - Application Detail`" />

  <AppLayout>
    <!-- SUCCESS ALERT -->
<div v-if="showSuccess" class="full-width-alert">
  <div class="alert-banner alert-success-banner">
    <div class="alert-body">{{ successMessage }}</div>
    <button type="button" class="close-btn" @click="showSuccess = false">×</button>
  </div>
</div>

<!-- ERROR ALERT -->
<div v-if="showError" class="full-width-alert">
  <div class="alert-banner alert-error-banner">
    <div class="alert-body">{{ errorMessage }}</div>
    <button type="button" class="close-btn" @click="showError = false">×</button>
  </div>
</div>

<div>
    Action Application Details
</div>
    
  </AppLayout>
</template>

<style scoped>
.btn-primary {
  padding: 0.5rem 1rem;
  background-color: var(--ats-primary);
  color: #fff;
  border-radius: 5px;
}
.btn-danger {
  padding: 0.5rem 1rem;
  background-color: #dc2626;
  color: #fff;
  border-radius: 5px;
}
.btn-secondary {
  padding: 0.5rem 1rem;
  background-color: #f3f4f6;
  color: #374151;
  border-radius: 5px;
}

.close-btn {
  background: transparent;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
}
</style>