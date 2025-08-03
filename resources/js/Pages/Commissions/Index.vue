<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import pickBy from 'lodash/pickBy';
import axios from 'axios';

const props = defineProps({
    pendingCommissions: Array,
    history: Object,
    users: Array,
    filters: Object,
    current_period: String,
    start_date_formatted: String,
    end_date_formatted: String,
});

// --- LÓGICA DE FILTROS ---

const periodFilterForm = useForm({
    period: props.current_period,
});
const applyPeriodFilter = () => {
    const combinedFilters = { ...historyFilterForm.value, ...periodFilterForm.data() };
    router.get(route('commissions.index'), pickBy(combinedFilters), {
        preserveState: true,
        preserveScroll: true,
    });
};

const historyFilterForm = ref({
    filter_user_id: props.filters.filter_user_id || null,
});
watch(historyFilterForm, (newVal) => {
    const combinedFilters = { ...newVal, period: periodFilterForm.period };
     router.get(route('commissions.index'), pickBy(combinedFilters), {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}, { deep: true });


// --- LÓGICA DE PAGOS ---

const payCommission = (commission) => {
    if (confirm(`¿Pagar ${formatCurrency(commission.total_commission)} a ${commission.user_name}?`)) {
        const paymentForm = useForm({
            user_id: commission.user_id,
            amount: commission.total_commission,
            period_start: props.start_date_formatted,
            period_end: props.end_date_formatted,
        });
        paymentForm.post(route('commissions.payout'), { preserveScroll: true });
    }
};


// --- LÓGICA DEL MODAL DE DETALLES ---

const showDetailsModal = ref(false);
const modalDetails = ref([]);
const modalTitle = ref('');
const isLoadingModal = ref(false);

const viewPendingDetails = (commission) => {
    modalTitle.value = `Detalles para ${commission.user_name}`;
    modalDetails.value = commission.details;
    showDetailsModal.value = true;
};

const viewHistoryDetails = async (payout) => {
    modalTitle.value = `Detalles para ${payout.user.name} (${formatDate(payout.period_start)} - ${formatDate(payout.period_end)})`;
    isLoadingModal.value = true;
    showDetailsModal.value = true;
    
    const toYmd = (dateString) => new Date(dateString).toISOString().split('T')[0];

    try {
        const response = await axios.get(route('commissions.details', {
            user_id: payout.user.id,
            period_start: toYmd(payout.period_start),
            period_end: toYmd(payout.period_end),
        }));
        modalDetails.value = response.data;
    } catch (error) {
        console.error("Error al cargar los detalles:", error.response?.data || error.message);
        let errorMessage = "No se pudieron cargar los detalles.";
        if (error.response?.data?.message) {
            errorMessage += `\n\nError del servidor:\n${error.response.data.message}`;
        }
        alert(errorMessage);
        showDetailsModal.value = false;
    } finally {
        isLoadingModal.value = false;
    }
};

const closeModal = () => {
    showDetailsModal.value = false;
    modalDetails.value = [];
    modalTitle.value = '';
};


// --- FUNCIONES DE FORMATO ---

const formatCurrency = (value) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
const formatDate = (dateString) => new Date(dateString).toLocaleDateString('es-VE', { timeZone: 'America/Caracas' });

const bcvRate = ref(null);
const dateBcv = ref(null);
const ratesLoading = ref(true);
const ratesError = ref(null);

// Llamada a la API al montar el componente
onMounted(async () => {
    try {
        ratesError.value = null;
        ratesLoading.value = true;
        const response = await axios.get('https://pydolarve.org/api/v2/dollar');
        bcvRate.value = response.data.monitors.bcv.price;
        dateBcv.value = response.data.datetime.date;
    } catch (error) {
        console.error("Error fetching exchange rates:", error);
        ratesError.value = "No se pudieron cargar las tasas de cambio.";
    } finally {
        ratesLoading.value = false;
    }
});

const formatVes = (amount) => {
    if (amount === null || typeof amount === 'undefined') return 'N/A';
    return new Intl.NumberFormat('es-VE', { style: 'currency', currency: 'VES' }).format(amount);
};

</script>

<template>
    <Head title="Comisiones" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Módulo de Comisiones</h2>
        </template>
        <div class="py-12">
                        <!-- Card: Referencia Cambiaria -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
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
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <!-- SECCIÓN PARA COMISIONES PENDIENTES -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="applyPeriodFilter" class="flex items-end space-x-4 mb-6">
                        <div>
                            <label for="period_select" class="block text-sm font-medium text-gray-700">Seleccionar Período</label>
                            <select 
                                id="period_select"
                                v-model="periodFilterForm.period"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            >
                                <option value="first_half">Primera Quincena (1 - 15)</option>
                                <option value="second_half">Segunda Quincena (16 - Fin de Mes)</option>
                            </select>
                        </div>
                        <PrimaryButton :disabled="periodFilterForm.processing">
                            Filtrar
                        </PrimaryButton>
                    </form>

                    <div class="shadow-md sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr><th colspan="3" class="px-6 py-3 text-left text-sm font-semibold text-gray-800 bg-gray-200">Comisiones pendientes del {{ start_date_formatted }} al {{ end_date_formatted }}</th></tr>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Técnico</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Comisión a Pagar</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="commission in pendingCommissions" :key="commission.user_id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ commission.user_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-green-700">{{ formatCurrency(commission.total_commission) }}</div>
                                        <div v-if="bcvRate" class="text-xs text-gray-500">
                                            aprox. {{ formatVes(commission.total_commission * bcvRate) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-2 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button @click="viewPendingDetails(commission)" class="p-1 text-gray-500 hover:text-blue-600 focus:outline-none" title="Ver Detalles">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                            </button>
                                            <PrimaryButton @click="payCommission(commission)" v-if="commission.total_commission > 0">Pagar</PrimaryButton>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="pendingCommissions.length === 0"><td colspan="3" class="text-center p-4 text-gray-500">No hay comisiones pendientes para este período.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECCIÓN HISTORIAL DE PAGOS -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Historial de Pagos</h3>
                    <div class="mb-4">
                        <select v-model="historyFilterForm.filter_user_id" class="border-gray-300 rounded-md shadow-sm w-full md:w-1/3">
                            <option :value="null">Filtrar por técnico...</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Técnico Pagado</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Monto</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Período</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Fecha de Pago</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Pagado Por</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Detalles</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="payout in history.data" :key="payout.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ payout.user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center font-bold text-green-700">{{ formatCurrency(payout.amount) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(payout.period_start) }} - {{ formatDate(payout.period_end) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ formatDate(payout.paid_at) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ payout.payer.name }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <button @click="viewHistoryDetails(payout)" class="p-1 text-gray-500 hover:text-blue-600 focus:outline-none" title="Ver Detalles">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="history.data.length === 0"><td colspan="6" class="text-center p-4 text-gray-500">No se encontraron registros.</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination class="mt-6" :links="history.links" />
                </div>
            </div>
        </div>

        <!-- MODAL PARA MOSTRAR DETALLES -->
        <div v-if="showDetailsModal" @click.self="closeModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full flex items-center justify-center z-50 px-4">
            <div class="relative mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ modalTitle }}</h3>
                        <button @click="closeModal" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="mt-4 px-2 py-3">
                        <div v-if="isLoadingModal" class="text-gray-500 text-center py-8">Cargando detalles...</div>
                        <div v-else class="overflow-y-auto max-h-96 border rounded-lg">
                             <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Orden</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Rev.</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Comisión</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(detail, index) in modalDetails" :key="index">
                                        <td class="px-4 py-2 whitespace-nowrap">{{ detail.order_id }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ detail.review_id }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ detail.service_name }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-right">{{ formatCurrency(detail.service_price) }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-right font-semibold text-green-700">{{ formatCurrency(detail.commission_earned) }}</td>
                                    </tr>
                                     <tr v-if="!modalDetails || modalDetails.length === 0"><td colspan="5" class="text-center p-4 text-gray-500">No hay detalles para mostrar.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="items-center px-4 py-3 text-right">
                        <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
