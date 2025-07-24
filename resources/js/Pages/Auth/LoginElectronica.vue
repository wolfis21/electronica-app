<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
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
    <div class="flex min-h-screen items-center justify-center p-6 font-sans bg-gray-900 text-white">
        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <h1 class="text-4xl font-black tracking-wider">
                    <span class="text-white">ELECTRÓNICA</span>
                    <span class="text-cyan-400">TPLKG</span>
                </h1>
            </div>
            <div class="bg-gray-800 p-8 rounded-xl border border-gray-700">
                <div class="text-left mb-8">
                    <h2 class="text-2xl font-bold text-white">Iniciar Sesión</h2>
                    <p class="text-gray-400">Accede a tu panel de control.</p>
                </div>
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <InputLabel for="email" value="Correo Electrónico" class="text-gray-300" />
                        <TextInput id="email" type="email" class="mt-1 block w-full bg-gray-700/50 border-gray-600 text-white focus:border-cyan-500 focus:ring-cyan-500" v-model="form.email" required autofocus />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div>
                        <InputLabel for="password" value="Contraseña" class="text-gray-300"/>
                        <TextInput id="password" type="password" class="mt-1 block w-full bg-gray-700/50 border-gray-600 text-white focus:border-cyan-500 focus:ring-cyan-500" v-model="form.password" required />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                    <div class="flex items-center justify-end">
                        <PrimaryButton class="w-full justify-center text-lg py-3 bg-blue-600 hover:bg-blue-700" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Ingresar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>