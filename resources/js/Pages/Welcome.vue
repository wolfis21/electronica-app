<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="SM Soluciones Electrónicas | Soporte Técnico Profesional" />

    <PublicLayout>
        <!-- Section 1: Hero -->
        <section class="relative w-full overflow-hidden bg-surface-container-lowest">
            <div class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop py-16 md:py-24 grid md:grid-cols-2 gap-12 items-center">
                <div class="z-10">
                    <h1 class="font-headline-xl text-headline-xl text-on-surface mb-6">
                        Soporte Técnico Profesional para tu Tecnología
                    </h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant mb-10 max-w-lg">Especialistas en reparación de dispositivos. Rápido, confiable y garantizado. Resolvemos tus problemas técnicos con precisión industrial.</p>
                    <div class="flex flex-wrap gap-4">
                        <button class="bg-primary text-on-primary px-8 py-4 rounded-lg font-label-md text-label-md font-bold shadow-[0_0_20px_rgba(0,242,255,0.4)] hover:brightness-110 transition-all active:scale-95">
                            Ver Servicios
                        </button>
                        <button class="border-2 border-primary text-primary px-8 py-4 rounded-lg font-label-md text-label-md font-bold hover:bg-primary/10 transition-all active:scale-95">
                            Rastrear Orden
                        </button>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-primary/20 rounded-xl -rotate-2 blur-2xl"></div>
                    <div class="relative rounded-xl overflow-hidden shadow-2xl aspect-video md:aspect-square border border-surface-container-highest">
                        <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAwfGJ9B9N_NNe-eXCwZMgQZ5F6jxoFAGKj_FmycRiRURwYdas3Ypd1R4ZtwV4stY5_niLodl2gh0Thxl6IxJlL4G8Gj_KAUCpzkstzbNjsNPwhP9-Sw-dJqH8InZcBSeBGfRqN8wcMN4GbN7HHcxVE27BIcTnyT_U1kYx8Tr5JQKWOr0_xaOUFKy9esztLhBXegTVp2dh1cfZe-jvwi9L7_Go4zfgxJBX7z6nHnPR1GssLr06IrUU4JQ9lgartqxd2FZAen5EhEkLU')"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Value Proposition -->
        <section class="bg-surface-container-low py-20">
            <div class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Card Velocidad -->
                    <div class="bento-card bg-surface-container-high p-8 rounded-xl flex flex-col items-start gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'opsz' 32;">speed</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface">Velocidad</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant">Diagnóstico en 24 horas y reparaciones urgentes para que nunca pierdas el ritmo de tu trabajo o vida personal.</p>
                    </div>
                    <!-- Card Calidad -->
                    <div class="bento-card bg-surface-container-high p-8 rounded-xl flex flex-col items-start gap-4 border-t-4 border-t-primary">
                        <div class="w-12 h-12 bg-primary/20 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'opsz' 32;">verified</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface">Calidad</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant">Utilizamos repuestos originales y procesos estandarizados de grado industrial para asegurar la durabilidad de cada solución.</p>
                    </div>
                    <!-- Card Transparencia -->
                    <div class="bento-card bg-surface-container-high p-8 rounded-xl flex flex-col items-start gap-4">
                        <div class="w-12 h-12 bg-primary/20 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'opsz' 32;">visibility</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface">Transparencia</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant">Presupuestos claros sin costos ocultos y seguimiento en tiempo real del estado de tu reparación a través de nuestro portal.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Track Order -->
        <section class="py-20 bg-surface">
            <div class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop">
                <div class="bg-surface-container-highest rounded-2xl p-8 md:p-12 flex flex-col md:flex-row items-center gap-8 overflow-hidden relative border border-primary/20">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary opacity-10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                    <div class="flex-1 text-on-surface z-10">
                        <h2 class="font-headline-lg text-headline-lg mb-4">¿Ya tienes una orden con nosotros?</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant">Ingresa tu número de seguimiento para consultar el progreso de tu reparación o instalación de red.</p>
                    </div>
                    <div class="w-full md:w-auto z-10">
                        <form class="flex flex-col sm:flex-row gap-4 bg-surface-container-low p-2 rounded-xl backdrop-blur-sm border border-surface-container-highest">
                            <input class="flex-1 bg-surface-container-high border-none rounded-lg px-6 py-3 font-body-md text-on-surface placeholder-on-surface-variant/50 focus:ring-2 focus:ring-primary outline-none min-w-[240px]" placeholder="ID de Orden (Ej: SM-1234)" type="text">
                            <button class="bg-primary text-on-primary px-8 py-3 rounded-lg font-label-md text-label-md font-bold hover:brightness-110 transition-all whitespace-nowrap shadow-[0_0_10px_rgba(0,242,255,0.2)]" type="button">
                                Consultar Estado
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4: Services Preview -->
        <section class="py-20 bg-surface-container-lowest">
            <div class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                    <div class="max-w-2xl">
                        <span class="font-label-sm text-label-sm text-primary uppercase tracking-widest mb-2 block">Nuestra Experiencia</span>
                        <h2 class="font-headline-lg text-headline-lg text-on-surface">Soluciones Integrales de Ingeniería Electrónica</h2>
                    </div>
                    <a class="text-primary font-label-md text-label-md flex items-center gap-2 hover:underline" href="#">
                        Ver todos los servicios <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Service 1 -->
                    <div class="md:col-span-8 group relative overflow-hidden rounded-xl bg-surface-container border border-surface-container-highest aspect-video md:aspect-[2/1]">
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA6czMnjQsF6JAtImM5nDrEJRdzbl46DQrdOVtlhMONZuBEu9gnBOSPEFMHjC_hS7vZwlWPdVz3HM2TeyHuQ7aam-3Qjenf5rrgmgtds6Su19HJb1EpeSAE5Ql_85i3N2jGhYMJt6yhvtZXOMYtLanZyosA1CCgWP0dEsZzWWqrVt9M8BnEfeoTHZcqmbcrRTzGUcagbxTMGZG_lNUK5IpAQwHof_Zt69E2xiYYryIgSvWDapcsblSMO-faJdKzcUyR8CkEFX36SmKY')"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-surface/95 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-8 text-on-surface">
                            <h3 class="font-headline-md text-headline-md mb-2">Reparación de Hardware</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant max-w-md">Intervención a nivel de componentes en laptops, desktops y dispositivos móviles con tecnología de punta.</p>
                        </div>
                    </div>
                    <!-- Service 2 -->
                    <div class="md:col-span-4 group relative overflow-hidden rounded-xl bg-surface-container border border-surface-container-highest aspect-square">
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" style="background-image: url('https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&amp;w=1000&amp;auto=format&amp;fit=crop')"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-surface/95 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-8 text-on-surface">
                            <div class="w-10 h-10 bg-primary/20 rounded-lg flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-primary">volume_up</span>
                            </div>
                            <h3 class="font-headline-md text-headline-md mb-2">Audio y Vídeo</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Mantenimiento de plantas de sonido, parlantes y sistemas de audio profesional.</p>
                        </div>
                    </div>
                    <!-- Service 3 -->
                    <div class="md:col-span-4 group relative overflow-hidden rounded-xl bg-surface-container border border-surface-container-highest aspect-square">
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBBw0O9LdV_qKmfNbAc4XwJZPJ97YmBLryIIjjqrdZdGoCDyOzluPmAlH_pDvdu68BiagfXOIgE9OVSE_DBZimxt5tTyTgxFkS79QZfuPevlVeo2sleDCmZykbhJeMWwnYigCB-ew8UOYbhHRs6irpsnBVtP3u0UUVSmAkg5ilGqy-C2lsti3UnElbkez5wSIXV0EKZQK55zOpZu-Um6vbO_eO_Vu4kPgEJShlWUdRscUpuGo7xHxTKvLqCaaE5HUeCMZcS2yG-d37-')"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-surface/95 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-8 text-on-surface">
                            <h3 class="font-headline-md text-headline-md mb-2">Software</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">Optimización de sistemas y seguridad digital.</p>
                        </div>
                    </div>
                    <!-- Service 4 -->
                    <div class="md:col-span-8 bg-primary p-8 rounded-xl flex flex-col justify-between border border-primary">
                        <div>
                            <h3 class="font-headline-md text-headline-md text-on-primary mb-4">¿Necesitas una cotización personalizada?</h3>
                            <p class="font-body-md text-body-md text-on-primary/90">Contamos con planes especiales para mantenimiento preventivo empresarial y soporte remoto inmediato.</p>
                        </div>
                        <div class="flex mt-6">
                            <button class="bg-surface text-on-surface px-8 py-3 rounded-lg font-label-md text-label-md font-bold hover:bg-surface-container-highest transition-all shadow-lg">
                                Solicitar Cotización
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style>
/* Scoped styles needed for Welcome page if any */
.bento-card { transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; border: 1px solid rgba(0, 242, 255, 0.1); }
.bento-card:hover { transform: translateY(-4px); box-shadow: 0px 4px 20px rgba(0, 242, 255, 0.15); border-color: rgba(0, 242, 255, 0.4); }
</style>