<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    company: Object, // Pasa el objeto de la compañía
    companies: Array, // En caso de que decidas listar varias
    can: Object, // Para los permisos del usuario
});
</script>

<template>
    <Head title="Gestión de Empresas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Empresas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Empresa</h3>

                    <div v-if="props.company">
                        <p><strong>Nombre:</strong> {{ props.company.name }}</p>
                        <p><strong>Descripción:</strong> {{ props.company.description }}</p>
                        <p><strong>Teléfono:</strong> {{ props.company.phone }}</p>
                        <p><strong>Email:</strong> {{ props.company.email }}</p>
                        <p><strong>Dirección:</strong> {{ props.company.address }}</p>

                        <div class="mt-6" v-if="props.can.manage_companies">
                            <Link :href="route('companies.edit', props.company.id)" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Editar Información de la Empresa
                            </Link>
                        </div>
                    </div>
                    <div v-else>
                        <p>No hay información de la empresa registrada.
                            <Link :href="route('companies.create')" class="text-blue-600 hover:text-blue-900">
                                Crear Compañía
                            </Link>
                        </p>
                        </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>