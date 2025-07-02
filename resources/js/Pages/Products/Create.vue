<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const form = useForm({
    name: '',
    code: '', // Nuevo campo
    description: '',
    price: null, // Ahora nullable
    price_sale: '', // Nuevo campo
    is_service: false,
    stock: null,
});

watch(() => form.is_service, (newValue) => {
    if (newValue) {
        form.stock = null;
    } else {
        form.stock = 0;
    }
});

const submit = () => {
    form.post(route('products.store'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Crear Producto/Servicio" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nuevo Producto o Servicio</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="name" value="Nombre" />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="code" value="Código (EAN/SKU)" /> <!-- Nuevo campo -->
                                <TextInput
                                    id="code"
                                    v-model="form.code"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError class="mt-2" :message="form.errors.code" />
                            </div>

                            <div>
                                <InputLabel for="price" value="Precio de Costo (Opcional)" /> <!-- Ahora opcional -->
                                <TextInput
                                    id="price"
                                    v-model="form.price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1 block w-full"
                                />
                                <InputError class="mt-2" :message="form.errors.price" />
                            </div>

                            <div>
                                <InputLabel for="price_sale" value="Precio de Venta" /> <!-- Nuevo campo, requerido -->
                                <TextInput
                                    id="price_sale"
                                    v-model="form.price_sale"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.price_sale" />
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <InputLabel for="description" value="Descripción" />
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="3"
                                ></textarea>
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
                                <TextInput
                                    id="stock"
                                    v-model="form.stock"
                                    type="number"
                                    min="0"
                                    class="mt-1 block w-full"
                                    :required="!form.is_service"
                                />
                                <InputError class="mt-2" :message="form.errors.stock" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Crear Producto/Servicio
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>