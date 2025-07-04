<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';

const props = defineProps({
    payment: Object, // El objeto de pago a editar, con la orden ya cargada
});

const paymentForm = useForm({
    _method: 'put', // Importante para que Laravel reconozca la solicitud como PUT
    payment_date: props.payment.payment_date ? new Date(props.payment.payment_date).toISOString().slice(0, 10) : new Date().toISOString().slice(0, 10),
    amount: props.payment.amount,
    currency: props.payment.currency,
    payment_method: props.payment.payment_method,
    reference_number: props.payment.reference_number || '',
    note: props.payment.note || '',
    status: props.payment.status,
    orders_id: props.payment.orders_id, // El ID de la orden no se cambia en la edición
});

const submitPayment = () => {
    paymentForm.post(route('payments.update', props.payment.id), {
        onSuccess: () => {
            // No resetear el formulario, ya que estamos editando
            // Opcional: mostrar un mensaje de éxito o redirigir
        },
        onError: (errors) => {
            console.error('Error al actualizar pago:', errors);
        },
    });
};

// Opciones para los select
const currencies = ['USD', 'EUR', 'GBP', 'VES']; // Agrega más si es necesario
const paymentMethods = [
    { value: 'cash', label: 'Efectivo' },
    { value: 'card', label: 'Tarjeta' },
    { value: 'bank_transfer', label: 'Transferencia Bancaria' },
    { value: 'other', label: 'Otro' },
];
const paymentStatuses = [
    { value: 'pending', label: 'Pendiente' },
    { value: 'completed', label: 'Completado' },
    { value: 'failed', label: 'Fallido' },
    { value: 'refunded', label: 'Reembolsado' },
];
</script>

<template>
    <Head :title="`Editar Pago #${payment.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Pago #{{ payment.id }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                    <div v-if="payment.order" class="mb-8 p-4 border rounded-md bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Orden Asociada</h3>
                        <p class="text-gray-700"><strong>ID:</strong> #{{ payment.order.id }}</p>
                        <p class="text-gray-700"><strong>Equipo:</strong> {{ payment.order.name_equip }}</p>
                        <p class="text-gray-700"><strong>Cliente:</strong> {{ payment.order.customer?.fullname || 'N/A' }}</p>
                        <p class="text-gray-700"><strong>Estado de la Orden:</strong> {{ payment.order.status.replace(/_/g, ' ') }}</p>
                        <Link :href="route('orders.show', payment.order.id)" class="mt-2 inline-block text-blue-600 hover:underline text-sm">
                            Ver Detalles Completos de la Orden
                        </Link>
                    </div>
                    <div v-else class="mb-8 p-4 border rounded-md bg-gray-50 text-gray-600">
                        <p>No se pudo cargar la información de la orden asociada.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Editar Datos del Pago</h3>
                        <form @submit.prevent="submitPayment">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="payment_date" value="Fecha de Pago" />
                                    <TextInput
                                        id="payment_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="paymentForm.payment_date"
                                        required
                                    />
                                    <InputError :message="paymentForm.errors.payment_date" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="amount" value="Monto" />
                                    <TextInput
                                        id="amount"
                                        type="number"
                                        step="0.01"
                                        class="mt-1 block w-full"
                                        v-model="paymentForm.amount"
                                        required
                                    />
                                    <InputError :message="paymentForm.errors.amount" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="currency" value="Moneda" />
                                    <SelectInput
                                        id="currency"
                                        class="mt-1 block w-full"
                                        v-model="paymentForm.currency"
                                        :options="currencies"
                                        required
                                    />
                                    <InputError :message="paymentForm.errors.currency" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="payment_method" value="Método de Pago" />
                                    <SelectInput
                                        id="payment_method"
                                        class="mt-1 block w-full"
                                        v-model="paymentForm.payment_method"
                                        :options="paymentMethods"
                                        required
                                    />
                                    <InputError :message="paymentForm.errors.payment_method" class="mt-2" />
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <InputLabel for="reference_number" value="Número de Referencia (Opcional)" />
                                    <TextInput
                                        id="reference_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="paymentForm.reference_number"
                                    />
                                    <InputError :message="paymentForm.errors.reference_number" class="mt-2" />
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <InputLabel for="note" value="Notas (Opcional)" />
                                    <textarea
                                        id="note"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        rows="3"
                                        v-model="paymentForm.note"
                                    ></textarea>
                                    <InputError :message="paymentForm.errors.note" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="status" value="Estado del Pago" />
                                    <SelectInput
                                        id="status"
                                        class="mt-1 block w-full"
                                        v-model="paymentForm.status"
                                        :options="paymentStatuses"
                                        required
                                    />
                                    <InputError :message="paymentForm.errors.status" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <PrimaryButton :class="{ 'opacity-25': paymentForm.processing }" :disabled="paymentForm.processing">
                                    Actualizar Pago
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
