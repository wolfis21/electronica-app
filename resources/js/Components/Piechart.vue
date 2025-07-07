<script setup>
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

const props = defineProps({
  chartData: {
    type: Object,
    required: true,
  },
  chartOptions: {
    type: Object,
    default: () => ({
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: {
            color: '#D1D5DB', // Color de texto gris claro para la leyenda
          }
        }
      }
    }),
  },
});

// Paleta de colores que se alinea con tu marca. Puedes añadir más si tienes más estados.
const brandColors = ['#0055A4', '#F59E0B', '#10B981', '#EF4444', '#6B7280'];

const finalChartData = {
  labels: props.chartData.labels,
  datasets: [
    {
      backgroundColor: brandColors,
      data: props.chartData.data,
    },
  ],
};
</script>

<template>
  <Pie :data="finalChartData" :options="chartOptions" style="max-height: 200px;" />
</template>