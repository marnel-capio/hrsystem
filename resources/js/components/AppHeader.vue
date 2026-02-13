<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { UserCog } from 'lucide-vue-next'

// Get Inertia page props and typed user
interface AuthUser {
  first_name?: string
  last_name?: string
  email_address?: string
}

const page = usePage()
const user = page.props.auth?.user as AuthUser | undefined

// Dropdown state
const open = ref(false)
const dropdownRef = ref<HTMLElement | null>(null)

// Toggle dropdown
function toggle() {
  open.value = !open.value
}

// Logout function
function logout() {
  router.post('/logout')
}

// Close dropdown when clicking outside
function handleClickOutside(e: MouseEvent) {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target as Node)) {
    open.value = false
  }
}

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})

// Computed full name
const fullName = computed(() => {
  const first = user?.first_name?.trim() || ''
  const last = user?.last_name?.trim() || ''
  const name = `${first} ${last}`.trim()
  return name.length ? name : 'User'
})
</script>

<template>
  <header class="header">
    <h1 class="header-title">
      Welcome, {{ fullName }}
    </h1>

    <div class="header-user" ref="dropdownRef">
      <button
        class="user-btn"
        @click.stop="toggle"
        aria-haspopup="true"
        :aria-expanded="open"
      >
        <UserCog :size="20" />
      </button>

      <div v-if="open" class="user-dropdown">
        <div class="user-name">
          {{ fullName }}
        </div>

        <a href="/account" class="dropdown-link">
          Account Settings
        </a>

        <button class="dropdown-link danger" @click="logout">
          Log out
        </button>
      </div>
    </div>
  </header>
</template>
