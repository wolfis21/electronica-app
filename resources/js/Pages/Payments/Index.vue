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
                                        <Link :href="route('payments.show', payment.id)"
                                            class="font-medium text-blue-600 hover:underline">Ver</Link>
                                        <Link v-if="can.edit_payments" :href="route('payments.edit', payment.id)"
                                            class="font-medium text-yellow-600 hover:underline">Editar</Link>
                                        <DangerButton v-if="can.delete_payments" @click="confirmDelete(payment.id)">
                                            Eliminar
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
