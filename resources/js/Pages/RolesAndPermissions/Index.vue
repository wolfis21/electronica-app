<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <h2 class="font-bold text-xl md:text-2xl text-gray-800 leading-tight">Roles y Permisos</h2>
                <Link :href="route('roles.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase rounded-lg shadow-md transition duration-150 transform hover:scale-105">
                    Crear Nuevo Rol
                </Link>
            </div>
        </template>

        <div class="py-6 md:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Main Desktop Table View -->
                <div class="hidden md:block bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Roles del Sistema</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="role in roles" :key="role.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ role.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ role.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ role.description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <Link :href="route('roles.edit', role.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</Link>
                                            <button @click="deleteRole(role.id)" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tarjetas Responsivas Móviles -->
                <div class="block md:hidden space-y-4">
                    <div v-if="roles.length === 0" class="text-center py-10 text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        No se encontraron roles.
                    </div>
                    <div
                        v-for="role in roles"
                        :key="role.id"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 relative"
                    >
                        <!-- Header: ID + Name -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">ID DE ROL</span>
                                <span class="text-xl font-bold text-gray-900">#{{ role.id }}</span>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-indigo-50 text-indigo-700 uppercase tracking-wider">
                                {{ role.name }}
                            </span>
                        </div>

                        <!-- Body: Description -->
                        <div class="space-y-2 mb-4 border-b border-gray-100 pb-3">
                            <p class="text-sm text-gray-600">
                                <span class="text-gray-400 block uppercase text-[9px] tracking-wider font-bold mb-0.5">Descripción</span>
                                {{ role.description }}
                            </p>
                        </div>

                        <!-- Footer: Actions -->
                        <div class="flex justify-end items-center space-x-3 text-gray-500">
                            <Link :href="route('roles.edit', role.id)" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                            </Link>
                            <button @click="deleteRole(role.id)" class="text-red-500 hover:text-red-700" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    roles: Array,
    permissions: Array, // Aunque no se usa directamente en esta vista, se pasa desde el controlador
    can: Object, // Para verificar permisos del usuario actual si lo implementas
});

const deleteRole = (roleId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este rol?')) {
        router.delete(route('roles.destroy', roleId), {
            onSuccess: () => {
                // Flash message handled by Laravel/Inertia
            },
            onError: (errors) => {
                console.error(errors);
                // Puedes mostrar un error más específico si la DB lo impide (ej. rol con usuarios)
                alert(errors.error || 'Error al eliminar el rol.');
            }
        });
    }
};
</script>