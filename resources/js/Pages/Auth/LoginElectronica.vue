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

    <div class="relative min-h-screen bg-gray-900 font-sans text-white flex items-center justify-center p-4 overflow-hidden">
        
        <!-- Imagen optimizada con lazy loading y múltiples formatos -->
        <picture class="absolute inset-0 z-0">
            <source srcset="/images/taller.webp" type="image/webp">
            <source srcset="/images/taller.avif" type="image/avif">
            <img 
                class="h-full w-full object-cover" 
                src="/images/taller.jpg" 
                alt="Taller de electrónica"
                loading="eager"
                decoding="async"
                fetchpriority="high"
            >
        </picture>
        <div class="absolute inset-0 bg-black/60 z-10"></div>

        <div class="relative z-20 w-full max-w-sm sm:max-w-md">
            <div class="bg-gray-900/80 backdrop-blur-sm p-4 sm:p-6 md:p-8 rounded-2xl border border-gray-700/50 shadow-2xl mx-4">
                
                <div class="text-center mb-8">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight break-words">
                        <span class="text-white">ELECTRÓNICA</span><span class="text-cyan-400">TPLKG</span>
                    </h1>
                    <br>
                    <p class="mt-2 text-gray-400">Bienvenido de nuevo</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <InputLabel for="email" value="Correo Electrónico" class="text-gray-300" />
                        <TextInput id="email" type="email" class="mt-1 block w-full bg-gray-800/70 border-gray-600 text-white focus:border-cyan-400 focus:ring-cyan-400 rounded-md" v-model="form.email" required autofocus />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div>
                        <InputLabel for="password" value="Contraseña" class="text-white-300"/>
                        <TextInput id="password" type="password" class="mt-1 block w-full bg-gray-800/70 border-gray-600 text-white focus:border-cyan-400 focus:ring-cyan-400 rounded-md" v-model="form.password" required />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <label class="flex items-center select-none self-start">
                            <Checkbox name="remember" v-model:checked="form.remember" class="bg-gray-700 border-gray-600 text-cyan-400 focus:ring-cyan-500" />
                            <span class="ms-2 text-sm text-white-300">Mantener sesión</span>
                        </label>
                    </div>
                    
                    <div>
                        <PrimaryButton class="w-full justify-center text-base font-bold py-3 bg-cyan-500 hover:bg-cyan-600 text-gray-900 rounded-lg transition-all duration-300 transform hover:scale-105" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Ingresar
                        </PrimaryButton>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <Link
                        :href="route('welcome')"
                        class="text-sm font-medium text-gray-400 hover:text-white transition-colors duration-200"
                    >
                        ← Ir al sitio web principal
                    </Link>
                </div>
            </div>

            <div class="text-center mt-8 text-white-400 text-sm px-4">
                <span>{{ currentDate }}</span> — <span>{{ currentTime }}</span>
            </div>
        </div>
    </div>
</template>