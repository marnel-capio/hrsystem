<script setup lang="ts">
import { ref, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import {
    User,
    ChartLine,
    ChartColumnIncreasing,
    FileChartColumnIncreasing,
    FileText,
    CircleUser,
    Menu
} from 'lucide-vue-next'

const page = usePage()
const current = page.url

const collapsed = ref(localStorage.getItem('sidebar-collapsed') === 'true')
function toggleSidebar() { collapsed.value = !collapsed.value }
watch(collapsed, (value) => localStorage.setItem('sidebar-collapsed', String(value)))

const showApplicationTracker = ref(localStorage.getItem('app-tracker-open') === 'true')
watch(showApplicationTracker, (value) => localStorage.setItem('app-tracker-open', String(value)))
watch(() => page.url, (url) => {
    if (url.startsWith('/application-tracker') || url.startsWith('/action') || url.startsWith('/intermediate')) {
        showApplicationTracker.value = true
    }
}, { immediate: true })
function toggleApplicationTracker() { showApplicationTracker.value = !showApplicationTracker.value }
function closeApplicationTracker() { showApplicationTracker.value = false }

// 🔹 User permission
const userPermission: number = Number(page.props.auth.user.permissions)

// 🔹 Permissions and hidden links from shared props
const menuPermissions = page.props.menuPermissions as Record<string, number[]> || {}
const hiddenLinks = page.props.hiddenLinks as Record<string, number[]> || {}

// 🔹 Helpers
function canAccess(path: string) {
    return menuPermissions?.[path]?.includes(userPermission) ?? true
}
function isHidden(path: string) {
    return hiddenLinks?.[path]?.includes(userPermission) ?? false
}
function isActive(path: string) { return current === path }
function isActiveStartsWith(path: string) { return current.startsWith(path) }
</script>

<template>
    <aside :class="['sidebar', { collapsed }]">
        <div class="sidebar-header">
            <Link href="/" class="sidebar-home">
                <span v-if="!collapsed" class="sidebar-title">HR System</span>
            </Link>
            <button class="sidebar-toggle" @click="toggleSidebar">
                <Menu :size="20" />
            </button>
        </div>

        <nav class="sidebar-nav">
            <!-- USERS -->
            <Link v-if="canAccess('/user') && !isHidden('/user')" href="/user" class="sidebar-link"
                :class="{ active: isActiveStartsWith('/user') }" @click="closeApplicationTracker">
                <User :size="18" />
                <span v-if="!collapsed">Users</span>
            </Link>

            <!-- APPLICATION TRACKER TOGGLER (always visible if user can access tracker) -->
            <div v-if="canAccess('/application-tracker')" class="sidebar-link"
                :class="{ active: isActiveStartsWith('/application-tracker') || isActiveStartsWith('/action') || isActiveStartsWith('/intermediate') }"
                @click="toggleApplicationTracker">
                <ChartLine :size="18" />
                <span v-if="!collapsed">Application Tracker</span>
            </div>

            <!-- SUBMENU -->
            <div v-if="showApplicationTracker && !collapsed" class="sidebar-submenu">

                <!-- DASHBOARD (hidden only for 4,6,7) -->
                <Link v-if="canAccess('/application-tracker-dashboard') && !isHidden('/application-tracker-dashboard')" href="/application-tracker-dashboard"
                    class="sidebar-group" :class="{ active: isActive('/application-tracker-dashboard') }">
                    <ChartColumnIncreasing :size="16" />
                    <span>Dashboard</span>
                </Link>

                <!-- ACTION -->
                <div v-if="canAccess('/action') && !isHidden('/action')" class="sidebar-group"
                    :class="{ active: isActiveStartsWith('/action') }">
                    <FileChartColumnIncreasing :size="16" />
                    <span>Action</span>
                </div>

                <Link v-if="canAccess('/action/batches')" href="/action/batches" class="sidebar-sublink level-1"
                    :class="{ active: isActiveStartsWith('/action/batches') }">
                    <FileText :size="14" />
                    <span>Action Batches</span>
                </Link>

                <Link v-if="canAccess('/action/schedules')" href="/action/schedules" class="sidebar-sublink level-1"
                    :class="{ active: isActiveStartsWith('/action/schedules') }">
                    <FileText :size="14" />
                    <span>Resource Schedules</span>
                </Link>

                <Link v-if="canAccess('/action/applicants')" href="/action/applicants" class="sidebar-sublink level-1"
                    :class="{ active: isActiveStartsWith('/action/applicants') }">
                    <CircleUser :size="14" />
                    <span>Action Applicants</span>
                </Link>

                <Link v-if="canAccess('/action/applications')" href="/action/applications"
                    class="sidebar-sublink level-1" :class="{ active: isActiveStartsWith('/action/applications') }">
                    <FileText :size="14" />
                    <span>Action Application</span>
                </Link>

                <!-- INTERMEDIATE -->
                <div v-if="canAccess('/intermediate') && !isHidden('/intermediate')" class="sidebar-group"
                    :class="{ active: isActiveStartsWith('/intermediate') }">
                    <FileChartColumnIncreasing :size="16" />
                    <span>Intermediate</span>
                </div>

                <Link v-if="canAccess('/intermediate/projects')" href="/intermediate/projects"
                    class="sidebar-sublink level-1" :class="{ active: isActiveStartsWith('/intermediate/projects') }">
                    <FileText :size="14" />
                    <span>Projects</span>
                </Link>

                <Link v-if="canAccess('/intermediate/requests')" href="/intermediate/requests"
                    class="sidebar-sublink level-1" :class="{ active: isActiveStartsWith('/intermediate/requests') }">
                    <FileText :size="14" />
                    <span>Resource Requisition Forms</span>
                </Link>

                <Link v-if="canAccess('/intermediate/applicants')" href="/intermediate/applicants"
                    class="sidebar-sublink level-1" :class="{ active: isActiveStartsWith('/intermediate/applicants') }">
                    <CircleUser :size="14" />
                    <span>Intermediate Applicants</span>
                </Link>

                <Link v-if="canAccess('/intermediate/applications')" href="/intermediate/applications"
                    class="sidebar-sublink level-1"
                    :class="{ active: isActiveStartsWith('/intermediate/applications') }">
                    <FileText :size="14" />
                    <span>Intermediate Application</span>
                </Link>

                <!-- WALIK-IN -->
                <Link v-if="canAccess('/walk-in-application') && !isHidden('/walk-in-application')"
                    href="/walk-in-application" class="sidebar-group"
                    :class="{ active: isActiveStartsWith('/walk-in-application') }">

                    <FileChartColumnIncreasing :size="16" />
                    <span>Walk-in Application</span>

                </Link>
            </div>
        </nav>
    </aside>
</template>

<style scoped>
.sidebar-submenu {
    margin-left: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.sidebar-group {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-top: 0.75rem;
    font-size: 0.7rem;
    text-transform: uppercase;
    opacity: 0.6;
    padding-left: 0.25rem;
}

.sidebar-sublink {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.85rem;
    padding: 0.35rem 0.5rem;
    border-radius: 6px;
    opacity: 0.85;
}

.sidebar-sublink.level-1 {
    margin-left: 0.75rem;
}

.sidebar-sublink:hover {
    background: rgba(255, 255, 255, 0.08);
}

.sidebar-sublink.active {
    background: rgba(255, 255, 255, 0.12);
    opacity: 1;
}

.sidebar-group.active {
    opacity: 1;
    color: white;
}

.sidebar-group.active svg {
    opacity: 1;
}
</style>