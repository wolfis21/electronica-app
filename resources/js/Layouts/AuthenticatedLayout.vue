<script setup>
import { ref, h, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

// --- ESTADO Y PROPIEDADES ---
const isSidebarOpen = ref(false);
const page = usePage();

// --- PERMISOS ---
const can = page.props.auth.user?.can || {};

// --- ICONOS ---
const createIcon = (renderFn) => ({ render: renderFn });
const DashboardIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('rect', { x: "3", y: "3", width: "7", height: "7" }), h('rect', { x: "14", y: "3", width: "7", height: "7" }), h('rect', { x: "14", y: "14", width: "7", height: "7" }), h('rect', { x: "3", y: "14", width: "7", height: "7" })]));
const BriefcaseIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('rect', { x: '2', y: '7', width: '20', height: '14', rx: '2', ry: '2' }), h('path', { d: 'M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16' })]));
const UsersIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('path', { d: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2' }), h('circle', { cx: '9', cy: '7', r: '4' }), h('path', { d: 'M23 21v-2a4 4 0 0 0-3-3.87' }), h('path', { d: 'M16 3.13a4 4 0 0 1 0 7.75' })]));
const BuildingIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('rect', { x: '4', y: '2', width: '16', height: '20', rx: '2', ry: '2' }), h('path', { d: 'M9 22v-4h6v4' }), h('path', { d: 'M8 6h.01' }), h('path', { d: 'M16 6h.01' }), h('path', { d: 'M12 6h.01' }), h('path', { d: 'M12 10h.01' }), h('path', { d: 'M12 14h.01' }), h('path', { d: 'M16 10h.01' }), h('path', { d: 'M16 14h.01' }), h('path', { d: 'M8 10h.01' }), h('path', { d: 'M8 14h.01' })]));
const ShieldCheckIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('path', { d: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' }), h('path', { d: 'm9 12 2 2 4-4' })]));
const DollarSignIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('line', { x1: '12', y1: '1', x2: '12', y2: '23' }), h('path', { d: 'M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6' })]));
const ChartBarIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '2' }, [h('path', { d: 'M12 20V10' }), h('path', { d: 'M18 20V4' }), h('path', { d: 'M6 20V16' })]));

// --- ESTRUCTURA DEL MENÚ ---
const menuStructure = [
    {
        title: 'Principal',
        items: [
            { name: 'Dashboard', icon: DashboardIcon, href: route('dashboard'), current: route().current('dashboard'), permission: true },
            { name: 'Analítica', icon: ChartBarIcon, href: '#', current: false, permission: true },
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
    }
];

// --- MENÚ FILTRADO POR PERMISOS ---
const filteredMenuItems = computed(() => {
    return menuStructure
        .map(section => ({
            ...section,
            items: section.items.filter(item => item.permission)
        }))
        .filter(section => section.items.length > 0);
});
</script>

<template>
    <div class="flex h-screen bg-gray-100">

        <aside class="hidden lg:flex w-64 flex-col bg-gray-800 text-white">
            <div class="h-16 flex items-center justify-center border-b border-gray-700 px-4">
                <Link :href="route('dashboard')" class="text-xl font-semibold text-white truncate">
                Hola, {{ $page.props.auth.user.name }}
                </Link>
            </div>
            <nav class="flex-1 px-2 py-4 space-y-2">
                <div v-for="menu in filteredMenuItems" :key="menu.title" class="mb-4">
                    <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ menu.title }}
                    </p>
                    <Link v-for="item in menu.items" :key="item.name" :href="item.href" :class="[
                        'flex items-center space-x-3 py-2 px-4 rounded-lg transition-colors duration-200',
                        item.current ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                    ]">
                    <component :is="item.icon" class="h-5 w-5" />
                    <span>{{ item.name }}</span>
                    </Link>
                </div>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="font-semibold text-xl text-gray-800 leading-tight">
                                <slot name="header" />
                            </div>
                        </div>
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="relative">
                                <button class="p-1 rounded-full text-gray-400 hover:text-gray-500 relative">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                                            <div>{{ $page.props.auth.user.name }}</div>
                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Perfil </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button"> Cerrar Sesión
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-grow p-6 overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>
</template>