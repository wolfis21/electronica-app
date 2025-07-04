<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { watch, ref } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    products: Object, // Paginator object
    filters: Object,
    can: Object,
});

const searchForm = useForm({
    search: props.filters.search || '',
});

watch(() => searchForm.search, debounce(() => {
    searchForm.get(route('products.index'), {
        preserveState: true,
        replace: true,
    });
}, 300));

const confirmDelete = (productId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este producto/servicio? Esta acción es irreversible.')) {
        useForm({}).delete(route('products.destroy', productId), {
            preserveScroll: true,
            onSuccess: () => alert('Producto/Servicio eliminado con éxito.'),
            onError: () => alert('Error al eliminar el producto/servicio.'),
        });
    }
};
</script>

<template>
    <Head title="Gestión de Productos/Servicios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Productos y Servicios</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Listado de Productos y Servicios</h3>
                            <Link v-if="can.create_products" :href="route('products.create')" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Crear Nuevo
                            </Link>
                        </div>

                        <!-- Campo de búsqueda -->
                        <div class="mb-4">
                            <TextInput
                                id="search"
                                v-model="searchForm.search"
                                type="text"
                                class="mt-1 block w-1/3"
                                placeholder="Buscar por Nombre, Descripción o Código..."
                            />
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th> <!-- Nueva columna -->
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo</th> <!-- Cambiado de Precio a Costo -->
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Venta</th> <!-- Nueva columna -->
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                        <th v-if="can.edit_products || can.delete_products" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="product in products.data" :key="product.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ product.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ product.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ product.code || 'N/A' }}</td> <!-- Mostrar código -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{'bg-green-100 text-green-800': product.is_service, 'bg-blue-100 text-blue-800': !product.is_service}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ product.is_service ? 'Servicio' : 'Producto' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ product.price !== null ? parseFloat(product.price).toFixed(2) + ' $' : 'N/A' }}</td> <!-- Mostrar costo -->
                                        <td class="px-6 py-4 whitespace-nowrap">{{ parseFloat(product.price_sale).toFixed(2) }} $</td> <!-- Mostrar precio venta -->
                                        <td class="px-6 py-4 whitespace-nowrap">{{ product.stock !== null ? product.stock : 'N/A' }}</td>
                                        <td  v-if="can.edit_products || can.delete_products" class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-2">
                                            <Link v-if="can.edit_products" :href="route('products.edit', product.id)" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                </svg>
                                            </Link>
                                            <DangerButton v-if="can.delete_products" @click="confirmDelete(product.id)" title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.928a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.14-2.006-2.238a48.787 48.787 0 0 0-6.412 0c-1.096.098-2.006 1.058-2.006 2.238v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </DangerButton>
                                        </td>
                                    </tr>
                                    <tr v-if="products.data.length === 0">
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No se encontraron productos o servicios.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="mt-4 flex justify-between items-center" v-if="products.links.length > 3">
                            <Link v-for="link in products.links" :key="link.label" :href="link.url"
                                :class="{'bg-blue-500 text-white': link.active, 'text-gray-700': !link.active}"
                                class="px-3 py-2 leading-tight border rounded-md mr-1"
                                v-html="link.label">
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>