<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue'; // Reutilizamos el layout público

// Definimos las características para la tabla comparativa
const features = [
    { name: 'Usuarios Incluidos', soloista: '2', director: '10', sinfonia: 'Ilimitados' },
    { name: 'Gestión de Órdenes', soloista: true, director: true, sinfonia: true },
    { name: 'Campos Personalizados', soloista: false, director: true, sinfonia: true },
    { name: 'Facturación Recurrente', soloista: false, director: true, sinfonia: true },
    { name: 'Informes Personalizables', soloista: false, director: true, sinfonia: true },
    { name: 'Integraciones (API & Zapier)', soloista: false, director: false, sinfonia: true },
    { name: 'Soporte por Email', soloista: true, director: true, sinfonia: true },
    { name: 'Soporte por Chat', soloista: false, director: true, sinfonia: true },
    { name: 'Gerente de Cuenta Dedicado', soloista: false, director: false, sinfonia: true },
];

// Helper para mostrar la marca de verificación o un guión
const checkmark = (value) => {
    if (typeof value === 'boolean') {
        return value ? '✓' : '—';
    }
    return value;
};
</script>

<template>
    <Head title="Planes y Precios" />

    <PublicLayout>
        <section class="pt-32 pb-16 bg-orq-light-gray">
            <div class="container mx-auto px-6 text-center">
                <h1 class="font-serif text-5xl md:text-6xl font-bold text-orq-dark-gray">Encuentra el Plan Perfecto para tu Orquesta</h1>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Planes claros y sin sorpresas. Escala a tu propio ritmo.</p>
            </div>
        </section>

        <section class="py-16">
             <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="border border-gray-200 rounded-xl p-8 text-center flex flex-col">
                        <h3 class="font-serif text-3xl font-bold text-orq-dark-gray">Soloista</h3>
                        <div class="my-6"><span class="text-5xl font-bold text-orq-dark-gray">$29</span><span class="text-gray-500">/ mes</span></div>
                        <p class="flex-grow text-gray-500">Ideal para empezar a organizarte.</p>
                        <Link href="#" class="mt-8 block w-full py-3 px-6 bg-orq-gold text-orq-blue font-bold rounded-lg hover:scale-105 transition-transform">
                            Suscribirse
                        </Link>
                    </div>

                    <div class="relative border-2 border-orq-blue rounded-xl p-8 text-center flex flex-col shadow-2xl">
                         <div class="absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2"><span class="px-4 py-1 bg-orq-blue text-white text-sm font-bold rounded-full">MÁS POPULAR</span></div>
                        <h3 class="font-serif text-3xl font-bold text-orq-dark-gray">Director</h3>
                        <div class="my-6"><span class="text-5xl font-bold text-orq-dark-gray">$79</span><span class="text-gray-500">/ mes</span></div>
                        <p class="flex-grow text-gray-500">Para negocios en pleno crecimiento.</p>
                        <Link href="#" class="mt-8 block w-full py-3 px-6 bg-orq-blue text-white font-bold rounded-lg hover:scale-105 transition-transform shadow-lg">
                            Suscribirse
                        </Link>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-8 text-center flex flex-col">
                        <h3 class="font-serif text-3xl font-bold text-orq-dark-gray">Sinfonía</h3>
                        <div class="my-6"><span class="text-4xl font-bold text-orq-dark-gray">Personalizado</span></div>
                        <p class="flex-grow text-gray-500">Soluciones a la medida de tu empresa.</p>
                        <Link :href="route('contact')" class="mt-8 block w-full py-3 px-6 border border-orq-dark-gray text-orq-dark-gray font-bold rounded-lg hover:bg-orq-dark-gray hover:text-white transition-colors">
                            Contactar
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-orq-light-gray">
            <div class="container mx-auto px-6">
                <h2 class="text-center font-serif text-4xl font-bold text-orq-dark-gray mb-12">Comparación de Características</h2>
                <div class="max-w-4xl mx-auto">
                    <div v-for="(feature, index) in features" :key="feature.name" class="grid grid-cols-4 gap-4 items-center py-4" :class="{'border-b border-gray-200': index < features.length - 1}">
                        <div class="col-span-4 md:col-span-1 font-bold text-gray-700">{{ feature.name }}</div>
                        <div class="text-center text-gray-600">{{ checkmark(feature.soloista) }}</div>
                        <div class="text-center font-bold text-orq-blue">{{ checkmark(feature.director) }}</div>
                        <div class="text-center text-gray-600">{{ checkmark(feature.sinfonia) }}</div>
                    </div>
                </div>
            </div>
        </section>

    </PublicLayout>
</template>

<style scoped>
/* Estilos para la tabla en móvil */
@media (max-width: 767px) {
    .grid-cols-4 {
        grid-template-columns: 2fr 1fr 1fr 1fr;
    }
}
</style>