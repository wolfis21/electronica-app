<script setup>
// Imports de Vue y Laravel/Inertia
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// Imports de Componentes de Formulario
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

// --- Imports para el Carrusel ---
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel';

// --- Lógica del Formulario (sin cambios) ---
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

// --- Imágenes para el Carrusel ---
const carouselImages = ref([
    { src: '/images/admin.jpg', alt: 'Panel de administración del sistema' },
    { src: '/images/admin2.jpg', alt: 'Equipo de trabajo colaborando' },
    { src: '/images/admin3.jpg', alt: 'Persona analizando datos en una tableta' },
]);

</script>

<template>

    <Head title="Iniciar Sesión" />

    <div class="flex min-h-screen font-sans" style="background-color: #1E1E1E;">

        <div class="flex w-full lg:w-1/2 items-center justify-center p-8">
            <div class="w-full max-w-md">

                <div class="text-center mb-12">
                    <h1 class="text-4xl font-black tracking-wider">
                        <Link href="/">
                        <span class="text-white">ELECTRÓNICA</span>
                        <span style="color: #00A99D;">TPLKG</span>
                        </Link>
                    </h1>
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-white">Iniciar Sesión</h2>
                    <p class="text-gray-400">Ingresa para acceder a tu panel de control.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <InputLabel for="email" value="Correo Electrónico" class="text-gray-300" />
                        <TextInput id="email" type="email"
                            class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            v-model="form.email" required autofocus />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div>
                        <InputLabel for="password" value="Contraseña" class="text-gray-300" />
                        <TextInput id="password" type="password"
                            class="mt-1 block w-full bg-gray-800 border-gray-700 text-white focus:border-cyan-500 focus:ring-cyan-500"
                            v-model="form.password" required />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember"
                                class="bg-gray-700 border-gray-600" />
                            <span class="ms-2 text-sm text-gray-400">Mantener sesión</span>
                        </label>
                    </div>
                    <div>
                        <PrimaryButton class="w-full justify-center text-lg py-3"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                            style="background-color: #0055A4;">
                            Ingresar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>

        <div class="hidden lg:flex w-1/2 relative items-center justify-center p-8">
            
            <Carousel :autoplay="5000" :wrap-around="true" class="w-full max-w-2xl">
                <Slide v-for="image in carouselImages" :key="image.src">
                    <div class="carousel__item w-full">
                        
                        <img :src="image.src" :alt="image.alt"
                            class="w-full h-auto object-cover rounded-2xl shadow-2xl border-2 border-white/10">

                    </div>
                </Slide>
            </Carousel>
        </div>
    </div>
</template>

<style>
/* Estilos para personalizar la navegación del carrusel y hacerla visible en el tema oscuro */
.carousel__prev,
.carousel__next {
    background-color: rgba(0, 0, 0, 0.5) !important;
    color: white !important;
    border-radius: 50%;
    transform: translateY(-50%);
}

.carousel__prev:hover,
.carousel__next:hover {
    background-color: rgba(0, 0, 0, 0.8) !important;
}

.carousel__pagination-button::after {
    background-color: rgba(255, 255, 255, 0.5) !important;
}

.carousel__pagination-button--active::after {
    background-color: white !important;
}
</style>