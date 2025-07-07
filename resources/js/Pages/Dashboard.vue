<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PieChart from '@/Components/Piechart.vue';

const props = defineProps({
    charts: Object,
    kpis: Object,
    lists: Object,
    filters: Object,
});

const statusColors = {
    'Pendiente': 'bg-red-100 text-red-800',
    'En progreso': 'bg-yellow-100 text-yellow-800',
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            Dashboard de Actividad
        </template>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Resumen General</h2>
            <div class="flex space-x-2">
                <Link v-for="period in [7, 30, 90]" :key="period"
                    :href="route('dashboard', { period: period })"
                    :class="['px-4 py-2 text-sm font-semibold rounded-lg transition-colors', props.filters?.period === period ? 'bg-indigo-600 text-white' : 'bg-white shadow-sm hover:bg-gray-50']"
                    preserve-scroll>
                    {{ period }} Días
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
             <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-white rounded-xl shadow-md">
                    <h3 class="font-semibold text-gray-900 mb-4">Órdenes por Estado</h3>
                    <div class="h-64">
                        <PieChart :chart-data="props.charts.orders_by_status" />
                    </div>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-md">
                    <h3 class="font-semibold text-gray-900 mb-4">Carga de Trabajo</h3>
                    <div class="h-64">
                        <PieChart :chart-data="props.charts.orders_by_user" />
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white rounded-xl shadow-md space-y-4">
                <h3 class="font-semibold text-gray-900 mb-2">Indicadores Clave</h3>
                <div class="text-center p-4 rounded-lg bg-gray-50">
                    <p class="text-gray-500 text-sm">Nuevas Órdenes</p>
                    <p class="text-3xl font-bold text-gray-800">{{ props.kpis.new_orders }}</p>
                </div>
                 <div class="text-center p-4 rounded-lg bg-gray-50">
                    <p class="text-gray-500 text-sm">Órdenes Completadas</p>
                    <p class="text-3xl font-bold text-green-600">{{ props.kpis.completed_orders }}</p>
                </div>
                <div class="text-center p-4 rounded-lg bg-gray-50">
                    <p class="text-gray-500 text-sm">Nuevos Clientes</p>
                    <p class="text-3xl font-bold text-gray-800">{{ props.kpis.new_customers }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
           <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Últimas Órdenes en Curso</h3>
                <div v-if="props.lists.orders_in_progress.length > 0" class="space-y-3">
                    <div v-for="order in props.lists.orders_in_progress" :key="order.id" class="flex items-center justify-between">
                        <p class="text-gray-700">{{ order.name_equip }}</p>
                        <span :class="['px-2 py-0.5 text-xs font-semibold rounded-full capitalize', statusColors[order.status] || 'bg-gray-200 text-gray-800']">
                            {{ order.status.replace(/_/g, ' ') }}
                        </span>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-500">No hay órdenes en curso.</p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Empleados con Órdenes Activas</h3>
                <div v-if="props.lists.active_employees.length > 0" class="space-y-3">
                    <div v-for="employee in props.lists.active_employees" :key="employee.id" class="flex items-center justify-between">
                        <p class="text-gray-700">{{ employee.name }}</p>
                        <span class="text-sm font-bold text-gray-800">{{ employee.orders_count }} <span class="font-normal text-gray-500">órdenes</span></span>
                    </div>
                </div>
                <p v-else class="text-sm text-gray-500">Ningún empleado tiene órdenes activas.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>