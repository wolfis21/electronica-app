<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed, watch, ref, onMounted } from 'vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    order: Object,
    review: Object, // La revisión a editar, con sus productos cargados
    products: Array, // Lista de todos los productos/servicios disponibles
});

const form = useForm({
    description_tec: props.review.description_tec,
    // Mapear los productos ya adjuntos para inicializar selected_products
    selected_products: props.review.products.map(p => ({
        id: p.id,
        name: p.name,
        price_sale: parseFloat(p.pivot.price_at_time_of_review), // Usar el precio registrado en el pivote
        is_service: p.is_service,
        stock: p.stock, // Stock actual del producto (no el del momento de la revisión)
        quantity: p.pivot.quantity,
    })),
});

const availableProducts = computed(() => {
    return props.products.map(product => ({
        value: product.id,
        label: `${product.name} (${product.is_service ? 'Servicio' : 'Producto'}) - ${parseFloat(product.price_sale).toFixed(2)}$`,
        price_sale: parseFloat(product.price_sale),
        is_service: product.is_service,
        stock: product.stock,
    }));
});

const currentSelectedProductId = ref(null);
const currentSelectedQuantity = ref(1);

const totalBudget = computed(() => {
    let sum = 0;
    form.selected_products.forEach(item => {
        sum += item.price_sale * item.quantity;
    });
    return sum.toFixed(2);
});

const addProduct = () => {
    if (currentSelectedProductId.value) {
        const productToAdd = availableProducts.value.find(p => p.value === currentSelectedProductId.value);
        if (productToAdd) {
            const existingProductIndex = form.selected_products.findIndex(p => p.id === productToAdd.value);

            if (existingProductIndex !== -1) {
                form.selected_products[existingProductIndex].quantity += currentSelectedQuantity.value;
            } else {
                form.selected_products.push({
                    id: productToAdd.value,
                    name: productToAdd.label.split('(')[0].trim(),
                    price_sale: productToAdd.price_sale,
                    is_service: productToAdd.is_service,
                    stock: productToAdd.stock,
                    quantity: currentSelectedQuantity.value,
                });
            }
            currentSelectedProductId.value = null;
            currentSelectedQuantity.value = 1;
        }
    }
};

const removeProduct = (index) => {
    form.selected_products.splice(index, 1);
};

const submit = () => {
    form.put(route('reviews.update', [props.order.id, props.review.id]));
};
</script>

<template>
    <Head :title="`Editar Revisión para Orden #${order.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Revisión para Orden #{{ order.id }} - Equipo: {{ order.name_equip }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <InputLabel for="description_tec" value="Descripción Técnica del Problema / Diagnóstico" />
                            <textarea
                                id="description_tec"
                                v-model="form.description_tec"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="5"
                                required
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description_tec" />
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Productos y Servicios para la Revisión</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 items-end">
                            <div>
                                <InputLabel for="product_select" value="Seleccionar Producto/Servicio" />
                                <select
                                    id="product_select"
                                    v-model="currentSelectedProductId"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >
                                    <option :value="null" disabled>Selecciona un producto o servicio</option>
                                    <option v-for="product in availableProducts" :key="product.value" :value="product.value">
                                        {{ product.label }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <InputLabel for="quantity" value="Cantidad" />
                                <TextInput
                                    id="quantity"
                                    v-model="currentSelectedQuantity"
                                    type="number"
                                    min="1"
                                    class="mt-1 block w-full"
                                    required
                                />
                            </div>
                            <PrimaryButton @click.prevent="addProduct" :disabled="!currentSelectedProductId || currentSelectedQuantity < 1">
                                Añadir
                            </PrimaryButton>
                        </div>

                        <InputError class="mt-2" :message="form.errors.selected_products" />

                        <div v-if="form.selected_products.length > 0" class="overflow-x-auto shadow-md sm:rounded-lg mb-4">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto/Servicio</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unitario Venta</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in form.selected_products" :key="item.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ item.name }}
                                            <span v-if="!item.is_service && item.stock !== null" :class="{'text-red-500': item.stock < item.quantity, 'text-green-500': item.stock >= item.quantity}" class="text-xs ml-2">
                                                (Stock: {{ item.stock }})
                                            </span>
                                            <span v-else-if="!item.is_service && item.stock === null" class="text-xs ml-2 text-gray-500">
                                                (Stock: N/A)
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ parseFloat(item.price_sale).toFixed(2) }} $</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <TextInput
                                                v-model="form.selected_products[index].quantity"
                                                type="number"
                                                min="1"
                                                class="w-20"
                                            />
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ (item.price_sale * item.quantity).toFixed(2) }} $</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <DangerButton @click.prevent="removeProduct(index)">Eliminar</DangerButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-gray-500 text-center py-4">
                            No se han añadido productos o servicios a la revisión.
                        </div>

                        <div class="flex justify-end mt-4 text-xl font-bold text-gray-900">
                            Presupuesto Total: {{ totalBudget }} $
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Actualizar Revisión
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>