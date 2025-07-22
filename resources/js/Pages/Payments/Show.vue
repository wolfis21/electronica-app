<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    payment: Object, // Objeto de pago con la relación 'order' cargada
    can: Object, // Objeto de permisos
});

// Función de eliminación corregida para usar Inertia
const confirmDelete = (paymentId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este pago? Esta acción es irreversible y afectará el saldo de la orden.')) {
        router.delete(route('payments.destroy', paymentId), {
            preserveScroll: true,
        });
    }
};

// --- Helpers para formateo ---
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString('es-VE', { dateStyle: 'long', timeStyle: 'short' });
};

const formatCurrency = (amount, currency) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: currency || 'USD' }).format(amount);
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
            <div class="flex justify-between items-center">
                <!-- Izquierda: Botón de Volver y Título -->
                <div class="flex items-center gap-4">
                    <Link :href="route('payments.index')"
                          class="p-2 rounded-full text-gray-400 hover:bg-gray-200 hover:text-gray-600 transition-colors"
                          title="Volver al listado de pagos">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Detalles de Pago #{{ payment.id }}
                    </h2>
                </div>

                <!-- Derecha: Acciones Principales -->
                <div class="flex items-center space-x-2">
                    <Link v-if="can.edit_payments" :href="route('payments.edit', payment.id)"
                          class="inline-flex items-center px-3 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition">
                        Editar
                    </Link>
                    <DangerButton v-if="can.delete_payments" @click="confirmDelete(payment.id)">
                        Eliminar
                    </DangerButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- COLUMNA IZQUIERDA: DETALLES DEL PAGO -->
                    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                Información del Pago
                            </h3>
                            <dl class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Monto</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ formatCurrency(payment.amount, payment.currency) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                    <dd class="mt-1">
                                        <span :class="{
                                            'bg-yellow-100 text-yellow-800': payment.status === 'pending',
                                            'bg-green-100 text-green-800': payment.status === 'completed',
                                            'bg-red-100 text-red-800': payment.status === 'failed',
                                            'bg-blue-100 text-blue-800': payment.status === 'refunded',
                                        }" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize">
                                            {{ formatStatus(payment.status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Pago</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ formatDate(payment.payment_date) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Método de Pago</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ formatPaymentMethod(payment.payment_method) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Número de Referencia</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ payment.reference_number || 'N/A' }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Notas</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ payment.note || 'Ninguna.' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- COLUMNA DERECHA: ORDEN ASOCIADA -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div v-if="payment.order" class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                Orden Asociada
                            </h3>
                            <dl class="mt-4 space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ID Orden</dt>
                                    <dd class="mt-1 text-sm">
                                        <Link v-if="can.view_orders" :href="route('orders.show', payment.order.id)" class="text-blue-600 hover:underline font-semibold">
                                            #{{ payment.order.id }} - {{ payment.order.name_equip }}
                                        </Link>
                                        <span v-else class="text-gray-900 font-semibold">#{{ payment.order.id }} - {{ payment.order.name_equip }}</span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cliente</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ payment.order.customer?.fullname || 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Responsable</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ payment.order.user?.name || 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div v-else class="p-6 text-center text-gray-500">
                            <p>No se encontró la orden asociada a este pago.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
