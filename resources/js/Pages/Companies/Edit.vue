<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    company: Object, // La compañía que vamos a editar
    can: Object,     // Para los permisos, aunque aquí el middleware ya lo cubre
});

const form = useForm({
    name: props.company.name,
    description: props.company.description,
    phone: props.company.phone,
    email: props.company.email,
    address: props.company.address,
});

const submit = () => {
    form.put(route('companies.update', props.company.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Opcional: mensaje de éxito usando flash messages
            router.visit(route('companies.index'), {
                onSuccess: () => alert('Información de la empresa actualizada.'),
            });
        },
        onError: () => {
            alert('Hubo un error al actualizar la información.');
        },
    });
};
</script>

<template>
    <Head :title="`Editar ${props.company.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Información de la Empresa</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="name" value="Nombre de la Empresa" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="description" value="Descripción" />
                            <textarea
                                id="description"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.description"
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="phone" value="Teléfono" />
                            <TextInput
                                id="phone"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.phone"
                            />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="address" value="Dirección" />
                            <TextInput
                                id="address"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.address"
                            />
                            <InputError class="mt-2" :message="form.errors.address" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Actualizar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>