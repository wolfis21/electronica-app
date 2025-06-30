<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Rol: {{ role.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Rol</label>
                                <input type="text" id="name" v-model="form.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <div v-if="form.errors.name" class="text-red-600 mt-1">{{ form.errors.name }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea id="description" v-model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                <div v-if="form.errors.description" class="text-red-600 mt-1">{{ form.errors.description }}</div>
                            </div>

                            <h4 class="text-lg font-medium text-gray-900 mt-6 mb-3">Asignar Permisos</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                <div v-for="permission in all_permissions" :key="permission.id" class="flex items-center">
                                    <input type="checkbox" :id="`perm-${permission.id}`" :value="permission.id" v-model="form.permissions" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label :for="`perm-${permission.id}`" class="ml-2 block text-sm text-gray-900">{{ permission.name }}</label>
                                </div>
                            </div>
                            <div v-if="form.errors.permissions" class="text-red-600 mt-1">{{ form.errors.permissions }}</div>

                            <div class="flex items-center justify-end mt-4">
                                <Link :href="route('roles.index')" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</Link>
                                <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700" :class="{ 'opacity-25': form.processing }">
                                    Actualizar Rol
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    role: Object, // El rol actual que se está editando
    all_permissions: Array, // Todos los permisos disponibles
});

const form = useForm({
    name: props.role.name,
    description: props.role.description,
    permissions: props.role.current_permissions || [], // IDs de los permisos actuales del rol
});

const submit = () => {
    form.put(route('roles.update', props.role.id));
};
</script>