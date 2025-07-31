<template>
    <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-xl shadow-md p-6 border border-red-100">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Centro de Soporte Técnico</h3>
                <p class="text-gray-600 mb-4">
                    ¿Encontraste un error o problema en el sistema? Ayúdanos a mejorar reportando cualquier bug, 
                    funcionamiento inesperado o sugerencia de mejora.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button @click="openBugReport"
                       class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Reportar Bug o Problema
                    </button>
                    <button @click="openSuggestion"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Enviar Sugerencia
                    </button>
                </div>
                <div class="mt-3 text-sm text-gray-500">
                    <p><strong>Tu reporte incluirá automáticamente:</strong> Usuario, fecha/hora, página actual, y navegador utilizado.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { supportConfig } from '@/config/support';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    bugReportUrl: {
        type: String,
        default: null
    },
    suggestionUrl: {
        type: String,
        default: null
    },
    additionalData: {
        type: Object,
        default: () => ({})
    }
});

const page = usePage();

const openBugReport = () => {
    const url = props.bugReportUrl || supportConfig.generateBugReportUrl({
        user: page.props.auth.user?.name || 'Usuario Anónimo',
        email: page.props.auth.user?.email || '',
        ...props.additionalData
    });
    
    window.open(url, '_blank', 'noopener,noreferrer');
};

const openSuggestion = () => {
    const url = props.suggestionUrl || supportConfig.generateSuggestionUrl({
        user: page.props.auth.user?.name || 'Usuario Anónimo',
        email: page.props.auth.user?.email || '',
        ...props.additionalData
    });
    
    window.open(url, '_blank', 'noopener,noreferrer');
};
</script>
