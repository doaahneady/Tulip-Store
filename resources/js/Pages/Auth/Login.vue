<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout variant="split" image-src="/images/logo-girl.jpg" image-alt="Sign in image">
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
            <div class="mt-6">
              <button type="button" class="w-full flex items-center justify-center gap-3 px-4 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" aria-hidden="true" class="h-5 w-5">
                  <path fill="#EA4335" d="M24 9.5c3.94 0 6.7 1.69 8.24 3.11l5.59-5.59C34.56 3.12 29.66 1 24 1 14.91 1 6.84 6.62 3.63 14.8l7.92 6.15C12.7 14.2 17.88 9.5 24 9.5z"/>
                  <path fill="#4285F4" d="M46.5 24.5c0-1.5-.13-2.6-.41-3.8H24v7.2h12.7c-.6 3.2-2.5 5.9-5.37 7.6l7.86 6.12C43.87 37.7 46.5 31.6 46.5 24.5z"/>
                  <path fill="#FBBC04" d="M11.55 29.02c-1.2-3.5-1.2-7.5 0-11l-7.92-6.15C1.73 16.21 1 20.01 1 24c0 3.99.73 7.79 2.63 11.13l7.92-6.11z"/>
                  <path fill="#34A853" d="M24 47c6.36 0 11.72-2.1 15.67-5.7l-7.86-6.12c-2.21 1.49-5.06 2.39-7.81 2.39-5.96 0-11.02-4.02-12.81-9.54l-7.92 6.11C6.84 41.38 14.91 47 24 47z"/>
                </svg>
                <span class="text-sm text-gray-700">Sign in with Google</span>
              </button>
            </div>
        </form>
    </GuestLayout>
</template>
