<script setup>
import { ref, computed, h } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// --- PROPS ---
// Estos datos vendrían de tu backend a través de Inertia.
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

// --- ESTADO REACTIVO ---
const isSidebarOpen = ref(false);

// --- PROPIEDADES COMPUTADAS ---
const currentDate = computed(() => {
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  return new Date().toLocaleDateString('es-ES', options);
});

// --- COMPONENTES LOCALES ---
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

// --- ICONOS SVG ---
const createIcon = (renderFn) => ({ render: renderFn });
const MenuIcon = createIcon(() => h('svg', { class: 'w-6 h-6', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [ h('line', { x1: "4", y1: "12", x2: "20", y2: "12" }), h('line', { x1: "4", y1: "6", x2: "20", y2: "6" }), h('line', { x1: "4", y1: "18", x2: "20", y2: "18" }) ]));
const UserIcon = createIcon(() => h('svg', { class: 'w-6 h-6', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [ h('path', { d: 'M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2' }), h('circle', { cx: '12', cy: '7', r: '4' }) ]));
const BellIcon = createIcon(() => h('svg', { class: 'w-6 h-6', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [ h('path', { d: 'M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9' }), h('path', { d: 'M13.73 21a2 2 0 0 1-3.46 0' }) ]));
const DashboardIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [ h('rect', { x: "3", y: "3", width: "7", height: "7" }), h('rect', { x: "14", y: "3", width: "7", height: "7" }), h('rect', { x: "14", y: "14", width: "7", height: "7" }), h('rect', { x: "3", y: "14", width: "7", height: "7" }) ]));
const BriefcaseIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [ h('rect', { x: '2', y: '7', width: '20', height: '14', rx: '2', ry: '2' }), h('path', { d: 'M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16' }) ]));
const UsersIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [ h('path', { d: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2' }), h('circle', { cx: '9', cy: '7', r: '4' }), h('path', { d: 'M23 21v-2a4 4 0 0 0-3-3.87' }), h('path', { d: 'M16 3.13a4 4 0 0 1 0 7.75' }) ]));
const BuildingIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [ h('rect', { x: '4', y: '2', width: '16', height: '20', rx: '2', ry: '2' }), h('path', { d: 'M9 22v-4h6v4' }), h('path', { d: 'M8 6h.01' }), h('path', { d: 'M16 6h.01' }), h('path', { d: 'M12 6h.01' }), h('path', { d: 'M12 10h.01' }), h('path', { d: 'M12 14h.01' }), h('path', { d: 'M16 10h.01' }), h('path', { d: 'M16 14h.01' }), h('path', { d: 'M8 10h.01' }), h('path', { d: 'M8 14h.01' }) ]));
const ShieldCheckIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [ h('path', { d: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' }), h('path', { d: 'm9 12 2 2 4-4' }) ]));
const LogoPlaceholderIcon = createIcon(() => h('svg', { class: 'h-10 w-auto text-white', viewBox: '0 0 48 48', fill: 'none', xmlns: 'http://www.w3.org/2000/svg' }, [ h('path', { d: 'M24 4C12.954 4 4 12.954 4 24s8.954 20 20 20 20-8.954 20-20S35.046 4 24 4zm0 6c2.21 0 4 1.79 4 4s-1.79 4-4 4-4-1.79-4-4 1.79-4 4-4zm0 24c-6.627 0-12-5.373-12-12h24c0 6.627-5.373 12-12 12z', fill: 'currentColor' }) ]));


// --- DATOS NAVEGACIÓN ---
// Lista de navegación unificada
const navItems = [
  { name: 'Dashboard', icon: DashboardIcon, href: route('dashboard'), current: route().current('dashboard') },
  { name: 'Gestión de Roles', icon: ShieldCheckIcon, href: route('roles.index'), current: route().current('roles.*') },
  { name: 'Gestión de Empresas', icon: BuildingIcon, href: route('companies.index'), current: route().current('companies.*') },
  { name: 'Empleados/Usuarios', icon: UsersIcon, href: '#', current: route().current('employees.*') },
  { name: 'Órdenes', icon: BriefcaseIcon, href: '#', current: false },
];
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <!-- Layout Principal del Dashboard -->
    <div class="flex h-screen bg-gray-100 font-sans">
      
      <!-- Barra Lateral (Sidebar) -->
      <aside 
        :class="[
          'bg-gray-800 text-white flex flex-col w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform transition-transform duration-200 ease-in-out z-30',
          isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
          'md:relative md:translate-x-0'
        ]"
      >
        <!-- Sección del Logo -->
        <div class="px-4 mb-2">
          <a href="#" class="flex items-center space-x-2">
            <!-- REEMPLAZA ESTE SVG CON TU LOGO -->
            <LogoPlaceholderIcon />
            <span class="text-2xl font-extrabold">Tu Empresa</span>
          </a>
        </div>

        <!-- Sección de Navegación -->
        <nav class="flex-1">
          <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestión</p>
          <div class="mt-2 space-y-1">
            <Link
              v-for="item in navItems"
              :key="item.name"
              :href="item.href"
              :class="[
                'flex items-center space-x-3 py-2.5 px-4 rounded-lg transition-colors duration-200',
                item.current ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white'
              ]"
            >
              <component :is="item.icon" />
              <span>{{ item.name }}</span>
          </Link>
          </div>
        </nav>
      </aside>

      <!-- Contenido Principal -->
      <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Encabezado del Contenido -->
        <header class="bg-white shadow-sm flex items-center justify-between p-4">
            <div class="flex items-center">
                <button @click="isSidebarOpen = !isSidebarOpen" class="text-gray-500 focus:outline-none md:hidden mr-4">
                    <MenuIcon />
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard de Actividad</h1>
                    <p class="text-sm text-gray-500 capitalize">{{ currentDate }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button class="relative text-gray-500 hover:text-gray-700">
                    <BellIcon />
                    <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <div class="flex items-center space-x-2">
                    <img src="https://placehold.co/40x40/7C3AED/FFFFFF?text=A" alt="Avatar de usuario" class="h-10 w-10 rounded-full" />
                    <div class="hidden sm:block">
                        <div class="font-semibold text-gray-700">Admin</div>
                        <div class="text-xs text-gray-500">Superusuario</div>
                    </div>
                </div>
                 <UserIcon class="text-gray-500 sm:hidden" />
            </div>
        </header>

        <!-- Área de Contenido con Scroll -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 md:p-8">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <DashboardCard title="Órdenes en Curso">
              <div v-for="order in props.ordenesEnCurso" :key="order.id" class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                <div>
                  <p class="font-medium text-gray-700">{{ order.name }}</p>
                  <p class="text-xs text-gray-500">{{ order.id }}</p>
                </div>
                <span :class="['px-2 py-1 text-xs font-semibold rounded-full', { 'bg-yellow-100 text-yellow-800': order.status === 'En progreso' }, { 'bg-red-100 text-red-800': order.status === 'Pendiente' }, { 'bg-green-100 text-green-800': order.status === 'Completada' }]">
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
                <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Ver</button>
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
        </main>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
