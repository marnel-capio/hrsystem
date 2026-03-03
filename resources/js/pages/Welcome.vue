<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome" />
    <div class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a]">
        <nav class="flex gap-4">
            <template v-if="$page.props.auth.user">
                <Link href="/dashboard" class="btn">Dashboard</Link>
            </template>
            <template v-else>
                <Link href="/login" class="btn">Log in</Link>
                <Link v-if="canRegister" href="/register" class="btn">Register</Link>
            </template>
        </nav>
    </div>
</template>
