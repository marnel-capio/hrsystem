<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';
import awsLogo from '@/images/aws-logo.jpg'

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="auth-bg">
        <div class="auth-card">
            <img :src="awsLogo" alt="AWS" class="aws-logo" />
            <h1 v-if="title" class="auth-title">{{ title }}</h1>
            <p v-if="description" class="auth-description">
                {{ description }}
            </p>

            <slot />
        </div>
    </div>
</template>

<style scoped>
.auth-bg {
    min-height: 100vh;
    background-color: #80ABCB;
    display: flex;
    align-items: center;
    justify-content: center;
}

.auth-card {
    background: #ffffff;
    width: 360px;
    padding: 32px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(46, 53, 158, 0.25);
    text-align: center;
}

.auth-title {
    color: #2E359E;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 8px;
}

.auth-description {
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 24px;
}

.aws-logo {
    display: block;
    margin: 0 auto 16px;
    max-width: 120px;
    height: auto;
}
</style>
