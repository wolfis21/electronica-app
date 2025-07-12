<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

// Lista de servicios que ofrece Orquesta. Puedes modificarla según tu negocio.
const services = [
    {
        name: 'Gestión de Órdenes',
        description: 'Centraliza y supervisa cada orden de trabajo, desde la recepción hasta la entrega final, en una sola vista.'
    },
    {
        name: 'Portal de Clientes',
        description: 'Ofrece a tus clientes un portal para ver el estado de sus órdenes en tiempo real, mejorando la transparencia.'
    },
    {
        name: 'Análisis e Informes',
        description: 'Visualiza el rendimiento de tu negocio con gráficas y KPIs para tomar decisiones basadas en datos.'
    },
    {
        name: 'Gestión de Inventario',
        description: 'Controla tu stock de productos y repuestos, asociándolos directamente a las órdenes de servicio.'
    },
    {
        name: 'Facturación y Pagos',
        description: 'Genera facturas y lleva un registro de los pagos recibidos de forma integrada y sin complicaciones.'
    },
    {
        name: 'Comunicación de Equipo',
        description: 'Mantén a tu equipo sincronizado con notas y actualizaciones internas en cada tarea asignada.'
    }
];
</script>

<template>
    <Head title="Servicios - Orquesta" />

    <PublicLayout>
        <div class="bg-orq-light-gray py-24">
            <div class="container mx-auto px-6 text-center">
                <h1 class="font-serif text-5xl md:text-6xl font-bold text-orq-dark-gray">Nuestra Composición de Servicios</h1>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    Cada módulo de Orquesta está diseñado para trabajar en perfecta armonía, brindándote control total sobre tu negocio.
                </p>
            </div>
        </div>

        <div class="py-20">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="service in services" :key="service.name" class="p-8 border border-gray-200 rounded-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <h3 class="font-serif text-2xl font-bold text-orq-dark-gray mb-3">{{ service.name }}</h3>
                        <p class="text-gray-600">
                            {{ service.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </PublicLayout>
</template>