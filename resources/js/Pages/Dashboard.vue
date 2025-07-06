<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

// --- PROPS (Datos que vienen del controlador de Laravel) ---
// La página sigue recibiendo sus propios datos para mostrar en las tarjetas.
const props = defineProps({
  ordenesEnCurso: {
    type: Array,
    default: () => [
      { id: 'ORD-001', name: 'Orden #1', status: 'En progreso' },
      { id: 'ORD-002', name: 'Orden #2', status: 'Pendiente' },
      { id: 'ORD-003', name: 'Orden #3', status: 'En progreso' },
      { id: 'ORD-004', name: 'Orden #4', status: 'Completada' },
    ]
  },
  revisionesEnCurso: {
    type: Array,
    default: () => [
      { id: 'REV-101', team: 'Equipo #1', task: 'Revisión #1' },
      { id: 'REV-102', team: 'Equipo #2', task: 'Revisión de red' },
      { id: 'REV-103', team: 'Equipo #3', task: 'Revisión Pantalla' },
    ]
  },
  listaEmpleados: {
    type: Array,
    default: () => [
      { id: 'EMP-01', name: 'Jose Desilva', avatar: 'https://placehold.co/40x40/E2E8F0/4A5568?text=JD' },
      { id: 'EMP-02', name: 'Isaac Saado', avatar: 'https://placehold.co/40x40/E2E8F0/4A5568?text=IS' },
      { id: 'EMP-03', name: 'Maria Rodriguez', avatar: 'https://placehold.co/40x40/E2E8F0/4A5568?text=MR' },
    ]
  }
});

// --- LÓGICA LOCAL DE LA PÁGINA ---
const currentDate = computed(() => {
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  // Usando la fecha actual real.
  return new Date().toLocaleDateString('es-PA', options);
});

// --- COMPONENTE LOCAL (Solo para esta página) ---
const DashboardCard = {
  props: ['title'],
  template: `
    <div class="bg-white rounded-xl shadow-md p-6">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ title }}</h3>
      <div class="space-y-3">
        <slot></slot>
      </div>
    </div>
  `
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        
        <template #header>
            Dashboard de Actividad
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <DashboardCard title="Órdenes en Curso">
                <div v-for="order in props.ordenesEnCurso" :key="order.id" class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-700">{{ order.name }}</p>
                        <p class="text-xs text-gray-500">{{ order.id }}</p>
                    </div>
                    <span :class="['px-2 py-1 text-xs font-semibold leading-5 rounded-full capitalize', { 'bg-yellow-100 text-yellow-800': order.status === 'En progreso' }, { 'bg-red-100 text-red-800': order.status === 'Pendiente' }, { 'bg-green-100 text-green-800': order.status === 'Completada' }]">
                        {{ order.status }}
                    </span>
                </div>
            </DashboardCard>

            <DashboardCard title="Revisiones en curso">
                <div v-for="revision in props.revisionesEnCurso" :key="revision.id" class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-700">{{ revision.task }}</p>
                        <p class="text-xs text-gray-500">{{ revision.team }}</p>
                    </div>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Ver</a>
                </div>
            </DashboardCard>

            <DashboardCard title="Lista de Empleados">
                <div v-for="employee in props.listaEmpleados" :key="employee.id" class="flex items-center space-x-4 bg-gray-50 p-3 rounded-lg">
                    <img :src="employee.avatar" :alt="`Avatar de ${employee.name}`" class="h-10 w-10 rounded-full" />
                    <div>
                        <p class="font-medium text-gray-800">{{ employee.name }}</p>
                        <p class="text-xs text-gray-500">{{ employee.id }}</p>
                    </div>
                </div>
            </DashboardCard>

        </div>
    </AuthenticatedLayout>
</template>