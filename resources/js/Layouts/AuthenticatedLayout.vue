<script setup>
import { ref, h, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

// -----------------------------------------------------------------------------
// 1. ESTADO Y PROPIEDADES (State & Props)
// -----------------------------------------------------------------------------

// Controla si la barra lateral está abierta en móviles
const isSidebarOpen = ref(false);

// Obtenemos la información global de la página, incluyendo el usuario autenticado y sus permisos
const page = usePage();

// -----------------------------------------------------------------------------
// 2. PERMISOS (Permissions)
// -----------------------------------------------------------------------------

// Extraemos los permisos del objeto 'user'. Esto hace el código más limpio.
// Si `page.props.auth.user` es null (no logueado), `can` será undefined y las comprobaciones fallarán de forma segura.
const can = page.props.auth.user?.can || {};

// -----------------------------------------------------------------------------
// 3. ICONOS (Icons)
// -----------------------------------------------------------------------------

// Función para crear componentes de iconos SVG de forma limpia
const createIcon = (renderFn) => ({ render: renderFn });

const MenuIcon = createIcon(() => h('svg', { class: 'w-6 h-6', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('line', { x1: "4", y1: "12", x2: "20", y2: "12" }), h('line', { x1: "4", y1: "6", x2: "20", y2: "6" }), h('line', { x1: "4", y1: "18", x2: "20", y2: "18" })]));
const BellIcon = createIcon(() => h('svg', { class: 'w-6 h-6', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('path', { d: 'M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9' }), h('path', { d: 'M13.73 21a2 2 0 0 1-3.46 0' })]));
const DashboardIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('rect', { x: "3", y: "3", width: "7", height: "7" }), h('rect', { x: "14", y: "3", width: "7", height: "7" }), h('rect', { x: "14", y: "14", width: "7", height: "7" }), h('rect', { x: "3", y: "14", width: "7", height: "7" })]));
const BriefcaseIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('rect', { x: '2', y: '7', width: '20', height: '14', rx: '2', ry: '2' }), h('path', { d: 'M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16' })]));
const UsersIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('path', { d: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2' }), h('circle', { cx: '9', cy: '7', r: '4' }), h('path', { d: 'M23 21v-2a4 4 0 0 0-3-3.87' }), h('path', { d: 'M16 3.13a4 4 0 0 1 0 7.75' })]));
const BuildingIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('rect', { x: '4', y: '2', width: '16', height: '20', rx: '2', ry: '2' }), h('path', { d: 'M9 22v-4h6v4' }), h('path', { d: 'M8 6h.01' }), h('path', { d: 'M16 6h.01' }), h('path', { d: 'M12 6h.01' }), h('path', { d: 'M12 10h.01' }), h('path', { d: 'M12 14h.01' }), h('path', { d: 'M16 10h.01' }), h('path', { d: 'M16 14h.01' }), h('path', { d: 'M8 10h.01' }), h('path', { d: 'M8 14h.01' })]));
const ShieldCheckIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('path', { d: 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' }), h('path', { d: 'm9 12 2 2 4-4' })]));
const LogoPlaceholderIcon = createIcon(() => h('svg', { class: 'h-10 w-auto text-white', viewBox: '0 0 48 48', fill: 'none', xmlns: 'http://www.w3.org/2000/svg' }, [h('path', { d: 'M24 4C12.954 4 4 12.954 4 24s8.954 20 20 20 20-8.954 20-20S35.046 4 24 4zm0 6c2.21 0 4 1.79 4 4s-1.79 4-4 4-4-1.79-4-4 1.79-4 4-4zm0 24c-6.627 0-12-5.373-12-12h24c0 6.627-5.373 12-12 12z', fill: 'currentColor' })]));
const DollarSignIcon = createIcon(() => h('svg', { class: 'h-5 w-5', viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "2" }, [h('line', { x1: '12', y1: '1', x2: '12', y2: '23' }), h('path', { d: 'M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6' })]));


// -----------------------------------------------------------------------------
// 4. ESTRUCTURA DEL MENÚ (Navigation Structure)
// -----------------------------------------------------------------------------

// Esta es la estructura COMPLETA e IDEAL de nuestro menú.
// Más abajo, usaremos una 'propiedad computada' para filtrarla según los permisos.
const menuStructure = [
    {
        title: 'Principal',
        items: [
            { name: 'Dashboard', icon: DashboardIcon, href: route('dashboard'), current: route().current('dashboard'), permission: true }, // Siempre visible
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
            { name: 'Órdenes', icon: BriefcaseIcon, href: route('orders.index'), current: route().current('orders.*'), permission: true }, // Asumiendo que todos ven órdenes
            { name: 'Clientes', icon: UsersIcon, href: route('customers.index'), current: route().current('customers.*'), permission: can.view_customers },
            { name: 'Productos/Servicios', icon: BriefcaseIcon, href: route('products.index'), current: route().current('products.*'), permission: can.view_products },
            { name: 'Gestión de Pagos', icon: DollarSignIcon, href: route('payments.index'), current: route().current('payments.*'), permission: can.view_payments },
        ]
    }
];

// -----------------------------------------------------------------------------
// 5. MENÚ FILTRADO (The Magic: Filtered Menu)
// -----------------------------------------------------------------------------
// Esta propiedad computada es la clave. Filtra la estructura del menú
// para que solo contenga los ítems y secciones que el usuario actual tiene permiso para ver.
const filteredMenuItems = computed(() => {
    return menuStructure
        .map(section => {
            // Filtramos los items de cada sección según su permiso
            const visibleItems = section.items.filter(item => item.permission);

            // Retornamos la sección solo si tiene items visibles
            return { ...section, items: visibleItems };
        })
        .filter(section => section.items.length > 0); // Eliminamos las secciones que quedaron vacías
});

</script>

<template>
    <div class="flex h-screen bg-gray-100 font-sans">

        <aside :class="[
            'bg-gray-800 text-white flex flex-col w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform transition-transform duration-200 ease-in-out z-30',
            isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
            'md:relative md:translate-x-0'
        ]">
            <div class="px-4 mb-2">
                <Link :href="route('dashboard')" class="flex items-center space-x-2">
                <LogoPlaceholderIcon />
                <span class="text-xl font-semibold text-white">
                    Hola, {{ $page.props.auth.user.name }}
                </span>
                </Link>
            </div>

            <nav class="flex-1">
                <div v-for="menu in filteredMenuItems" :key="menu.title" class="mb-6">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ menu.title }}</p>
                    <div class="mt-2 space-y-1">
                        <Link v-for="item in menu.items" :key="item.name" :href="item.href" :class="[
                            'flex items-center space-x-3 py-2.5 px-4 rounded-lg transition-colors duration-200',
                            item.current ? 'bg-gray-900 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white'
                        ]">
                        <component :is="item.icon" />
                        <span>{{ item.name }}</span>
                        </Link>
                    </div>
                </div>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white shadow-sm flex items-center justify-between p-4">
                <div class="flex items-center">
                    <button @click="isSidebarOpen = !isSidebarOpen"
                        class="text-gray-500 focus:outline-none md:hidden mr-4">
                        <MenuIcon />
                    </button>
                    <div class="font-semibold text-xl text-gray-800 leading-tight">
                        <slot name="header" />
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="relative text-gray-500 hover:text-gray-700">
                        <BellIcon />
                        <span
                            class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                    <div class="relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                        {{ $page.props.auth.user.name }}
                                        <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')"> Perfil </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Cerrar Sesión
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 md:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>