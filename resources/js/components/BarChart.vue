<script setup lang="ts">
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

// Tambahkan props
const props = defineProps<{
    data: any,
    options?: any
}>()
</script>

<template>
    <Bar :data="props.data" :options="props.options || { responsive: true, maintainAspectRatio: false }" />
</template>