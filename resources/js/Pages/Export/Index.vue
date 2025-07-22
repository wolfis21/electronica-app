<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3'; // <-- Se añadió useForm
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { ref, computed } from 'vue';

// --- Lógica para EXPORTACIÓN (sin cambios) ---
const selectedType = ref('orders');
const selectedFormat = ref('xlsx');
const downloadUrl = computed(() => {
    return route('export.download', {
        type: selectedType.value,
        format: selectedFormat.value
    });
});

// --- Lógica para IMPORTACIÓN (añadida) ---
// Usamos 'useForm' para manejar el archivo que se va a subir.
const importForm = useForm({
    import_file: null,
});

// Esta función se llama al enviar el formulario de importación.
const submitImport = () => {
    importForm.post(route('import.store'), {
        onSuccess: () => importForm.reset(), // Limpia el campo del archivo si la subida fue exitosa
    });
};
</script>

<template>
    <Head title="Importar y Exportar Datos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Centro de Importación y Exportación
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Exportar Datos
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <InputLabel for="type" value="¿Qué datos deseas exportar?" />
                            <select id="type" v-model="selectedType"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="orders">Órdenes</option>
                                <option value="customers">Clientes</option>
                                <option value="products">Productos/Servicios</option>
                                <option value="payments">Pagos</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel value="Elige el formato del archivo" />
                            <div class="mt-2 flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input type="radio" v-model="selectedFormat" value="xlsx"
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Excel (.xlsx)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" v-model="selectedFormat" value="csv"
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">CSV (.csv)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <a :href="downloadUrl"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Generar y Descargar
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Importar Datos</h3>
                    
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-2">
                            Paso 1: Descarga la plantilla para asegurarte de que tus datos tengan el formato correcto.
                        </p>
                        <a href="/templates/orders_template.xlsx" download class="text-sm text-indigo-600 hover:underline font-semibold">
                            Descargar Plantilla para Órdenes
                        </a>
                    </div>
                    
                    <div class="border-t pt-6">
                        <form @submit.prevent="submitImport">
                             <InputLabel for="import_file" value="Paso 2: Sube el archivo completado" />
                             <input 
                                id="import_file"
                                type="file" 
                                @input="importForm.import_file = $event.target.files[0]"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                required
                            />
                            <p v-if="importForm.errors.import_file" class="text-sm text-red-600 mt-2">
                                {{ importForm.errors.import_file }}
                            </p>

                             <div class="mt-6 flex justify-end">
                                <PrimaryButton :disabled="importForm.processing">
                                    Importar Datos
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>