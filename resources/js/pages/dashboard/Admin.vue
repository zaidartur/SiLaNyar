<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppShell from '@/layouts/AppShell.vue';
import StatKpiCard from '@/components/ui/StatKpiCard.vue';
import WorkflowTracker from '@/components/ui/WorkflowTracker.vue';

defineProps<{
    statistik: {
        pengajuan: number;
        jadwal: number;
        pengujian: number;
        hasil_uji: number;
    };
}>();
</script>

<template>
    <Head title="Dashboard Staf Administrator" />

    <AppShell>
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Staf Administrator
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            Pusat Kendali Pelayanan Lab
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Ikhtisar Operasional Laboratorium Lingkungan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Monitoring pendaftaran sampel, penjadwalan PPCU, proses analisis parameter, dan penerbitan LHU.
                    </p>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <v-btn
                        component="a"
                        href="/pegawai/pengajuan"
                        color="primary"
                        variant="elevated"
                        elevation="1"
                        rounded="lg"
                        prepend-icon="mdi-file-document-edit-outline"
                        class="text-none font-semibold text-xs"
                    >
                        Kelola Pengajuan
                    </v-btn>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <StatKpiCard
                    label="Pengajuan Masuk"
                    :value="statistik.pengajuan"
                    icon="mdi-clipboard-text-outline"
                    color="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                    description="Total permohonan pengujian terdaftar"
                />

                <StatKpiCard
                    label="Jadwal Penjemputan"
                    :value="statistik.jadwal"
                    icon="mdi-calendar-clock-outline"
                    color="bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400"
                    description="Penugasan pengambilan contoh uji"
                />

                <StatKpiCard
                    label="Uji Laboratorium"
                    :value="statistik.pengujian"
                    icon="mdi-flask-outline"
                    color="bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400"
                    description="Parameter dalam analisis analis"
                />

                <StatKpiCard
                    label="Laporan LHU"
                    :value="statistik.hasil_uji"
                    icon="mdi-certificate-outline"
                    color="bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-400"
                    description="Lembar Hasil Uji terbit & tervalidasi"
                />
            </div>

            <!-- Workflow Tracker DLH Karanganyar -->
            <WorkflowTracker />

            <!-- Quick Action & SOP Guidance -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Panel Aksi Cepat -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                            Aksi Cepat Pelayanan
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Pintasan menu utama untuk mempercepat validasi dan administrasi dokumen lab.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <Link
                            href="/pegawai/pengajuan"
                            class="group p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 hover:border-emerald-500 dark:hover:border-emerald-500 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 transition flex items-start gap-3"
                        >
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0">
                                <v-icon size="20">mdi-file-check-outline</v-icon>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">
                                    Verifikasi Pengajuan
                                </h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Periksa kelengkapan berkas pemohon dan kaji kelayakan teknis.
                                </p>
                            </div>
                        </Link>

                        <Link
                            href="/pegawai/pengambilan"
                            class="group p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 hover:border-amber-500 dark:hover:border-amber-500 hover:bg-amber-50/40 dark:hover:bg-amber-950/20 transition flex items-start gap-3"
                        >
                            <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 flex items-center justify-center shrink-0">
                                <v-icon size="20">mdi-truck-delivery-outline</v-icon>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-amber-700 dark:group-hover:text-amber-400">
                                    Penjadwalan PPCU
                                </h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Atur tanggal sampling lapangan dan koordinasi tim PPCU.
                                </p>
                            </div>
                        </Link>

                        <Link
                            href="/pegawai/pembayaran"
                            class="group p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50/40 dark:hover:bg-blue-950/20 transition flex items-start gap-3"
                        >
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0">
                                <v-icon size="20">mdi-cash-check</v-icon>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-blue-700 dark:group-hover:text-blue-400">
                                    Verifikasi Pembayaran
                                </h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Validasi bukti transfer atau setor retribusi daerah.
                                </p>
                            </div>
                        </Link>

                        <Link
                            href="/pegawai/parameter"
                            class="group p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 hover:border-purple-500 dark:hover:border-purple-500 hover:bg-purple-50/40 dark:hover:bg-purple-950/20 transition flex items-start gap-3"
                        >
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 flex items-center justify-center shrink-0">
                                <v-icon size="20">mdi-tune-variant</v-icon>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-purple-700 dark:group-hover:text-purple-400">
                                    Baku Mutu Parameter
                                </h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Konfigurasi ambang batas, metode uji, dan tarif per perda.
                                </p>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Petunjuk Operasional SOP -->
                <div class="lg:col-span-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                            <v-icon color="primary" size="20">mdi-information-outline</v-icon>
                            Pedoman Layanan
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Standar Pelayanan Minimal Laboratorium
                        </p>
                    </div>

                    <div class="space-y-3 text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block mb-0.5">1. Verifikasi Sampel</span>
                            Pastikan volume cairan mencukupi dan wadah tertutup rapat tanpa kontaminasi.
                        </div>

                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block mb-0.5">2. Pengendalian Mutu</span>
                            Hasil uji wajib diverifikasi berjenjang oleh Penyelia dan Kepala Laboratorium sebelum diajukan TTE.
                        </div>

                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block mb-0.5">3. Akuntabilitas Retribusi</span>
                            Setiap pembayaran tercatat langsung di pembukuan kas retribusi daerah secara transparan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>
