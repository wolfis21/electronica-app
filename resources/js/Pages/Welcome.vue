<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';


const setupScrollAnimations = () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.scroll-animate').forEach(el => observer.observe(el));
};

onMounted(setupScrollAnimations);
</script>

<template>
    <Head title="Orquesta: El Conductor de tu Negocio" />

    <PublicLayout>
        
        <section class="relative pt-24 pb-20 md:pt-32 md:pb-24 bg-orq-light-gray">
            <div class="container mx-auto px-6 relative z-10">
                <div class="max-w-3xl text-center mx-auto">
                    <h1 class="font-serif text-4xl sm:text-5xl md:text-7xl font-bold text-orq-dark-gray leading-tight">
                        Orquestando la eficiencia de tu negocio.
                    </h1>
                    <p class="mt-6 text-lg md:text-xl text-gray-600">
                        La plataforma definitiva que silencia el caos y sincroniza tus operaciones. Toma la batuta y dirige tu negocio hacia una nueva melodía de crecimiento.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link :href="route('register')" class="w-full sm:w-auto text-center px-8 py-4 rounded-lg text-lg font-bold text-orq-blue bg-orq-gold transition-transform hover:scale-105 shadow-lg">
                            Comenzar ahora
                        </Link>
                        <Link href="#" class="w-full sm:w-auto text-center font-bold text-orq-blue hover:text-blue-900">
                            Solicitar una demo
                        </Link>
                    </div>
                </div>
            </div>
        </section>

       <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto scroll-animate">
                <h2 class="font-serif text-3xl md:text-5xl font-bold text-orq-dark-gray">Un Plan para Cada Director</h2>
                <p class="mt-4 text-lg text-gray-600">Desde el solista que recién comienza hasta la sinfonía empresarial completa. Encuentra el ritmo perfecto para tu negocio.</p>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">

                <div class="scroll-animate border border-gray-200 rounded-xl p-8 text-center flex flex-col transition-transform hover:scale-105">
                    <h3 class="font-serif text-3xl font-bold text-orq-dark-gray">Soloista</h3>
                    <p class="text-gray-500 mt-2">Ideal para empezar a organizarte.</p>
                    <div class="my-8">
                        <span class="text-5xl font-bold text-orq-dark-gray">$29</span>
                        <span class="text-gray-500">/ mes</span>
                    </div>
                    <ul class="space-y-3 text-gray-600 text-left flex-grow">
                        <li class="flex items-center"><svg class="w-5 h-5 text-orq-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Hasta 2 Usuarios</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-orq-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Gestión de Órdenes</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-orq-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Soporte por Email</li>
                    </ul>
                    <Link :href="route('pricing')" class="mt-8 block w-full py-3 px-6 border border-orq-blue text-orq-blue font-bold rounded-lg hover:bg-orq-blue hover:text-white transition-colors">
                        Ver Detalles
                    </Link>
                </div>

                <div class="scroll-animate relative border-2 border-orq-blue rounded-xl p-8 text-center flex flex-col shadow-2xl transform lg:scale-105">
                    <div class="absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2">
                        <span class="px-4 py-1 bg-orq-blue text-white text-sm font-bold rounded-full">MÁS POPULAR</span>
                    </div>
                    <h3 class="font-serif text-3xl font-bold text-orq-dark-gray">Director</h3>
                    <p class="text-gray-500 mt-2">Para negocios en pleno crecimiento.</p>
                    <div class="my-8">
                        <span class="text-5xl font-bold text-orq-dark-gray">$79</span>
                        <span class="text-gray-500">/ mes</span>
                    </div>
                    <ul class="space-y-3 text-gray-600 text-left flex-grow">
                        <li class="flex items-center"><svg class="w-5 h-5 text-orq-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Hasta 10 Usuarios</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-orq-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Informes Personalizables</li>
                        <li class="flex items-center"><svg class="w-5 h-5 text-orq-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Soporte Prioritario y Chat</li>
                    </ul>
                    <Link :href="route('pricing')" class="mt-8 block w-full py-3 px-6 bg-orq-blue text-white font-bold rounded-lg hover:bg-blue-900 transition-colors shadow-lg">
                        Elegir Plan
                    </Link>
                </div>

                <div class="scroll-animate border border-gray-200 rounded-xl p-8 text-center flex flex-col transition-transform hover:scale-105">
                    <h3 class="font-serif text-3xl font-bold text-orq-dark-gray">Sinfonía</h3>
                    <p class="text-gray-500 mt-2">Soluciones a la medida de tu empresa.</p>
                    <div class="my-8">
                        <span class="text-4xl font-bold text-orq-dark-gray">Personalizado</span>
                    </div>
                    <ul class="space-y-3 text-gray-600 text-left flex-grow">
                         <li class="flex items-center"><svg class="w-5 h-5 text-orq-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Usuarios Ilimitados</li>
                         <li class="flex items-center"><svg class="w-5 h-5 text-orq-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Automatización y API</li>
                         <li class="flex items-center"><svg class="w-5 h-5 text-orq-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Gerente de Cuenta Dedicado</li>
                    </ul>
                    <Link :href="route('contact')" class="mt-8 block w-full py-3 px-6 border border-orq-dark-gray text-orq-dark-gray font-bold rounded-lg hover:bg-orq-dark-gray hover:text-white transition-colors">
                        Contactar
                    </Link>
                </div>

            </div>
        </div>
       </section>
        
        <section class="py-16 md:py-24 bg-orq-blue text-white">
             <div class="container mx-auto px-6 grid md:grid-cols-2 gap-8 md:gap-16 items-center">
                 <div class="scroll-animate text-center md:text-left">
                     <h2 class="font-serif text-3xl md:text-4xl font-bold mb-4">El Conductor de tu Negocio</h2>
                     <p class="text-lg text-blue-100/90">
                        Cada negocio es una orquesta. Pero con demasiada frecuencia, suena a caos. Orquestra es el director que toma cada instrumento —cada cliente, cada venta, cada tarea— y lo pone en perfecta armonía. Te damos la batuta para que dirijas con confianza, precisión y calma.
                     </p>
                 </div>
                 <div class="scroll-animate hidden md:flex items-center justify-center h-80 rounded-2xl bg-black/20">
                     <p class="text-2xl font-bold text-white/30">Placeholder para Imagen de Marca</p>
                 </div>
             </div>
        </section>

        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto scroll-animate">
                    <h2 class="font-serif text-3xl md:text-5xl font-bold text-orq-dark-gray">Nuestra Composición de Servicios</h2>
                    <p class="mt-4 text-lg text-gray-600">Cada módulo de Orquestra es un instrumento afinado, diseñado para trabajar en perfecta armonía.</p>
                </div>
                <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="scroll-animate p-8 text-center bg-orq-light-gray rounded-lg">
                        <div class="w-16 h-16 rounded-full bg-orq-gold/20 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-orq-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-orq-dark-gray">Gestión de Órdenes</h4>
                        <p class="mt-2 text-gray-600">Centraliza y dirige cada orden de trabajo en tiempo real.</p>
                    </div>
                     <div class="scroll-animate p-8 text-center bg-orq-light-gray rounded-lg">
                         <div class="w-16 h-16 rounded-full bg-orq-gold/20 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-orq-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-orq-dark-gray">Portal de Clientes</h4>
                        <p class="mt-2 text-gray-600">Dale a tus clientes un asiento en primera fila para ver el estado de sus órdenes.</p>
                    </div>
                     <div class="scroll-animate p-8 text-center bg-orq-light-gray rounded-lg">
                         <div class="w-16 h-16 rounded-full bg-orq-gold/20 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-orq-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2v.5a.5.5 0 01-1 0V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v.5a.5.5 0 01-1 0V5z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-orq-dark-gray">Análisis e Informes</h4>
                        <p class="mt-2 text-gray-600">Dirige con datos, no con suposiciones. Transforma números en decisiones.</p>
                    </div>
                </div>
            </div>
        </section>

    </PublicLayout>
</template>

<style>
/* Los estilos de animación no necesitan cambios */
.scroll-animate {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}
.scroll-animate.animate-fade-in-up {
    opacity: 1;
    transform: translateY(0);
}
</style>