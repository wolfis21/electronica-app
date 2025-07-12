<script setup>
import { Link, usePage } from '@inertiajs/vue3';

// Usamos usePage() para acceder a las props compartidas globalmente como 'canLogin'
const page = usePage();


</script>

<template>
    <div class="bg-white font-sans">
        <header :class="[
            'w-full fixed top-0 left-0 z-20 transition-all duration-300',
            isScrolled ? 'py-4 bg-white/90 backdrop-blur-lg shadow-md' : 'py-6 bg-transparent'
        ]">
            <nav class="container mx-auto px-6 flex justify-between items-center">
                <Link href="/" class="flex items-center space-x-3">
                <img src="/images/orquesta-logo.png" alt="Logo de Orquesta" class="h-8 w-auto" />
                <span class="text-2xl font-serif font-bold text-orq-dark-gray">Orquesta</span>
                </Link>

                <div class="hidden md:flex items-center space-x-10 text-orq-dark-gray font-bold">
                    <Link :href="route('welcome')" class="relative group">
                    <span>Inicio</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-orq-blue scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></span>
                    </Link>
                    <Link :href="route('about')" class="relative group">
                    <span>Nosotros</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-orq-blue scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></span>
                    </Link>
                    <Link :href="route('services')" class="relative group">
                    <span>Servicios</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-orq-blue scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></span>
                    </Link>
                    <Link :href="route('contact')" class="relative group">
                    <span>Contacto</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-orq-blue scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out"></span>
                    </Link>
                </div>

                <div v-if="page.props.auth.user" class="hidden md:flex">
                    <Link :href="route('dashboard')"
                        class="px-5 py-2 rounded-md font-bold text-white bg-orq-blue transition-transform hover:scale-105">
                    Ir al Dashboard
                    </Link>
                </div>
                <div v-else class="hidden md:flex items-center space-x-4">
                    <Link :href="route('login')"
                        class="font-bold text-orq-dark-gray hover:text-orq-blue transition-colors">
                    Iniciar Sesión
                    </Link>
                    <Link v-if="page.props.canRegister" :href="route('register')"
                        class="px-5 py-2 rounded-md font-bold text-orq-blue bg-orq-gold transition-transform hover:scale-105">
                    Solicitar Acceso
                    </Link>
                </div>
            </nav>
        </header>

        <main>
            <slot />
        </main>

        <footer class="text-white" style="background-color: #1A237E;">
            <div class="container mx-auto px-6 pt-16 pb-8">
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <Link href="/" class="flex items-center space-x-3 mb-4">
                        <img src="/images/orquesta-logo.png" alt="Logo de Orquesta" class="h-8 w-auto" />
                        <span class="text-2xl font-serif font-bold text-white">Orquesta</span>
                        </Link>
                        <p class="text-gray-400">El conductor de tu negocio.</p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Navegación</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>
                                <Link :href="route('about')" class="hover:text-[#FBC02D]">Nosotros</Link>
                            </li>
                            <li>
                                <Link :href="route('services')" class="hover:text-[#FBC02D]">Servicios</Link>
                            </li>
                            <li>
                                <Link :href="route('contact')" class="hover:text-[#FBC02D]">Contacto</Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Legal</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-[#FBC02D]">Política de Privacidad</a></li>
                            <li><a href="#" class="hover:text-[#FBC02D]">Términos de Servicio</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-16 pt-8 border-t border-blue-900/50 text-center text-gray-500 text-sm">
                    <p>© {{ new Date().getFullYear() }} Orquesta. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>
</template>