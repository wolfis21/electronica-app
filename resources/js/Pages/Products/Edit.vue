<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { watch, ref, computed } from 'vue';

const props = defineProps({
    product: Object,
});

const form = useForm({
    name: props.product.name,
    code: props.product.code, // Nuevo campo
    description: props.product.description,
    price: props.product.price, // Ahora nullable
    price_sale: props.product.price_sale, // Nuevo campo
    is_service: props.product.is_service,
    stock: props.product.stock,
});

watch(() => form.is_service, (newValue) => {
    if (newValue) {
        form.stock = null;
    } else {
        form.stock = 0;
    }
});

const salePriceError = ref('');

watch(() => form.price_sale, (newValue) => {
    if (parseFloat(newValue) <= parseFloat(form.price)) {
        salePriceError.value = 'El precio de venta debe ser mayor al precio de costo.';
    } else {
        salePriceError.value = '';
    }
});

const submit = () => {
    if (parseFloat(form.price_sale) <= parseFloat(form.price)) {
        alert('El precio de venta debe ser mayor al precio de costo.');
        return;
    }
    form.put(route('products.update', props.product.id));
};


const isService = ref(false);
const costPrice = ref(0);

watch(() => form.price, (newValue) => {
    costPrice.value = newValue || 0; // Sincronizar precio de costo con costPrice
});

const calculatedSalePrice = computed(() => {
    return isService.value ? 0 : (costPrice.value / 0.70).toFixed(2); // Calcular precio con ganancia del 30%
});
</script>

<template>

    <Head :title="`Editar Producto/Servicio: ${product.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Producto o Servicio: {{ product.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="name" value="Nombre" />
                                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                                    autofocus />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="code" value="Código (EAN/SKU)" />
                                <TextInput id="code" v-model="form.code" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.code" />
                            </div>

                            <div>
                                <InputLabel for="price" value="Precio de Costo" />
                                <TextInput id="price" v-model="form.price" type="number" step="0.01" min="0"
                                    class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.price" />
                            </div>

                            <div>
                                <InputLabel for="price_sale" value="Precio de Venta" />
                                <TextInput id="price_sale" v-model="form.price_sale" type="number" step="0.01" min="0"
                                    class="mt-1 block w-full" required />
                                <InputError class="mt-2" :message="form.errors.price_sale || salePriceError" />
                                <!-- Precio Calculado -->
                                <div v-if="!form.is_service">
                                    <p class="text-sm text-gray-500">Precio de Venta Calculado: <span
                                            class="font-bold text-gray-800">${{ calculatedSalePrice }}</span></p>
                                </div>
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <InputLabel for="description" value="Descripción" />
                                <textarea id="description" v-model="form.description"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="3"></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="col-span-1 md:col-span-2 flex items-center mt-4">
                                <Checkbox id="is_service" v-model:checked="form.is_service" />
                                <InputLabel for="is_service" class="ms-2">
                                    Es un servicio (no tiene stock)
                                </InputLabel>
                                <InputError class="mt-2" :message="form.errors.is_service" />
                            </div>

                            <div v-if="!form.is_service">
                                <InputLabel for="stock" value="Stock" />
                                <TextInput id="stock" v-model="form.stock" type="number" min="0"
                                    class="mt-1 block w-full" :required="!form.is_service" />
                                <InputError class="mt-2" :message="form.errors.stock" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Actualizar Producto/Servicio
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>