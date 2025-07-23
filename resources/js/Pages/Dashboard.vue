<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PieChart from '@/Components/PieChart.vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref, onMounted, onUnmounted } from 'vue'; // Importa onMounted y onUnmounted

const props = defineProps({
    charts: Object,
    kpis: Object,
    lists: Object,
    filters: Object,
});

// Colores para las insignias de estado
const statusColors = {
    'Pendiente': 'bg-red-100 text-red-800',
    'En proceso': 'bg-yellow-100 text-yellow-800',
    'in_progress': 'bg-yellow-100 text-yellow-800',
};

// Variable reactiva para la fecha y hora seleccionada en el datepicker
const date = ref(new Date());

// Variable reactiva para la hora actual
const currentTime = ref('');
let timerId;

// Función para actualizar la hora actual
const updateTime = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
};

// Configura el intervalo para actualizar la hora cuando el componente se monta
onMounted(() => {
    updateTime(); // Actualiza inmediatamente
    timerId = setInterval(updateTime, 1000); // Actualiza cada segundo
});

// Limpia el intervalo cuando el componente se desmonta para evitar fugas de memoria
onUnmounted(() => {
    clearInterval(timerId);
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>Dashboard de Actividad</template>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Resumen General</h2>
            <div class="flex space-x-2">
                <Link v-for="period in [7, 30, 90]" :key="period" :href="route('dashboard', { period: period })"
                    :class="['px-4 py-2 text-sm font-semibold rounded-lg transition-colors', props.filters?.period === period ? 'bg-indigo-600 text-white shadow-md' : 'bg-white shadow-sm hover:bg-gray-50']"
                    preserve-scroll>
                Últimos {{ period }} Días
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
            <div class="p-6 bg-white rounded-xl shadow-md col-span-1 lg:col-span-1 xl:col-span-1">
                <h3 class="font-semibold text-gray-900 mb-4">Órdenes por Estado</h3>
                <div class="h-64">
                    <PieChart :chart-data="props.charts.orders_by_status" />
                </div>
            </div>

            <div class="p-6 bg-white rounded-xl shadow-md col-span-1 lg:col-span-1 xl:col-span-1">
                <h3 class="font-semibold text-gray-900 mb-4">Carga de Trabajo (Órdenes Abiertas)</h3>
                <div class="h-64">
                    <PieChart :chart-data="props.charts.orders_by_user" />
                </div>
            </div>
            <div class="space-y-4 col-span-1 lg:col-span-1 xl:col-span-1">
                <div class="p-6 bg-white rounded-xl shadow-md text-center">
                    <p class="text-gray-500 text-sm">Nuevas Órdenes</p>
                    <p class="text-4xl font-bold text-gray-800">{{ props.kpis.new_orders }}</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-md text-center">
                    <p class="text-gray-500 text-sm">Órdenes Completadas</p>
                    <p class="text-4xl font-bold text-green-600">{{ props.kpis.completed_orders }}</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-md text-center">
                    <p class="text-gray-500 text-sm">Ingresos Totales</p>
                    <p class="text-4xl font-bold text-indigo-600">${{ parseFloat(props.kpis.total_revenue).toFixed(2) }}
                    </p>
                </div>
            </div>

            <div class="p-6 bg-white rounded-xl shadow-md col-span-1 lg:col-span-1 xl:col-span-1 flex flex-col items-center justify-center">
                <p class="text-xl font-bold text-indigo-600 mb-4">{{ currentTime }}</p>
                <Datepicker v-model="date" class="justify-center" inline autoApply :enable-time-picker="false"></Datepicker>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-white rounded-xl shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Últimas Órdenes en Curso</h3>
                <div v-if="props.lists.orders_in_progress.length > 0" class="space-y-3">
                    <div v-for="order in props.lists.orders_in_progress" :key="order.id"
                        class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ order.name_equip }}</p>
                            <p class="text-xs text-gray-500">Asignado a: {{ order.user.name }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span
                                :class="['px-2 py-0.5 text-xs font-semibold rounded-full capitalize', statusColors[order.status] || 'bg-gray-200 text-gray-800']">
                                {{ order.status.replace(/_/g, ' ') }}
                            </span>
                            <Link :href="route('orders.show', order.id)"
                                class="text-sm text-indigo-600 hover:underline font-semibold">Ver</Link>
                        </div>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-500">No hay órdenes en curso.</p>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Empleados con Órdenes Activas</h3>
                <div v-if="props.lists.active_employees.length > 0" class="space-y-3">
                    <div v-for="employee in props.lists.active_employees" :key="employee.id"
                        class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900">{{ employee.name }}</p>
                        <span class="text-sm font-bold text-gray-700">{{ employee.orders_count }} <span
                                class="font-normal text-gray-500">órdenes</span></span>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-500">Ningún empleado tiene órdenes activas.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* Estilos adicionales para mejorar la apariencia del calendario si es necesario */
/* Puedes inspeccionar los elementos del Datepicker y aplicar estilos de Tailwind para personalizar */
/* Ejemplo: para cambiar el color de los días seleccionados o el hover */
.dp__active_date {
    background-color: #4f46e5 !important; /* Color de tu tema indigo-600 */
    color: white !important;
}

.dp__date_hover:hover {
    background-color: #e0e7ff !important; /* Un gris claro o un indigo-100 */
}

/* Ocultar el input del datepicker si solo quieres mostrar el calendario inline */
.dp__input_wrap {
    display: none;
}
</style>