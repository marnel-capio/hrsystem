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

      <Link
        v-if="userPermissions === 1 || userPermissions === 2"
        :href="`/intermediate/projects/${project.id}/edit`"
        class="bg-[#1C7BA5] text-white px-4 py-2 text-xs rounded cursor-pointer"
      >
        Edit
      </Link>
    </div>

    <!-- Main Content -->
    <div class="mx-5 mt-6 grid grid-cols-3 gap-6">
      <!-- LEFT -->
      <div class="col-span-1 space-y-4">
        <div class="bg-[#2F359E] text-white rounded-xl p-6 shadow">
          <h3 class="text-lg font-bold text-center">{{ project.project_name }}</h3>
          <p class="text-xs opacity-80 text-center">Project Name</p>
        </div>

        <div class="bg-white rounded-xl p-4 shadow border">
          <table class="min-w-full table-auto">
            <tbody>
              <tr class="mt-5">
                  <th class="px-2 py-2 text-left font-semibold text-xs text-gray-600">
                    Updated by
                  </th>
                  <td class="text-xs px-2">{{ project.updated_by_name }}</td>
              </tr>
              <tr class="mt-5">
                <th class="px-2 py-2 text-left font-semibold text-xs text-gray-600 mt-5">
                  Updated Time
                </th>
                <td class="text-xs px-2">{{ formatDateTime(project.updated_time) }}</td> 
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- RIGHT -->
      <div class="col-span-2 bg-white rounded-xl shadow border p-6">
        <h4 class="text-xs font-bold mb-3 text-center">REMARKS</h4>
        <p class="text-xs">{{ project.remarks }}</p>
      </div>
    </div>
  </AppLayout>
</template>
