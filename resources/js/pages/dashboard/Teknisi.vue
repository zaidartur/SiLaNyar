<script lang="ts" setup>
import { Head, Link } from '@inertiajs/vue3';
import AppShell from '@/layouts/AppShell.vue';
import StatKpiCard from '@/components/ui/StatKpiCard.vue';
import WorkflowTracker from '@/components/ui/WorkflowTracker.vue';

defineProps<{
    statistik: { jadwalPengujian: number; jadwalPengambilan: number };
    pengujian?: Array<any>;
    pengambilan?: Array<any>;
}>();
</script>

<template>
    <Head title="Dashboard Analis Laboratorium" />

    <AppShell>
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Analis Laboratorium
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            Ruang Analisis & Pengujian Sampel
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Antrian Analisis Parameter Lingkungan
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Monitoring sampel uji masuk, input hasil analisis parameter fisika dan kimia, serta validasi data awal.
                    </p>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <v-btn
                        component="a"
                        href="/pegawai/pengujian"
                        color="primary"
                        variant="elevated"
                        elevation="1"
                        rounded="lg"
                        prepend-icon="mdi-flask-outline"
                        class="text-none font-semibold text-xs"
                    >
                        Mulai Analisis
                    </v-btn>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatKpiCard
                    label="Antrian Analisis Sampel"
                    :value="statistik.jadwalPengujian"
                    icon="mdi-test-tube"
                    color="bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400"
                    description="Contoh uji siap untuk dianalisis parameter"
                />

                <StatKpiCard
                    label="Contoh Uji Masuk"
                    :value="statistik.jadwalPengambilan"
                    icon="mdi-flask-round-bottom"
                    color="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                    description="Telah diverifikasi PPCU dan tiba di lab"
                />

                <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-200">
                                Jaminan Mutu Laboratorium
                            </span>
                            <v-icon size="20" color="white">mdi-check-decagram-outline</v-icon>
                        </div>
                        <p class="text-xs text-emerald-100/90 mt-2 leading-relaxed">
                            Setiap pengujian wajib menggunakan instrumen terkalibrasi dan metode acuan standar SNI / APHA.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-emerald-700/50 flex items-center justify-between text-xs text-emerald-200">
                        <span>ISO/IEC 17025:2017</span>
                        <v-icon size="16">mdi-shield-check</v-icon>
                    </div>
                </div>
            </div>

            <!-- Workflow Tracker (Fokus Tahap 4: Pengujian) -->
            <WorkflowTracker :current-step-index="3" />

            <!-- Aksi Cepat Ruang Pengujian -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                <v-icon color="primary" size="20">mdi-clipboard-list-outline</v-icon>
                                Antrian Pengujian Aktif
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Akses lembar kerja parameter yang ditugaskan kepada Anda.
                            </p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Buka daftar pengujian untuk memasukkan nilai hasil ukur parameter kimia, fisika, atau biologi, serta mengunggah bukti grafik/analisis pendukung.
                    </p>

                    <div class="pt-2">
                        <Link
                            href="/pegawai/pengujian"
                            class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 transition shadow-sm"
                        >
                            <v-icon size="16">mdi-arrow-right-circle-outline</v-icon>
                            Buka Meja Kerja Analisis
                        </Link>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                <v-icon color="primary" size="20">mdi-certificate-outline</v-icon>
                                Lembar Hasil Uji (LHU)
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Verifikasi hasil sebelum ditinjau Penyelia.
                            </p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Periksa kembali kesesuaian nilai ambang batas baku mutu dan pastikan tidak ada kesalahan ketik sebelum mengirimkan draf ke Penyelia Laboratorium.
                    </p>

                    <div class="pt-2">
                        <Link
                            href="/pegawai/hasiluji"
                            class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl text-xs font-bold text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition border border-emerald-200 dark:border-emerald-800"
                        >
                            <v-icon size="16">mdi-file-eye-outline</v-icon>
                            Lihat Rekapitulasi Hasil Uji
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>
