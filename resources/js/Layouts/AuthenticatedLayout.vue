<script setup>
import { ref, h, computed, onMounted, onUnmounted } from 'vue'; // Importa onMounted y onUnmounted
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

// --- ESTADO ---
const isSidebarOpen = ref(false);
const page = usePage();
const can = page.props.auth.user?.can || {};

// Verificar si el usuario es técnico
const isTechnician = computed(() => {
    return page.props.auth.user?.role_id === 3;
});

// --- LÓGICA PARA FECHA Y HORA (AÑADIDA AQUÍ) ---
const currentTime = ref('');
const currentDate = computed(() => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date().toLocaleDateString('es-ES', options);
});
let timerId;

// Función para actualizar la hora actual
const updateTime = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit' });
};

// Configura el intervalo para actualizar la hora cuando el componente se monta
onMounted(() => {
    updateTime(); // Actualiza inmediatamente
    timerId = setInterval(updateTime, 1000); // Actualiza cada segundo
});

// Limpia el intervalo cuando el componente se desmonta para evitar fugas de memoria
onUnmounted(() => {
    clearInterval(timerId);
});


// --- ICONOS ---
const createIcon = (renderFn) => ({ render: renderFn });
const MenuIcon = createIcon(() => h('svg', { class: 'w-6 h-6', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('line', { x1: "4", y1: "12", x2: "20", y2: "12" }), h('line', { x1: "4", y1: "6", x2: "20", y2: "6" }), h('line', { x1: "4", y1: "18", x2: "20", y2: "18" })]));
const DashboardIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('rect', { x: "3", y: "3", width: "7", height: "7" }), h('rect', { x: "14", y: "3", width: "7", height: "7" }), h('rect', { x: "14", y: "14", width: "7", height: "7" }), h('rect', { x: "3", y: "14", width: "7", height: "7" })]));
const ChartBarIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2' }, [h('path', { d: 'M12 20V10' }), h('path', { d: 'M18 20V4' }), h('path', { d: 'M6 20V16' })]));
const downloadIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2' }, [h('path', { d: 'M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4' }), h('polyline', { points: '7 10 12 15 17 10' }), h('line', { x1: '12', y1: '15', x2: '12', y2: '3' })]));
const ShieldCheckIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('path', { d: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' }), h('path', { d: 'm9 12 2 2 4-4' })]));
const BuildingIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('rect', { x: '4', y: '2', width: '16', height: '20', rx: '2', ry: '2' }), h('path', { d: 'M9 22v-4h6v4' }), h('path', { d: 'M8 6h.01' }), h('path', { d: 'M16 6h.01' }), h('path', { d: 'M12 6h.01' }), h('path', { d: 'M12 10h.01' }), h('path', { d: 'M12 14h.01' }), h('path', { d: 'M16 10h.01' }), h('path', { d: 'M16 14h.01' }), h('path', { d: 'M8 10h.01' }), h('path', { d: 'M8 14h.01' })]));
const UsersIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('path', { d: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2' }), h('circle', { cx: '9', cy: '7', r: '4' }), h('path', { d: 'M23 21v-2a4 4 0 0 0-3-3.87' }), h('path', { d: 'M16 3.13a4 4 0 0 1 0 7.75' })]));
const BriefcaseIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('rect', { x: '2', y: '7', width: '20', height: '14', rx: '2', ry: '2' }), h('path', { d: 'M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16' })]));
const DollarSignIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('line', { x1: '12', y1: '1', x2: '12', y2: '23' }), h('path', { d: 'M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6' })]));
const ExclamationTriangleIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('path', { d: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z' })]));
const LightBulbIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('path', { d: 'M9 21h6' }), h('path', { d: 'M12 17v4' }), h('path', { d: 'M12 3a5 5 0 0 1 5 5c0 1.5-.5 3-2 4l-3 1-3-1c-1.5-1-2-2.5-2-4a5 5 0 0 1 5-5z' })]));


// --- ESTRUCTURA DEL MENÚ ---
const menuStructure = computed(() => {
    if (isTechnician.value) {
        // Menú simplificado para técnicos
        return [
            {
                title: 'Principal',
                items: [
                    { name: 'Dashboard', icon: DashboardIcon, href: route('dashboard'), current: route().current('dashboard'), permission: true },
                ]
            },
            {
                title: 'Menu Contabilidad',
                items: [
                    { name: 'Órdenes', icon: BriefcaseIcon, href: route('orders.index'), current: route().current('orders.*'), permission: true },
                    { name: 'Clientes', icon: UsersIcon, href: route('customers.index'), current: route().current('customers.*'), permission: can.view_customers },
                    { name: 'Productos/Servicios', icon: BriefcaseIcon, href: route('products.index'), current: route().current('products.*'), permission: can.view_products },
                ]
            },
        ];
    }
    
    // Menú completo para otros roles
    return [
        {
            title: 'Principal',
            items: [
                { name: 'Dashboard', icon: DashboardIcon, href: route('dashboard'), current: route().current('dashboard'), permission: true },
                { name: 'Analítica', icon: ChartBarIcon, href: route('analytics.index'), current: route().current('analytics.index'), permission: true },
                { name: 'Importar/Exportar Datos', icon: downloadIcon, href: route('export.index'), current: route().current('export.index'), permission: true },
            ]
        },
        {
            title: 'Menu Administrador',
            items: [
                { name: 'Gestión de Roles', icon: ShieldCheckIcon, href: route('roles.index'), current: route().current('roles.*'), permission: can.manage_roles },
                { name: 'Gestión de Empresas', icon: BuildingIcon, href: route('companies.index'), current: route().current('companies.*'), permission: can.manage_companies },
                { name: 'Empleados/Usuarios', icon: UsersIcon, href: route('employees_users.index'), current: route().current('employees_users.*'), permission: can.view_users },
            ]
        },
        {
            title: 'Menu Contabilidad',
            items: [
                { name: 'Órdenes', icon: BriefcaseIcon, href: route('orders.index'), current: route().current('orders.*'), permission: true },
                { name: 'Clientes', icon: UsersIcon, href: route('customers.index'), current: route().current('customers.*'), permission: can.view_customers },
                { name: 'Productos/Servicios', icon: BriefcaseIcon, href: route('products.index'), current: route().current('products.*'), permission: can.view_products },
                { name: 'Gestión de Pagos', icon: DollarSignIcon, href: route('payments.index'), current: route().current('payments.*'), permission: can.view_payments },
            ]
        },
    ];
});

// --- MENÚ FILTRADO POR PERMISOS ---
const filteredMenuItems = computed(() => {
    return menuStructure.value
        .map(section => ({ ...section, items: section.items.filter(item => item.permission) }))
        .filter(section => section.items.length > 0);
});
</script>

<template>
    <div class="relative min-h-screen lg:flex">
        <div v-show="isSidebarOpen" @click="isSidebarOpen = false"
            class="fixed inset-0 z-20 bg-black opacity-50 lg:hidden" aria-hidden="true"></div>

        <aside :class="[
            'fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-gray-800 text-white transform transition-transform duration-300 ease-in-out',
            'lg:relative lg:translate-x-0',
            isSidebarOpen ? 'translate-x-0' : '-translate-x-full'
        ]">
            <div class="h-16 flex items-center justify-center border-b border-gray-700 px-4">
                <Link :href="route('dashboard')" class="text-xl font-semibold text-white truncate">
                Hola, {{ $page.props.auth.user.name }}
                </Link>
            </div>

            <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
                <div v-for="menu in filteredMenuItems" :key="menu.title" class="mb-4">
                    <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ menu.title }}
                    </p>
                    <template v-for="item in menu.items" :key="item.name">
                        <!-- Enlaces externos -->
                        <a v-if="item.external" :href="item.href" target="_blank" rel="noopener noreferrer" :class="[
                            'flex items-center space-x-3 py-2 px-4 rounded-lg transition-colors duration-200',
                            item.current ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                        ]">
                            <component :is="item.icon" class="h-5 w-5" />
                            <span>{{ item.name }}</span>
                            <svg class="ml-auto h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                        <!-- Enlaces internos -->
                        <Link v-else :href="item.href" :class="[
                            'flex items-center space-x-3 py-2 px-4 rounded-lg transition-colors duration-200',
                            item.current ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                        ]">
                            <component :is="item.icon" class="h-5 w-5" />
                            <span>{{ item.name }}</span>
                        </Link>
                    </template>
                </div>
            </nav>

            <div class="border-t border-gray-700 p-4 lg:hidden">
                <div>
                    <div class="font-medium text-base text-white">{{ $page.props.auth.user.name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ $page.props.auth.user.email }}</div>
                </div>
                <div class="mt-3 space-y-1 border-t border-gray-700 pt-3">
                    <DropdownLink :href="route('profile.edit')"
                        class="text-gray-300 hover:text-white hover:bg-gray-700 rounded-md"> Perfil </DropdownLink>
                    
                    <!-- Sección de Soporte para Móvil -->
                    <div class="py-2">
                        <p class="px-3 py-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Centro de Soporte
                        </p>
                        <a href="https://forms.google.com/tu-formulario-bugs-aqui" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md transition duration-150">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <span>Reportar Problema</span>
                            <svg class="ml-auto h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                        <a href="https://forms.google.com/tu-formulario-sugerencias-aqui" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md transition duration-150">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            <span>Enviar Sugerencia</span>
                            <svg class="ml-auto h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                    
                    <DropdownLink :href="route('logout')" method="post" as="button"
                        class="text-gray-300 hover:text-white hover:bg-gray-700 rounded-md"> Cerrar Sesión
                    </DropdownLink>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <button @click="isSidebarOpen = !isSidebarOpen"
                                class="lg:hidden mr-4 text-gray-500 hover:text-gray-700">
                                <MenuIcon />
                            </button>
                            <div class="font-semibold text-xl text-gray-800 leading-tight">
                                <slot name="header" />
                            </div>
                        </div>

                        <div class="hidden lg:flex items-center sm:ml-6">
                            <div class="text-right mr-6">
                                <div class="font-bold text-gray-700 text-lg">{{ currentTime }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ currentDate }}</div>
                            </div>

                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            class="flex items-center space-x-2 text-sm font-medium text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition duration-150">
                                            <div
                                                class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                                                {{ $page.props.auth.user.name.charAt(0) }}
                                            </div>
                                            <div class="hidden md:block">{{ $page.props.auth.user.name }}</div>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Perfil </DropdownLink>
                                        <div class="border-t border-gray-100"></div>
                                        <div class="px-4 py-2 text-xs text-gray-500 uppercase tracking-wider font-semibold">
                                            Centro de Soporte
                                        </div>
                                        <a href="https://forms.google.com/tu-formulario-bugs-aqui" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-red-50 hover:text-red-700 focus:outline-none focus:bg-red-50 focus:text-red-700 transition duration-150 ease-in-out">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                <span>Reportar Problema</span>
                                                <svg class="ml-auto h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </div>
                                        </a>
                                        <a href="https://forms.google.com/tu-formulario-sugerencias-aqui" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:bg-blue-50 focus:text-blue-700 transition duration-150 ease-in-out">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                                </svg>
                                                <span>Enviar Sugerencia</span>
                                                <svg class="ml-auto h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </div>
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                        <DropdownLink :href="route('logout')" method="post" as="button"> Cerrar Sesión
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-grow p-6 overflow-y-auto bg-gray-100">
                <slot />
            </main>
        </div>
    </div>
</template>