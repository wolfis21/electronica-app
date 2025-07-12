<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const form = useForm({
    name: '',
    email: '',
    message: ''
});

const submit = () => {
    // Por ahora, solo simula el envío.
    // form.post(route('contact.store'));
    alert('Formulario enviado (simulación)');
};
</script>

<template>
    <Head title="Contacto - Orquesta" />

    <PublicLayout>
        <div class="bg-white py-24">
            <div class="container mx-auto px-6 text-center">
                <h1 class="font-serif text-5xl md:text-6xl font-bold text-orq-dark-gray">Hablemos</h1>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    Estamos aquí para afinar los detalles de tu negocio. Contáctanos y descubre cómo podemos crear una sinfonía de éxito juntos.
                </p>
            </div>
        </div>

        <div class="py-20 bg-orq-light-gray">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                    
                    <div class="text-orq-dark-gray">
                        <h3 class="font-serif text-3xl font-bold mb-6">Información de Contacto</h3>
                        <div class="space-y-4">
                            <p><strong>Dirección:</strong> Ciudad de Panamá, Panamá</p>
                            [cite_start]<p><strong>Email:</strong> <a href="mailto:soporte@panacompu.com" class="text-orq-blue hover:underline">soporte@panacompu.com</a> [cite: 5]</p>
                            [cite_start]<p><strong>Teléfono:</strong> <a href="tel:+5072018619" class="text-orq-blue hover:underline">+(507) 201-8619</a> [cite: 4]</p>
                            [cite_start]<p><strong>WhatsApp:</strong> <a href="https://wa.me/50762770904" class="text-orq-blue hover:underline">+(507) 6277-0904</a> [cite: 4]</p>
                        </div>
                    </div>

                    <div>
                         <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="name" value="Nombre Completo" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                            </div>
                             <div>
                                <InputLabel for="email" value="Correo Electrónico" />
                                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                            </div>
                             <div>
                                <InputLabel for="message" value="Mensaje" />
                                <textarea id="message" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.message" rows="5" required></textarea>
                            </div>
                            <div class="text-right">
                                <PrimaryButton :disabled="form.processing" style="background-color: #1A237E;">
                                    Enviar Mensaje
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>