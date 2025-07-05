<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    payments: Object, // Paginación de pagos
    can: Object, // Objeto de permisos
});

const confirmDelete = (paymentId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este pago? Esta acción es irreversible.')) {
        // Lógica para eliminar el pago
        // Inertia.delete(route('payments.destroy', paymentId));
        console.log(`Eliminar pago con ID: ${paymentId}`);
        alert('Funcionalidad de eliminación no implementada en este ejemplo.');
    }
};

const formatStatus = (status) => {
    if (!status) return 'N/A';
    return status.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
};

const formatPaymentMethod = (method) => {
    if (!method) return 'N/A';
    return method.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
};
</script>

<template>
    <Head title="Pagos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Pagos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-end mb-6">
                        <Link v-if="can.create_payments" :href="route('payments.create')"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Registrar Nuevo Pago
                        </Link>
                    </div>

                    <div v-if="payments.data.length > 0" class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3 px-6">ID Pago</th>
                                    <th scope="col" class="py-3 px-6">Fecha</th>
                                    <th scope="col" class="py-3 px-6">Monto</th>
                                    <th scope="col" class="py-3 px-6">Método</th>
                                    <th scope="col" class="py-3 px-6">Referencia</th>
                                    <th scope="col" class="py-3 px-6">Estado</th>
                                    <th scope="col" class="py-3 px-6">Orden Asociada</th>
                                    <th scope="col" class="py-3 px-6">Cliente</th>
                                    <th scope="col" class="py-3 px-6">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="payment in payments.data" :key="payment.id" class="bg-white border-b hover:bg-gray-50">
                                    <td class="py-4 px-6">{{ payment.id }}</td>
                                    <td class="py-4 px-6">{{ new Date(payment.payment_date).toLocaleDateString() }}</td>
                                    <td class="py-4 px-6">{{ payment.amount }} {{ payment.currency }}</td>
                                    <td class="py-4 px-6">{{ formatPaymentMethod(payment.payment_method) }}</td>
                                    <td class="py-4 px-6">{{ payment.reference_number || 'N/A' }}</td>
                                    <td class="py-4 px-6">
                                        <span :class="{
                                            'bg-yellow-100 text-yellow-800': payment.status === 'pending',
                                            'bg-green-100 text-green-800': payment.status === 'completed',
                                            'bg-red-100 text-red-800': payment.status === 'failed',
                                            'bg-blue-100 text-blue-800': payment.status === 'refunded',
                                        }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize">
                                            {{ formatStatus(payment.status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <Link v-if="can.view_orders && payment.order" :href="route('orders.show', payment.order.id)" class="text-blue-600 hover:underline">
                                            #{{ payment.order.id }} - {{ payment.order.name_equip }}
                                        </Link>
                                        <span v-else-if="payment.order">#{{ payment.order.id }} - {{ payment.order.name_equip }}</span>
                                        <span v-else>Orden no disponible</span>
                                    </td>
                                    <td class="py-4 px-6">{{ payment.order?.customer?.fullname || 'N/A' }}</td>
                                    <td class="py-4 px-6 flex items-center space-x-2">
                                        <Link :href="route('payments.show', payment.id)" class="font-medium text-blue-600 hover:underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </Link>
                                        <Link v-if="can.edit_payments" :href="route('payments.edit', payment.id)" class="font-medium text-yellow-600 hover:underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>
                                        </Link>
                                        <DangerButton v-if="can.delete_payments" @click="confirmDelete(payment.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.928a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.14-2.006-2.238a48.787 48.787 0 0 0-6.412 0c-1.096.098-2.006 1.058-2.006 2.238v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </DangerButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="p-4 text-gray-600">
                        No hay pagos registrados aún.
                    </div>

                    <Pagination :links="payments.links" class="mt-6" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
