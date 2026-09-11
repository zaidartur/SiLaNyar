<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppShell from '@/layouts/AppShell.vue';
import StatKpiCard from '@/components/ui/StatKpiCard.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import { usePPCUTasks } from '@/composables/usePPCUTasks';
import type { PPCUTask, PPCUStatistik } from '@/types/ppcu.types';

const props = defineProps<{
    statistik: PPCUStatistik;
    pengambilan?: PPCUTask[];
}>();

const {
    filterStatus,
    searchQuery,
    filteredTasks,
    formatDate,
    getStatusBadge,
    openGpsNavigation,
} = usePPCUTasks(props.pengambilan || []);

function refreshData() {
    router.reload({ only: ['statistik', 'pengambilan'] });
}
</script>

<template>
    <Head title="Dashboard Petugas Pengambil Contoh Uji (PPCU)" />

    <AppShell>
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Petugas PPCU
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            Operasional Lapangan
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Jadwal & Tugas Pengambilan Sampel
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Kelola penjemputan contoh uji di lokasi pemohon dan serah terima ke loket laboratorium.
                    </p>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <v-btn
                        variant="outlined"
                        color="primary"
                        density="comfortable"
                        rounded="lg"
                        prepend-icon="mdi-refresh"
                        class="text-none font-semibold text-xs"
                        @click="refreshData"
                    >
                        Muat Ulang
                    </v-btn>
                    <v-btn
                        component="a"
                        href="/pegawai/pengambilan"
                        color="primary"
                        variant="elevated"
                        elevation="1"
                        density="comfortable"
                        rounded="lg"
                        prepend-icon="mdi-format-list-checks"
                        class="text-none font-semibold text-xs"
                    >
                        Tabel Lengkap
                    </v-btn>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatKpiCard
                    label="Tugas Penjemputan"
                    :value="statistik.tugasPengambilan"
                    icon="mdi-map-marker-distance"
                    color="bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400"
                    description="Jadwal penugasan pengambilan contoh uji"
                />

                <StatKpiCard
                    label="Sampel Tiba di Lab"
                    :value="statistik.selesai"
                    icon="mdi-flask-round-bottom-check"
                    color="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                    description="Telah diverifikasi dan diterima di loket lab"
                />

                <div class="sm:col-span-2 lg:col-span-1 bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-200">
                                Prosedur Pengambilan
                            </span>
                            <v-icon size="20" color="white">mdi-shield-check</v-icon>
                        </div>
                        <p class="text-xs text-emerald-100/90 mt-2 leading-relaxed">
                            Contoh uji yang diambil langsung oleh PPCU otomatis terjamin kesesuaian teknik sampling dan terhindar dari penolakan di loket lab.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-emerald-700/50 flex items-center justify-between text-xs text-emerald-200">
                        <span>Standar SNI / ISO 17025</span>
                        <v-icon size="16">mdi-check-circle-outline</v-icon>
                    </div>
                </div>
            </div>

            <!-- Task Filter and List Section -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 sm:p-6 shadow-sm space-y-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <!-- Filter Tabs / Chips -->
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0">
                        <v-btn
                            size="small"
                            rounded="lg"
                            class="text-none font-semibold text-xs"
                            :variant="filterStatus === 'all' ? 'flat' : 'text'"
                            :color="filterStatus === 'all' ? 'primary' : 'default'"
                            @click="filterStatus = 'all'"
                        >
                            Semua ({{ (pengambilan || []).length }})
                        </v-btn>
                        <v-btn
                            size="small"
                            rounded="lg"
                            class="text-none font-semibold text-xs"
                            :variant="filterStatus === 'diproses' ? 'flat' : 'text'"
                            :color="filterStatus === 'diproses' ? 'primary' : 'default'"
                            @click="filterStatus = 'diproses'"
                        >
                            Perlu Diambil
                        </v-btn>
                        <v-btn
                            size="small"
                            rounded="lg"
                            class="text-none font-semibold text-xs"
                            :variant="filterStatus === 'diterima' ? 'flat' : 'text'"
                            :color="filterStatus === 'diterima' ? 'primary' : 'default'"
                            @click="filterStatus = 'diterima'"
                        >
                            Selesai di Lab
                        </v-btn>
                    </div>

                    <!-- Search Input -->
                    <div class="w-full md:w-72">
                        <v-text-field
                            v-model="searchQuery"
                            placeholder="Cari kode, lokasi, instansi..."
                            density="compact"
                            variant="outlined"
                            hide-details
                            prepend-inner-icon="mdi-magnify"
                            rounded="lg"
                            class="text-xs"
                        />
                    </div>
                </div>

                <!-- Empty State -->
                <EmptyState
                    v-if="filteredTasks.length === 0"
                    title="Tidak Ada Jadwal Pengambilan"
                    description="Belum ada tugas penjemputan sampel yang cocok dengan filter atau pencarian saat ini."
                    icon="mdi-calendar-check"
                    action-label="Tampilkan Semua Tugas"
                    @action="() => { filterStatus = 'all'; searchQuery = ''; }"
                />

                <!-- Task Cards Grid (Mobile First) -->
                <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div
                        v-for="task in filteredTasks"
                        :key="task.id"
                        class="border border-slate-200/90 dark:border-slate-800 rounded-xl p-4 transition hover:shadow-md hover:border-emerald-400 dark:hover:border-emerald-600 bg-white dark:bg-slate-900/90 flex flex-col justify-between"
                    >
                        <div>
                            <!-- Card Header: Kode & Status -->
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ task.kode_pengambilan }}
                                </span>
                                <span
                                    class="text-[11px] font-semibold px-2 py-0.5 rounded-full"
                                    :class="getStatusBadge(task.status).bgClass"
                                >
                                    {{ getStatusBadge(task.status).label }}
                                </span>
                            </div>

                            <!-- Instansi / Pemohon -->
                            <h3 class="font-bold text-sm text-slate-900 dark:text-slate-100 line-clamp-1">
                                {{ task.form_pengajuan?.instansi?.nama_instansi || 'Permohonan Pengujian' }}
                            </h3>

                            <!-- Waktu & Kategori -->
                            <div class="mt-2 space-y-1.5 text-xs text-slate-500 dark:text-slate-400">
                                <div class="flex items-center gap-2">
                                    <v-icon size="16" class="text-slate-400">mdi-calendar-clock</v-icon>
                                    <span>Jadwal: {{ formatDate(task.waktu_pengambilan) }}</span>
                                </div>

                                <div v-if="task.form_pengajuan?.lokasi" class="flex items-start gap-2">
                                    <v-icon size="16" class="text-slate-400 shrink-0 mt-0.5">mdi-map-marker</v-icon>
                                    <span class="line-clamp-2 text-slate-700 dark:text-slate-300">
                                        {{ task.form_pengajuan.lokasi }}
                                    </span>
                                </div>

                                <div v-if="task.keterangan" class="flex items-start gap-2 pt-1 border-t border-slate-100 dark:border-slate-800/80">
                                    <v-icon size="16" class="text-slate-400 shrink-0 mt-0.5">mdi-information-outline</v-icon>
                                    <span class="text-[11px] text-slate-500 italic">
                                        {{ task.keterangan }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                            <v-btn
                                v-if="task.form_pengajuan?.lokasi"
                                variant="text"
                                color="primary"
                                density="comfortable"
                                size="small"
                                rounded="lg"
                                prepend-icon="mdi-navigation-variant-outline"
                                class="text-none font-semibold text-xs"
                                @click="openGpsNavigation(task.form_pengajuan?.lokasi)"
                            >
                                Buka Peta GPS
                            </v-btn>
                            <span v-else class="text-xs text-slate-400">Lokasi Loket Lab</span>

                            <Link
                                :href="`/pegawai/pengambilan`"
                                class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition"
                            >
                                Detail Tugas
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>
