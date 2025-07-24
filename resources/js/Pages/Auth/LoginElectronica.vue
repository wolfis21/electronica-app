<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
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

// --- LÓGICA PARA FECHA Y HORA ---
const currentTime = ref('');
const currentDate = computed(() => {
    return new Date().toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' });
});
let timerId;
const updateTime = () => {
    currentTime.value = new Date().toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit' });
};
onMounted(() => {
    updateTime();
    timerId = setInterval(updateTime, 1000);
});
onUnmounted(() => {
    clearInterval(timerId);
});
</script>

<template>
    <Head title="Iniciar Sesión" />

    <div class="flex min-h-screen font-sans bg-gray-900 text-white">
        <div class="flex flex-1 flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                
                <div class="text-left mb-2">
                    <h1 class="text-4xl font-black tracking-wider">
                        <span class="text-white">ELECTRÓNICA</span>
                        <span class="text-cyan-400">TPLKG</span>
                    </h1>
                </div>
                <br />

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold tracking-tight text-white">Iniciar Sesión</h2>
                </div>

                <div class="mt-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="email" value="Correo Electrónico" class="text-white" />
                            <TextInput id="email" type="email" class="mt-1 block w-full bg-gray-800 border-gray-600 text-white focus:border-cyan-400 focus:ring-cyan-400" v-model="form.email" required autofocus />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                        <div>
                            <InputLabel for="password" value="Contraseña" class="text-white"/>
                            <TextInput id="password" type="password" class="mt-1 block w-full bg-gray-800 border-gray-600 text-white focus:border-cyan-400 focus:ring-cyan-400" v-model="form.password" required />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <Checkbox name="remember" v-model:checked="form.remember" class="bg-gray-700 border-gray-600 text-cyan-400 focus:ring-cyan-500" />
                                <span class="ms-2 text-sm text-gray-400">Mantener sesión iniciada</span>
                            </label>

                        </div>
                        
                        <div>
                            <PrimaryButton class="w-full justify-center text-lg py-3 bg-blue-600 hover:bg-blue-700" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Ingresar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
<br />
            <footer class="text-center text-xs text-white-500">
                <p>Desarrollado por Orquesta</p>
                <p>Isaac Saado y José Andrés Dasilva</p>
            </footer>
        </div>

        <div class="relative hidden w-0 flex-1 lg:block">
            <img class="absolute inset-0 h-full w-full object-cover" src="/images/taller.jpg" alt="Taller de electrónica">
        </div>
    </div>
</template>