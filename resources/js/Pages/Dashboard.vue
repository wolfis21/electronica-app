<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PieChart from '@/Components/PieChart.vue';
import SupportSection from '@/Components/SupportSection.vue';
import { computed } from 'vue';

const props = defineProps({
    charts: Object,
    kpis: Object,
    lists: Object,
    filters: Object,
    isTechnician: Boolean,
});

const statusColors = {
    'Pendiente': 'bg-red-100 text-red-800 border-red-200',
    'En proceso': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'in_progress': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'Completada': 'bg-green-100 text-green-800 border-green-200',
    'Cancelada': 'bg-gray-100 text-gray-800 border-gray-200',
};

// Formatear ingresos con separadores de miles
const formattedRevenue = computed(() => {
    const rawValue = props.kpis?.total_revenue || 0;
    const numericValue = parseFloat(rawValue);
    return numericValue.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
});

// Texto del período para mostrar
const periodText = computed(() => {
    const period = props.filters?.period || 30;
    return `Últimos ${period} días`;
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Dashboard de Actividad
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">{{ periodText }}</p>
                </div>
                <div class="flex space-x-2">
                    <Link v-for="period in [7, 30, 90]" :key="period" 
                        :href="route('dashboard', { period: period })"
                        :class="['px-4 py-2 text-sm font-semibold rounded-lg transition-colors', props.filters?.period === period ? 'bg-indigo-600 text-white shadow-md' : 'bg-white shadow-sm hover:bg-gray-50 text-gray-700']"
                        preserve-scroll>
                        {{ period }}d
                    </Link>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- KPIs Principales -->
            <div :class="['grid gap-6', props.isTechnician ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1 md:grid-cols-3']">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-blue-100 text-sm">Nuevas Órdenes</p>
                            <p class="text-3xl font-bold">{{ props.kpis?.new_orders || 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-green-100 text-sm">Órdenes Completadas</p>
                            <p class="text-3xl font-bold">{{ props.kpis?.completed_orders || 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de ingresos solo para usuarios no técnicos -->
                <div v-if="!props.isTechnician" class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-xl shadow-lg text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-purple-100 text-sm">Ingresos Totales</p>
                            <p class="text-2xl font-bold">${{ formattedRevenue }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos de Análisis -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Órdenes por Estado</h3>
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="h-64">
                        <PieChart v-if="props.charts?.orders_by_status" :chart-data="props.charts.orders_by_status" />
                        <div v-else class="flex flex-col items-center justify-center h-full text-gray-500">
                            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-center">No hay datos de órdenes</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Carga de Trabajo por Técnico</h3>
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 9.288 0M15 7a3 3 0 11-6 0 3 3 0 6 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="h-64">
                        <PieChart v-if="props.charts?.orders_by_user" :chart-data="props.charts.orders_by_user" />
                        <div v-else class="flex flex-col items-center justify-center h-full text-gray-500">
                            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 9.288 0M15 7a3 3 0 11-6 0 3 3 0 6 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-center">No hay datos de carga de trabajo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Órdenes y Empleados Activos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Últimas Órdenes en Curso</h3>
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div v-if="props.lists?.orders_in_progress?.length > 0" class="space-y-4">
                        <div v-for="order in props.lists.orders_in_progress" :key="order.id"
                            class="p-4 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ order.name_equip }}</p>
                                            <p class="text-xs text-gray-500">Asignado a: {{ order.user?.name || 'Sin asignar' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span :class="['px-3 py-1 text-xs font-semibold rounded-full border capitalize', statusColors[order.status] || 'bg-gray-100 text-gray-800 border-gray-200']">
                                        {{ order.status.replace(/_/g, ' ') }}
                                    </span>
                                    <Link :href="route('orders.show', order.id)"
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full hover:bg-indigo-100 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-else class="flex flex-col items-center justify-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-center">No hay órdenes en curso</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Empleados con Órdenes Activas</h3>
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 9.288 0M15 7a3 3 0 11-6 0 3 3 0 6 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div v-if="props.lists?.active_employees?.length > 0" class="space-y-4">
                        <div v-for="(employee, index) in props.lists.active_employees" :key="employee.id"
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-4">
                                    <span class="text-sm font-medium text-indigo-600">{{ employee.name.charAt(0) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ employee.name }}</p>
                                    <p class="text-xs text-gray-500">Técnico activo</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="text-right">
                                    <span class="text-lg font-bold text-gray-900">{{ employee.orders_count }}</span>
                                    <span class="text-sm text-gray-500 ml-1">órdenes</span>
                                </div>
                                <div class="w-12 h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-green-500 rounded-full" 
                                         :style="`width: ${Math.min((employee.orders_count / Math.max(...props.lists.active_employees.map(e => e.orders_count))) * 100, 100)}%`"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-else class="flex flex-col items-center justify-center py-12 text-gray-500">
                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 9.288 0M15 7a3 3 0 11-6 0 3 3 0 6 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-center">Ningún empleado tiene órdenes activas</p>
                    </div>
                </div>
            </div>

            <!-- Sección de Soporte -->
            <SupportSection :additional-data="{ section: 'Dashboard', period: props.filters?.period }" />
        </div>
    </AuthenticatedLayout>
</template>
