<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

// La lógica para las animaciones de scroll se mantiene igual
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
        
        <section class="relative pt-32 pb-24 bg-orq-light-gray">
            <div class="container mx-auto px-6 relative z-10">
                <div class="max-w-3xl text-center mx-auto">
                    <h1 class="font-serif text-5xl md:text-7xl font-bold text-orq-dark-gray leading-tight">
                        Orquestando la eficiencia de tu negocio.
                    </h1>
                    <p class="mt-6 text-lg md:text-xl text-gray-600">
                        La plataforma definitiva que silencia el caos y sincroniza tus operaciones. Toma la batuta y dirige tu negocio hacia una nueva melodía de crecimiento.                     </p>
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

        <section class="py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto scroll-animate">
                    <h2 class="font-serif text-4xl md:text-5xl font-bold text-orq-dark-gray">Del Ruido a la Melodía.</h2>
                    <p class="mt-4 text-lg text-gray-600">Conocemos la frustración de las oportunidades perdidas por falta de sincronización. Este caos operativo es el verdadero enemigo del crecimiento.</p>
                </div>
                <div class="mt-16 grid md:grid-cols-2 gap-8">
                    <div class="scroll-animate p-8 bg-orq-light-gray rounded-xl">
                        <h3 class="text-2xl font-bold text-orq-dark-gray mb-4">El Ruido</h3>
                        <p class="text-gray-700">
                            Un cliente llama, pero el equipo no tiene la información. Una orden de trabajo se retrasa, afectando todo el ritmo. El inventario no cuadra. Ese es el ruido del estrés operativo.
                        </p>
                    </div>
                    <div class="scroll-animate p-8 bg-orq-blue rounded-xl text-white">
                        <h3 class="text-2xl font-bold text-white mb-4">La Melodía</h3>
                        <p class="text-blue-100/90">
                           Ahora imagina: cada tarea fluye sin esfuerzo, tu equipo está perfectamente sincronizado y tú tienes el control total desde un solo lugar. Esa es la promesa de Orquestra.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="py-24 bg-orq-blue text-white">
             <div class="container mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
                 <div class="scroll-animate">
                     <h2 class="font-serif text-4xl font-bold mb-4">El Conductor de tu Negocio [cite: 171]</h2>
                     <p class="text-lg text-blue-100/90">
                         [cite_start]Cada negocio es una orquesta. [cite: 172] [cite_start]Pero con demasiada frecuencia, esa orquesta suena a caos. [cite: 174] [cite_start]Orquestra es el director que toma cada instrumento —cada cliente, cada venta, cada tarea— y lo pone en perfecta armonía. [cite: 173, 176] [cite_start]Te damos la batuta para que dirijas con confianza, precisión y calma. [cite: 177]
                     </p>
                 </div>
                 <div class="scroll-animate hidden md:flex items-center justify-center h-80 rounded-2xl bg-black/20">
                     <p class="text-2xl font-bold text-white/30">Placeholder para Imagen de Marca</p>
                 </div>
             </div>
        </section>

        <section class="py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto scroll-animate">
                    <h2 class="font-serif text-4xl md:text-5xl font-bold text-orq-dark-gray">Nuestra Composición de Servicios</h2>
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
                        <p class="mt-2 text-gray-600">Dirige con datos, no con suposiciones. Transforma números en decisiones estratégicas.</p>
                    </div>
                </div>
            </div>
        </section>

    </PublicLayout>
</template>

<style>
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