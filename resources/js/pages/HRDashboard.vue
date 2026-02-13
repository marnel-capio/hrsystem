<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
</script>

<template>
    <AppLayout>
        <div class="dashboard-wrapper">
            <h1 class="dashboard-title">
                Hi, this is the HR Dashboard Page.
            </h1>
        </div>
    </AppLayout>
</template>

<style scoped>
.dashboard-wrapper {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dashboard-title {
    font-size: 1.6rem;
    font-weight: 600;
    color: var(--ats-text);
}
</style>
