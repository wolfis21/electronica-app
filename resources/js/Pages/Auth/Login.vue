<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue'; // Opcional, si lo usas
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
    <Head title="Iniciar Sesión" />

    <div class="flex min-h-screen font-sans">

        <div class="hidden lg:block w-1/2">
            <img 
                src="/images/login-photo.jpg" 
                alt="Técnico trabajando en un circuito"
                class="w-full h-full object-cover"
            />
        </div>

        <div class="flex w-full lg:w-1/2 items-center justify-center bg-gray-100 p-8">
            <div class="w-full max-w-md">
                
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-black tracking-wider">
                        <span style="color: #1E1E1E;">ELECTRÓNICA</span>
                        <span style="color: #00A99D;">TPLKG</span>
                    </h1>
                </div>
                
                <div class="text-center mb-10">
                    <p class="text-gray-600">Por favor, ingresa tus credenciales para continuar.</p>
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Correo Electrónico" />
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
                        <InputLabel for="password" value="Contraseña" />
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
                            <span class="ms-2 text-sm text-gray-600">Mantener sesión iniciada</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            ¿Olvidaste tu contraseña?
                        </Link>

                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Ingresar
                        </PrimaryButton>
                    </div>
                </form>

                <div class="text-center mt-8 text-sm text-gray-500">
                    <p>¿No tienes una cuenta? 
                        <Link :href="route('register')" class="font-semibold text-indigo-600 hover:text-indigo-800">
                            Solicita Acceso
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>