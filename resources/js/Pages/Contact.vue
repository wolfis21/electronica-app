<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue'; // Importamos el componente de error

// El prop 'success' recibirá el mensaje desde el controlador de Laravel
defineProps({
    success: String,
});

const form = useForm({
    name: '',
    email: '',
    message: ''
});

const submit = () => {
    // Ahora apunta a la ruta real y resetea el formulario si tiene éxito
    form.post(route('contact.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Contacto - Orquesta" />

    <PublicLayout>
        <section class="bg-orq-light-gray py-20 md:py-24">
            <div class="container mx-auto px-6 text-center">
                <h1 class="font-serif text-4xl md:text-6xl font-bold text-orq-dark-gray">Hablemos</h1>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    ¿Tienes una pregunta o estás listo para empezar? Estamos aquí para afinar los detalles de tu negocio y crear una sinfonía de éxito juntos.
                </p>
            </div>
        </section>

        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-6">

                <div v-if="success" class="mb-8 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-lg shadow-md">
                    <p class="font-bold">¡Gracias!</p>
                    <p>{{ success }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16 items-start">
                    
                    <div class="text-orq-dark-gray space-y-8">
                        <div>
                            <h3 class="font-serif text-3xl font-bold mb-4">Información de Contacto</h3>
                            <p class="text-gray-600">Puedes encontrarnos en los siguientes canales. Estaremos encantados de atenderte.</p>
                        </div>
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <p><strong>Email:</strong> <a href="mailto:infobermutech@gmail.com" class="text-orq-blue hover:underline">infobermutech@gmail.com</a></p>
                            </div>
                            <div class="flex items-start gap-4">
                               <p><strong>WhatsApp:</strong> <a href="https://wa.me/+50761640985" class="text-orq-blue hover:underline">+(507) 6164-0985</a></p>
                            </div>
                            <div class="flex items-start gap-4">
                                <p><strong>Dirección:</strong> Ciudad de Panamá, Panamá</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orq-light-gray p-8 rounded-xl shadow-lg">
                         <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="name" value="Nombre Completo" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
                             <div>
                                <InputLabel for="email" value="Correo Electrónico" />
                                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                             <div>
                                <InputLabel for="message" value="¿En qué podemos ayudarte?" />
                                <textarea id="message" class="mt-1 block w-full border-gray-300 focus:border-orq-gold focus:ring-orq-gold rounded-md shadow-sm" v-model="form.message" rows="5" required></textarea>
                                <InputError class="mt-2" :message="form.errors.message" />
                            </div>
                            <div class="text-right">
                                <PrimaryButton class="bg-orq-blue hover:bg-blue-900" :disabled="form.processing">
                                    <span v-if="form.processing">Enviando...</span>
                                    <span v-else>Enviar Mensaje</span>
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>