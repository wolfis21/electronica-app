<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>

    <Head title="Bienvenido a Orquesta" />

    <div class="flex flex-col min-h-screen font-sans" style="background-color: #FFFFFF;">

        <header class="w-full absolute top-0 left-0 z-10">
            <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
                <Link href="/" class="flex items-center space-x-3">
                <img src="/images/logo_orquestra.png" alt="Logo de Orquesta" class="h-8 w-auto" />
                <span class="text-2xl font-bold" style="color: #263238;">Orquestra</span>
                </Link>

                <div class="hidden md:flex items-center space-x-10 text-orq-dark-gray font-bold">
                    <Link :href="route('welcome')" class="hover:text-orq-blue transition-colors">Inicio</Link>
                    <Link :href="route('about')" class="hover:text-orq-blue transition-colors">Nosotros</Link>
                    <Link :href="route('services')" class="hover:text-orq-blue transition-colors">Servicios</Link>
                    <Link :href="route('contact')" class="hover:text-orq-blue transition-colors">Contacto</Link>
                </div>

                <div v-if="canLogin" class="hidden md:flex items-center space-x-4">
                    <Link :href="route('login')"
                        class="font-semibold transition-colors text-[#263238] hover:text-[#1A237E]">
                    Iniciar Sesión
                    </Link>
                    <Link v-if="canRegister" :href="route('register')"
                        class="px-5 py-2 rounded-md font-bold text-[#1A237E] transition-transform hover:scale-105"
                        style="background-color: #FBC02D;">
                    Solicitar Acceso
                    </Link>
                </div>
            </nav>
        </header>

        <main class="flex-grow flex items-center">
            <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">

                <div class="text-center md:text-left">
                    <h1 class="text-4xl md:text-5xl font-extrabold leading-tight" style="color: #263238;">
                        Orquestando la eficiencia de tu negocio.
                    </h1>
                    <p class="mt-4 text-lg max-w-lg mx-auto md:mx-0" style="color: #546E7A;">
                        Orquestra es la plataforma definitiva para la gestión de servicios, clientes y órdenes de
                        trabajo. Simplifica tus procesos y enfócate en crecer.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                        <Link :href="route('login')"
                            class="w-full sm:w-auto text-center px-8 py-4 rounded-lg text-lg font-bold text-orq-blue bg-orq-gold transition-transform hover:scale-105 shadow-lg">
                        Comenzar ahora
                        </Link>
                        <Link v-if="canRegister" :href="route('register')"
                            class="w-full sm:w-auto text-center font-bold text-orq-dark-gray hover:text-orq-blue">
                        Solicitar una demo
                        </Link>
                    </div>
                </div>

                <div class="hidden md:flex items-center justify-center">
                    <div class="relative w-full max-w-md h-80 rounded-xl flex items-center justify-center"
                        style="background-color: #ECEFF1;">
                        <svg class="w-24 h-24" style="color: #1A237E; opacity: 0.1;" viewBox="0 0 48 48" fill="none"
                            xmlns="images/admin1.jpg">
                            <path
                                d="M24 4C12.954 4 4 12.954 4 24s8.954 20 20 20 20-8.954 20-20S35.046 4 24 4zm0 6c2.21 0 4 1.79 4 4s-1.79 4-4 4-4-1.79-4-4 1.79-4 4-4zm0 24c-6.627 0-12-5.373-12-12h24c0 6.627-5.373 12-12 12z"
                                fill="currentColor"></path>
                        </svg>
                        <p class="absolute text-xl font-bold" style="color: #1A237E; opacity: 0.2;">Ilustración de Marca
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="w-full py-4">
            <div class="container mx-auto text-center">
                <p class="text-sm" style="color: #546E7A;">© {{ new Date().getFullYear() }} Orquesta. Todos los derechos
                    reservados.</p>
            </div>
        </footer>
    </div>
</template>