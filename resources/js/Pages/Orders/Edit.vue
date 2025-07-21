<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    order: Object,
    responsibleUsers: Array, // Cambiado el nombre del prop
});

const form = useForm({
    // Utiliza ?. y ?? para asegurar que los valores sean seguros al inicializarse
    customer_fullname: props.order.customer?.fullname ?? '',
    customer_phone: props.order.customer?.phone ?? '',
    customer_address: props.order.customer?.address ?? '',
    customer_email: props.order.customer?.email ?? '',
    customer_name_company: props.order.customer?.name_company ?? '',

    name_equip: props.order.name_equip ?? '',
    serial: props.order.serial ?? '',
    description: props.order.description ?? '',
    accessories: props.order.accessories ?? '',
    extra_notes: props.order.extra_notes ?? '',
    status: props.order.status ?? 'Pendiente',
    users_id: props.order.users_id ?? null,
});
const submit = () => {
    form.put(route('orders.update', props.order.id));
};
</script>

<template>
    <Head title="Editar Orden" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Orden #{{ order.id }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <!-- Sección Cliente (se asume que el DNI no se edita directamente desde aquí) -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Datos del Cliente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="customer_dni_display" value="Cédula/DNI" />
                                <TextInput
                                    id="customer_dni_display"
                                    :model-value="order.customer.dni"
                                    type="text"
                                    class="mt-1 block w-full bg-gray-100"
                                    disabled
                                />
                            </div>
                            <div>
                                <InputLabel for="customer_fullname" value="Nombre Completo" />
                                <TextInput
                                    id="customer_fullname"
                                    v-model="form.customer_fullname"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.customer_fullname" />
                            </div>
                            <div>
                                <InputLabel for="customer_phone" value="Teléfono" />
                                <TextInput
                                    id="customer_phone"
                                    v-model="form.customer_phone"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError class="mt-2" :message="form.errors.customer_phone" />
                            </div>
                            <div>
                                <InputLabel for="customer_address" value="Dirección" />
                                <TextInput
                                    id="customer_address"
                                    v-model="form.customer_address"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError class="mt-2" :message="form.errors.customer_address" />
                            </div>
                            <div>
                                <InputLabel for="customer_email" value="Email" />
                                <TextInput
                                    id="customer_email"
                                    v-model="form.customer_email"
                                    type="email"
                                    class="mt-1 block w-full"
                                />
                                <InputError class="mt-2" :message="form.errors.customer_email" />
                            </div>
                            <div>
                                <InputLabel for="customer_name_company" value="Nombre de la Empresa (Opcional)" />
                                <TextInput
                                    id="customer_name_company"
                                    v-model="form.customer_name_company"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError class="mt-2" :message="form.errors.customer_name_company" />
                            </div>
                        </div>

                        <!-- Sección Orden -->
                        <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">Datos del Equipo y la Orden</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="name_equip" value="Nombre del Equipo" />
                                <TextInput
                                    id="name_equip"
                                    v-model="form.name_equip"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.name_equip" />
                            </div>

                            <div>
                                <InputLabel for="serial" value="Serial" />
                                <TextInput
                                    id="serial"
                                    v-model="form.serial"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError class="mt-2" :message="form.errors.serial" />
                            </div>

                            <div class="md:col-span-2">
                                <InputLabel for="description" value="Descripción del Problema" />
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="4"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div class="md:col-span-2">
                                <InputLabel for="accessories" value="Accesorios Incluidos" />
                                <textarea
                                    id="accessories"
                                    v-model="form.accessories"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="2"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.accessories" />
                            </div>

                            <div class="md:col-span-2">
                                <InputLabel for="extra_notes" value="Notas Adicionales" />
                                <textarea
                                    id="extra_notes"
                                    v-model="form.extra_notes"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="2"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.extra_notes" />
                            </div>

                            <div>
                                <InputLabel for="status" value="Estado" />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En proceso">En Progreso</option>
                                    <option value="Completado">Completada</option>
                                    <option value="Cancelado">Cancelada</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                            <div>
                                <InputLabel for="users_id" value="Usuario Responsable" />
                                <select
                                    id="users_id"
                                    v-model="form.users_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option v-for="user in responsibleUsers" :key="user.id" :value="user.id">
                                        {{ user.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.users_id" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Actualizar Orden
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>