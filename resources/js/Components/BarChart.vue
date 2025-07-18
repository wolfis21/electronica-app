<script setup>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

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
      indexAxis: 'y', // <-- Esto hace la gráfica de barras horizontal, mejor para rankings
      plugins: {
        legend: {
          display: false,
        },
      },
      scales: {
        x: {
          beginAtZero: true,
        },
      },
    }),
  },
});

const finalChartData = {
  labels: props.chartData.labels,
  datasets: [
    {
      label: 'Total',
      backgroundColor: '#1A237E', // Color Azul Noche
      borderColor: '#1A237E',
      borderWidth: 1,
      data: props.chartData.data,
    },
  ],
};
</script>

<template>
  <Bar :data="finalChartData" :options="chartOptions" />
</template>