<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { onMounted } from 'vue';

// Se añaden categorías a las características para agruparlas en la tabla
const features = [
    { category: 'General', name: 'Usuarios Incluidos', soloista: '2', director: '10', sinfonia: 'Ilimitados' },
    { category: 'General', name: 'Soporte Técnico', soloista: 'Email', director: 'Email y Chat Prioritario', sinfonia: 'Gerente de Cuenta Dedicado' },
    { category: 'Operaciones', name: 'Gestión de Órdenes', soloista: true, director: true, sinfonia: true },
    { category: 'Operaciones', name: 'Campos Personalizados', soloista: false, director: true, sinfonia: true },
    { category: 'Clientes', name: 'Base de Datos de Clientes', soloista: true, director: true, sinfonia: true },
    { category: 'Clientes', name: 'Historial de Interacciones', soloista: false, director: true, sinfonia: true },
    { category: 'Finanzas', name: 'Facturación y Pagos', soloista: true, director: true, sinfonia: true },
    { category: 'Finanzas', name: 'Facturación Recurrente', soloista: false, director: true, sinfonia: true },
    { category: 'Crecimiento', name: 'Informes Personalizables', soloista: false, director: true, sinfonia: true },
    { category: 'Crecimiento', name: 'Acceso a API', soloista: false, director: false, sinfonia: true },
];

// Lógica para las animaciones de scroll
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
    <Head title="Planes y Precios" />

    <PublicLayout>
        <section class="pt-32 pb-20 md:pt-40 md:pb-24 bg-white text-center">
            <div class="container mx-auto px-6">
                <h1 class="font-serif text-4xl md:text-6xl font-bold text-orq-dark-gray leading-tight">
                    El Plan Perfecto para tu Orquesta.
                </h1>
                <p class="mt-6 text-lg text-gray-600 max-w-2xl mx-auto">
                    Sin sorpresas, sin contratos a largo plazo. Solo crecimiento.
                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-orq-blue">
             <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    
                    <div class="scroll-animate bg-white rounded-xl p-8 text-center flex flex-col border border-gray-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                        <h3 class="font-serif text-3xl font-bold text-orq-dark-gray">Soloista</h3>
                        <p class="text-gray-500 mt-2 flex-grow">Para quienes inician su melodía de negocio.</p>
                        <div class="my-8"><span class="text-5xl font-bold text-orq-dark-gray">$29</span><span class="text-gray-500">/ mes</span></div>
                        <Link href="#" class="mt-auto block w-full py-3 px-6 border border-orq-blue text-orq-blue font-bold rounded-lg hover:bg-orq-blue hover:text-white transition-colors">
                            Empezar con Soloista
                        </Link>
                    </div>

                    <div class="scroll-animate relative bg-white rounded-xl p-8 text-center flex flex-col border-2 border-orq-gold shadow-2xl transform lg:scale-110">
                         <div class="absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2"><span class="px-4 py-1 bg-orq-blue text-white text-sm font-bold rounded-full uppercase tracking-wider">Recomendado</span></div>
                        <h3 class="font-serif text-3xl font-bold text-orq-dark-gray">Director</h3>
                        <p class="text-gray-500 mt-2 flex-grow">La solución completa para dirigir y escalar tu operación.</p>
                        <div class="my-8"><span class="text-5xl font-bold text-orq-dark-gray">$79</span><span class="text-gray-500">/ mes</span></div>
                        <Link href="#" class="mt-auto block w-full py-3 px-6 bg-orq-gold text-orq-blue font-bold rounded-lg hover:scale-105 transition-transform shadow-lg">
                            Elegir Plan Director
                        </Link>
                    </div>

                    <div class="scroll-animate bg-white rounded-xl p-8 text-center flex flex-col border border-gray-200 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                        <h3 class="font-serif text-3xl font-bold text-orq-dark-gray">Sinfonía</h3>
                        <p class="text-gray-500 mt-2 flex-grow">Personalización y soporte avanzado para empresas establecidas.</p>
                        <div class="my-6"><span class="text-4xl font-bold text-orq-dark-gray">Personalizado</span></div>
                        <Link :href="route('contact')" class="mt-auto block w-full py-3 px-6 border border-orq-dark-gray text-orq-dark-gray font-bold rounded-lg hover:bg-orq-dark-gray hover:text-white transition-colors">
                            Contactar para Cotización
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-gray-50">
            <div class="container mx-auto px-6">
                <h2 class="text-center font-serif text-4xl font-bold text-orq-dark-gray mb-12 scroll-animate">Compara las Características en Detalle</h2>
                
                <div class="max-w-6xl mx-auto scroll-animate">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 rounded-tl-lg">Característica</th>
                                    <th scope="col" class="px-6 py-3 text-center">Soloista</th>
                                    <th scope="col" class="px-6 py-3 text-center bg-orq-blue/10">Director</th>
                                    <th scope="col" class="px-6 py-3 text-center rounded-tr-lg">Sinfonía</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(feature, index) in features" :key="feature.name">
                                    <tr v-if="index === 0 || features[index-1].category !== feature.category">
                                        <th colspan="4" class="px-6 py-4 font-bold text-orq-blue bg-gray-100/50">
                                            {{ feature.category }}
                                        </th>
                                    </tr>
                                    <tr class="bg-white border-b">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ feature.name }}</th>
                                        <td class="px-6 py-4 text-center">{{ feature.soloista === true ? '✓' : feature.soloista === false ? '—' : feature.soloista }}</td>
                                        <td class="px-6 py-4 text-center font-bold text-orq-blue bg-orq-blue/10">{{ feature.director === true ? '✓' : feature.director === false ? '—' : feature.director }}</td>
                                        <td class="px-6 py-4 text-center">{{ feature.sinfonia === true ? '✓' : feature.sinfonia === false ? '—' : feature.sinfonia }}</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-6 max-w-3xl">
                <h2 class="text-center font-serif text-4xl font-bold text-orq-dark-gray mb-12 scroll-animate">Preguntas Frecuentes</h2>
                <div class="space-y-8">
                    <div class="scroll-animate">
                        <h4 class="font-bold text-lg text-orq-dark-gray">¿Puedo cambiar de plan más adelante?</h4>
                        <p class="text-gray-600 mt-2">¡Por supuesto! Puedes mejorar o reducir tu plan en cualquier momento desde tu panel de control. Los cambios se aplicarán en tu siguiente ciclo de facturación.</p>
                    </div>
                    <div class="scroll-animate">
                        <h4 class="font-bold text-lg text-orq-dark-gray">¿Qué tipo de soporte ofrecen?</h4>
                        <p class="text-gray-600 mt-2">Todos nuestros planes incluyen soporte por correo electrónico. El plan Director añade chat prioritario y el plan Sinfonía te asigna un gerente de cuenta dedicado para un soporte personalizado.</p>
                    </div>
                     <div class="scroll-animate">
                        <h4 class="font-bold text-lg text-orq-dark-gray">¿Hay algún contrato o permanencia?</h4>
                        <p class="text-gray-600 mt-2">No. Orquestra es un servicio de pago por uso y puedes cancelar tu suscripción cuando quieras, sin penalizaciones.</p>
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