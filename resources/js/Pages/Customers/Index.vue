<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue'; // Para el campo de búsqueda
import { watch, ref } from 'vue'; // Para la búsqueda reactiva
import { debounce } from 'lodash'; // Necesitarás instalar lodash si no lo tienes: npm install lodash

const props = defineProps({
    customers: Object, // Paginator object
    filters: Object, // Para mantener el estado de los filtros de búsqueda
    can: Object,     // Permisos
});

// Formulario para la búsqueda
const searchForm = useForm({
    search: props.filters.search || '',
});

// Observar cambios en el campo de búsqueda y realizar la petición con debounce
watch(() => searchForm.search, debounce(() => {
    searchForm.get(route('customers.index'), {
        preserveState: true, // Mantener el estado de desplazamiento y formulario
        replace: true,       // Reemplazar la entrada en el historial del navegador
    });
}, 300)); // Debounce de 300ms

const confirmDelete = (customerId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este cliente? Esta acción es irreversible.')) {
        useForm({}).delete(route('customers.destroy', customerId), {
            preserveScroll: true,
            onSuccess: () => alert('Cliente eliminado con éxito.'),
            onError: () => alert('Error al eliminar el cliente.'),
        });
    }
};

console.log('Customers prop received:', props.customers);
console.log('Customers data received:', props.customers.data);
</script>

<template>
    <Head title="Gestión de Clientes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <h2 class="font-bold text-xl md:text-2xl text-gray-800 leading-tight">Clientes</h2>
                <Link v-if="can.create_customers" :href="route('customers.create')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase rounded-lg shadow-md transition duration-150 transform hover:scale-105">
                    Crear Nuevo Cliente
                </Link>
            </div>
        </template>

        <div class="py-6 md:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Search and Filters Container -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
                    <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
                        <!-- Search Box with Magnifying Glass Icon -->
                        <div class="relative flex-grow max-w-xl">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <TextInput
                                id="search"
                                v-model="searchForm.search"
                                type="text"
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                placeholder="Buscar por Nombre, DNI o Teléfono..."
                            />
                        </div>
                    </div>
                </div>

                <!-- Main Desktop Table View -->
                <div class="hidden md:block bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                                        <th v-if="can.edit_customers || can.delete_customers" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="customer in customers.data" :key="customer.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ customer.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ customer.fullname }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ customer.dni }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ customer.phone || 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ customer.email || 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ customer.name_company || 'N/A' }}</td>
                                        <td v-if="can.edit_customers || can.delete_customers" class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-2">
                                            <Link v-if="can.edit_customers" :href="route('customers.edit', customer.id)" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                </svg>
                                            </Link>
                                            <DangerButton v-if="can.delete_customers" @click="confirmDelete(customer.id)" title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.928a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.14-2.006-2.238a48.787 48.787 0 0 0-6.412 0c-1.096.098-2.006 1.058-2.006 2.238v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </DangerButton>
                                        </td>
                                    </tr>
                                    <tr v-if="customers.data.length === 0">
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No se encontraron clientes.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tarjetas Responsivas Móviles -->
                <div class="block md:hidden space-y-4">
                    <div v-if="customers.data.length === 0" class="text-center py-10 text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        No se encontraron clientes.
                    </div>
                    <div
                        v-for="customer in customers.data"
                        :key="customer.id"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 relative"
                    >
                        <!-- Header: ID + DNI -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">ID DE CLIENTE</span>
                                <span class="text-xl font-bold text-gray-900">#{{ customer.id }}</span>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-gray-100 text-gray-800 uppercase tracking-wider">
                                DNI: {{ customer.dni }}
                            </span>
                        </div>

                        <!-- Body: Full Name, Phone, Email, Company -->
                        <div class="space-y-2.5 mb-4 border-b border-gray-100 pb-3">
                            <h4 class="text-base font-bold text-gray-900 leading-tight">{{ customer.fullname }}</h4>
                            
                            <!-- Phone -->
                            <div class="flex items-center text-sm text-gray-600 gap-2">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>{{ customer.phone || 'N/A' }}</span>
                            </div>

                            <!-- Email -->
                            <div class="flex items-center text-sm text-gray-600 gap-2">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate">{{ customer.email || 'N/A' }}</span>
                            </div>

                            <!-- Company -->
                            <div v-if="customer.name_company" class="flex items-center text-sm text-gray-600 gap-2">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>{{ customer.name_company }}</span>
                            </div>
                        </div>

                        <!-- Footer: Actions -->
                        <div v-if="can.edit_customers || can.delete_customers" class="flex justify-end items-center space-x-3 text-gray-500">
                            <!-- Edit -->
                            <Link v-if="can.edit_customers" :href="route('customers.edit', customer.id)" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                            </Link>

                            <!-- Delete -->
                            <button v-if="can.delete_customers" @click="confirmDelete(customer.id)" class="text-red-500 hover:text-red-700" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                <div v-if="customers.links.length > 3" class="mt-6 flex justify-center">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, key) in customers.links" :key="key">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                :class="{
                                    'z-10 bg-blue-50 border-blue-500 text-blue-600': link.active,
                                    'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active,
                                    'rounded-l-md': link.label.includes('Previous'),
                                    'rounded-r-md': link.label.includes('Next'),
                                }"
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium focus:z-20"
                                v-html="link.label.replace('&laquo; Previous', '&laquo; Anterior').replace('Next &raquo;', 'Siguiente &raquo;')"
                            />
                            <span
                                v-else
                                :class="{
                                    'z-10 bg-blue-50 border-blue-500 text-blue-600': link.active,
                                    'bg-white border-gray-300 text-gray-500': !link.active,
                                    'rounded-l-md': link.label.includes('Previous'),
                                    'rounded-r-md': link.label.includes('Next'),
                                }"
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                v-html="link.label.replace('&laquo; Previous', '&laquo; Anterior').replace('Next &raquo;', 'Siguiente &raquo;')"
                            ></span>
                        </template>
                    </nav>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>