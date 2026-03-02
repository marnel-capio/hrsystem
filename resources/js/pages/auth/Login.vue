<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import awsLogo from '@/images/aws-logo.jpg'

// ✅ Props from Inertia flash messages
const props = defineProps<{
  flash?: {
    success?: string
  }
}>()

// Toast state
const showSuccess = ref(false)
const successMessage = ref<string | null>(null)

// Show toast if flash.success exists
onMounted(() => {
  if (props.flash?.success) {
    successMessage.value = props.flash.success
    showSuccess.value = true

    // Auto hide after 5 seconds
    setTimeout(() => {
      showSuccess.value = false
    }, 5000)
  }
})

// Inertia login form
const form = useForm({
  email_address: '',
  password: '',
})

const submit = () => {
  form.clearErrors()
  form.post('/login', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset('password')
      router.visit('/dashboard')
    },
    onError: (errors) => {
      console.log('Login errors:', errors)
    },
  })
}
</script>

<template>
  <div class="login-wrapper">
    <div class="login-card">
      <img :src="awsLogo" alt="AWS Logo" class="aws-logo" />
      <h1>HR System</h1>

      <form @submit.prevent="submit">
        <div class="form-group">
          <label>Email</label>
          <input v-model="form.email_address" type="text" placeholder="Enter your email" autofocus />
          <span v-if="form.errors.email_address" class="error">
            {{ form.errors.email_address }}
          </span>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input v-model="form.password" type="password" placeholder="Enter your password" />
          <span v-if="form.errors.password" class="error">
            {{ form.errors.password }}
          </span>
        </div>

        <button type="submit" :disabled="form.processing">
          {{ form.processing ? 'Signing in…' : 'Sign In' }}
        </button>

        <a href="/forgot-password" class="forgot">Forgot your password?</a>
      </form>
    </div>

    <!-- Toast / Alert full-width at top -->
    <div v-if="showSuccess" class="full-width-alert">
      <div class="alert-success-banner">
        <div class="alert-body">{{ successMessage }}</div>
        <button type="button" class="close-btn" @click="showSuccess = false">×</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>