<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
</script>

<template>
    <AuthBase
        title="Create an account"
        description="Enter your details below to create your account"
    >
        <Head title="Register" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">

                <!-- First Name -->
                <div class="grid gap-2">
                    <Label for="first_name">First Name</Label>
                    <Input
                        id="first_name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="given-name"
                        name="first_name"
                        placeholder="First name"
                    />
                    <InputError :message="errors.first_name" />
                </div>

                <!-- Middle Name -->
                <div class="grid gap-2">
                    <Label for="middle_name">Middle Name</Label>
                    <Input
                        id="middle_name"
                        type="text"
                        :tabindex="2"
                        autocomplete="additional-name"
                        name="middle_name"
                        placeholder="Middle name (optional)"
                    />
                    <InputError :message="errors.middle_name" />
                </div>

                <!-- Last Name -->
                <div class="grid gap-2">
                    <Label for="last_name">Last Name</Label>
                    <Input
                        id="last_name"
                        type="text"
                        required
                        :tabindex="3"
                        autocomplete="family-name"
                        name="last_name"
                        placeholder="Last name"
                    />
                    <InputError :message="errors.last_name" />
                </div>

                <!-- Contact Number -->
                <div class="grid gap-2">
                    <Label for="contact_no">Contact Number</Label>
                    <Input
                        id="contact_no"
                        type="text"
                        :tabindex="4"
                        name="contact_no"
                        placeholder="e.g. +65 9123 4567"
                    />
                    <InputError :message="errors.contact_no" />
                </div>

                <!-- Address -->
                <div class="grid gap-2">
                    <Label for="address">Address</Label>
                    <Input
                        id="address"
                        type="text"
                        :tabindex="5"
                        name="address"
                        placeholder="Address (optional)"
                    />
                    <InputError :message="errors.address" />
                </div>

                <!-- Email -->
                <div class="grid gap-2">
                    <Label for="email_address">Email Address</Label>
                    <Input
                        id="email_address"
                        type="email"
                        required
                        :tabindex="6"
                        autocomplete="email"
                        name="email_address"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email_address" />
                </div>

                <!-- Password -->
                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="7"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <!-- Confirm Password -->
                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm Password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="8"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <!-- Submit Button -->
                <Button
                    type="submit"
                    class="mt-2 w-full"
                    tabindex="9"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Create account
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground mt-4">
                Already have an account?
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4"
                    :tabindex="10"
                    >Log in</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
