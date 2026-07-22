<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Precios - SM Soluciones Electrónicas" />

    <PublicLayout>
        <main class="pt-24 pb-20 cyber-grid min-h-screen">
            <!-- Hero Header -->
            <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-16 md:py-24 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded bg-surface-container-high border border-electric-cyan/30 text-electric-cyan mb-6">
                    <span class="material-symbols-outlined text-[20px]">payments</span>
                    <span class="font-label-sm uppercase tracking-widest">Tarifas Transparentes</span>
                </div>
                <h1 class="font-headline-xl text-on-surface mb-6">Transparencia en Precios</h1>
                <p class="font-body-lg text-on-surface-variant max-w-2xl mx-auto">
                    Sin cargos ocultos ni sorpresas. Proporcionamos una estructura de costos clara y profesional con un enfoque técnico de alta precisión.
                </p>
            </section>

            <!-- Pricing Bento Grid -->
            <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop grid grid-cols-1 md:grid-cols-12 gap-gutter">
                <!-- Diagnóstico Section (Feature Card) -->
                <div class="md:col-span-12 lg:col-span-4 bg-surface-container-low p-8 rounded-xl technical-shadow bento-card border-t-4 border-t-electric-cyan">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h2 class="font-headline-lg text-on-surface">Diagnóstico</h2>
                            <p class="font-body-md text-on-surface-variant">Análisis técnico inicial</p>
                        </div>
                        <span class="material-symbols-outlined text-electric-cyan text-[32px]">biotech</span>
                    </div>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-electric-cyan font-bold text-[32px]">$</span>
                        <span class="text-on-surface font-bold text-[48px]">15.000</span>
                        <span class="text-on-surface-variant font-label-sm ml-2">Tarifa base</span>
                    </div>
                    <div class="bg-surface-container-highest/30 p-4 rounded border border-outline-variant/50 mb-8">
                        <p class="font-label-sm text-electric-cyan font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">info</span>
                            DEDUCTIBLE DE LA REPARACIÓN
                        </p>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3 font-body-md">
                            <span class="material-symbols-outlined text-electric-cyan text-[20px]">check_circle</span>
                            Revisión integral de hardware
                        </li>
                        <li class="flex items-center gap-3 font-body-md">
                            <span class="material-symbols-outlined text-electric-cyan text-[20px]">check_circle</span>
                            Presupuesto detallado de partes
                        </li>
                        <li class="flex items-center gap-3 font-body-md">
                            <span class="material-symbols-outlined text-electric-cyan text-[20px]">check_circle</span>
                            Tiempo estimado de entrega
                        </li>
                    </ul>
                </div>

                <!-- Mantenimiento Section -->
                <div class="md:col-span-12 lg:col-span-8 bg-surface-container-low p-8 rounded-xl technical-shadow bento-card">
                    <div class="flex items-center gap-3 mb-8">
                        <span class="material-symbols-outlined text-on-surface text-[32px]">settings_suggest</span>
                        <h2 class="font-headline-lg text-on-surface">Mantenimiento Preventivo</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                        <!-- Basic Cleaning -->
                        <div class="p-6 rounded bg-surface-container-high border border-outline-variant transition-colors hover:border-electric-cyan/50">
                            <div class="flex justify-between items-center mb-4">
                                <span class="font-label-md text-on-surface font-bold">Limpieza Básica</span>
                                <span class="font-headline-lg text-electric-cyan">$25.000</span>
                            </div>
                            <p class="font-body-md text-on-surface-variant mb-6">Ideal para equipos en entornos de oficina con poco polvo.</p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 font-body-md">
                                    <span class="material-symbols-outlined text-[16px] text-outline">arrow_forward</span>
                                    Soplado de componentes
                                </div>
                                <div class="flex items-center gap-2 font-body-md">
                                    <span class="material-symbols-outlined text-[16px] text-outline">arrow_forward</span>
                                    Limpieza externa
                                </div>
                            </div>
                        </div>

                        <!-- Deep Cleaning -->
                        <div class="p-6 rounded bg-surface-container-lowest border border-electric-cyan/20 ring-1 ring-electric-cyan/20">
                            <div class="flex justify-between items-center mb-4">
                                <span class="font-label-md text-electric-cyan font-bold">Limpieza Profunda</span>
                                <span class="font-headline-lg text-on-surface">$45.000</span>
                            </div>
                            <p class="font-body-md text-on-surface-variant mb-6">Restauración térmica y limpieza de circuitos para alto rendimiento.</p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 font-body-md text-on-surface">
                                    <span class="material-symbols-outlined text-[16px] text-electric-cyan">check</span>
                                    Cambio de pasta térmica premium
                                </div>
                                <div class="flex items-center gap-2 font-body-md text-on-surface">
                                    <span class="material-symbols-outlined text-[16px] text-electric-cyan">check</span>
                                    Desarme completo y baño químico
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Reparaciones Comunes Section -->
            <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop mt-12">
                <div class="md:col-span-12 bg-surface-container-low rounded-xl technical-shadow overflow-hidden border border-outline-variant">
                    <div class="p-8 border-b border-outline-variant bg-surface-container flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-on-surface text-[32px]">build</span>
                            <h2 class="font-headline-lg text-on-surface">Reparaciones Comunes</h2>
                        </div>
                        <div class="flex items-center gap-2 text-primary-container bg-primary-container/10 px-3 py-1 rounded border border-primary-container/20">
                            <span class="material-symbols-outlined text-[18px]">priority_high</span>
                            <span class="font-label-sm">Precios base sugeridos</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-high">
                                    <th class="px-8 py-4 font-label-md text-on-surface-variant uppercase tracking-widest">Servicio</th>
                                    <th class="px-8 py-4 font-label-md text-on-surface-variant uppercase tracking-widest">Descripción</th>
                                    <th class="px-8 py-4 font-label-md text-on-surface-variant uppercase tracking-widest">Desde</th>
                                    <th class="px-8 py-4 font-label-md text-on-surface-variant uppercase tracking-widest text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                <tr class="hover:bg-surface-container-high transition-colors">
                                    <td class="px-8 py-6 font-body-md font-bold text-on-surface">Cambio de Pantalla</td>
                                    <td class="px-8 py-6 font-body-md text-on-surface-variant">Reemplazo de display original o compatible tipo AAA</td>
                                    <td class="px-8 py-6 font-headline-lg text-electric-cyan">$80.000</td>
                                    <td class="px-8 py-6 text-right">
                                        <Link :href="route('contact')" class="text-electric-cyan font-label-md hover:underline decoration-electric-cyan/30 underline-offset-4">Consultar stock</Link>
                                    </td>
                                </tr>
                                <tr class="hover:bg-surface-container-high transition-colors">
                                    <td class="px-8 py-6 font-body-md font-bold text-on-surface">Cambio de Batería</td>
                                    <td class="px-8 py-6 font-body-md text-on-surface-variant">Instalación de batería de alta densidad con garantía</td>
                                    <td class="px-8 py-6 font-headline-lg text-electric-cyan">$35.000</td>
                                    <td class="px-8 py-6 text-right">
                                        <Link :href="route('contact')" class="text-electric-cyan font-label-md hover:underline decoration-electric-cyan/30 underline-offset-4">Consultar stock</Link>
                                    </td>
                                </tr>
                                <tr class="hover:bg-surface-container-high transition-colors">
                                    <td class="px-8 py-6 font-body-md font-bold text-on-surface">Puerto de Carga</td>
                                    <td class="px-8 py-6 font-body-md text-on-surface-variant">Microsoldadura o cambio de flex de carga</td>
                                    <td class="px-8 py-6 font-headline-lg text-electric-cyan">$30.000</td>
                                    <td class="px-8 py-6 text-right">
                                        <Link :href="route('contact')" class="text-electric-cyan font-label-md hover:underline decoration-electric-cyan/30 underline-offset-4">Consultar stock</Link>
                                    </td>
                                </tr>
                                <tr class="hover:bg-surface-container-high transition-colors">
                                    <td class="px-8 py-6 font-body-md font-bold text-on-surface">Optimización OS</td>
                                    <td class="px-8 py-6 font-body-md text-on-surface-variant">Formateo, backup y actualización de controladores</td>
                                    <td class="px-8 py-6 font-headline-lg text-electric-cyan">$40.000</td>
                                    <td class="px-8 py-6 text-right">
                                        <Link :href="route('contact')" class="text-electric-cyan font-label-md hover:underline decoration-electric-cyan/30 underline-offset-4">Ver detalles</Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Technical Note -->
            <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop mt-12">
                <div class="bg-surface-container-low border border-outline-variant p-6 rounded flex items-start gap-4">
                    <span class="material-symbols-outlined text-electric-cyan">done_outline</span>
                    <p class="font-body-md text-on-surface-variant italic">
                        Todos los precios están sujetos a inspección técnica detallada. Los valores finales pueden variar dependiendo del modelo exacto, disponibilidad de repuestos y grado de complejidad de la reparación. Los precios incluyen IVA.
                    </p>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop mt-20 text-center">
                <div class="bg-surface-container-lowest text-on-surface rounded-xl p-12 relative overflow-hidden border border-outline-variant ring-1 ring-electric-cyan/10">
                    <div class="absolute inset-0 opacity-5 pointer-events-none cyber-grid"></div>
                    <h2 class="font-headline-xl mb-4 relative z-10">¿Tienes un caso especial?</h2>
                    <p class="font-body-lg text-on-surface-variant mb-8 max-w-xl mx-auto relative z-10">Solicita una cotización personalizada para servidores, redes empresariales o reparaciones por volumen.</p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4 relative z-10">
                        <Link :href="route('contact')" class="bg-electric-cyan text-surface-container-lowest px-8 py-4 rounded font-label-md font-bold hover:brightness-110 transition-all active:scale-95 uppercase tracking-widest">Solicitar Presupuesto</Link>
                        <Link :href="route('contact')" class="border border-outline text-on-surface px-8 py-4 rounded font-label-md font-bold hover:bg-white/5 transition-all active:scale-95 uppercase tracking-widest">Hablar con Soporte</Link>
                    </div>
                </div>
            </section>
        </main>
    </PublicLayout>
</template>

<style>
.technical-shadow {
    box-shadow: 0px 4px 24px rgba(0, 0, 0, 0.4);
}
.bento-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #3a494b;
}
.bento-card:hover {
    transform: translateY(-4px);
    border-color: #00f2ff;
    box-shadow: 0px 10px 30px rgba(0, 242, 255, 0.15);
}
.cyber-grid {
    background-image: radial-gradient(circle at 1px 1px, #3a494b 1px, transparent 0);
    background-size: 32px 32px;
}
</style>