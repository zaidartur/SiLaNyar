<script setup lang="ts">
import BarChart from '@/components/BarChart.vue';
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

interface LaporanKeuanganItem {
    id: number;
    tanggal_pembayaran: string | null;
    total_biaya: number;
    form_pengajuan?: {
        kode_pengajuan?: string;
        instansi?: {
            nama?: string;
        };
    };
}

interface DiagramData {
    label: string[];
    data: {
        label: string;
        backgroundColor: string;
        borderColor: string;
        data: number[];
        fill: boolean;
        tension: number;
    };
}

const page = usePage();

const laporanKeuangan = (page.props.laporan_keuangan as LaporanKeuanganItem[]) || [];
const totalPemasukan = (page.props.total_pemasukan as number) || 0;
const diagram = (page.props.diagram as DiagramData) || { label: [], data: {} };
</script>

<template>
    <Head title="Laporan Keuangan" />
    <AdminLayout>
        <div class="container mx-auto p-4">
            <h1 class="mb-4 text-2xl font-bold">Laporan Keuangan</h1>

            <!-- Total Pemasukan -->
            <div class="mb-6">
                <span class="font-semibold">Total Pemasukan:</span>
                <span class="text-lg font-bold text-green-700">Rp {{ totalPemasukan.toLocaleString('id-ID') }}</span>
            </div>

            <!-- Diagram -->
            <div class="mb-8 rounded bg-white p-4 shadow" style="height: 350px">
                <BarChart
                    :data="{
                        labels: diagram.label,
                        datasets: [
                            {
                                ...diagram.data,
                                backgroundColor: 'rgba(46, 125, 50, 0.6)',
                                borderColor: 'rgba(46, 125, 50, 1)',
                                borderWidth: 3,
                            },
                        ],
                    }"
                    :options="{ responsive: true, maintainAspectRatio: false }"
                />
            </div>

            <!-- Tabel Laporan -->
            <div class="overflow-x-auto">
                <table class="min-w-full rounded bg-white shadow">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Tanggal</th>
                            <th class="border px-4 py-2">Kode Pengajuan</th>
                            <th class="border px-4 py-2">Instansi</th>
                            <th class="border px-4 py-2">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in laporanKeuangan" :key="item.id">
                            <td class="border px-4 py-2">
                                {{ item.tanggal_pembayaran ? new Date(item.tanggal_pembayaran).toLocaleDateString('id-ID') : '-' }}
                            </td>
                            <td class="border px-4 py-2">{{ item.form_pengajuan?.kode_pengajuan || '-' }}</td>
                            <td class="border px-4 py-2">{{ item.form_pengajuan?.instansi?.nama || '-' }}</td>
                            <td class="border px-4 py-2 text-right">Rp {{ Number(item.total_biaya).toLocaleString('id-ID') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
