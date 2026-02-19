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

// 🔹 Import constants
import { MENU_PERMISSIONS, DISABLED_LINKS } from '@/constants'

// 🔹 Current page
const page = usePage()
const current = page.url

// 🔹 Sidebar collapse
const collapsed = ref(localStorage.getItem('sidebar-collapsed') === 'true')
function toggleSidebar() { collapsed.value = !collapsed.value }
watch(collapsed, (value) => localStorage.setItem('sidebar-collapsed', String(value)))

// 🔹 Application Tracker toggle
const showApplicationTracker = ref(localStorage.getItem('app-tracker-open') === 'true')
watch(showApplicationTracker, (value) => localStorage.setItem('app-tracker-open', String(value)))
watch(() => page.url, (url) => {
    if (url.startsWith('/application-tracker') || url.startsWith('/action') || url.startsWith('/intermediate')) {
        showApplicationTracker.value = true
    }
}, { immediate: true })
function toggleApplicationTracker() { showApplicationTracker.value = !showApplicationTracker.value }
function closeApplicationTracker() { showApplicationTracker.value = false }

// 🔹 User permission number
const userPermission: number = Number(page.props.auth.user.permissions)

// 🔹 Helpers
function canAccess(path: string) { return MENU_PERMISSIONS[path]?.includes(userPermission) }
function isDisabled(path: string) { return DISABLED_LINKS[path]?.includes(userPermission) }
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
            <Link v-if="canAccess('/user')" href="/user" class="sidebar-link"
                :class="{ active: current.startsWith('/user') }" @click="closeApplicationTracker">
                <User :size="18" />
                <span v-if="!collapsed">Users</span>
            </Link>

            <!-- APPLICATION TRACKER TOGGLER -->
            <div v-if="canAccess('/application-tracker')" class="sidebar-link"
                :class="{ active: isActiveStartsWith('/application-tracker') || isActiveStartsWith('/action') || isActiveStartsWith('/intermediate') }"
                @click="toggleApplicationTracker">
                <ChartLine :size="18" />
                <span v-if="!collapsed">Application Tracker</span>
            </div>

            <!-- SUBMENU -->
            <div v-if="showApplicationTracker && !collapsed" class="sidebar-submenu">

                <!-- DASHBOARD -->
                <Link v-if="canAccess('/application-tracker')"
                    :href="!isDisabled('/application-tracker') ? '/application-tracker' : undefined"
                    class="sidebar-group"
                    :class="{ active: isActive('/application-tracker'), 'disabled-link': isDisabled('/application-tracker') }"
                    @click.stop.prevent="isDisabled('/application-tracker') ? null : $event">
                    <ChartColumnIncreasing :size="16" />
                    <span>Dashboard</span>
                </Link>

                <!-- ACTION -->
                <div v-if="canAccess('/action')" class="sidebar-group"
                    :class="{ active: current.startsWith('/action') }" @click.stop>
                    <FileChartColumnIncreasing :size="16" />
                    <Link :href="isDisabled('/action') ? '#' : '/action'">
                        <span :class="{ 'disabled-link': isDisabled('/action') }">Action</span>
                    </Link>
                </div>

                <Link v-if="canAccess('/action/batches')" href="/action/batches" class="sidebar-sublink level-1"
                    :class="{ active: isActiveStartsWith('/action/batches') }" @click.stop>
                    <FileText :size="14" />
                    <span>Action Batches</span>
                </Link>

                <Link v-if="canAccess('/action/schedules')" href="/action/schedules" class="sidebar-sublink level-1"
                    :class="{ active: isActiveStartsWith('/action/schedules') }" @click.stop>
                    <FileText :size="14" />
                    <span>Resource Schedules</span>
                </Link>

                <Link v-if="canAccess('/action/applicants')" href="/action/applicants" class="sidebar-sublink level-1"
                    :class="{ active: isActiveStartsWith('/action/applicants') }" @click.stop>
                    <CircleUser :size="14" />
                    <span>Action Applicants</span>
                </Link>

                <Link v-if="canAccess('/action/applications')" href="/action/applications"
                    class="sidebar-sublink level-1" :class="{ active: isActiveStartsWith('/action/applications') }"
                    @click.stop>
                    <FileText :size="14" />
                    <span>Action Application</span>
                </Link>

                <!-- INTERMEDIATE -->
                <div v-if="canAccess('/intermediate')" class="sidebar-group"
                    :class="{ active: current.startsWith('/intermediate') }" @click.stop>
                    <FileChartColumnIncreasing :size="16" />
                    <Link :href="isDisabled('/intermediate') ? '#' : '/intermediate'">
                        <span :class="{ 'disabled-link': isDisabled('/intermediate') }">Intermediate</span>
                    </Link>
                </div>

                <Link v-if="canAccess('/intermediate/projects')" href="/intermediate/projects"
                    class="sidebar-sublink level-1" :class="{ active: isActiveStartsWith('/intermediate/projects') }"
                    @click.stop>
                    <FileText :size="14" />
                    <span>Projects</span>
                </Link>

                <Link v-if="canAccess('/intermediate/requests')" href="/intermediate/requests"
                    class="sidebar-sublink level-1" :class="{ active: isActiveStartsWith('/intermediate/requests') }"
                    @click.stop>
                    <FileText :size="14" />
                    <span>Resource Requisition Forms</span>
                </Link>

                <Link v-if="canAccess('/intermediate/applicants')" href="/intermediate/applicants"
                    class="sidebar-sublink level-1" :class="{ active: isActiveStartsWith('/intermediate/applicants') }"
                    @click.stop>
                    <CircleUser :size="14" />
                    <span>Intermediate Applicants</span>
                </Link>

                <Link v-if="canAccess('/intermediate/applications')" href="/intermediate/applications"
                    class="sidebar-sublink level-1"
                    :class="{ active: isActiveStartsWith('/intermediate/applications') }" @click.stop>
                    <FileText :size="14" />
                    <span>Intermediate Application</span>
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

/* Disabled link styling */
.disabled-link {
    pointer-events: none;
    opacity: 0.5;
}
</style>
