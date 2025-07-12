<script setup>
import { Link, usePage } from '@inertiajs/vue3';

// Usamos usePage() para acceder a las props compartidas globalmente como 'canLogin'
const page = usePage();
</script>

<template>
    <div class="flex flex-col min-h-screen bg-white font-sans">

        <header class="w-full sticky top-0 left-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200">
            <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
                
                <Link href="/" class="flex items-center space-x-3">
                    <img src="/images/orquesta-logo.png" alt="Logo de Orquesta" class="h-8 w-auto" />
                    <span class="text-2xl font-serif font-bold text-orq-dark-gray">Orquesta</span>
                </Link>

                <div class="hidden md:flex items-center space-x-10 text-orq-dark-gray font-bold">
                    <Link :href="route('welcome')" class="hover:text-orq-blue transition-colors">Inicio</Link>
                    <Link :href="route('about')" class="hover:text-orq-blue transition-colors">Nosotros</Link>
                    <Link :href="route('services')" class="hover:text-orq-blue transition-colors">Servicios</Link>
                    <Link :href="route('contact')" class="hover:text-orq-blue transition-colors">Contacto</Link>
                </div>

                <div v-if="page.props.auth.user" class="hidden md:flex">
                     <Link :href="route('dashboard')" class="px-5 py-2 rounded-md font-bold text-white bg-orq-blue transition-transform hover:scale-105">
                        Ir al Dashboard
                    </Link>
                </div>
                <div v-else class="hidden md:flex items-center space-x-4">
                    <Link :href="route('login')" class="font-bold text-orq-dark-gray hover:text-orq-blue transition-colors">
                        Iniciar Sesión
                    </Link>
                    <Link v-if="page.props.canRegister" :href="route('register')" class="px-5 py-2 rounded-md font-bold text-orq-blue bg-orq-gold transition-transform hover:scale-105">
                        Solicitar Acceso
                    </Link>
                </div>
            </nav>
        </header>

        <main class="flex-grow">
            <slot />
        </main>
        
        <footer class="w-full py-6 bg-orq-light-gray">
             <div class="container mx-auto text-center">
                <p class="text-sm" style="color: #546E7A;">© {{ new Date().getFullYear() }} Orquesta. Todos los derechos reservados.</p>
            </div>
        </footer>
    </div>
</template>