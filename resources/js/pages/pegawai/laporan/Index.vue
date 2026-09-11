<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AppShell from '@/layouts/AppShell.vue';
import StatKpiCard from '@/components/ui/StatKpiCard.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import BarChart from '@/components/BarChart.vue';

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
        backgroundColor?: string;
        borderColor?: string;
        data: number[];
        fill?: boolean;
        tension?: number;
    };
}

const page = usePage();

const laporanKeuangan = computed(() => {
    return (page.props.laporan_keuangan as LaporanKeuanganItem[]) || [];
});

const totalPemasukan = computed(() => {
    return (page.props.total_pemasukan as number) || 0;
});

const diagram = computed(() => {
    return (page.props.diagram as DiagramData) || { label: [], data: { label: '', data: [] } };
});

function formatRupiah(amount: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(amount);
}

function formatDate(dateStr?: string | null): string {
    if (!dateStr) return '-';
    try {
        const date = new Date(dateStr);
        return new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        }).format(date);
    } catch {
        return dateStr;
    }
}
</script>

<template>
    <Head title="Laporan Keuangan & Retribusi Lab" />

    <AppShell>
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Keuangan Daerah
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            Retribusi Laboratorium Lingkungan
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Laporan Realisasi Penerimaan Retribusi
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Rekapitulasi pendapatan pengujian sampel laboratorium sesuai Peraturan Daerah Kabupaten Karanganyar.
                    </p>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatKpiCard
                    label="Total Penerimaan Retribusi"
                    :value="formatRupiah(totalPemasukan)"
                    icon="mdi-cash-multiple"
                    color="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                    description="Akumulasi penerimaan kas lab terverifikasi"
                />

                <StatKpiCard
                    label="Transaksi Pembayaran Lunas"
                    :value="laporanKeuangan.length"
                    icon="mdi-receipt-text-check-outline"
                    color="bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400"
                    description="Jumlah berkas pengujian yang telah diselesaikan"
                />

                <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-200">
                                Akuntabilitas Keuangan
                            </span>
                            <v-icon size="20" color="white">mdi-shield-check</v-icon>
                        </div>
                        <p class="text-xs text-emerald-100/90 mt-2 leading-relaxed">
                            Penerimaan retribusi disetorkan langsung ke Kas Daerah Kabupaten Karanganyar sesuai dengan tarif uji yang sah.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-emerald-700/50 flex items-center justify-between text-xs text-emerald-200">
                        <span>Standar Pengawasan BPKD</span>
                        <v-icon size="16">mdi-check-circle-outline</v-icon>
                    </div>
                </div>
            </div>

            <!-- Diagram Tren Pendapatan -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                            <v-icon color="primary" size="20">mdi-chart-bar</v-icon>
                            Grafik Penerimaan Bulanan
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Visualisasi akumulasi penerimaan retribusi per periode
                        </p>
                    </div>
                </div>

                <div v-if="diagram.label.length > 0" class="h-80 w-full pt-2">
                    <BarChart
                        :data="{
                            labels: diagram.label,
                            datasets: [
                                {
                                    ...diagram.data,
                                    backgroundColor: 'rgba(27, 94, 32, 0.75)',
                                    borderColor: 'rgba(27, 94, 32, 1)',
                                    borderWidth: 2,
                                    borderRadius: 8,
                                },
                            ],
                        }"
                        :options="{
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                },
                            },
                        }"
                    />
                </div>
                <div v-else class="py-12 text-center text-xs text-slate-400">
                    Belum ada data grafik untuk ditampilkan.
                </div>
            </div>

            <!-- Tabel Rincian Transaksi -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                        Rincian Transaksi Pembayaran
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Daftar seluruh setoran retribusi yang berhasil diverifikasi bendahara penerima.
                    </p>
                </div>

                <EmptyState
                    v-if="laporanKeuangan.length === 0"
                    title="Belum Ada Transaksi"
                    description="Belum terdapat data pembayaran retribusi pengujian yang tercatat dalam sistem."
                    icon="mdi-cash-register"
                />

                <div v-else class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 uppercase font-bold text-[11px] border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">Tanggal Bayar</th>
                                <th class="px-4 py-3">Kode Pengajuan</th>
                                <th class="px-4 py-3">Instansi / Pemohon</th>
                                <th class="px-4 py-3 text-right rounded-r-lg">Nominal Retribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr
                                v-for="item in laporanKeuangan"
                                :key="item.id"
                                class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition"
                            >
                                <td class="px-4 py-3.5 font-medium text-slate-900 dark:text-slate-100">
                                    {{ formatDate(item.tanggal_pembayaran) }}
                                </td>
                                <td class="px-4 py-3.5 font-mono font-bold text-emerald-700 dark:text-emerald-400">
                                    {{ item.form_pengajuan?.kode_pengajuan || '-' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    {{ item.form_pengajuan?.instansi?.nama || 'Pemohon Perorangan' }}
                                </td>
                                <td class="px-4 py-3.5 text-right font-extrabold text-slate-900 dark:text-slate-100">
                                    {{ formatRupiah(Number(item.total_biaya)) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppShell>
</template>
