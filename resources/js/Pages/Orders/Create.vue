<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    responsibleUsers: Array,
    currentUserId: Number,
});

const form = useForm({
    customer_dni: '',
    customer_fullname: '',
    customer_phone: '',
    customer_address: '',
    customer_email: '',
    name_equip: '',
    serial: '',
    description: '',
    accessories: '',
    extra_notes: '',
    status: 'pending',
    users_id: props.currentUserId,
});

const customerDniLoading = ref(false);
const customerFoundInDb = ref(false);

const searchCustomerByDni = async () => {
    if (form.customer_dni.length > 0) {
        customerDniLoading.value = true;
        try {
            const response = await fetch(route('customers.searchByDni', form.customer_dni));
            const data = await response.json();

            if (data.found) {
                customerFoundInDb.value = true;
                form.customer_fullname = data.customer.fullname;
                form.customer_phone = data.customer.phone;
                form.customer_address = data.customer.address;
                form.customer_email = data.customer.email;
            } else {
                customerFoundInDb.value = false;
                form.customer_fullname = '';
                form.customer_phone = '';
                form.customer_address = '';
                form.customer_email = '';
            }
        } catch (error) {
            console.error('Error al buscar cliente por DNI:', error);
            customerFoundInDb.value = false;
            form.customer_fullname = '';
            form.customer_phone = '';
            form.customer_address = '';
            form.customer_email = '';
        } finally {
            customerDniLoading.value = false;
        }
    } else {
        customerFoundInDb.value = false;
        form.customer_fullname = '';
        form.customer_phone = '';
        form.customer_address = '';
        form.customer_email = '';
    }
};

let dniTypingTimer = null;
watch(() => form.customer_dni, (newDni) => {
    clearTimeout(dniTypingTimer);
    if (newDni.length > 0) {
        dniTypingTimer = setTimeout(() => {
            searchCustomerByDni();
        }, 500);
    } else {
        customerFoundInDb.value = false;
        form.customer_fullname = '';
        form.customer_phone = '';
        form.customer_address = '';
        form.customer_email = '';
    }
});

const submit = () => {
    form.post(route('orders.store'), {
        onFinish: () => form.reset('customer_dni', 'customer_fullname', 'customer_phone', 'customer_address', 'customer_email'),
    });
};
</script>

<template>
    <Head title="Crear Orden" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nueva Orden</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <!-- Sección Cliente -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Datos del Cliente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="customer_dni" value="Cédula/DNI" />
                                <TextInput
                                    id="customer_dni"
                                    v-model="form.customer_dni"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    @blur="searchCustomerByDni"
                                />
                                <div v-if="customerDniLoading" class="text-sm text-gray-500 mt-1">Buscando cliente...</div>
                                <InputError class="mt-2" :message="form.errors.customer_dni" />
                                <p v-if="form.customer_dni && customerFoundInDb" class="text-sm text-green-600 mt-1">
                                    Cliente encontrado. Datos cargados.
                                </p>
                                <p v-else-if="form.customer_dni && !customerFoundInDb && !customerDniLoading" class="text-sm text-red-600 mt-1">
                                    Cliente no encontrado. Se creará uno nuevo.
                                </p>
                            </div>

                            <div>
                                <InputLabel for="customer_fullname" value="Nombre Completo" />
                                <TextInput
                                    id="customer_fullname"
                                    v-model="form.customer_fullname"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :required="!customerFoundInDb"
                                    :disabled="customerFoundInDb"
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
                                    :disabled="customerFoundInDb"
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
                                    :disabled="customerFoundInDb"
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
                                    :disabled="customerFoundInDb"
                                />
                                <InputError class="mt-2" :message="form.errors.customer_email" />
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
                                Crear Orden
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>