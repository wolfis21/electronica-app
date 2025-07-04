<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    order: Object,
    customer: Object, // Prop para el cliente
    user: Object, // Prop para el usuario (empleado)
    can: Object, // Permisos del usuario actual
});

// Función para confirmar la eliminación de la orden
const confirmDelete = (orderId) => {
    if (confirm('¿Estás seguro de que quieres eliminar esta orden?')) {
        // Lógica para eliminar la orden, por ejemplo:
        // Inertia.delete(route('orders.destroy', orderId));
        console.log(`Eliminar orden con ID: ${orderId}`);
        alert('Funcionalidad de eliminación no implementada en este ejemplo.');
    }
};

// Función para confirmar la eliminación de la revisión
const confirmDeleteReview = (orderId, reviewId) => {
    if (confirm('¿Estás seguro de que quieres eliminar esta revisión?')) {
        // Lógica para eliminar la revisión
        // Inertia.delete(route('reviews.destroy', [orderId, reviewId]));
        console.log(`Eliminar revisión con ID: ${reviewId} de la orden ${orderId}`);
        alert('Funcionalidad de eliminación de revisión no implementada en este ejemplo.');
    }
};
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
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Información de la Orden</h3>
                        <p class="text-gray-700"><strong>Equipo:</strong> {{ order.name_equip }}</p>
                        <p class="text-gray-700"><strong>Serial:</strong> {{ order.serial }}</p>
                        <p class="text-gray-700"><strong>Descripción del Problema:</strong> {{ order.description }}</p>
                        <p class="text-gray-700"><strong>Accesorios:</strong> {{ order.accessories }}</p>
                        <p class="text-gray-700"><strong>Notas Extra:</strong> {{ order.extra_notes }}</p>
                        <p class="text-gray-700"><strong>Estado:</strong>
                            <span :class="{
                                'bg-blue-100 text-blue-800': order.status === 'En proceso',
                                'bg-green-100 text-green-800': order.status === 'Completado',
                                'bg-red-100 text-red-800': order.status === 'Cancelado',
                                'bg-yellow-100 text-yellow-800': order.status === 'Pendiente',
                                'bg-purple-100 text-purple-800': order.status === 'waiting_for_parts',
                            }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize">
                                {{ order.status.replace(/_/g, ' ') }}
                            </span>
                        </p>
                        <p class="text-gray-700"><strong>Fecha de Creación:</strong> {{ new Date(order.created_at).toLocaleString() }}</p>
                        <p class="text-gray-700"><strong>Última Actualización:</strong> {{ new Date(order.updated_at).toLocaleString() }}</p>
                    </div>

                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Información del Cliente</h3>
                        <p class="text-gray-700"><strong>Nombre:</strong> {{ customer.fullname }}</p>
                        <p class="text-gray-700"><strong>Empresa:</strong> {{ customer.name_company || 'N/A' }}</p>
                        <p class="text-gray-700"><strong>DNI:</strong> {{ customer.dni }}</p>
                        <p class="text-gray-700"><strong>Teléfono:</strong> {{ customer.phone }}</p>
                        <p class="text-gray-700"><strong>Email:</strong> {{ customer.email }}</p>
                        <p class="text-gray-700"><strong>Dirección:</strong> {{ customer.address }}</p>
                    </div>

                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Información del Empleado Responsable</h3>
                        <p class="text-gray-700"><strong>Nombre:</strong> {{ user.name }}</p>
                        <p class="text-700"><strong>Email:</strong> {{ user.email }}</p>
                    </div>

                    <!-- Sección para mostrar la revisión o el botón de crear -->
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Revisión de la Orden</h3>
                        <template v-if="order.reviews && order.reviews.length > 0">
                            <!-- Accede a la primera revisión en el array -->
                            <p class="text-gray-700"><strong>Fecha de Revisión:</strong> {{ new Date(order.reviews[0].created_at).toLocaleString() }}</p>
                            <p class="text-gray-700"><strong>Descripción Técnica:</strong> {{ order.reviews[0].description_tec || 'N/A' }}</p>
                            <p class="text-gray-700"><strong>Presupuesto:</strong> {{ order.reviews[0].budget || 'N/A' }}</p>

                            <!-- Mostrar productos/servicios asociados a la revisión -->
                            <div v-if="order.reviews[0].products && order.reviews[0].products.length > 0" class="mt-4">
                                <h4 class="text-md font-medium text-gray-800 mb-2">Productos/Servicios Utilizados:</h4>
                                <ul class="list-disc list-inside text-gray-700">
                                    <li v-for="product in order.reviews[0].products" :key="product.id">
                                        {{ product.name }} ({{ product.pivot.quantity }} unidades) - Precio al momento de la revisión: ${{ product.pivot.price_at_time_of_review }}
                                    </li>
                                </ul>
                            </div>
                            <p v-else class="text-gray-700 mt-2">No hay productos/servicios asociados a esta revisión.</p>

                            <div class="mt-4 flex justify-end space-x-2">
                                <Link v-if="can.view_reviews && can.edit_reviews" :href="route('reviews.show', [order.id, order.reviews[0].id])" class="inline-flex items-center px-4 py-2 bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-600 focus:bg-indigo-600 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Ver Detalles de Revisión
                                </Link>
                                <Link v-if="can.edit_reviews" :href="route('reviews.edit', [order.id, order.reviews[0].id])" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Editar Revisión
                                </Link>
                            </div>
                        </template>
                        <template v-else>
                            <p class="text-gray-700 mb-4">No hay una revisión asociada a esta orden.</p>
                            <Link v-if="can.create_reviews" :href="route('orders.reviews.create', order.id)" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Crear Revisión
                            </Link>
                        </template>
                    </div>

                    <div class="mt-6 flex justify-end space-x-2">
                        <Link v-if="can.edit_orders" :href="route('orders.edit', order.id)" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Editar Orden
                        </Link>
                        <DangerButton v-if="can.delete_orders" @click="confirmDelete(order.id)">
                            Eliminar Orden
                        </DangerButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
