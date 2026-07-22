<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    message: ''
});

const isSubmitting = ref(false);
const isSuccess = ref(false);

const submitForm = () => {
    // Simulating form submission visually as requested in the design script
    isSubmitting.value = true;
    
    setTimeout(() => {
        isSubmitting.value = false;
        isSuccess.value = true;
        
        setTimeout(() => {
            isSuccess.value = false;
            form.reset();
        }, 3000);
    }, 1500);

    // If you want to connect to the backend, you can uncomment this:
    /*
    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => {
            isSuccess.value = true;
            setTimeout(() => {
                isSuccess.value = false;
                form.reset();
            }, 3000);
        }
    });
    */
};
</script>

<template>
    <Head title="Contacto - SM Soluciones Electrónicas" />

    <PublicLayout>
        <main class="pt-20">
            <!-- Section 1: Header -->
            <section class="relative py-16 md:py-24 overflow-hidden">
                <div class="absolute inset-0 z-0 bg-primary-container opacity-[0.03]">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, #00f2ff 1px, transparent 0); background-size: 40px 40px;"></div>
                </div>
                <div class="relative z-10 max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop text-center">
                    <span class="inline-block px-4 py-1 rounded-lg bg-surface-container-highest text-electric-cyan font-label-sm text-label-sm uppercase tracking-wider mb-4 border border-outline-variant">Estamos aquí para ayudarte</span>
                    <h1 class="font-headline-xl text-headline-xl text-on-surface mb-6">Contáctanos</h1>
                    <p class="max-w-2xl mx-auto font-body-lg text-body-lg text-on-surface-variant">¿Tienes problemas con tus equipos electrónicos o necesitas optimizar tus sistemas? Nuestro equipo de expertos está listo para brindarte asistencia técnica de primer nivel. Si tu problema específico no figura en nuestros servicios, utiliza este formulario para consultarnos directamente.</p>
                </div>
            </section>

            <!-- Main Content Area: Contact Form & Info -->
            <section class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop pb-24">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
                    <!-- Section 2: Contact Form -->
                    <div class="lg:col-span-7 bg-surface-container-low p-8 md:p-12 rounded-xl technical-card border border-outline-variant">
                        <div class="mb-6 p-4 rounded-lg bg-electric-cyan/20 border border-electric-cyan flex items-center gap-3">
                            <span class="material-symbols-outlined text-electric-cyan">info</span>
                            <p class="font-label-md text-label-md text-on-surface">¿Tu equipo no está en la lista? <span class="font-bold text-electric-cyan">Consúltanos aquí</span> y te daremos una solución personalizada.</p>
                        </div>
                        <h2 class="font-headline-md text-headline-md text-on-surface mb-8 flex items-center gap-3">
                            <span class="material-symbols-outlined text-electric-cyan">mail</span>
                            Envíanos un mensaje
                        </h2>
                        
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col gap-2">
                                    <label class="font-label-md text-label-md text-on-surface-variant">Nombre Completo</label>
                                    <input v-model="form.name" class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-electric-cyan focus:ring-1 focus:ring-electric-cyan transition-all bg-surface-container-lowest text-on-surface font-body-md placeholder-on-surface-variant/30" placeholder="Ej. Juan Pérez" required="" type="text">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="font-label-md text-label-md text-on-surface-variant">Correo Electrónico</label>
                                    <input v-model="form.email" class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-electric-cyan focus:ring-1 focus:ring-electric-cyan transition-all bg-surface-container-lowest text-on-surface font-body-md placeholder-on-surface-variant/30" placeholder="nombre@ejemplo.com" required="" type="email">
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="font-label-md text-label-md text-on-surface-variant">Teléfono de Contacto</label>
                                <input v-model="form.phone" class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-electric-cyan focus:ring-1 focus:ring-electric-cyan transition-all bg-surface-container-lowest text-on-surface font-body-md placeholder-on-surface-variant/30" placeholder="+54 11 1234-5678" type="tel">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="font-label-md text-label-md text-on-surface-variant">Descripción del Problema</label>
                                <textarea v-model="form.message" class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:border-electric-cyan focus:ring-1 focus:ring-electric-cyan transition-all bg-surface-container-lowest text-on-surface font-body-md placeholder-on-surface-variant/30" placeholder="Cuéntanos brevemente qué sucede con tu equipo..." required="" rows="5"></textarea>
                            </div>
                            
                            <button 
                                type="submit" 
                                :disabled="isSubmitting || isSuccess"
                                :class="[
                                    'w-full font-headline-md text-headline-md py-4 rounded-lg shadow-lg transition-all flex justify-center items-center gap-2 uppercase tracking-tight font-bold',
                                    isSuccess ? 'bg-[#10B981] text-white' : 'bg-electric-cyan text-surface-container-lowest hover:brightness-105 group'
                                ]"
                            >
                                <template v-if="isSubmitting">
                                    <span class="material-symbols-outlined animate-spin">sync</span> ENVIANDO...
                                </template>
                                <template v-else-if="isSuccess">
                                    <span class="material-symbols-outlined text-white">check_circle</span> MENSAJE ENVIADO
                                </template>
                                <template v-else>
                                    Enviar Consulta
                                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">send</span>
                                </template>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Section 3: Contact Info -->
                    <div class="lg:col-span-5 flex flex-col gap-6">
                        <!-- Contact Cards -->
                        <div class="bg-surface-container-lowest p-8 rounded-xl technical-card border border-outline-variant border-t-4 border-t-electric-cyan">
                            <h3 class="font-headline-md text-headline-md mb-6 text-on-surface">Información de Contacto</h3>
                            <div class="space-y-6">
                                <div class="flex items-start gap-4">
                                    <div class="bg-surface-container-high p-2 rounded-lg border border-outline-variant">
                                        <span class="material-symbols-outlined text-electric-cyan">location_on</span>
                                    </div>
                                    <div>
                                        <p class="font-label-md text-label-md text-electric-cyan mb-1 uppercase">Ubicación</p>
                                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                                            Urbanización Curagua avenida principal, Puerto Ordaz, Venezuela
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="bg-surface-container-high p-2 rounded-lg border border-outline-variant">
                                        <span class="material-symbols-outlined text-electric-cyan">call</span>
                                    </div>
                                    <div>
                                        <p class="font-label-md text-label-md text-electric-cyan mb-1 uppercase">Teléfonos</p>
                                        <div class="font-body-md text-body-md text-on-surface-variant space-y-1">
                                            <p class="font-bold text-on-surface">0414 - 8773560</p>
                                            <p class="text-on-surface-variant">0424 - 9342951</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="bg-surface-container-high p-2 rounded-lg border border-outline-variant">
                                        <span class="material-symbols-outlined text-electric-cyan">schedule</span>
                                    </div>
                                    <div>
                                        <p class="font-label-md text-label-md text-electric-cyan mb-1 uppercase">Horarios de Atención</p>
                                        <p class="font-body-md text-body-md text-on-surface-variant">Lunes a Viernes: 09:00 - 18:00<br>Sábados: 10:00 - 13:00</p>
                                    </div>
                                </div>
                            </div>
                            <a class="mt-10 flex items-center justify-center gap-3 bg-[#25D366] text-surface-container-lowest py-4 rounded-lg font-bold text-lg hover:brightness-110 transition-all shadow-md active:scale-95" href="https://wa.me/541112345678" target="_blank">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.305 1.652zm6.599-3.835c1.523.904 3.13 1.379 4.799 1.38h.005c5.454 0 9.893-4.439 9.895-9.895.001-2.645-1.029-5.131-2.902-7.005-1.873-1.874-4.359-2.903-7.004-2.903-5.454 0-9.894 4.44-9.896 9.896-.001 1.83.5 3.568 1.446 5.054l-1.077 3.931 4.034-1.058zm12.846-7.235c-.345-.173-2.042-1.007-2.359-1.122-.317-.115-.548-.173-.779.173-.231.346-.893 1.123-1.095 1.353-.201.23-.404.259-.75.086-.345-.173-1.458-.537-2.777-1.714-1.026-.915-1.719-2.045-1.92-2.39-.202-.346-.022-.533.15-.705.155-.154.345-.404.519-.606.173-.202.23-.346.346-.577.115-.231.058-.433-.029-.606-.086-.173-.779-1.874-1.066-2.566-.28-.673-.564-.582-.779-.593-.201-.01-.432-.012-.664-.012-.231 0-.606.086-.923.433-.317.346-1.211 1.183-1.211 2.883 0 1.7 1.24 3.345 1.413 3.576.173.231 2.441 3.728 5.912 5.228.825.357 1.47.57 1.972.729.828.263 1.58.226 2.176.137.664-.099 2.042-.835 2.331-1.643.288-.808.288-1.5.202-1.643-.086-.144-.317-.23-.664-.403z"></path></svg>
                                Escríbenos por WhatsApp
                            </a>
                        </div>
                        
                        <!-- Visual Accent Card -->
                        <div class="relative overflow-hidden rounded-xl h-full min-h-[200px] border border-outline-variant technical-card">
                            <div class="absolute inset-0 bg-cover bg-center transition-transform hover:scale-110 duration-700" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuD1LgEdGOKwLCIILSQDqOm_80x6mRgcSArz3qUm4hi5aOOzRPFI4KH0IVNRqXow853w3M2BG97yM7f9Y3YBcnk9rFOdoHAcAZmI1vnjtU8wDD3XE6bzqHylsCN6_nJv1N4ayE0w19nzBXfCnCGXDSlLHvuKxFTtWIg3Zf07E0oxCbc0MexqrNmF2_Y0fJe5Zb9s4LJtf3xNhxdt_bMXh1at0Qk99_CwL5k52mwVZb2m9pZVIhk0XkCa1EPYc8643Rf7lb4wREil08dk')"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-surface-container-lowest/90 to-transparent flex items-end p-6">
                                <p class="text-on-surface font-headline-md text-headline-md leading-tight">Soluciones <span class="text-electric-cyan">tecnológicas</span> a tu medida.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 4: Location Map -->
            <section class="bg-surface-container py-20 border-t border-outline-variant">
                <div class="max-w-max-width mx-auto px-margin-mobile md:px-margin-desktop">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
                        <div>
                            <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Nuestro Centro Técnico</h2>
                            <p class="font-body-md text-body-md text-on-surface-variant">Visítanos en el corazón de la ciudad para una atención personalizada.</p>
                        </div>
                        <a href="https://maps.google.com" target="_blank" class="bg-surface-container-lowest border border-outline-variant text-on-surface px-8 py-3 rounded-lg font-label-md text-label-md hover:border-electric-cyan hover:text-electric-cyan transition-all flex items-center gap-2 uppercase tracking-wider font-bold">
                            <span class="material-symbols-outlined">directions</span>
                            Cómo llegar
                        </a>
                    </div>
                    <div class="w-full h-[450px] rounded-xl overflow-hidden shadow-xl border border-outline-variant bg-surface-container-low relative technical-card">
                        <!-- Map Placeholder -->
                        <div class="absolute inset-0 bg-[#090e19] flex items-center justify-center">
                            <div class="flex flex-col items-center gap-4 text-on-surface-variant opacity-60">
                                <span class="material-symbols-outlined text-6xl text-electric-cyan">map</span>
                                <p class="font-label-md text-label-md uppercase tracking-widest">Cargando Mapa del Centro Técnico...</p>
                            </div>
                            <!-- Decorative Map Markers simulation -->
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                                <div class="relative">
                                    <div class="w-12 h-12 bg-electric-cyan/20 rounded-full animate-ping absolute -inset-0"></div>
                                    <div class="relative z-10 w-12 h-12 bg-surface-container-highest flex items-center justify-center rounded-lg text-electric-cyan shadow-lg border border-electric-cyan">
                                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">location_on</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </PublicLayout>
</template>

<style>
.technical-card {
    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
}
.technical-card:hover {
    box-shadow: 0px 8px 30px rgba(0, 242, 255, 0.15);
    border-color: #00f2ff;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: #090e19;
}
::-webkit-scrollbar-thumb {
    background: #303541;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background: #00f2ff;
}
</style>