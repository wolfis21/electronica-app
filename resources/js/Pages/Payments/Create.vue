<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';

// --- STATE MANAGEMENT ---
const paymentForm = useForm({
    orders_id: null,
    payment_date: new Date().toISOString().slice(0, 10),
    amount: '',
    currency: 'USD',
    payment_method: 'cash',
    reference_number: '',
    note: '',
    status: 'completed',
});

const searchQuery = ref('');
const searchResults = ref([]);
const selectedOrder = ref(null);
const isLoading = ref(false);

// --- STATE PARA TASAS DE CAMBIO ---
const bcvRate = ref(null);
const dateBcv = ref(null);
const ratesLoading = ref(true);
const ratesError = ref(null);

// --- LLAMADA A LA API AL MONTAR EL COMPONENTE ---
onMounted(async () => {
    try {
        ratesError.value = null;
        ratesLoading.value = true;
        const [bcvResponse] = await Promise.all([
            axios.get('https://pydolarve.org/api/v2/dollar')
        ]);

        bcvRate.value = bcvResponse.data.monitors.bcv.price;
        dateBcv.value = bcvResponse.data.datetime.date;

    } catch (error) {
        console.error("Error fetching exchange rates:", error);
        ratesError.value = "No se pudieron cargar las tasas de cambio.";
    } finally {
        ratesLoading.value = false;
    }
});


// --- ARRAYS PARA SELECTS ---
const currencies = ['USD', 'EUR', 'VES', 'COP'];
const paymentStatuses = [
    { value: 'completed', label: 'Completado' },
    { value: 'pending', label: 'Pendiente' },
    { value: 'failed', label: 'Fallido' },
    { value: 'refunded', label: 'Reembolsado' },
];

// --- COMPUTED PROPERTIES ---
const pendingBalance = computed(() => {
    if (!selectedOrder.value) return 0;
    return Number(selectedOrder.value.pending_balance);
});

// --- METHODS ---
const searchOrders = debounce(async () => {
    if (searchQuery.value.length < 2) {
        searchResults.value = [];
        return;
    }
    isLoading.value = true;
    try {
        const response = await axios.get(route('payments.searchOrdersForPayment', { query: searchQuery.value }));
        searchResults.value = response.data;
    } catch (error) {
        console.error("Error buscando órdenes:", error);
        searchResults.value = [];
    } finally {
        isLoading.value = false;
    }
}, 300);

const selectOrder = (order) => {
    selectedOrder.value = order;
    paymentForm.orders_id = order.id;
    searchQuery.value = `Orden #${order.id} - ${order.customer_name}`;
    searchResults.value = [];
};

const setFullPayment = () => {
    paymentForm.amount = pendingBalance.value.toFixed(2);
};

const resetAll = () => {
    paymentForm.reset();
    selectedOrder.value = null;
    searchQuery.value = '';
    searchResults.value = [];
};

const submitPayment = () => {
    paymentForm.post(route('payments.store'), {
        onSuccess: () => {
            alert('Pago registrado exitosamente.');
            resetAll();
        },
        onError: (errors) => {
            console.error('Error al crear pago:', errors);
        },
    });
};

// Formato específico para Bolívares
const formatVes = (amount) => {
    if (amount === null || typeof amount === 'undefined') return 'N/A';
    return new Intl.NumberFormat('es-VE', { style: 'currency', currency: 'VES' }).format(amount);
};
</script>

<template>
    <Head title="Registrar Pago" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Nuevo Pago</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 space-y-8">

                    <!-- Card: Referencia Cambiaria -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 flex items-center">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            Referencia Cambiaria (BCV)
                        </h3>
                        <div v-if="ratesLoading" class="mt-2 text-sm text-gray-500">Cargando tasas...</div>
                        <div v-else-if="ratesError" class="mt-2 text-sm text-red-600">{{ ratesError }}</div>
                        <dl v-else class="mt-2 grid grid-cols-2 gap-x-4">
                            <div>
                                <dt class="text-xs font-medium text-gray-500">Tasa USD - {{ dateBcv }}</dt>
                                <dd class="text-sm text-gray-900 font-mono">{{ formatVes(bcvRate) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- PASO 1: BUSCAR ORDEN -->
                    <div class="relative border-t pt-8">
                        <label for="search" class="block text-sm font-medium text-gray-700">Buscar Orden por ID o Cliente</label>
                        <input type="text" id="search" v-model="searchQuery" @input="searchOrders"
                               placeholder="Escribe para buscar..."
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        
                        <ul v-if="searchResults.length > 0" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg max-h-60 overflow-auto">
                            <li v-for="order in searchResults" :key="order.id" @click="selectOrder(order)"
                                class="px-4 py-3 cursor-pointer hover:bg-indigo-50 border-b last:border-b-0">
                                <p class="font-semibold text-gray-800">Orden #{{ order.id }} - {{ order.name_equip }}</p>
                                <p class="text-sm text-gray-600">{{ order.customer_name }}</p>
                                <p class="text-sm text-red-600 font-medium">Pendiente: ${{ Number(order.pending_balance).toFixed(2) }}</p>
                            </li>
                        </ul>
                        <p v-if="isLoading" class="text-sm text-gray-500 mt-2">Buscando...</p>
                    </div>

                    <!-- PASO 2: REGISTRAR PAGO -->
                    <div v-if="selectedOrder" class="border-t pt-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 p-6 rounded-lg mb-8">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Total a Pagar</h4>
                                <p class="text-2xl font-bold text-gray-800">${{ Number(selectedOrder.total_due).toFixed(2) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Total Pagado</h4>
                                <p class="text-2xl font-bold text-green-600">${{ Number(selectedOrder.total_paid).toFixed(2) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Saldo Pendiente</h4>
                                <p class="text-2xl font-bold text-red-600">${{ pendingBalance.toFixed(2) }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitPayment" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                                    <input type="date" id="payment_date" v-model="paymentForm.payment_date" required class="mt-1 block w-full input-style">
                                </div>
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Monto a Pagar</label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <input type="number" id="amount" v-model="paymentForm.amount" required step="0.01" :max="pendingBalance"
                                               class="flex-1 block w-full rounded-none rounded-l-md input-style">
                                        <button type="button" @click="setFullPayment"
                                                class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm hover:bg-gray-100">
                                            Pagar Completo
                                        </button>
                                    </div>
                                    <p v-if="paymentForm.errors.amount" class="text-sm text-red-600 mt-1">{{ paymentForm.errors.amount }}</p>
                                </div>
                                
                                <div>
                                    <label for="currency" class="block text-sm font-medium text-gray-700">Moneda del Pago</label>
                                    <select id="currency" v-model="paymentForm.currency" required class="mt-1 block w-full input-style">
                                        <option v-for="currency in currencies" :key="currency" :value="currency">{{ currency }}</option>
                                    </select>
                                    <p v-if="paymentForm.errors.currency" class="text-sm text-red-600 mt-1">{{ paymentForm.errors.currency }}</p>
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                    <select id="payment_method" v-model="paymentForm.payment_method" required class="mt-1 block w-full input-style">
                                        <option value="cash">Efectivo</option>
                                        <option value="card">Tarjeta</option>
                                        <option value="bank_transfer">Transferencia</option>
                                        <option value="other">Otro</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Estado del Pago</label>
                                    <select id="status" v-model="paymentForm.status" required class="mt-1 block w-full input-style">
                                        <option v-for="status in paymentStatuses" :key="status.value" :value="status.value">{{ status.label }}</option>
                                    </select>
                                    <p v-if="paymentForm.errors.status" class="text-sm text-red-600 mt-1">{{ paymentForm.errors.status }}</p>
                                </div>

                                <div>
                                    <label for="reference_number" class="block text-sm font-medium text-gray-700">N° de Referencia (Opcional)</label>
                                    <input type="text" id="reference_number" v-model="paymentForm.reference_number" class="mt-1 block w-full input-style">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="note" class="block text-sm font-medium text-gray-700">Notas (Opcional)</label>
                                    <textarea id="note" v-model="paymentForm.note" rows="3" class="mt-1 block w-full input-style"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-4">
                                <button type="button" @click="resetAll" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Cancelar</button>
                                <button type="submit" :disabled="paymentForm.processing"
                                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 disabled:opacity-50">
                                    Registrar Pago
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.input-style {
    @apply border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500;
}
</style>
