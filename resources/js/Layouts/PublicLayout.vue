<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const mobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};
</script>

<template>
    <div class="bg-surface text-on-surface antialiased min-h-screen flex flex-col relative">
        <!-- TopNavBar -->
        <nav class="fixed top-0 w-full z-50 glass-effect border-b border-surface-container-highest flex justify-center">
            <div class="w-full max-w-max-width px-margin-mobile md:px-margin-desktop h-20 flex justify-between items-center">
                <div class="flex items-center gap-3 z-50">
                    <img alt="Logo SM Soluciones Electrónicas" class="h-10 md:h-12 w-10 md:w-12 object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrOwtOiLTygvFj7dXHyE3W_N7dwaO0V9fylL4D1YAfUOfIGjol1WOjXrep4tnu4zaR2EqDCuXpcesNpFk_vcF82l-eiFBbS0gbccXn4kzkjAQs4zPTGnDo8yJmTbLOu8AqxiM_YjqN_gp1_mroiwUQ5alaMOAhOiPXpFBZNtI1ObJeu88SENf6Em30WJ2z-dzoudGLqcuXzD9BJsjXl5edwgxZpR-bjDe3xrdO2aZBAlDvaIBxeL8_W586Wrd1wGBM70DjxEXA6hVt">
                    <span class="font-headline-md text-lg md:text-headline-md font-bold text-on-surface">SM Soluciones</span>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <Link :href="route('welcome')" :class="route().current('welcome') ? 'font-label-md text-label-md text-primary border-b-2 border-primary pb-1 font-bold' : 'font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors'">Inicio</Link>
                    <Link :href="route('about')" :class="route().current('about') ? 'font-label-md text-label-md text-primary border-b-2 border-primary pb-1 font-bold' : 'font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors'">Nosotros</Link>
                    <Link :href="route('services')" :class="route().current('services') ? 'font-label-md text-label-md text-primary border-b-2 border-primary pb-1 font-bold' : 'font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors'">Servicios</Link>
                    <Link :href="route('pricing')" :class="route().current('pricing') ? 'font-label-md text-label-md text-primary border-b-2 border-primary pb-1 font-bold' : 'font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors'">Precios</Link>
                    <Link :href="route('contact')" :class="route().current('contact') ? 'font-label-md text-label-md text-primary border-b-2 border-primary pb-1 font-bold' : 'font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors'">Contacto</Link>
                </div>
                
                <!-- Desktop Action Button -->
                <Link :href="route('login')" class="hidden md:block bg-primary text-on-primary px-6 py-2 rounded-lg font-label-md text-label-md hover:brightness-110 transition-all active:scale-95 duration-200 shadow-[0_0_15px_rgba(0,242,255,0.3)] text-center">
                    Iniciar Sesión
                </Link>

                <!-- Mobile Menu Button -->
                <button @click="toggleMobileMenu" class="md:hidden text-on-surface p-2 z-50">
                    <span class="material-symbols-outlined text-[28px]">{{ mobileMenuOpen ? 'close' : 'menu' }}</span>
                </button>
            </div>
        </nav>
        
        <!-- Mobile Sidebar (Slide-over) -->
        <transition name="slide">
            <div v-if="mobileMenuOpen" class="fixed inset-0 z-40 bg-surface/98 backdrop-blur-xl md:hidden flex flex-col pt-24 px-8 pb-8">
                <div class="flex flex-col gap-6 mt-4 flex-grow">
                    <Link @click="toggleMobileMenu" :href="route('welcome')" :class="route().current('welcome') ? 'font-headline-lg text-primary font-bold' : 'font-headline-lg text-on-surface-variant'">Inicio</Link>
                    <Link @click="toggleMobileMenu" :href="route('about')" :class="route().current('about') ? 'font-headline-lg text-primary font-bold' : 'font-headline-lg text-on-surface-variant'">Nosotros</Link>
                    <Link @click="toggleMobileMenu" :href="route('services')" :class="route().current('services') ? 'font-headline-lg text-primary font-bold' : 'font-headline-lg text-on-surface-variant'">Servicios</Link>
                    <Link @click="toggleMobileMenu" :href="route('pricing')" :class="route().current('pricing') ? 'font-headline-lg text-primary font-bold' : 'font-headline-lg text-on-surface-variant'">Precios</Link>
                    <Link @click="toggleMobileMenu" :href="route('contact')" :class="route().current('contact') ? 'font-headline-lg text-primary font-bold' : 'font-headline-lg text-on-surface-variant'">Contacto</Link>
                </div>
                
                <div class="mt-auto pt-8 border-t border-surface-container-highest">
                    <Link @click="toggleMobileMenu" :href="route('login')" class="block w-full bg-primary text-on-primary px-6 py-4 rounded-xl font-label-md text-lg hover:brightness-110 transition-all text-center uppercase tracking-widest font-bold shadow-[0_0_20px_rgba(0,242,255,0.2)]">
                        Iniciar Sesión
                    </Link>
                </div>
            </div>
        </transition>
        
        <main class="pt-20 flex-grow overflow-x-hidden">
            <slot />
        </main>
        
        <!-- Footer -->
        <footer class="w-full bg-surface-container-lowest border-t border-surface-container-highest py-12 px-margin-mobile md:px-margin-desktop mt-auto">
            <div class="max-w-max-width mx-auto flex flex-col md:flex-row justify-between items-start gap-12">
                <div class="max-w-xs">
                    <div class="flex items-center gap-3 mb-6">
                        <img alt="Logo Footer" class="h-10 w-10 object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrOwtOiLTygvFj7dXHyE3W_N7dwaO0V9fylL4D1YAfUOfIGjol1WOjXrep4tnu4zaR2EqDCuXpcesNpFk_vcF82l-eiFBbS0gbccXn4kzkjAQs4zPTGnDo8yJmTbLOu8AqxiM_YjqN_gp1_mroiwUQ5alaMOAhOiPXpFBZNtI1ObJeu88SENf6Em30WJ2z-dzoudGLqcuXzD9BJsjXl5edwgxZpR-bjDe3xrdO2aZBAlDvaIBxeL8_W586Wrd1wGBM70DjxEXA6hVt">
                        <span class="font-headline-md text-headline-md font-black text-on-surface">SM Soluciones</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Liderando el mercado de soporte técnico con precisión y confianza desde hace más de una década.</p>
                    <div class="flex gap-4 mt-6">
                        <a class="w-10 h-10 rounded-full border border-surface-container-highest flex items-center justify-center hover:bg-primary/20 hover:text-primary transition-all" href="#">
                            <span class="material-symbols-outlined text-sm">share</span>
                        </a>
                        <a class="w-10 h-10 rounded-full border border-surface-container-highest flex items-center justify-center hover:bg-primary/20 hover:text-primary transition-all" href="#">
                            <span class="material-symbols-outlined text-sm">mail</span>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-12 md:gap-24">
                    <div>
                        <h4 class="font-label-md text-label-md font-bold text-on-surface mb-4">Servicios</h4>
                        <ul class="space-y-2">
                            <li class=""><a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary hover:underline transition-all" href="#">Reparación Hardware</a></li>
                            <li class=""><a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary hover:underline transition-all" href="#">Soporte Software</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-label-md text-label-md font-bold text-on-surface mb-4">Empresa</h4>
                        <ul class="space-y-2">
                            <li class=""><a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary hover:underline transition-all" href="#">Nosotros</a></li>
                            <li class=""><a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary hover:underline transition-all" href="#">Privacidad</a></li>
                            <li class=""><a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary hover:underline transition-all" href="#">Términos</a></li>
                        </ul>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <h4 class="font-label-md text-label-md font-bold text-on-surface mb-4">Soporte</h4>
                        <ul class="space-y-2">
                            <li class=""><a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary hover:underline transition-all" href="#">FAQ</a></li>
                            <li class=""><a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary hover:underline transition-all" href="#">Contacto</a></li>
                            <li class=""><a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary hover:underline transition-all" href="#">Rastrear Orden</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="max-w-max-width mx-auto mt-12 pt-8 border-t border-surface-container-highest flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="font-label-sm text-label-sm text-on-surface-variant opacity-80">© 2024 SM Soluciones Electrónicas. All rights reserved.</p>
                <div class="flex gap-6">
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Privacy Policy</span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Terms of Service</span>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
/* Los estilos base se movieron a app.blade.php pero si es necesario clases extra para Vue, se agregan aquí */
.glass-effect { background: rgba(23, 27, 39, 0.8); backdrop-filter: blur(8px); }

/* Transición para el menú lateral */
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateX(100%);
  opacity: 0;
}
</style>