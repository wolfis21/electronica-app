<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import PieChart from '@/Components/Piechart.vue';

const props = defineProps({
    ordersAnalysis: Object,
    paymentsAnalysis: Object,
    employeePerformance: Array,
    filters: Object,
});

</script>

<template>

    <Head title="Analíticas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analíticas
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <div class="flex justify-end space-x-2">
                    <Link v-for="period in [7, 30, 90]" :key="period"
                        :href="route('analytics.index', { period: period })"
                        :class="['px-4 py-2 text-sm font-semibold rounded-lg transition-colors', props.filters?.period === period ? 'bg-indigo-600 text-white shadow-md' : 'bg-white shadow-sm hover:bg-gray-50']"
                        preserve-scroll>
                    Últimos {{ period }} Días
                    </Link>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Análisis de Órdenes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <div class="text-center p-6 rounded-lg bg-gray-50">
                            <p class="text-gray-500 text-sm">Órdenes Totales</p>
                            <p class="text-4xl font-bold text-gray-800">{{ props.ordersAnalysis.total }}</p>
                        </div>

                        <div class="md:col-span-2 p-4 h-80">
                            <h4 class="font-semibold text-center text-gray-700 mb-2">Desglose por Estado</h4>
                            <PieChart v-if="props.ordersAnalysis?.by_status?.data.length > 0"
                                :chart-data="props.ordersAnalysis.by_status" />
                            <div v-else class="flex items-center justify-center h-full text-gray-500">
                                No hay datos de órdenes en este período.
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Análisis de Pagos y Ventas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div class="space-y-6">
                                <div class="text-center p-6 rounded-lg bg-gray-50">
                                    <p class="text-gray-500 text-sm">Ingresos Totales</p>
                                    <p class="text-4xl font-bold text-green-600">${{
                                        props.paymentsAnalysis.total_revenue.toFixed(2) }}</p>
                                </div>
                                <div class="text-center p-6 rounded-lg bg-gray-50">
                                    <p class="text-gray-500 text-sm">Ticket Promedio</p>
                                    <p class="text-4xl font-bold text-gray-800">${{
                                        props.paymentsAnalysis.average_ticket.toFixed(2) }}</p>
                                </div>
                            </div>

                            <div class="md:col-span-2 p-4 h-80">
                                <h4 class="font-semibold text-center text-gray-700 mb-2">Ingresos por Método de Pago
                                </h4>
                                <PieChart v-if="props.paymentsAnalysis.by_method.data.length > 0"
                                    :chart-data="props.paymentsAnalysis.by_method" />
                                <div v-else class="flex items-center justify-center h-full text-gray-500">
                                    No hay datos de pagos en este período.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Rendimiento de Empleados</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Empleado</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Órdenes Completadas</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tiempo Promedio de Resolución</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-if="props.employeePerformance.length === 0">
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No hay datos
                                            de
                                            rendimiento en este período.</td>
                                    </tr>
                                    <tr v-for="employee in props.employeePerformance" :key="employee.name">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                                            employee.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                            employee.completed_orders_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                            employee.avg_completion_time_formatted }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>