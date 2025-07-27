<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    order: Object,
    customer: Object,
    user: Object,
    can: Object,
});

const hasReview = computed(() => props.order.reviews && props.order.reviews.length > 0);

// Helper para formatear fechas
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString('es-VE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head :title="`Detalles de Orden #${order.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <Link :href="route('orders.index')"
                        class="p-2 rounded-full text-gray-400 hover:bg-gray-200 hover:text-gray-600 transition-colors"
                        title="Volver al listado">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Detalles de Orden #{{ order.id }}
                    </h2>
                </div>

                <div class="flex items-center space-x-2">
                    <Link v-if="can.edit_orders"
                        :href="route('orders.edit', order.id)"
                        class="inline-flex items-center px-3 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition">
                        Editar
                    </Link>

                    <div v-if="!hasReview" class="relative inline-block group">
                        <span class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest opacity-50 cursor-not-allowed">
                            Confirmar Retiro
                        </span>
                        <div class="absolute top-full right-0 mt-2 w-max invisible group-hover:visible bg-gray-900 text-white text-xs rounded py-1 px-2 z-10">
                            Debes crear una revisión primero
                        </div>
                    </div>
                    <a v-else :href="route('orders.confirmPickup', order.id)" target="_blank"
                    :class="{ 'opacity-50 cursor-not-allowed pointer-events-none': order.status === 'Cancelado' }"
                    class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                        Confirmar Retiro
                    </a>

                    <a :href="route('orders.pdf', order.id)" target="_blank"
                    class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6m-6-4v-4m6 4v-4m-6-8H6a2 2 0 00-2 2v2a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2z" /></svg>
                        Imprimir
                    </a>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- COLUMNA IZQUIERDA -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Card: Información de la Orden -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                    Información de la Orden
                                </h3>
                                <dl class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                        <dd class="mt-1">
                                            <span :class="{
                                                'bg-blue-100 text-blue-800': order.status === 'En proceso',
                                                'bg-green-100 text-green-800': order.status === 'Completado',
                                                'bg-red-100 text-red-800': order.status === 'Cancelado',
                                                'bg-yellow-100 text-yellow-800': order.status === 'Pendiente',
                                                'bg-purple-100 text-purple-800': order.status === 'waiting_for_parts',
                                            }" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize">
                                                {{ order.status.replace(/_/g, ' ') }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Equipo</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ order.name_equip }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Serial</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ order.serial || 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Accesorios</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ order.accessories || 'Ninguno' }}</dd>
                                    </div>
                                    <div class="md:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Descripción del Problema</dt>
                                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ order.description || 'No especificada.' }}</dd>
                                    </div>
                                    <div class="md:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Notas Adicionales</dt>
                                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ order.extra_notes || 'Ninguna.' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Card: Revisión Técnica -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Revisión Técnica
                                </h3>
                                <div class="mt-4">
                                    <template v-if="hasReview">
                                        <Link v-if="can.view_reviews" :href="route('reviews.show', [order.id, order.reviews[0].id])" class="inline-flex items-center px-4 py-2 bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-600 transition">
                                            Ver Revisión Detallada
                                        </Link>
                                    </template>
                                    <template v-else>
                                        <p class="text-sm text-gray-500 mb-3">Esta orden aún no tiene una revisión técnica.</p>
                                        <Link v-if="can.create_reviews" :href="route('orders.reviews.create', order.id)" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 transition">
                                            Crear Revisión
                                        </Link>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- COLUMNA DERECHA -->
                    <div class="space-y-8">
                        <!-- Card: Información del Cliente -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    Información del Cliente
                                </h3>
                                <dl class="mt-4 space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ customer.fullname }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">DNI / RIF</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ customer.dni }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ customer.phone || 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ customer.email || 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Empresa</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ customer.name_company || 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Card: Empleado y Fechas -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    Responsable y Fechas
                                </h3>
                                <dl class="mt-4 space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Empleado Asignado</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ user.name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(order.created_at) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ formatDate(order.updated_at) }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
