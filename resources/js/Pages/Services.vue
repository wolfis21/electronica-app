<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Servicios - SM Soluciones Electrónicas" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="relative py-32 overflow-hidden bg-surface-container-lowest">
            <div class="absolute inset-0 opacity-40">
                <div class="w-full h-full bg-cover bg-center mix-blend-luminosity" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAHhnBZIPCEIPAN2iGAwN8XLhHOvWcfw7AhWYQWE46Lj1bZbGFhb_mUI68-de0mckHIvtE5vDXkzFfmOOyde5hhea7-WgHIXJ7CZg48y7bJ56ywCB_iOvBv8A5gzP65u_LlHSr9Lgbpg9bw3jEGaKRCzmb8a0X2A9UbdCX1e8RjsoZClcdGs6_lQLZoHol7ndAAcTLAB7XEIi0ud4tqcI1RpYrZQoKw1KBxifVWL1EjCxnLqqEIIM-d73gsWAvUVyFq4c_iz6brbVA4')"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-surface-container-lowest/10 via-surface-container-lowest to-surface-container-lowest"></div>
            </div>
            <div class="relative max-w-container-max mx-auto px-margin-desktop text-center">
                <h1 class="font-headline-xl text-headline-xl text-on-surface mb-8 tracking-tighter">CATÁLOGO DE <span class="text-electric-cyan">SERVICIOS</span></h1>
                <p class="text-on-surface-variant max-w-3xl mx-auto font-body-lg text-body-lg leading-relaxed">
                    Especialistas en la restauración técnica y optimización de ecosistemas digitales. Soluciones integrales para hardware y software.
                </p>
            </div>
        </section>

        <!-- Comprehensive Service Catalog -->
        <section class="py-24 max-w-container-max mx-auto px-margin-desktop gradient-mesh">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
                <!-- Informática -->
                <div class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/20 hover:bg-surface-container transition-all card-reveal group">
                    <div class="bg-surface-container-highest w-16 h-16 rounded-lg flex items-center justify-center mb-8 border border-electric-cyan/20">
                        <span class="material-symbols-outlined text-3xl text-electric-cyan reveal-icon transition-transform duration-300">laptop_mac</span>
                    </div>
                    <h3 class="font-headline-lg text-2xl mb-4 text-on-surface uppercase tracking-tight">Informática</h3>
                    <p class="font-body-md text-on-surface-variant mb-6">Mantenimiento y reparación experta de PC y Laptops.</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">settings_suggest</span> Reparación de hardware
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">construction</span> Bisagras y carcasas
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">air</span> Limpieza interna profunda
                        </li>
                    </ul>
                </div>
                
                <!-- Visualización -->
                <div class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/20 hover:bg-surface-container transition-all card-reveal group">
                    <div class="bg-surface-container-highest w-16 h-16 rounded-lg flex items-center justify-center mb-8 border border-outline-variant/20">
                        <span class="material-symbols-outlined text-3xl text-on-surface reveal-icon transition-transform duration-300">tv</span>
                    </div>
                    <h3 class="font-headline-lg text-2xl mb-4 text-on-surface uppercase tracking-tight">Visualización</h3>
                    <p class="font-body-md text-on-surface-variant mb-6">Diagnóstico y restauración de equipos de imagen de alta resolución.</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">monitor</span> Televisores Smart y LED
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">desktop_windows</span> Monitores profesionales
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">dynamic_feed</span> Calibración de color
                        </li>
                    </ul>
                </div>

                <!-- Captura -->
                <div class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/20 hover:bg-surface-container transition-all card-reveal group">
                    <div class="bg-surface-container-highest w-16 h-16 rounded-lg flex items-center justify-center mb-8 border border-outline-variant/20">
                        <span class="material-symbols-outlined text-3xl text-on-surface reveal-icon transition-transform duration-300">photo_camera</span>
                    </div>
                    <h3 class="font-headline-lg text-2xl mb-4 text-on-surface uppercase tracking-tight">Captura</h3>
                    <p class="font-body-md text-on-surface-variant mb-6">Mantenimiento de precisión para equipos ópticos y de grabación.</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">camera</span> Cámaras fotográficas
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">videocam</span> Equipos de video
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">shutter_speed</span> Limpieza de sensores
                        </li>
                    </ul>
                </div>

                <!-- Audio Profesional -->
                <div class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/20 hover:bg-surface-container transition-all card-reveal group">
                    <div class="bg-surface-container-highest w-16 h-16 rounded-lg flex items-center justify-center mb-8 border border-outline-variant/20">
                        <span class="material-symbols-outlined text-3xl text-on-surface reveal-icon transition-transform duration-300">surround_sound</span>
                    </div>
                    <h3 class="font-headline-lg text-2xl mb-4 text-on-surface uppercase tracking-tight">Audio Profesional</h3>
                    <p class="font-body-md text-on-surface-variant mb-6">Restauración de fidelidad sonora y potencia para sistemas de audio.</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">speaker</span> Plantas de sonido
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">equalizer</span> Consolas y mezcladores
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">headphones</span> Equipos Hi-Fi
                        </li>
                    </ul>
                </div>

                <!-- Software -->
                <div class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/20 hover:bg-surface-container transition-all card-reveal group">
                    <div class="bg-surface-container-highest w-16 h-16 rounded-lg flex items-center justify-center mb-8 border border-outline-variant/20">
                        <span class="material-symbols-outlined text-3xl text-on-surface reveal-icon transition-transform duration-300">terminal</span>
                    </div>
                    <h3 class="font-headline-lg text-2xl mb-4 text-on-surface uppercase tracking-tight">Software</h3>
                    <p class="font-body-md text-on-surface-variant mb-6">Optimización lógica y despliegue de entornos operativos estables.</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">download</span> Sistemas Operativos
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">apps</span> Programas especializados
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">memory</span> Drivers y Firmware
                        </li>
                    </ul>
                </div>

                <!-- Electrónica General -->
                <div class="bg-surface-container-low p-8 rounded-xl cyber-border cyber-glow transition-all card-reveal group">
                    <div class="bg-surface-container-highest w-16 h-16 rounded-lg flex items-center justify-center mb-8 border border-electric-cyan/30">
                        <span class="material-symbols-outlined text-3xl text-electric-cyan reveal-icon transition-transform duration-300">developer_board</span>
                    </div>
                    <h3 class="font-headline-lg text-2xl mb-4 text-on-surface uppercase tracking-tight">Electrónica General</h3>
                    <p class="font-body-md text-on-surface-variant mb-6">Microelectrónica y resolución de fallas a nivel de componente.</p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">electric_bolt</span> Problemas eléctricos
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">hardware</span> Cambio de componentes
                        </li>
                        <li class="flex items-center gap-3 font-label-sm text-on-surface-variant uppercase">
                            <span class="material-symbols-outlined text-electric-cyan text-sm">analytics</span> Pruebas de carga
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Process Section -->
        <section class="py-32 bg-surface-container-lowest relative overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(0,242,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(0,242,255,0.02)_1px,transparent_1px)] bg-[size:40px_40px] pointer-events-none"></div>
            <div class="max-w-container-max mx-auto px-margin-desktop relative">
                <div class="text-center mb-24">
                    <h2 class="font-headline-lg text-headline-lg mb-6 text-on-surface uppercase tracking-widest">Protocolo de Operación</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl mx-auto">Trazabilidad absoluta en cada etapa del proceso técnico.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 relative">
                    <!-- Connector Line -->
                    <div class="hidden md:block absolute top-[48px] left-[10%] right-[10%] h-[1px] bg-outline-variant/20 z-0"></div>
                    
                    <!-- Step 1 -->
                    <div class="relative z-10 flex flex-col items-center text-center group">
                        <div class="w-24 h-24 rounded-full bg-surface-container border border-electric-cyan/30 flex items-center justify-center mb-8 shadow-xl group-hover:border-electric-cyan group-hover:cyber-glow transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl text-electric-cyan">sensors</span>
                        </div>
                        <h3 class="font-label-md text-lg mb-3 text-on-surface uppercase tracking-wider">Ingesta</h3>
                        <p class="font-body-sm text-on-surface-variant px-4">Escaneo inicial y registro en base de datos centralizada.</p>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="relative z-10 flex flex-col items-center text-center group">
                        <div class="w-24 h-24 rounded-full bg-surface-container border border-outline-variant/30 flex items-center justify-center mb-8 shadow-xl group-hover:border-electric-cyan group-hover:cyber-glow transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl text-on-surface">analytics</span>
                        </div>
                        <h3 class="font-label-md text-lg mb-3 text-on-surface uppercase tracking-wider">Análisis</h3>
                        <p class="font-body-sm text-on-surface-variant px-4">Diagnóstico por termografía y microscopía digital.</p>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="relative z-10 flex flex-col items-center text-center group">
                        <div class="w-24 h-24 rounded-full bg-surface-container border border-outline-variant/30 flex items-center justify-center mb-8 shadow-xl group-hover:border-electric-cyan group-hover:cyber-glow transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl text-on-surface">precision_manufacturing</span>
                        </div>
                        <h3 class="font-label-md text-lg mb-3 text-on-surface uppercase tracking-wider">Ejecución</h3>
                        <p class="font-body-sm text-on-surface-variant px-4">Intervención técnica con componentes certificados OEM.</p>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="relative z-10 flex flex-col items-center text-center group">
                        <div class="w-24 h-24 rounded-full bg-surface-container border border-outline-variant/30 flex items-center justify-center mb-8 shadow-xl group-hover:border-electric-cyan group-hover:cyber-glow transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl text-on-surface">verified</span>
                        </div>
                        <h3 class="font-label-md text-lg mb-3 text-on-surface uppercase tracking-wider">Despacho</h3>
                        <p class="font-body-sm text-on-surface-variant px-4">Control de calidad final y entrega con garantía encriptada.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 max-w-container-max mx-auto px-margin-desktop">
            <div class="bg-surface-container-high rounded-xl p-16 text-center text-on-surface relative overflow-hidden border border-electric-cyan/20 cyber-glow">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(0,242,255,0.05)_0%,transparent_70%)]"></div>
                <h2 class="font-headline-lg text-headline-lg mb-6 relative z-10 uppercase tracking-tighter">¿SISTEMA <span class="text-electric-cyan">CRÍTICO</span> FUERA DE LÍNEA?</h2>
                <p class="font-body-lg text-body-lg mb-12 text-on-surface-variant max-w-2xl mx-auto relative z-10">Tiempos de respuesta optimizados y eficiencia técnica garantizada. Recuperamos su operatividad.</p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center relative z-10">
                    <Link :href="route('contact')" class="bg-electric-cyan text-surface px-12 py-5 rounded font-label-md font-bold uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all">Solicitar Diagnóstico</Link>
                    <Link :href="route('contact')" class="bg-transparent border border-outline-variant text-on-surface px-12 py-5 rounded font-label-md uppercase tracking-widest hover:bg-on-surface/5 transition-all">Consultar Técnico</Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style>
.cyber-border { border: 1px solid rgba(0, 242, 255, 0.2); position: relative; }
.cyber-border::after { content: ''; position: absolute; top: -1px; left: -1px; width: 10px; height: 10px; border-top: 2px solid #00f2ff; border-left: 2px solid #00f2ff; }
.cyber-glow { box-shadow: 0 0 20px rgba(0, 242, 255, 0.1); }
.gradient-mesh { background-image: radial-gradient(at 0% 0%, hsla(185,100%,50%,0.05) 0, transparent 50%), radial-gradient(at 100% 100%, hsla(185,100%,50%,0.05) 0, transparent 50%); }
.card-reveal:hover .reveal-icon { transform: translateY(-5px); color: #00f2ff; }
</style>