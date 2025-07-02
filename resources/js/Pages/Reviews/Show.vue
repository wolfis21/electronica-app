<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    order: Object,
    review: Object, // La revisión con sus productos cargados
    can: Object,
});

const confirmDelete = () => {
    if (confirm('¿Estás seguro de que quieres eliminar esta revisión? Esta acción es irreversible y restaurará el stock de los productos.')) {
        useForm({}).delete(route('reviews.destroy', [props.order.id, props.review.id]), {
            preserveScroll: true,
            onSuccess: () => alert('Revisión eliminada con éxito.'),
            onError: () => alert('Error al eliminar la revisión.'),
        });
    }
};
</script>

<template>
    <Head :title="`Detalles de Revisión para Orden #${order.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles de Revisión para Orden #{{ order.id }} - Equipo: {{ order.name_equip }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Información de la Revisión</h3>
                        <p class="text-gray-700"><strong>Diagnóstico Técnico:</strong></p>
                        <p class="mt-1 p-3 bg-gray-50 rounded-md whitespace-pre-wrap">{{ review.description_tec }}</p>
                        <p class="mt-4 text-gray-700"><strong>Presupuesto Total:</strong> <span class="font-bold text-green-700 text-xl">{{ parseFloat(review.budget).toFixed(2) }} $</span></p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Productos y Servicios Aplicados</h3>
                        <div v-if="review.products.length > 0" class="overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto/Servicio</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unitario (momento revisión)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="product in review.products" :key="product.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ product.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{'bg-green-100 text-green-800': product.is_service, 'bg-blue-100 text-blue-800': !product.is_service}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ product.is_service ? 'Servicio' : 'Producto' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ parseFloat(product.pivot.price_at_time_of_review).toFixed(2) }} $</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ product.pivot.quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ (product.pivot.price_at_time_of_review * product.pivot.quantity).toFixed(2) }} $</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-gray-500 text-center py-4">
                            No hay productos o servicios asociados a esta revisión.
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <Link :href="route('orders.show', order.id)" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition ease-in-out duration-150">
                            Volver a la Orden
                        </Link>
                        <Link v-if="can.edit_reviews" :href="route('reviews.edit', [order.id, review.id])" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Editar Revisión
                        </Link>
                        <DangerButton v-if="can.delete_reviews" @click="confirmDelete()">
                            Eliminar Revisión
                        </DangerButton>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>