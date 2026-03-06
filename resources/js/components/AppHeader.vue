<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { UserCog } from 'lucide-vue-next'

interface AuthUser {
  id?: number
  first_name?: string
  last_name?: string
  email_address?: string
  permissions?: number
}

const page = usePage()
const user = page.props.auth?.user as AuthUser | undefined

// ✅ permissions from backend constants
const menuPermissions = page.props.menuPermissions as Record<string, number[]> || {}
const hiddenLinks = page.props.hiddenLinks as Record<string, number[]> || {}

const userPermission = Number(user?.permissions ?? 0)

// ✅ helpers (same as sidebar)
function canAccess(path: string) {
  return menuPermissions?.[path]?.includes(userPermission) ?? true
}

function isHidden(path: string) {
  return hiddenLinks?.[path]?.includes(userPermission) ?? false
}

// Dropdown state
const open = ref(false)
const dropdownRef = ref<HTMLElement | null>(null)

function toggle() {
  open.value = !open.value
}

function logout() {
  router.post('/logout')
}

function handleClickOutside(e: MouseEvent) {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target as Node)) {
    open.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})

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
      >
        <UserCog :size="20" />
      </button>

      <div v-if="open" class="user-dropdown">

        <div class="user-name">
          {{ fullName }}
        </div>

        <!-- ✅ Account Settings hidden for permission 7 -->
        <a
          v-if="canAccess('/account/settings') && !isHidden('/account/settings')"
          :href="`/user/${user?.id}`"
          class="dropdown-link"
        >
          Account Settings
        </a>

        <button class="dropdown-link danger" @click="logout">
          Log out
        </button>

      </div>
    </div>
  </header>
</template>