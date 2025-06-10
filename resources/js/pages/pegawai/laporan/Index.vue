<script setup lang="ts">
import { Bar } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

// Contoh data, ganti dengan data dari backend jika ada
const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']
const dataKeuangan = [1200000, 1500000, 900000, 2000000, 1700000, 2100000]

const chartData = {
    labels,
    datasets: [
        {
            label: 'Pendapatan (Rp)',
            backgroundColor: '#16a34a',
            data: dataKeuangan,
        },
    ],
}

const chartOptions = {
    responsive: true,
    plugins: {
        legend: { display: true },
        title: {
            display: true,
            text: 'Laporan Keuangan per Bulan',
            font: { size: 18 }
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                callback: (value: number) => 'Rp ' + value.toLocaleString('id-ID')
            }
        }
    }
}
</script>

<template>
    <div class="p-8 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-customDarkGreen">Laporan Keuangan</h1>
        <div class="bg-white rounded-xl shadow p-6">
            <Bar :data="chartData" :options="chartOptions" />
        </div>
    </div>
</template>