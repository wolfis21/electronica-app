<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { ref, computed } from 'vue'; // <-- Importamos ref y computed

// 1. Usamos 'ref' para guardar las selecciones del usuario, en lugar de useForm.
const selectedType = ref('orders');
const selectedFormat = ref('xlsx');

// 2. Creamos una URL dinámica que cambia cuando el usuario selecciona algo.
const downloadUrl = computed(() => {
    // La función route() de Ziggy nos ayuda a construir la URL con los parámetros correctos.
    return route('export.download', { 
        type: selectedType.value, 
        format: selectedFormat.value 
    });
});
</script>

<template>
    <Head title="Exportar Datos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Centro de Exportación
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Generar un Reporte
                        </h3>
                        
                        <div class="space-y-6">
                            <div>
                                <InputLabel for="type" value="¿Qué datos deseas exportar?" />
                                <select 
                                    id="type" 
                                    v-model="selectedType"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >
                                    <option value="orders">Órdenes</option>
                                </select>
                            </div>

                            <div>
                                <InputLabel value="Elige el formato del archivo" />
                                <div class="mt-2 flex items-center space-x-6">
                                    <label class="flex items-center">
                                        <input type="radio" v-model="selectedFormat" value="xlsx" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Excel (.xlsx)</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" v-model="selectedFormat" value="csv" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">CSV (.csv)</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <a :href="downloadUrl" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Generar y Descargar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>