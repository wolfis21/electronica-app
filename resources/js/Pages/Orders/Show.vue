<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    order: Object, // La orden con sus relaciones customer y user ya cargadas
});
</script>

<template>
    <Head :title="`Detalles de Orden #${order.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles de Orden #{{ order.id }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="mb-6">
                        <Link :href="route('orders.index')" class="text-blue-600 hover:text-blue-900">
                            &larr; Volver a la Lista de Órdenes
                        </Link>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b pb-2">Datos del Cliente</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div>
                            <p class="text-gray-700 font-medium">Nombre Completo:</p>
                            <p class="text-gray-900">{{ order.customer.fullname }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 font-medium">Cédula/DNI:</p>
                            <p class="text-gray-900">{{ order.customer.dni }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 font-medium">Teléfono:</p>
                            <p class="text-gray-900">{{ order.customer.phone || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 font-medium">Dirección:</p>
                            <p class="text-gray-900">{{ order.customer.address || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 font-medium">Email:</p>
                            <p class="text-gray-900">{{ order.customer.email || 'N/A' }}</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b pb-2">Datos de la Orden</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div>
                            <p class="text-gray-700 font-medium">ID de Orden:</p>
                            <p class="text-gray-900">{{ order.id }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 font-medium">Usuario Responsable:</p>
                            <p class="text-gray-900">{{ order.user.name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 font-medium">Nombre del Equipo:</p>
                            <p class="text-gray-900">{{ order.name_equip }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 font-medium">Serial:</p>
                            <p class="text-gray-900">{{ order.serial || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 font-medium">Estado:</p>
                            <p class="text-gray-900">{{ order.status }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 font-medium">Fecha de Creación:</p>
                            <p class="text-gray-900">{{ new Date(order.created_at).toLocaleDateString() }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 font-medium">Última Actualización:</p>
                            <p class="text-gray-900">{{ new Date(order.updated_at).toLocaleDateString() }}</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <p class="text-gray-700 font-medium">Descripción del Problema:</p>
                        <p class="text-gray-900 p-2 bg-gray-50 rounded-md whitespace-pre-wrap">{{ order.description || 'N/A' }}</p>
                    </div>
                    <div class="mb-8">
                        <p class="text-gray-700 font-medium">Accesorios Incluidos:</p>
                        <p class="text-gray-900 p-2 bg-gray-50 rounded-md whitespace-pre-wrap">{{ order.accessories || 'N/A' }}</p>
                    </div>
                    <div class="mb-8">
                        <p class="text-gray-700 font-medium">Notas Adicionales:</p>
                        <p class="text-gray-900 p-2 bg-gray-50 rounded-md whitespace-pre-wrap">{{ order.extra_notes || 'N/A' }}</p>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <Link v-if="$page.props.auth.user.can.edit_all_orders" :href="route('orders.edit', order.id)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Editar Orden
                        </Link>
                        </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>