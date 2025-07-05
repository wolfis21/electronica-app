<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    payment: Object, // Objeto de pago con la relación 'order' cargada
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
    <Head :title="`Detalles de Pago #${payment.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles de Pago #{{ payment.id }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Información del Pago</h3>
                        <p class="text-gray-700"><strong>ID Pago:</strong> {{ payment.id }}</p>
                        <p class="text-gray-700"><strong>Fecha de Pago:</strong> {{ new Date(payment.payment_date).toLocaleString() }}</p>
                        <p class="text-gray-700"><strong>Monto:</strong> {{ payment.amount }} {{ payment.currency }}</p>
                        <p class="text-gray-700"><strong>Método de Pago:</strong> {{ formatPaymentMethod(payment.payment_method) }}</p>
                        <p class="text-gray-700"><strong>Número de Referencia:</strong> {{ payment.reference_number || 'N/A' }}</p>
                        <p class="text-gray-700"><strong>Notas:</strong> {{ payment.note || 'N/A' }}</p>
                        <p class="text-gray-700"><strong>Estado:</strong>
                            <span :class="{
                                'bg-yellow-100 text-yellow-800': payment.status === 'pending',
                                'bg-green-100 text-green-800': payment.status === 'completed',
                                'bg-red-100 text-red-800': payment.status === 'failed',
                                'bg-blue-100 text-blue-800': payment.status === 'refunded',
                            }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize">
                                {{ formatStatus(payment.status) }}
                            </span>
                        </p>
                        <p class="text-gray-700"><strong>Creado el:</strong> {{ new Date(payment.created_at).toLocaleString() }}</p>
                        <p class="text-gray-700"><strong>Última Actualización:</strong> {{ new Date(payment.updated_at).toLocaleString() }}</p>
                    </div>

                    <div v-if="payment.order" class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Información de la Orden Asociada</h3>
                        <p class="text-gray-700"><strong>ID Orden:</strong>
                            <Link v-if="can.view_orders" :href="route('orders.show', payment.order.id)" class="text-blue-600 hover:underline">
                                #{{ payment.order.id }}
                            </Link>
                            <span v-else>#{{ payment.order.id }}</span>
                        </p>
                        <p class="text-gray-700"><strong>Equipo:</strong> {{ payment.order.name_equip }}</p>
                        <p class="text-gray-700"><strong>Serial:</strong> {{ payment.order.serial || 'N/A' }}</p>
                        <p class="text-gray-700"><strong>Descripción del Problema:</strong> {{ payment.order.description || 'N/A' }}</p>
                        <p class="text-gray-700"><strong>Cliente:</strong> {{ payment.order.customer?.fullname || 'N/A' }} (DNI: {{ payment.order.customer?.dni || 'N/A' }})</p>
                        <p class="text-gray-700"><strong>Responsable:</strong> {{ payment.order.user?.name || 'N/A' }}</p>
                        <p class="text-gray-700"><strong>Estado de la Orden:</strong> {{ payment.order.status.replace(/_/g, ' ') }}</p>
                    </div>
                    <div v-else class="mb-6 border-b pb-4 text-gray-600">
                        <p>No se encontró la orden asociada a este pago.</p>
                    </div>

                    <div class="mt-6 flex justify-end space-x-2">
                        <Link v-if="can.edit_payments" :href="route('payments.edit', payment.id)" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Editar Pago
                        </Link>
                        <DangerButton v-if="can.delete_payments" @click="confirmDelete(payment.id)">
                            Eliminar Pago
                        </DangerButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
