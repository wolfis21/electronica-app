<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Sobre Nosotros - SM Soluciones Electrónicas" />

    <PublicLayout>
        <!-- Section 1: Header Hero -->
        <section class="relative py-24 px-margin-desktop max-w-container-max mx-auto overflow-hidden">
            <div class="absolute top-0 right-0 -z-10 opacity-20 blur-[100px] w-96 h-96 bg-electric-cyan/30 rounded-full"></div>
            <div class="max-w-3xl">
                <h1 class="font-headline-xl text-headline-xl text-on-surface mb-6 leading-none">Sobre Nosotros</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed max-w-2xl">Elevando los estándares de la reparación técnica con precisión milimétrica y compromiso absoluto.</p>
            </div>
        </section>

        <!-- Section 2: Our Story (Asymmetric Layout) -->
        <section class="py-20 px-margin-desktop max-w-container-max mx-auto">
            <div class="flex flex-col lg:flex-row gap-12 items-center">
                <div class="w-full lg:w-1/2 relative group">
                    <div class="absolute -inset-2 bg-electric-cyan opacity-10 rounded-xl blur-lg group-hover:opacity-20 transition-opacity"></div>
                    <div class="relative rounded-lg overflow-hidden border border-outline-variant shadow-2xl aspect-video bg-surface-container">
                        <img alt="Laboratorio SM" class="w-full h-full object-cover grayscale-[0.2] contrast-[1.1]" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA_0mUfP1Rr0coqGfMn2P-xLgP30Hq-VFDRhh2Vv5HwlYY-E5Rpq6CNwB-Pi2fyxp5MjgihUtSwVZ9S1PhteoVbwtsz8W-0OelS0JR4BGyZ8lhs1MnF3bWrTrkof5gKSYh8rmRkyWJ8awzlKBt7XY4tlr3Ldsk7hORAFO8ljDfxozLahZs2fW_2-U3-JRl_B5UdnT5jSqNfImT23bzwSWi-tRDoo1dFebKXFb16MnQAWNJSTREBdzxR954ZBlW4SskHfL3g8SrNVjSp">
                    </div>
                </div>
                <div class="w-full lg:w-1/2 space-y-8">
                    <div class="border-l-4 border-electric-cyan pl-8">
                        <h2 class="font-headline-lg text-headline-lg text-on-surface mb-4">Nuestra Historia</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant mb-6 leading-relaxed">
                            Con años de experiencia en el sector, SM Soluciones Electrónicas nació de una pasión compartida por la tecnología y la excelencia técnica. No solo reparamos dispositivos; devolvemos la operatividad a los activos más críticos de nuestros clientes.
                        </p>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">Nuestro compromiso con la innovación nos ha permitido resolver problemas complejos que otros consideran imposibles. Desde micro-soldadura hasta diagnósticos avanzados de hardware, cada desafío es una oportunidad para demostrar nuestro rigor técnico.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="p-8 bg-surface-container-high rounded-xl border border-outline-variant/50">
                            <span class="block font-headline-xl text-headline-xl text-electric-cyan text-glow">10+</span>
                            <span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-on-surface-variant">Años de Experiencia</span>
                        </div>
                        <div class="p-8 bg-surface-container-high rounded-xl border border-outline-variant/50">
                            <span class="block font-headline-xl text-headline-xl text-electric-cyan text-glow">5k+</span>
                            <span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-on-surface-variant">Reparaciones Exitosas</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Why Us (Bento Grid) -->
        <section class="py-24 bg-surface-container-lowest">
            <div class="max-w-container-max mx-auto px-margin-desktop">
                <div class="text-center mb-20">
                    <h2 class="font-headline-lg text-headline-xl text-on-surface">¿Por qué elegirnos?</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-4 max-w-xl mx-auto">La confianza se construye sobre la base de la transparencia y la calidad certificada.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-surface-container p-10 rounded-2xl border-b-2 border-electric-cyan/50 hover:border-electric-cyan transition-all duration-300 group">
                        <div class="w-14 h-14 bg-surface-container-highest flex items-center justify-center rounded-xl mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-electric-cyan text-3xl">verified_user</span>
                        </div>
                        <h3 class="font-headline-md text-headline-lg text-on-surface mb-4">Técnicos Certificados</h3>
                        <p class="font-body-sm text-body-md text-on-surface-variant">Nuestro equipo está compuesto por expertos altamente cualificados con certificaciones internacionales en microelectrónica y redes.</p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="bg-surface-container p-10 rounded-2xl border-b-2 border-outline-variant hover:border-primary-fixed transition-all duration-300 group">
                        <div class="w-14 h-14 bg-surface-container-highest flex items-center justify-center rounded-xl mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-on-surface text-3xl">memory</span>
                        </div>
                        <h3 class="font-headline-md text-headline-lg text-on-surface mb-4">Repuestos Originales</h3>
                        <p class="font-body-sm text-body-md text-on-surface-variant">Solo utilizamos componentes genuinos y de grado industrial para asegurar la longevidad de cada intervención técnica.</p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="bg-surface-container p-10 rounded-2xl border-b-2 border-electric-cyan/50 hover:border-electric-cyan transition-all duration-300 group">
                        <div class="w-14 h-14 bg-surface-container-highest flex items-center justify-center rounded-xl mb-8 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-electric-cyan text-3xl">security</span>
                        </div>
                        <h3 class="font-headline-md text-headline-lg text-on-surface mb-4">Garantía Total</h3>
                        <p class="font-body-sm text-body-md text-on-surface-variant">Respaldamos cada trabajo con una garantía integral. Si no está satisfecho, nuestro equipo de soporte priorizará su caso.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4: Team/Environment (Visual Focus) -->
        <section class="py-24 px-margin-desktop max-w-container-max mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                <div class="lg:col-span-5 space-y-8">
                    <h2 class="font-headline-lg text-headline-lg text-on-surface">Laboratorio de Precisión</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                        Contamos con un entorno técnico de vanguardia, equipado con estaciones de trabajo de control estático (ESD), microscopios de alta definición y analizadores de red de última generación.
                    </p>
                    <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                        Este espacio está diseñado para el diagnóstico profundo y la resolución eficiente, permitiendo a nuestros técnicos operar bajo las condiciones de limpieza y organización necesarias para la electrónica moderna.
                    </p>
                    <ul class="space-y-4 pt-4">
                        <li class="flex items-center gap-4 text-body-md font-medium text-on-surface">
                            <span class="material-symbols-outlined text-electric-cyan" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            Control ESD Estricto
                        </li>
                        <li class="flex items-center gap-4 text-body-md font-medium text-on-surface">
                            <span class="material-symbols-outlined text-electric-cyan" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            Instrumental de Medición Fluke
                        </li>
                        <li class="flex items-center gap-4 text-body-md font-medium text-on-surface">
                            <span class="material-symbols-outlined text-electric-cyan" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            Certificación de Grado Industrial
                        </li>
                    </ul>
                </div>
                <div class="lg:col-span-7 grid grid-cols-2 gap-6">
                    <div class="rounded-xl overflow-hidden h-[450px] shadow-2xl border border-outline-variant bg-surface-container relative">
                        <img alt="Inspección Microelectrónica" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpvJzkvX0Bd_udS2G0GcttPLw9oG66f0IPi7cHAqbGC0aWCLGdbWLz7gPIHBSGXODh9EjBAiW4e_SpENdbJm5Ky9AreDcOLx5iwtLwAfRWi1d3u4zZxl8Yp73UTN_oq0Hrf8CPS5K1UwkNT8UIEydfCn-NqQiM2t5FD9VRhUOcnBpPPg-1eDfXmqovqq9_gY3X-qX-j4iUrFyGpgKqfcVK5J9vZaSUIB1Z9OINEkQuOJ3WkCzFhxHLljoLkcOm9kAihSMCUQOpQ74x">
                        <div class="absolute inset-0 bg-gradient-to-t from-surface to-transparent opacity-40"></div>
                    </div>
                    <div class="rounded-xl overflow-hidden h-[450px] shadow-2xl border border-outline-variant bg-surface-container mt-12 relative">
                        <img alt="Infraestructura de Red" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD28AO3-nhbJKtFOQnHoNdMrJgIzgo-3fSOK6yZDdOkiN3E_1wjiAuuFvEnw5Xfa_W_jVOt22OcZe6oP3yanuf1TIkHGJaoobqhSJj78ULAE6bWMUREmHepiaMluYrgDwWVdoFHVtWMusex42jVCEOYbtltfKhiVqSN7f4BEUF7ZmQWyRW2gi86fh6dalFeNmqQv3jJESWHu78MFSv6vA3WE5lR9XSi8uV4TH4Gw2ZMYvKpQigpDmwWp-EiTICia_Ed1KvqGwpaUGKh">
                        <div class="absolute inset-0 bg-gradient-to-t from-surface to-transparent opacity-40"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="mb-24 px-margin-desktop max-w-container-max mx-auto">
            <div class="bg-surface-container-high border border-outline-variant rounded-3xl p-16 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-electric-cyan to-transparent"></div>
                <div class="relative z-10">
                    <h2 class="font-headline-xl text-headline-xl mb-6 text-on-surface">¿Listo para resolver sus problemas técnicos?</h2>
                    <p class="font-body-lg text-body-lg text-on-surface-variant mb-10 max-w-2xl mx-auto">Nuestro equipo de soporte está disponible para asesorarle sobre la mejor solución para sus dispositivos o infraestructura de red.</p>
                    <div class="flex flex-col sm:flex-row gap-6 justify-center">
                        <Link :href="route('contact')" class="bg-primary-container text-on-primary-container px-12 py-4 rounded-lg font-label-md font-bold hover:brightness-110 transition-all shadow-[0_0_20px_rgba(0,242,255,0.2)]">Contactar Ahora</Link>
                        <Link :href="route('services')" class="border border-outline text-on-surface px-12 py-4 rounded-lg font-label-md hover:bg-surface-bright transition-colors">Ver Servicios</Link>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style>
.glass-card {
    background: rgba(23, 27, 39, 0.7);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(0, 242, 255, 0.2);
}
.text-glow {
    text-shadow: 0 0 10px rgba(0, 242, 255, 0.5);
}
</style>