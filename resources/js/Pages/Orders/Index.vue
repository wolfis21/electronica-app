<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

const props = defineProps({
    orders: Object,
    can: Object,
    filters: Object,
});

const confirmDelete = (orderId) => {
    if (confirm('¿Estás seguro de que quieres eliminar esta orden?')) {
        // Usa 'router.delete' en lugar de 'usePage().form().delete'
        router.delete(route('orders.destroy', orderId), {
            preserveScroll: true,
            // Opcional: puedes usar los eventos de Inertia para mostrar notificaciones más elegantes
            onSuccess: () => {
                // Por ejemplo, si usas una librería de notificaciones
                // toast.success('Orden eliminada con éxito.');
            },
            onError: (errors) => {
                alert('Error al eliminar la orden.');
                console.log(errors);
            },
        });
    }
};

// Estado reactivo para el término de búsqueda
const search = ref(props.filters.search || '');
// Nuevo estado reactivo para el filtro de estado
const statusFilter = ref(props.filters.status || '');

// Observa cambios en el término de búsqueda y el filtro de estado
watch([search, statusFilter], debounce(([newSearch, newStatus]) => {
    router.get(route('orders.index'), {
        search: newSearch,
        status: newStatus, // Enviar el estado seleccionado
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));

// Opciones para el filtro de estado
const statusOptions = [
    { value: '', label: 'Todos los estados' },
    { value: 'Pendiente', label: 'Pendiente' },
    { value: 'En proceso', label: 'En proceso' }, // Asegúrate que estos valores coincidan exactamente con los de tu DB
    { value: 'Completado', label: 'Completado' },
    { value: 'Cancelado', label: 'Cancelado' },
];

</script>

<template>
    <Head title="Órdenes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Órdenes</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xxl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-3 md:mb-0">Listado de Órdenes</h3>
                            <div class="flex flex-wrap items-center space-y-3 md:space-y-0 md:space-x-4 w-full md:w-auto">
                                <input
                                    type="text"
                                    v-model="search"
                                    placeholder="Buscar órdenes..."
                                    class="block w-full md:w-64 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                />

                                <select
                                    v-model="statusFilter"
                                    class="block w-full md:w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                                    <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                                        {{ option.label }}
                                    </option>
                                </select>

                                <Link v-if="can.create_orders" :href="route('orders.create')" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Crear Nueva Orden
                                </Link>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI Cliente</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-if="orders.data.length === 0">
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No se encontraron órdenes.
                                        </td>
                                    </tr>
                                    <tr v-for="order in orders.data" :key="order.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ order.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ order.customer.fullname }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ order.customer.dni }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ order.user.name }}</td>
                                        <td class="px-6 py-4 break-words max-w-xs">{{ order.name_equip }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'bg-yellow-100 text-yellow-800': order.status === 'Pendiente',
                                                'bg-blue-100 text-blue-800': order.status === 'En proceso', // Corregido
                                                'bg-green-100 text-green-800': order.status === 'Completado', // Corregido
                                                'bg-red-100 text-red-800': order.status === 'Cancelado',
                                            }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ order.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-2">
                                            <Link :href="route('orders.show', order.id)" class="text-gray-600 hover:text-gray-900" title="Ver Detalles">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </Link>
                                            <Link v-if="can.edit_orders" :href="route('orders.edit', order.id)" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                </svg>
                                            </Link>
                                                <div v-if="!order.reviews || order.reviews.length === 0" class="relative group">
                                                    <span class="text-gray-300 cursor-not-allowed">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                        </svg>
                                                    </span>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 w-max
                                                                invisible group-hover:visible 
                                                                bg-gray-900 text-white text-xs font-normal rounded z-10">
                                                        Requiere Revisión
                                                    </div>
                                                </div>

                                                <a v-else
                                                :href="route('orders.confirmPickup', order.id)"
                                                target="_blank"
                                                :class="{
                                                    'text-gray-300 cursor-not-allowed pointer-events-none': order.status === 'Cancelado'
                                                }"
                                                class="text-green-600 hover:text-green-900"
                                                title="Confirmar Retiro y Generar PDF">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                </a>
                                            <DangerButton v-if="can.delete_orders" @click="confirmDelete(order.id)" title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.928a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.14-2.006-2.238a48.787 48.787 0 0 0-6.412 0c-1.096.098-2.006 1.058-2.006 2.238v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </DangerButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                         <div v-if="orders.links.length > 3" class="mt-6 flex justify-center">
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <template v-for="(link, key) in orders.links" :key="key">
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>