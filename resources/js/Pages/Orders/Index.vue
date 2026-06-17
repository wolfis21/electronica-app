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
            <div class="flex justify-between items-center w-full">
                <h2 class="font-bold text-xl md:text-2xl text-gray-800 leading-tight">Órdenes</h2>
                <Link v-if="can.create_orders" :href="route('orders.create')" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase rounded-lg shadow-md transition duration-150 transform hover:scale-105">
                    Crear Nueva Orden
                </Link>
            </div>
        </template>

        <div class="py-6 md:py-12">
            <div class="max-w-7xxl mx-auto sm:px-6 lg:px-8">
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
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Buscar por cliente u orden..."
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            />
                        </div>

                        <!-- Desktop Select Filter -->
                        <div class="hidden md:block">
                            <select
                                v-model="statusFilter"
                                class="block w-48 px-3 py-2.5 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            >
                                <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Status Chips Carousel for Mobile -->
                    <div class="flex md:hidden overflow-x-auto pt-4 space-x-2 no-scrollbar -mx-4 px-4">
                        <button
                            v-for="option in statusOptions"
                            :key="option.value"
                            @click="statusFilter = option.value"
                            :class="[
                                'whitespace-nowrap px-4 py-2 text-xs font-bold rounded-full border transition duration-150',
                                statusFilter === option.value
                                    ? 'bg-blue-100 border-blue-200 text-blue-700'
                                    : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'
                            ]"
                        >
                            {{ option.value === '' ? 'Todos los Estados' : option.label }}
                        </button>
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
                                                'bg-blue-100 text-blue-800': order.status === 'En proceso',
                                                'bg-green-100 text-green-800': order.status === 'Completado',
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
                    </div>
                </div>

                <!-- Tarjetas Responsivas Móviles -->
                <div class="block md:hidden space-y-4">
                    <div v-if="orders.data.length === 0" class="text-center py-10 text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                        No se encontraron órdenes.
                    </div>
                    <div
                        v-for="order in orders.data"
                        :key="order.id"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 relative"
                    >
                        <!-- Header: ID + Badge -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">ID DE ORDEN</span>
                                <span class="text-xl font-bold text-gray-900">#{{ order.id }}</span>
                            </div>
                            <span
                                :class="{
                                    'bg-amber-100 text-amber-800': order.status === 'Pendiente',
                                    'bg-blue-100 text-blue-800': order.status === 'En proceso',
                                    'bg-green-100 text-green-800': order.status === 'Completado',
                                    'bg-red-100 text-red-800': order.status === 'Cancelado',
                                }"
                                class="px-2.5 py-1 text-xs font-bold rounded-md uppercase tracking-wider"
                            >
                                {{ order.status }}
                            </span>
                        </div>

                        <!-- Body: Client Name, DNI, Date -->
                        <div class="space-y-2.5 mb-4 border-b border-gray-100 pb-3">
                            <h4 class="text-base font-bold text-gray-900 leading-tight">{{ order.customer.fullname }}</h4>
                            
                            <!-- DNI -->
                            <div class="flex items-center text-sm text-gray-600 gap-2">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.333 0 4 .667 4 2v1H5v-1c0-1.333 2.667-2 4-2z" />
                                </svg>
                                <span>{{ order.customer.dni }}</span>
                            </div>

                            <!-- Date -->
                            <div class="flex items-center text-sm text-gray-600 gap-2">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ new Date(order.created_at).toLocaleDateString('es-ES', { day: 'numeric', month: 'short', year: 'numeric' }) }}</span>
                            </div>

                            <!-- Equipment -->
                            <div class="text-xs bg-gray-50 border border-gray-100 rounded-lg p-2 text-gray-600 font-medium">
                                <span class="text-gray-400 block uppercase text-[9px] tracking-wider font-bold">Equipo/Dispositivo</span>
                                {{ order.name_equip }}
                            </div>
                        </div>

                        <!-- Footer: Actions -->
                        <div class="flex justify-between items-center">
                            <Link :href="route('orders.show', order.id)" class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                Ver Detalle <span class="text-xs font-bold">&gt;</span>
                            </Link>
                            <div class="flex items-center space-x-3 text-gray-500">
                                <!-- Edit -->
                                <Link v-if="can.edit_orders" :href="route('orders.edit', order.id)" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </Link>

                                <!-- Review / Pickup PDF icon -->
                                <div v-if="!order.reviews || order.reviews.length === 0" class="relative group">
                                    <span class="text-gray-300 cursor-not-allowed">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                </div>
                                <a v-else
                                    :href="route('orders.confirmPickup', order.id)"
                                    target="_blank"
                                    :class="{
                                        'text-gray-300 cursor-not-allowed pointer-events-none': order.status === 'Cancelado'
                                    }"
                                    class="text-green-600 hover:text-green-900"
                                    title="Confirmar Retiro y Generar PDF"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <button v-if="can.delete_orders" @click="confirmDelete(order.id)" class="text-red-500 hover:text-red-700" title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
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
    </AuthenticatedLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>