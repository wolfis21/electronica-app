<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { ref } from 'vue';
import axios from 'axios'; // Para la búsqueda de órdenes

// Formulario para los datos del pago
const paymentForm = useForm({
    orders_id: null, // ID de la orden seleccionada
    payment_date: new Date().toISOString().slice(0, 10), // Fecha actual por defecto en formato YYYY-MM-DD
    amount: '',
    currency: 'USD', // Moneda por defecto
    payment_method: 'cash', // Método de pago por defecto
    reference_number: '',
    note: '',
    status: 'completed', // Estado por defecto
});

// Formulario para la búsqueda de la orden
const orderSearchForm = useForm({
    order_id: '', // Este campo se usará como el término de búsqueda 'query'
});

// Variable reactiva para almacenar la orden seleccionada después de la búsqueda
const selectedOrder = ref(null);
// Variable reactiva para mostrar errores de búsqueda de la orden
const orderSearchError = ref('');

/**
 * Función para buscar una orden por su ID.
 * Realiza una llamada AJAX al backend para obtener los detalles de la orden.
 */
const searchOrder = async () => {
    orderSearchError.value = ''; // Limpiar errores anteriores
    selectedOrder.value = null; // Limpiar orden seleccionada anterior
    paymentForm.orders_id = null; // Asegurarse de que el orders_id del formulario de pago esté nulo

    // Validar que se haya ingresado un ID de Orden
    if (!orderSearchForm.order_id) {
        orderSearchError.value = 'Por favor, introduce un ID de Orden.';
        return;
    }

    try {
        // Realiza la solicitud GET a la ruta 'payments.searchOrdersLive'
        // El método searchOrdersLive espera un parámetro 'query'
        const response = await axios.get(route('payments.searchOrdersLive', { query: orderSearchForm.order_id }));

        // El método searchOrdersLive devuelve un array de órdenes.
        // Si se busca por ID, asumimos que esperamos solo una o ninguna.
        if (response.data && response.data.length > 0) {
            selectedOrder.value = response.data[0]; // Tomamos la primera orden encontrada
            paymentForm.orders_id = selectedOrder.value.id; // Asigna el ID de la orden al formulario de pago
            orderSearchError.value = ''; // Limpia cualquier error anterior
        } else {
            // Si no se encontraron órdenes, muestra un mensaje de error
            orderSearchError.value = 'Orden no encontrada. Verifica el ID.';
            selectedOrder.value = null;
            paymentForm.orders_id = null;
        }

    } catch (error) {
        // Manejo de errores de la solicitud AJAX
        selectedOrder.value = null;
        paymentForm.orders_id = null;
        if (error.response) {
            if (error.response.status === 404) {
                orderSearchError.value = 'Orden no encontrada. Verifica el ID.';
            } else if (error.response.status === 403) {
                orderSearchError.value = 'Acceso denegado. Asegúrate de tener los permisos correctos y de estar autenticado.';
                console.error('Error 403: Acceso denegado. Posiblemente no autenticado o sin permisos.');
            } else if (error.response.data && error.response.data.errors && error.response.data.errors.query) {
                // Si hay errores de validación específicos del backend para 'query'
                orderSearchError.value = error.response.data.errors.query[0];
            } else {
                orderSearchError.value = 'Error al buscar la orden. Inténtalo de nuevo.';
            }
        } else {
            orderSearchError.value = 'Error de red o desconocido al buscar la orden.';
        }
        console.error('Error searching order:', error);
    }
};

/**
 * Función para enviar el formulario de registro de pago.
 */
const submitPayment = () => {
    // Envía el formulario de pago a la ruta 'payments.store'
    paymentForm.post(route('payments.store'), {
        onSuccess: () => {
            // Si el pago se crea exitosamente, resetear los formularios
            paymentForm.reset();
            selectedOrder.value = null;
            orderSearchForm.reset();
            alert('Pago registrado exitosamente.'); // Notificación de éxito
        },
        onError: (errors) => {
            // Manejo de errores en caso de fallo al crear el pago
            console.error('Error al crear pago:', errors);
            alert('Hubo un error al registrar el pago. Por favor, revisa los campos.'); // Notificación de error
        },
    });
};

// Opciones disponibles para los campos de selección (select)
const currencies = ['USD', 'EUR', 'GBP', 'VES']; // Lista de monedas
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
    <Head title="Registrar Pago" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Nuevo Pago</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                    <!-- Sección de Búsqueda de Orden -->
                    <div class="mb-8 p-4 border rounded-md bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 1: Buscar Orden</h3>
                        <form @submit.prevent="searchOrder" class="flex items-center space-x-4">
                            <div>
                                <InputLabel for="order_id" value="ID de Orden" />
                                <TextInput
                                    id="order_id"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="orderSearchForm.order_id"
                                    required
                                    autofocus
                                />
                                <!-- Muestra el error de búsqueda de la orden -->
                                <InputError :message="orderSearchError || orderSearchForm.errors.order_id" class="mt-2" />
                            </div>
                            <PrimaryButton :class="{ 'opacity-25': orderSearchForm.processing }" :disabled="orderSearchForm.processing" class="mt-6">
                                Buscar Orden
                            </PrimaryButton>
                        </form>

                        <!-- Muestra los detalles de la orden seleccionada si existe -->
                        <div v-if="selectedOrder" class="mt-6 p-4 border-t pt-4">
                            <h4 class="font-semibold text-gray-800 mb-2">Detalles de la Orden Seleccionada:</h4>
                            <p class="text-gray-700"><strong>ID:</strong> #{{ selectedOrder.id }}</p>
                            <p class="text-gray-700"><strong>Equipo:</strong> {{ selectedOrder.name_equip }}</p>
                            <p class="text-gray-700"><strong>Serial:</strong> {{ selectedOrder.serial || 'N/A' }}</p>
                            <p class="text-gray-700"><strong>Cliente:</strong> {{ selectedOrder.customer?.fullname || 'N/A' }} (DNI: {{ selectedOrder.customer?.dni || 'N/A' }})</p>
                            <p class="text-gray-700"><strong>Responsable:</strong> {{ selectedOrder.user?.name || 'N/A' }}</p>
                            <p class="text-gray-700"><strong>Estado de la Orden:</strong> {{ selectedOrder.status.replace(/_/g, ' ') }}</p>
                        </div>
                    </div>

                    <!-- Sección de Registro de Pago (solo se muestra si hay una orden seleccionada) -->
                    <div v-if="selectedOrder">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 2: Datos del Pago</h3>
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
                                <PrimaryButton :class="{ 'opacity-25': paymentForm.processing }" :disabled="paymentForm.processing || !selectedOrder">
                                    Registrar Pago
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
