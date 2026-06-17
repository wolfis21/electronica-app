<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

// --- LÓGICA PARA EL HEADER INTERACTIVO ---

// Estado para controlar el menú móvil (abierto/cerrado)
const isMenuOpen = ref(false);

// Función para abrir/cerrar el menú móvil
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
    // Evita que la página haga scroll cuando el menú está abierto
    if (isMenuOpen.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
};

// Estado para detectar si el usuario ha hecho scroll
const isScrolled = ref(false);

// Función que se ejecuta al hacer scroll
const handleScroll = () => {
    // Si el scroll vertical es mayor a 20px, isScrolled es true
    isScrolled.value = window.scrollY > 20;
};

// Hooks del ciclo de vida de Vue
onMounted(() => {
    // Añade el listener de scroll cuando el componente se monta
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    // Limpia el listener cuando el componente se destruye para evitar fugas de memoria
    window.removeEventListener('scroll', handleScroll);
    document.body.style.overflow = 'auto'; // Restaura el scroll por si acaso
});
</script>

<template>
    <div class="font-sans antialiased text-orq-dark-gray">
        
        <header :class="[
    'fixed top-0 left-0 w-full z-50 transition-all duration-300 ease-in-out',
    isScrolled ? 'py-4 bg-white/95 shadow-md backdrop-blur-lg' : 'py-6 bg-white'
    // Se ha eliminado 'bg-transparent' para que siempre tenga fondo blanco
]">
    <div class="container mx-auto px-6">
        <nav class="flex justify-between items-center">
            <Link :href="route('welcome')" class="flex items-center space-x-3">
                <img src="/images/logo_orquestra.png" alt="Logo de Orquesta" class="h-10 w-auto" />
                <span class="font-serif text-2xl font-bold text-orq-dark-gray">Orquestra</span>
            </Link>

            <div class="hidden md:flex items-center space-x-8 font-bold text-orq-dark-gray">
                <link :href="route('welcome')" class="hover:text-orq-gold transition-colors">Inicio</link>
                <Link :href="route('pricing')" class="hover:text-orq-gold transition-colors">Precios</Link>
                <Link :href="route('about')" class="hover:text-orq-gold transition-colors">Nosotros</Link>
                <Link :href="route('contact')" class="hover:text-orq-gold transition-colors">Contacto</Link>
            </div>

            <div class="hidden md:block">
                <Link :href="route('contact')" class="px-5 py-2 rounded-md font-bold text-orq-blue bg-orq-gold transition-transform hover:scale-105">
                    Solicitar Acceso
                </Link>
            </div>

            <div class="md:hidden">
                <button @click="toggleMenu" class="focus:outline-none text-orq-dark-gray">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </nav>
    </div>
</header>

        <div v-if="isMenuOpen" class="fixed inset-0 bg-orq-blue z-40 flex flex-col items-center justify-center space-y-10">
            <button @click="toggleMenu" class="absolute top-8 right-7 text-white focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <Link @click="toggleMenu" :href="route('welcome')" class="text-3xl font-bold text-white hover:text-orq-gold transition-colors">Inicio</Link>
            <Link @click="toggleMenu" :href="route('pricing')" class="text-3xl font-bold text-white hover:text-orq-gold transition-colors">Precios</Link>
            <Link @click="toggleMenu" :href="route('about')" class="text-3xl font-bold text-white hover:text-orq-gold transition-colors">Nosotros</Link>
            <Link @click="toggleMenu" :href="route('contact')" class="text-3xl font-bold text-white hover:text-orq-gold transition-colors">Contacto</Link>
            <Link @click="toggleMenu" :href="route('contact')" class="mt-8 px-8 py-4 rounded-lg text-xl font-bold text-orq-blue bg-orq-gold transition-transform hover:scale-105">
                Solicitar Acceso
            </Link>
        </div>

        <main>
            <slot />
        </main>

        <footer class="bg-orq-blue text-white">
            <div class="container mx-auto px-6 pt-16 pb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                    <div class="sm:col-span-2 lg:col-span-1">
                        <Link :href="route('welcome')" class="flex items-center space-x-3 mb-4">
                            <img src="/images/logo_orquestra.png" alt="Logo de Orquesta" class="h-8 w-auto" />
                            <span class="text-2xl font-serif font-bold text-white">Orquestra</span>
                        </Link>
                        <p class="text-white-400">El conductor de tu negocio.</p>
                        <div class="flex space-x-4 mt-6">
                            <a href="#" class="text-white-400 hover:text-orq-gold transition-colors"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M9.19795 21.5H13.198V13.4901H16.8021L17.198 9.50977H13.198V7.5C13.198 6.94772 13.6457 6.5 14.198 6.5H17.198V2.5H14.198C11.4365 2.5 9.19795 4.73858 9.19795 7.5V9.50977H7.19795L6.80206 13.4901H9.19795V21.5Z" /></svg></a>
                            <a href="#" class="text-white-400 hover:text-orq-gold transition-colors"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M16.98,4.22A5.13,5.13,0,0,0,12,2,5.13,5.13,0,0,0,7.02,4.22C2.79,4.42,2,7.28,2,12s.79,7.58,5.02,7.78A5.13,5.13,0,0,0,12,22a5.13,5.13,0,0,0,4.98-2.22C21.21,19.58,22,16.72,22,12S21.21,4.42,16.98,4.22ZM12,6.86A5.14,5.14,0,1,1,6.86,12,5.14,5.14,0,0,1,12,6.86ZM17.22,7a1.4,1.4,0,1,1,1.4-1.4A1.4,1.4,0,0,1,17.22,7Z" /></svg></a>
                            <a href="#" class="text-white-400 hover:text-orq-gold transition-colors"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19,3A2,2,0,0,1,21,5V19a2,2,0,0,1-2,2H5a2,2,0,0,1-2-2V5A2,2,0,0,1,5,3H19M18.5,18.5V13.2A3.26,3.26,0,0,0,15.24,9.94C14.39,9.94,13.4,10.46,13,11.18V10.13H10V18.5H13V13.57c0-1.11.79-2.07,1.85-2.07,1.06,0,1.85.96,1.85,2.07V18.5H18.5M6.88,8.56a1.68,1.68,0,0,0,1.68-1.68A1.68,1.68,0,0,0,6.88,5.2,1.68,1.68,0,0,0,5.2,6.88,1.68,1.68,0,0,0,6.88,8.56M8.27,18.5V10.13H5.5V18.5H8.27Z" /></svg></a>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold uppercase tracking-wider mb-4">Producto</h4>
                        <ul class="space-y-2">
                            <li><Link :href="route('pricing')" class="text-white-400 hover:text-orq-gold transition-colors">Precios</Link></li>
                            <li><a href="#" class="text-white-400 hover:text-orq-gold transition-colors">Características</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold uppercase tracking-wider mb-4">Empresa</h4>
                        <ul class="space-y-2">
                            <li><Link :href="route('about')" class="text-white-400 hover:text-orq-gold transition-colors">Nosotros</Link></li>
                            <li><Link :href="route('contact')" class="text-white-400 hover:text-orq-gold transition-colors">Contacto</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold uppercase tracking-wider mb-4">Legal</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-white-400 hover:text-orq-gold transition-colors">Política de Privacidad</a></li>
                            <li><a href="#" class="text-white-400 hover:text-orq-gold transition-colors">Términos de Servicio</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-12 pt-8 border-t border-blue-900/50 text-center text-gray-500 text-sm">
                    <p>© {{ new Date().getFullYear() }} Orquestra Software. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>
</template>