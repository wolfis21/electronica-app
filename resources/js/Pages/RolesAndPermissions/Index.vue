<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Roles y Permisos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Roles del Sistema</h3>
                            <Link :href="route('roles.create')" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Crear Nuevo Rol</Link>
                        </div>

<!--                         <div v-if="$page.props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ $page.props.flash.success }}</span>
                        </div>
                        <div v-if="$page.props.flash.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ $page.props.flash.error }}</span>
                        </div> -->

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permisos Asignados</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="role in roles" :key="role.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ role.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ role.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ role.description }}</td>
                                        <td class="px-6 py-4">
                                            <span v-for="(permission, index) in role.permissions" :key="permission" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                                {{ permission }}
                                            </span>
                                            <span v-if="role.permissions.length === 0">Ninguno</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="route('roles.edit', role.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</Link>
                                            <button @click="deleteRole(role.id)" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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