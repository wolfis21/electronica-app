<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import DangerButton from '@/Components/DangerButton.vue';
import axios from 'axios';

const props = defineProps({
    order: Object,
    review: Object,
    products: Array,
    users: Array, 
});

// --- STATE DEL FORMULARIO PRINCIPAL ---
const form = useForm({
    description_tec: props.review.description_tec,
    user_id: props.review.user?.id || null,
    selected_products: props.review.products.map(p => ({
        id: p.id,
        name: p.name,
        price_sale: parseFloat(p.pivot.price_at_time_of_review),
        is_service: p.is_service,
        stock: p.stock,
        quantity: p.pivot.quantity,
    })),
});

// --- STATE LOCAL PARA LA LISTA DE PRODUCTOS ---
const localProducts = ref([...props.products]);

const availableProducts = computed(() => {
    return localProducts.value.map(product => ({
        value: product.id,
        label: `${product.name} (${product.is_service ? 'Servicio' : 'Producto'}) - ${parseFloat(product.price_sale).toFixed(2)}$`,
        price_sale: parseFloat(product.price_sale),
        is_service: product.is_service,
        stock: product.stock,
    })).sort((a, b) => a.label.localeCompare(b.label));
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
                form.selected_products[existingProductIndex].quantity += parseInt(currentSelectedQuantity.value, 10) || 1;
            } else {
                form.selected_products.push({
                    id: productToAdd.value,
                    name: productToAdd.label.split('(')[0].trim(),
                    price_sale: productToAdd.price_sale,
                    is_service: productToAdd.is_service,
                    stock: productToAdd.stock,
                    quantity: parseInt(currentSelectedQuantity.value, 10) || 1,
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


// --- LÓGICA DEL MODAL PARA CREAR PRODUCTO ---
const showCreateProductModal = ref(false);

const newProductForm = useForm({
    name: '',
    code: '',
    description: '',
    price: null,
    price_sale: '',
    is_service: false,
    stock: 0,
});

watch(() => newProductForm.is_service, (newValue) => {
    if (newValue) {
        newProductForm.stock = null;
    } else {
        newProductForm.stock = 0;
    }
});

const openCreateProductModal = () => {
    showCreateProductModal.value = true;
};

const closeCreateProductModal = () => {
    showCreateProductModal.value = false;
    newProductForm.reset();
    newProductForm.clearErrors();
};

const submitNewProduct = () => {
    const config = { headers: { 'Accept': 'application/json' } };
    axios.post(route('products.store'), newProductForm.data(), config)
        .then(response => {
            const newProduct = response.data;
            localProducts.value.push(newProduct);
            currentSelectedProductId.value = newProduct.id;
            closeCreateProductModal();
        })
        .catch(error => {
            if (error.response && error.response.status === 422) {
                newProductForm.setError(error.response.data.errors);
            } else {
                console.error('Error al crear el producto:', error);
                alert('Ocurrió un error inesperado. Revisa la consola.');
            }
        });
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
                            <textarea id="description_tec" v-model="form.description_tec" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="5" required></textarea>
                            <InputError class="mt-2" :message="form.errors.description_tec" />
                        </div>

                        <div class="mb-6">
                            <InputLabel for="user_id" value="Responsable de la Revisión" />
                            <select id="user_id" v-model="form.user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.user_id" />
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mt-6 mb-4">Productos y Servicios para la Revisión</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 items-end">
                            <div class="md:col-span-2">
                                <InputLabel for="product_select" value="Seleccionar Producto/Servicio" />
                                <div class="flex items-center space-x-2 mt-1">
                                    <select id="product_select" v-model="currentSelectedProductId" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option :value="null" disabled>Selecciona un producto o servicio</option>
                                        <option v-for="product in availableProducts" :key="product.value" :value="product.value">{{ product.label }}</option>
                                    </select>
                                    <PrimaryButton type="button" @click="openCreateProductModal" class="!p-2.5" title="Crear Nuevo Producto/Servicio">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                    </PrimaryButton>
                                </div>
                            </div>
                            <div>
                                <InputLabel for="quantity" value="Cantidad" />
                                <TextInput id="quantity" v-model="currentSelectedQuantity" type="number" min="1" class="mt-1 block w-full" required />
                            </div>
                        </div>
                         <div class="flex justify-end mb-4">
                             <PrimaryButton @click.prevent="addProduct" :disabled="!currentSelectedProductId || currentSelectedQuantity < 1">Añadir a la Lista</PrimaryButton>
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
                                        <td class="px-6 py-4 whitespace-nowrap">{{ item.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ parseFloat(item.price_sale).toFixed(2) }} $</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><TextInput v-model="form.selected_products[index].quantity" type="number" min="1" class="w-20"/></td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ (item.price_sale * item.quantity).toFixed(2) }} $</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><DangerButton @click.prevent="removeProduct(index)">Eliminar</DangerButton></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-gray-500 text-center py-4 border-2 border-dashed rounded-lg">
                            No se han añadido productos o servicios a la revisión.
                        </div>

                        <div class="flex justify-end mt-4 text-xl font-bold text-gray-900">Presupuesto Total: {{ totalBudget }} $</div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Actualizar Revisión</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL PARA CREAR NUEVO PRODUCTO/SERVICIO -->
        <div v-if="showCreateProductModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full flex items-center justify-center z-50 px-4">
            <div class="relative mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
                <form @submit.prevent="submitNewProduct">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 border-b pb-3">Crear Nuevo Producto o Servicio</h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="new_name" value="Nombre" />
                            <TextInput id="new_name" v-model="newProductForm.name" class="mt-1 block w-full" required autofocus />
                            <InputError :message="newProductForm.errors.name" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel for="new_code" value="Código (EAN/SKU)" />
                            <TextInput id="new_code" v-model="newProductForm.code" class="mt-1 block w-full" />
                            <InputError :message="newProductForm.errors.code" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel for="new_price" value="Precio de Costo (Opcional)" />
                            <TextInput id="new_price" v-model="newProductForm.price" type="number" step="0.01" min="0" class="mt-1 block w-full" />
                            <InputError :message="newProductForm.errors.price" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel for="new_price_sale" value="Precio de Venta" />
                            <TextInput id="new_price_sale" v-model="newProductForm.price_sale" type="number" step="0.01" min="0" class="mt-1 block w-full" required />
                            <InputError :message="newProductForm.errors.price_sale" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <InputLabel for="new_description" value="Descripción" />
                            <textarea id="new_description" v-model="newProductForm.description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3"></textarea>
                            <InputError :message="newProductForm.errors.description" class="mt-2" />
                        </div>
                        <div class="md:col-span-2 flex items-center">
                            <Checkbox id="new_is_service" v-model:checked="newProductForm.is_service" />
                            <InputLabel for="new_is_service" class="ms-2">Es un servicio (no tiene stock)</InputLabel>
                        </div>
                        <div v-if="!newProductForm.is_service">
                            <InputLabel for="new_stock" value="Stock" />
                            <TextInput id="new_stock" v-model="newProductForm.stock" type="number" min="0" class="mt-1 block w-full" :required="!newProductForm.is_service" />
                            <InputError :message="newProductForm.errors.stock" class="mt-2" />
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3 border-t pt-4">
                        <SecondaryButton type="button" @click="closeCreateProductModal">Cancelar</SecondaryButton>
                        <PrimaryButton :disabled="newProductForm.processing">Guardar</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
