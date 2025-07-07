<script setup>
import { Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement,
  Filler
} from 'chart.js';
import { computed } from 'vue';

// Se registran los componentes de Chart.js que vamos a utilizar
ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler);

// Se definen las props que este componente recibirá desde el Dashboard
const props = defineProps({
  // Los datos para la gráfica (etiquetas y valores)
  chartData: {
    type: Object,
    required: true,
  },
  // Opciones de configuración visual de la gráfica
  chartOptions: {
    type: Object,
    default: () => ({
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false, // Ocultamos la leyenda por defecto
            }
        },
        scales: {
            x: {
                grid: {
                    display: false // Ocultamos la cuadrícula del eje X
                },
                ticks: {
                    color: '#9CA3AF' // Color del texto del eje X
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: '#4B5563' // Color de la cuadrícula del eje Y
                },
                ticks: {
                    color: '#9CA3AF' // Color del texto del eje Y
                }
            }
        }
    }),
  },
  // Opciones específicas para el dataset (colores, etc.)
  datasetOptions: Object,
});

// Propiedad computada que formatea los datos para que Chart.js los entienda
const finalChartData = computed(() => {
    return {
        labels: props.chartData.labels,
        datasets: [
            {
                label: 'Data',
                tension: 0.4, // Curvatura de la línea
                fill: true, // Relleno debajo de la línea
                ...props.datasetOptions, // Permite pasar colores personalizados
                data: props.chartData.data,
            },
        ],
    };
});
</script>

<template>
  <Line :data="finalChartData" :options="chartOptions" />
</template>