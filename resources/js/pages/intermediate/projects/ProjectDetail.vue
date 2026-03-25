<script setup lang="ts">
import { Head, router, usePage, Link } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'

const page = usePage<any>()
const project = computed(() => page.props.project);
const userPermissions = computed(() => Number(page.props.user_permissions))
const showSuccess = ref(false)

const formatDate = (dateString: string | null) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  const options: Intl.DateTimeFormatOptions = {
    month: 'long',
    year: 'numeric',
  }
  return date.toLocaleDateString('en-US', options)
}

const formatDateTime = (dateString: string | null) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  const options: Intl.DateTimeFormatOptions = {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
    hour12: true,
  }
  return date.toLocaleString('en-US', options) 
}

const successMessage = computed(() => page.props.flash?.success)

const closeModal = () => { 
  showSuccess.value = false 
}

watch(successMessage, (val) => {
  if (val) {
    showSuccess.value = true;
    setTimeout(() => {
      showSuccess.value = false;
    }, 5000);
  }
}, { immediate: true });
</script>

<template>
  <Head title="Project Detail"/>

  <AppLayout>
    <!-- Success Toast -->
    <div v-if="showSuccess" class="full-width-alert">
      <div class="alert-banner alert-success-banner">
        <div class="alert-body">{{ successMessage }}</div>
        <button type="button" class="close-btn" @click="closeModal">×</button>
      </div>
    </div> 

    <!-- Header -->
    <div class="flex justify-between mx-5 mb-3">
      <h2 class="text-xl font-bold">Project Detail</h2>

    </div>
  </AppLayout>
</template>
