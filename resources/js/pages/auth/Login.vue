<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
//import awsLogo from '@/images/aws-logo.jpg'

// Inertia form: fields match your users table
const form = useForm({
  email_address: '',
  password: '',
})

// Submit login
const submit = () => {
  form.post('/login', {
    preserveScroll: true,
    // Let Inertia automatically follow the server redirect
    onSuccess: () => {
      form.reset('password') // clear password after login
      // No need to manually redirect, Inertia follows redirect from backend
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
      <!-- Logo -->
      <!-- <img :src="awsLogo" alt="AWS Logo" class="aws-logo" /> -->
      <h1>HR System</h1>

      <!-- Login form -->
      <form @submit.prevent="submit">
        <!-- Email input -->
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

        <!-- Password input -->
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

        <!-- Submit button -->
        <button type="submit" :disabled="form.processing">
          {{ form.processing ? 'Signing in…' : 'Sign In' }}
        </button>

        <!-- Forgot password link -->
        <a href="/forgot-password" class="forgot">
          Forgot your password?
        </a>
      </form>
    </div>
  </div>
</template>
