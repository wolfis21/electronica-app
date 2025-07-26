<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue'; // Asumiendo que tenemos un layout público

</script>

<template>
    <Head title="Nosotros - Orquesta" />

    <PublicLayout>
        <div class="bg-orq-light-gray py-24">
            <div class="container mx-auto px-6 text-center">
                <h1 class="font-serif text-5xl md:text-6xl font-bold text-orq-dark-gray">El Conductor de tu Negocio</h1>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    Con Orquesta, tu negocio no solo funciona. Suena increíble.
                </p>
            </div>
        </div>

        <div class="py-20">
            <div class="container mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
                <div class="hidden md:block">
                    <div class="relative w-full h-96 rounded-2xl bg-orq-blue opacity-10"></div>
                </div>
                <div class="text-gray-700">
                    <h2 class="font-serif text-4xl font-bold text-orq-dark-gray mb-4">Nuestra Historia</h2>
                    <p class="mb-4">
                        Cada negocio es una orquesta. El teléfono que suena es el violín, un nuevo cliente es el piano, y una orden de trabajo son los tambores. Cuando todo está afinado y en sintonía, la música es increíble: es el sonido del éxito.
                    </p>
                    <p>
                        Aquí es donde entra Orquestra. Somos la plataforma que toma cada instrumento y lo pone en perfecta armonía, silenciando el ruido para que puedas escuchar la melodía.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-orq-light-gray py-20">
            <div class="container mx-auto px-6 text-center">
                <h2 class="font-serif text-4xl font-bold text-orq-dark-gray mb-12">Los Directores</h2>
                <div class="grid md:grid-cols-2 gap-12 max-w-4xl mx-auto">
                    <div class="text-center">
                        <div class="w-40 h-40 rounded-full bg-gray-300 mx-auto mb-4 flex items-center justify-center">
                           <span class="text-gray-500">Foto</span>
                        </div>
                        <h3 class="text-2xl font-bold text-orq-dark-gray">Isaac Saado</h3>
                        <p class="text-gray-600 mt-2">Co-fundador y emprendedor con la misión de ayudar a los negocios a crecer.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-40 h-40 rounded-full bg-pink-300 mx-auto mb-4 flex items-center justify-center">
                           <span class="text-gray-500">Foto</span>
                        </div>
                        <h3 class="text-2xl font-bold text-orq-dark-gray">José Andrés Dasilva</h3>
                        <p class="text-gray-600 mt-2">Co-fundador y emprendedor apasionado por la sincronización del éxito empresarial.</p>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>