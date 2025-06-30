<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    employee: Object,
    user: Object,
    roles: Array,
    currentRoleId: Number, // <-- Renombrado a currentRoleId
    companies: Array,
    can: Object,
});

const form = useForm({
    fullname: props.employee.fullname,
    dni: props.employee.dni,
    phone: props.employee.phone,
    address: props.employee.address,
    companies_id: props.employee.companies_id,
    email: props.user ? props.user.email : '',
    password: '',
    password_confirmation: '',
    role_id: props.currentRoleId, // <-- Inicializado con currentRoleId
});

const submit = () => {
    form.put(route('employees_users.update', props.employee.id), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head :title="`Editar ${props.employee.fullname}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Empleado y Usuario</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Datos del Empleado</h3>
                        <div>
                            <InputLabel for="fullname" value="Nombre Completo" />
                            <TextInput
                                id="fullname"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.fullname"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.fullname" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="dni" value="DNI" />
                            <TextInput
                                id="dni"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.dni"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.dni" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="phone" value="Teléfono" />
                            <TextInput
                                id="phone"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.phone"
                            />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="address" value="Dirección" />
                            <TextInput
                                id="address"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.address"
                            />
                            <InputError class="mt-2" :message="form.errors.address" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="companies_id" value="Compañía" />
                            <select
                                id="companies_id"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.companies_id"
                                required
                            >
                                <option value="" disabled>Seleccione una compañía</option>
                                <option v-for="company in props.companies" :key="company.id" :value="company.id">
                                    {{ company.name }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.companies_id" />
                        </div>

                        <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">Datos del Usuario</h3>

                        <div class="mt-4">
                            <InputLabel for="email" value="Email (Usuario)" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password" value="Nueva Contraseña (dejar en blanco para no cambiar)" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                autocomplete="new-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="password_confirmation" value="Confirmar Nueva Contraseña" />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                autocomplete="new-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="role_id" value="Rol" />
                            <select
                                id="role_id"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.role_id"
                                required
                            >
                                <option value="" disabled>Seleccione un rol</option>
                                <option v-for="role in props.roles" :key="role.id" :value="role.id">
                                    {{ role.name }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.role_id" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Actualizar Empleado y Usuario
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>