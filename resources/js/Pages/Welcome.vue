<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';


defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    companyName: {
        type: String,
        required: true,
        default: 'Tu Empresa' // Un valor por defecto por si acaso.
    }
});

const currentDate = computed(() => {
  const date = new Date();
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return date.toLocaleDateString('es-ES', options);
});

</script>

<template>
    <Head title="Welcome" />
    
    <div class="flex flex-col min-h-screen bg-gray-50 text-gray-800 font-sans">

        <!-- Header -->
        <header class="p-4 sm:p-6">
            <div class="container mx-auto flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <p>{{ currentDate }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span class="font-bold text-xl text-gray-700">{{ companyName }}</span>
                </div>
            </div>
        </header>

        <!-- Contenido Principal -->
        <main class="flex-grow flex items-center justify-center p-4">
            <div class="w-full max-w-md text-center">
                
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">
                    Bienvenido a
                </h1>
                <p class="text-4xl sm:text-5xl font-bold text-indigo-600 mb-12">
                    {{ companyName }}
                </p>
                
                <div v-if="canLogin" class="space-y-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="block w-full bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-300 ease-in-out"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="block w-full bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-300 ease-in-out"
                        >
                            Iniciar Sesión
                        </Link>
                        
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="block w-full bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg border border-gray-300 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition duration-300 ease-in-out"
                        >
                            Solicitar Acceso
                        </Link>
                    </template>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="p-4 sm:p-6">
            <div class="container mx-auto text-left">
                <p class="text-sm text-gray-500">Desarrollado por Bermu Tech INC</p>
            </div>
        </footer>

    </div>
</template>
