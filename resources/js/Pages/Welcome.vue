<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Bienvenido" />

    <div class="flex flex-col min-h-screen font-sans" style="background-color: #1E1E1E;">

        <header class="w-full absolute top-0 left-0 z-10">
            <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
                <div class="text-xl font-black tracking-wider">
                    <Link href="/">
                        <span style="color: #FFFFFF;">ELECTRÓNICA</span>
                        <span style="color: #00A99D;">TPLKG</span>
                    </Link>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#inicio" class="text-gray-300 hover:text-white transition-colors">Inicio</a>
                    <a href="#nosotros" class="text-gray-300 hover:text-white transition-colors">Nosotros</a>
                    <a href="#servicios" class="text-gray-300 hover:text-white transition-colors">Servicios</a>
                    <a href="#contacto" class="text-gray-300 hover:text-white transition-colors">Contacto</a>
                </div>

                <div v-if="canLogin" class="hidden md:flex items-center space-x-4">
                    <Link :href="route('login')" class="text-gray-300 hover:text-white font-medium transition-colors">
                        Iniciar Sesión
                    </Link>
                    <Link v-if="canRegister" :href="route('register')" class="px-5 py-2 rounded-md font-semibold text-[#1E1E1E] transition-transform hover:scale-105" style="background-color: #FFD100;">
                        Solicitar Acceso
                    </Link>
                </div>
                
                <div class="md:hidden">
                    <button class="text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </nav>
        </header>

        <main class="flex-grow flex items-center">
            <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
                
                <div class="text-center md:text-left">
                    <h1 class="text-4xl md:text-5xl font-extrabold leading-tight" style="color: #FFFFFF;">
                        La gestión de tus servicios, <span style="color: #00A99D;">simplificada.</span>
                    </h1>
                    <p class="mt-4 text-lg text-gray-400 max-w-lg mx-auto md:mx-0">
                        Nuestra plataforma te da el control total sobre tus órdenes de trabajo, clientes y revisiones técnicas. Optimiza tu tiempo y mejora tu eficiencia.
                    </p>
                    <div class="mt-8">
                         <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                             class="inline-block px-8 py-3 rounded-lg text-lg font-semibold text-white transition-transform hover:scale-105" style="background-color: #0055A4;"
                        >
                            Ir al Dashboard
                        </Link>
                         <Link
                            v-else
                            :href="route('login')"
                            class="inline-block px-8 py-3 rounded-lg text-lg font-semibold text-white transition-transform hover:scale-105" style="background-color: #0055A4;"
                        >
                            Comenzar
                        </Link>
                    </div>
                </div>

                <div class="hidden md:block">
                    <div class="relative w-full h-80">
                         <div class="absolute -top-8 -left-8 w-48 h-48 rounded-lg opacity-50" style="background-color: #0055A4;"></div>
                         <div class="absolute -bottom-8 -right-8 w-48 h-48 rounded-lg opacity-50" style="background-color: #00A99D;"></div>
                         <div class="absolute inset-8 bg-gray-800/20 rounded-xl backdrop-blur-sm border border-gray-700/50 flex items-center justify-center">
                             <p class="text-2xl font-bold" style="color: #FFD100;">Gestión Administrativa</p>
                         </div>
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="w-full py-4">
             <div class="container mx-auto text-center">
                <p class="text-sm text-gray-500">Desarrollado por Bermu Tech INC</p>
            </div>
        </footer>
    </div>
</template>