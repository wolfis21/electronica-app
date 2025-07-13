<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    file: null, // Asegúrate de que esta propiedad exista
});

const submit = () => {
    // Cuando envías el formulario con archivos, usa form.post() directamente.
    // Inertia se encarga de serializar los datos, incluyendo el archivo.
    form.post(route('import.process'), {
        onSuccess: () => {
            form.reset();
            // Mostrar un mensaje de éxito, por ejemplo, usando un flash message
        },
        onError: (errors) => {
            console.error('Errores de importación:', errors);
            // Mostrar errores al usuario
        },
    });
};
</script>

<template>
    <Head title="Importar Datos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Importar Clientes y Órdenes</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">Seleccionar archivo Excel (.xlsx, .xls)</label>
                            <input
                                type="file"
                                id="file"
                                @input="form.file = $event.target.files[0]"
                                class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                            />
                            <p v-if="form.errors.file" class="text-red-500 text-xs mt-1">{{ form.errors.file }}</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <span v-if="form.processing">Importando...</span>
                                <span v-else>Importar Datos</span>
                            </button>
                        </div>

                        <div v-if="$page.props.flash.success" class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ $page.props.flash.success }}</span>
                        </div>
                        <div v-if="$page.props.flash.error" class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ $page.props.flash.error }}</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>