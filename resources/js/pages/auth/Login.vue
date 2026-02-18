<script setup lang="ts">
import { ref, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import awsLogo from '@/images/aws-logo.jpg'

// ✅ Get props from Inertia
const props = defineProps<{
  status?: string
}>()

// Store status in a local reactive ref
const statusMessage = ref<string | null>(props.status || null)

// Optional: watch for changes in props (usually not needed unless status updates dynamically)
watch(
  () => props.status,
  (newVal) => {
    if (newVal) statusMessage.value = newVal
  }
)

// Inertia form
const form = useForm({
  email_address: '',
  password: '',
})

// Submit login
const submit = () => {
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

      <!-- Status message -->
      <div
        v-if="statusMessage"
        class="mb-4 text-center text-sm font-medium text-green-600"
      >
        {{ statusMessage }}
      </div>

      <form @submit.prevent="submit">
        <div class="form-group">
          <label>Email</label>
          <input
            v-model="form.email_address"
            type="email"
            placeholder="Enter your email"
            autofocus
            required
          />
          <span v-if="form.errors.email_address" class="error">
            {{ form.errors.email_address }}
          </span>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input
            v-model="form.password"
            type="password"
            placeholder="Enter your password"
            required
          />
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
  </div>
</template>
