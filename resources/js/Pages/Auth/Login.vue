<script setup>
// La lógica del script no cambia. Sigue manejando el formulario y la autenticación.
import Checkbox from '@/Components/Checkbox.vue';
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

        <div class="hidden lg:flex w-1/2 items-center justify-center p-12 text-white relative bg-orq-blue">
            <div class="relative z-10 text-center">
                 <Link href="/" class="flex flex-col items-center justify-center space-y-4">
                    <img src="/images/logo_orquestra.png" alt="Logo de Orquesta" class="h-16 w-auto" />
                    <span class="text-4xl font-serif font-bold">Orquestra</span>
                </Link>
                <p class="mt-4 max-w-sm text-lg text-blue-100/80">
                    El conductor de tu negocio.
                </p>
            </div>
        </div>

        <div class="flex w-full lg:w-1/2 items-center justify-center bg-orq-light-gray p-8">
            <div class="w-full max-w-md">
                
                <div class="text-center mb-10">
                    <h2 class="font-serif text-4xl font-bold text-orq-dark-gray">Bienvenido</h2>
                    <p class="mt-2 text-gray-600">Por favor, ingresa tus credenciales.</p>
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <InputLabel for="email" value="Correo Electrónico" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full border-gray-300 focus:border-orq-gold focus:ring-orq-gold rounded-md"
                            v-model="form.email"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Contraseña" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full border-gray-300 focus:border-orq-gold focus:ring-orq-gold rounded-md"
                            v-model="form.password"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600">Mantener sesión</span>
                        </label>
                    </div>

                    <div>
                        <PrimaryButton class="w-full justify-center text-lg py-3 bg-orq-blue hover:bg-blue-900" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Ingresar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>