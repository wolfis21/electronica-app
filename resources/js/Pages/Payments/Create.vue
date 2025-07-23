<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3'; // Importamos useForm
import { ref, computed, onMounted } from 'vue'; // Importamos ref, computed, onMounted
import axios from 'axios'; // Mantenemos axios para la tasa de cambio

// Componentes de UI
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

// --- STATE MANAGEMENT ---

// Mantenemos tu formulario original, pero lo renombramos para claridad
const form = useForm({
    orders_id: null,
    payment_date: new Date().toISOString().slice(0, 10),
    amount: '',
    currency: 'USD',
    payment_method: 'cash',
    reference_number: '',
    note: '',
    status: 'completed',
});

// Props que vienen del controlador (PaymentController@create)
const props = defineProps({
    eligibleOrders: {
        type: Array,
        required: true,
    },
});

// Estado para la UI
const selectedOrder = ref(null);
const searchQuery = ref(''); // Para filtrar la tabla
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
const currencies = ['USD', 'VES']; // Simplificado como en tu código original
const paymentStatuses = [
    { value: 'completed', label: 'Completado' },
    { value: 'pending', label: 'Pendiente' },
    { value: 'failed', label: 'Fallido' },
    { value: 'refunded', label: 'Reembolsado' },
];
const paymentMethods = [
    { value: 'cash', label: 'Efectivo' },
    { value: 'card', label: 'Tarjeta' },
    { value: 'bank_transfer', label: 'Transferencia' },
    { value: 'other', label: 'Otro' },
];

// --- COMPUTED PROPERTIES ---

// Filtra las órdenes elegibles basándose en la búsqueda del usuario
const filteredOrders = computed(() => {
    if (!searchQuery.value) {
        return props.eligibleOrders;
    }
    const lowerCaseQuery = searchQuery.value.toLowerCase();
    return props.eligibleOrders.filter(order =>
        order.id.toString().includes(lowerCaseQuery) ||
        order.name_equip.toLowerCase().includes(lowerCaseQuery) ||
        order.customer_name.toLowerCase().includes(lowerCaseQuery)
    );
});

const pendingBalance = computed(() => {
    if (!selectedOrder.value) return 0;
    return Number(selectedOrder.value.pending_balance);
});

const pendingBalanceInVes = computed(() => {
    if (pendingBalance.value > 0 && bcvRate.value) {
        const rate = Number(bcvRate.value);
        return pendingBalance.value * rate;
    }
    return null; // Retorna null si no se puede calcular
});

const setFullPayment = () => {
    form.amount = pendingBalance.value.toFixed(2);
};

const resetAll = () => {
    form.reset();
    selectedOrder.value = null;
    searchQuery.value = '';
};

const submit = () => {
    form.post(route('payments.store'), {
        onSuccess: () => {
            // Podrías usar un toast de notificación aquí
            resetAll();
        },
        onError: (errors) => {
            console.error('Error al crear pago:', errors);
        },
    });
};

const selectOrderForPayment = (order) => {
    selectedOrder.value = order;
    form.orders_id = order.id;
    form.amount = order.pending_balance.toFixed(2); // Sugiere el monto pendiente
    searchQuery.value = ''; // Limpia la búsqueda para evitar confusiones
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                    <!-- Card: Referencia Cambiaria -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-800">
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

                    <!-- SECCIÓN 1: LISTA DE ÓRDENES ELEGIBLES (Nuevo Flujo) -->
                    <div v-if="!selectedOrder" class="mt-8">
                        <div v-if="eligibleOrders.length > 0">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Seleccione una Orden para Registrar el Pago
                            </h3>
                            <TextInput
                                type="text"
                                class="mt-1 block w-full md:w-1/2 mb-4"
                                v-model="searchQuery"
                                placeholder="Buscar por ID, Equipo o Cliente..."
                            />
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipo</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Pendiente</th>
                                            <th scope="col" class="relative px-6 py-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                                        <tr v-for="order in filteredOrders" :key="order.id">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ order.id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ order.name_equip }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ order.customer_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-red-600 font-semibold">${{ order.pending_balance.toFixed(2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <PrimaryButton @click="selectOrderForPayment(order)">
                                                    Seleccionar
                                                </PrimaryButton>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-else class="text-center py-8 px-4 border-2 border-dashed border-gray-300 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay órdenes pendientes de pago</h3>
                            <p class="mt-1 text-sm text-gray-500">Todas las órdenes con revisiones han sido pagadas o no hay revisiones asignadas.</p>
                        </div>
                    </div>

                    <!-- PASO 2: REGISTRAR PAGO (Mantenemos tu diseño original) -->
                    <div v-if="selectedOrder" class="border-t border-gray-200 pt-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 p-6 rounded-lg mb-8 text-gray-800">
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
                                <!-- MONTO EN VES (se muestra si la tasa está disponible) -->
                                <p v-if="pendingBalanceInVes" class="text-sm text-gray-500 mt-1">
                                    aprox. {{ formatVes(pendingBalanceInVes) }}
                                </p>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <InputLabel for="payment_date" value="Fecha de Pago" />
                                    <TextInput type="date" id="payment_date" v-model="form.payment_date" required class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <InputLabel for="amount" value="Monto a Pagar" />
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <TextInput type="number" id="amount" v-model="form.amount" required step="0.01" :max="pendingBalance"
                                               class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                                        <button type="button" @click="setFullPayment"
                                                class="relative -ml-px inline-flex items-center space-x-2 rounded-r-md border border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                            Pagar Completo
                                        </button>
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.amount" />
                                </div>
                                
                                <div>
                                    <InputLabel for="currency" value="Moneda del Pago" />
                                    <select id="currency" v-model="form.currency" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option v-for="currency in currencies" :key="currency" :value="currency">{{ currency }}</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.currency" />
                                </div>

                                <div>
                                    <InputLabel for="payment_method" value="Método de Pago" />
                                    <select id="payment_method" v-model="form.payment_method" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option v-for="method in paymentMethods" :key="method.value" :value="method.value">{{ method.label }}</option>
                                    </select>
                                </div>

                                <div>
                                    <InputLabel for="status" value="Estado del Pago" />
                                    <select id="status" v-model="form.status" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option v-for="status in paymentStatuses" :key="status.value" :value="status.value">{{ status.label }}</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.status" />
                                </div>

                                <div>
                                    <InputLabel for="reference_number" value="N° de Referencia (Opcional)" />
                                    <TextInput type="text" id="reference_number" v-model="form.reference_number" class="mt-1 block w-full" />
                                </div>

                                <div class="md:col-span-2">
                                    <InputLabel for="note" value="Notas (Opcional)" />
                                    <TextareaInput id="note" v-model="form.note" rows="3" class="mt-1 block w-full"></TextareaInput>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-4">
                                <button type="button" @click="resetAll" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Cancelar</button>
                                <PrimaryButton :disabled="form.processing">
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
