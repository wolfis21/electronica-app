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
            <div class="flex justify-between items-center w-full">
                <h2 class="font-bold text-xl md:text-2xl text-gray-800 leading-tight">Empleados y Usuarios</h2>
                <Link :href="route('employees_users.create')" v-if="props.can.add_users" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase rounded-lg shadow-md transition duration-150 transform hover:scale-105">
                    Añadir Nuevo
                </Link>
            </div>
        </template>

        <div class="py-6 md:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Main Desktop Table View -->
                <div class="hidden md:block bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Listado de Empleados y Usuarios</h3>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <DangerButton @click="deleteEmployee(employee.id)" v-if="props.can.delete_users">Eliminar</DangerButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tarjetas Responsivas Móviles -->
                <div class="block md:hidden space-y-4">
                    <div v-if="employees.data.length === 0" class="text-center py-10 text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        No se encontraron empleados o usuarios.
                    </div>
                    <div
                        v-for="employee in employees.data"
                        :key="employee.id"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 relative"
                    >
                        <!-- Header: DNI + Rol -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">DNI</span>
                                <span class="text-lg font-bold text-gray-900">{{ employee.dni }}</span>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-indigo-50 text-indigo-700 uppercase tracking-wider">
                                {{ employee.user && employee.user.role ? employee.user.role.name : 'Sin Rol' }}
                            </span>
                        </div>

                        <!-- Body: Fullname + Email -->
                        <div class="space-y-2.5 mb-4 border-b border-gray-100 pb-3">
                            <h4 class="text-base font-bold text-gray-900 leading-tight">{{ employee.fullname }}</h4>
                            <div class="flex items-center text-sm text-gray-600 gap-2">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                                <span>{{ employee.user ? employee.user.email : 'N/A (Sin Usuario)' }}</span>
                            </div>
                        </div>

                        <!-- Footer: Actions -->
                        <div v-if="props.can.delete_users" class="flex justify-end items-center">
                            <DangerButton @click="deleteEmployee(employee.id)">Eliminar</DangerButton>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
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
    </AuthenticatedLayout>
</template>