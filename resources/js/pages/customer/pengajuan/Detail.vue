<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    nama: string;
    harga?: number;
    no_telepon?: string;
    user: User;
}

interface JenisCairan {
    id: number;
    nama: string;
}

interface Parameter {
    id: number;
    nama_parameter: string;
    harga?: number;
}

interface Kategori {
    id: number;
    nama: string;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    volume_sampel: number;
    status_pengajuan: string;
    metode_pengambilan: string;
    lokasi: string;
    instansi: Instansi;
    kategori: Kategori;
    jenis_cairan: JenisCairan;
    parameter: Parameter[];
    total_biaya?: number;
}

const props = defineProps<{
    pengajuan: Pengajuan;
}>();

function formatRupiah(val?: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val || 0);
}

const totalBiaya = computed(() => {
    if (props.pengajuan.total_biaya) return props.pengajuan.total_biaya;
    if (Array.isArray(props.pengajuan.parameter)) {
        return props.pengajuan.parameter.reduce((sum, p) => sum + Number(p.harga ?? 0), 0);
    }
    return 0;
});
</script>

<template>
    <Head title="Detail Pengajuan Sampel" />
    <CustomerLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Informasi Pengajuan
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            #{{ props.pengajuan.kode_pengajuan }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Detail Pengajuan Sampel
                    </h1>
                </div>

                <div class="flex items-center gap-2">
                    <v-btn
                        component="a"
                        href="/customer/dashboard"
                        variant="outlined"
                        rounded="lg"
                        prepend-icon="mdi-arrow-left"
                        class="text-none font-semibold text-xs"
                    >
                        Kembali
                    </v-btn>
                </div>
            </div>

            <!-- Kartu Status & Data Utama -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Validasi</span>
                        <div class="mt-1">
                            <v-chip
                                v-if="props.pengajuan.status_pengajuan === 'diterima'"
                                color="success"
                                size="small"
                                variant="tonal"
                                class="font-bold uppercase tracking-wider"
                            >
                                <v-icon icon="mdi-check-circle" start size="14" /> Diterima
                            </v-chip>
                            <v-chip
                                v-else-if="props.pengajuan.status_pengajuan === 'ditolak'"
                                color="error"
                                size="small"
                                variant="tonal"
                                class="font-bold uppercase tracking-wider"
                            >
                                <v-icon icon="mdi-close-circle" start size="14" /> Ditolak
                            </v-chip>
                            <v-chip
                                v-else
                                color="warning"
                                size="small"
                                variant="tonal"
                                class="font-bold uppercase tracking-wider"
                            >
                                <v-icon icon="mdi-clock-outline" start size="14" /> Dalam Proses
                            </v-chip>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kode Pengajuan</span>
                        <p class="text-base font-extrabold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.pengajuan.kode_pengajuan }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Metode Pengambilan</span>
                        <p class="text-base font-bold text-slate-900 dark:text-slate-100 mt-0.5 capitalize">
                            {{ props.pengajuan.metode_pengambilan }}
                        </p>
                    </div>
                </div>

                <!-- Rincian Spesifikasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Instansi Pemohon:</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.pengajuan.instansi?.nama || '-' }}
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Nama Pemohon:</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.pengajuan.instansi?.user?.nama || '-' }}
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Jenis Cairan:</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.pengajuan.jenis_cairan?.nama || '-' }}
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Volume Sampel:</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.pengajuan.volume_sampel }} ml
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Kategori Baku Mutu:</span>
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.pengajuan.kategori?.nama || '-' }}
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Lokasi Pengambilan:</span>
                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.pengajuan.lokasi || '-' }}
                        </p>
                    </div>
                </div>

                <!-- Parameter Pengujian -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                        Parameter Analisis Laboratorium Terdaftar ({{ props.pengajuan.parameter?.length || 0 }})
                    </h3>

                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    <th class="px-4 py-2.5 text-left font-bold">No</th>
                                    <th class="px-4 py-2.5 text-left font-bold">Parameter Uji</th>
                                    <th class="px-4 py-2.5 text-right font-bold">Biaya Retribusi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr
                                    v-for="(p, idx) in props.pengajuan.parameter"
                                    :key="p.id"
                                    class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"
                                >
                                    <td class="px-4 py-2.5 text-slate-500">{{ idx + 1 }}</td>
                                    <td class="px-4 py-2.5 font-medium text-slate-900 dark:text-slate-100">{{ p.nama_parameter }}</td>
                                    <td class="px-4 py-2.5 text-right font-semibold text-slate-700 dark:text-slate-300">{{ formatRupiah(p.harga) }}</td>
                                </tr>
                                <tr v-if="!props.pengajuan.parameter || props.pengajuan.parameter.length === 0">
                                    <td colspan="3" class="px-4 py-4 text-center text-slate-400">
                                        Tidak ada parameter pengujian.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Total Biaya Estimasi -->
                <div class="flex items-center justify-between p-4 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-bold text-slate-500 dark:text-slate-400">Total Biaya Retribusi</span>
                        <p class="text-xs text-slate-600 dark:text-slate-400">Tarif pengujian laboratorium lingkungan DLH</p>
                    </div>
                    <span class="text-2xl font-black text-emerald-800 dark:text-emerald-300">
                        {{ formatRupiah(totalBiaya) }}
                    </span>
                </div>
            </v-card>
        </div>
    </CustomerLayout>
</template>
