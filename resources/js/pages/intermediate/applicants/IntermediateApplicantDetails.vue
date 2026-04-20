<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { usePage } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'

const page = usePage<any>()

const showToast = ref(false)
const toastMessage = ref<string | null>(null)
const toastType = ref<'success' | 'error'>('success')

const successMessage = computed(() => page.props.flash?.success)

const closeToast = () => {
    showToast.value = false
}

watch(successMessage, (val) => {
    if (val) {
        toastMessage.value = val
        toastType.value = 'success'
        showToast.value = true

        setTimeout(() => {
            showToast.value = false
        }, 5000)
    }
}, { immediate: true })
</script>
<template>
    <AppLayout>
        <div>Intermediate Applicant details</div>
<div v-if="showToast" class="full-width-alert">
    <div :class="['alert-banner', toastType === 'success' ? 'alert-success-banner' : 'alert-error-banner']">
        <div class="alert-body">
            {{ toastMessage }}
        </div>
        <button type="button" class="close-btn" @click="closeToast">×</button>
    </div>
</div>
</AppLayout>

</template>
