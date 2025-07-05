<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3'; // Asegúrate de importar useForm
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    employees: Object, // Laravel paginator object
    can: Object,
});

const deleteEmployee = (id) => {
    if (confirm('¿Estás seguro de que quieres eliminar a este empleado y su usuario asociado?')) {
        const deleteForm = useForm({});
        deleteForm.delete(route('employees_users.destroy', id), {
            onSuccess: () => {
                alert('Empleado y usuario eliminados.');
            },
            onError: () => {
                alert('Error al eliminar.');
            }
        });
    }
};
</script>

<template>
    <Head title="Gestión de Empleados y Usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Empleados y Usuarios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Listado de Empleados y Usuarios</h3>
                            <Link :href="route('employees_users.create')" v-if="props.can.add_users" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Añadir Nuevo Empleado/Usuario
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nombre Completo
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            DNI
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email (Usuario)
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rol
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="employee in employees.data" :key="employee.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ employee.fullname }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ employee.dni }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ employee.user ? employee.user.email : 'N/A (Sin Usuario)' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ employee.user && employee.user.role ? employee.user.role.name : 'Sin Rol' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <DangerButton @click="deleteEmployee(employee.id)" v-if="props.can.delete_users">Eliminar</DangerButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 flex justify-between items-center" v-if="employees.links.length > 3">
                            <Link
                                v-for="link in employees.links"
                                :key="link.label"
                                :href="link.url"
                                v-html="link.label"
                                class="px-3 py-1 text-sm rounded-md"
                                :class="{
                                    'bg-gray-800 text-white': link.active,
                                    'text-gray-600 hover:bg-gray-200': !link.active && link.url,
                                    'text-gray-400 cursor-not-allowed': !link.url,
                                }"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>