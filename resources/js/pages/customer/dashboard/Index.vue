<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppShell from '@/layouts/AppShell.vue';
import StatKpiCard from '@/components/ui/StatKpiCard.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import WorkflowTracker from '@/components/ui/WorkflowTracker.vue';

interface StatusItem {
    label: string;
    status: boolean;
    tanggal: string;
}

interface Statistik {
    proses: number;
    ditolak: number;
    diterima: number;
}

interface Kategori {
    id: number;
    nama: string;
}

interface JenisCairan {
    id: number;
    nama: string;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    kategori: Kategori;
    jenis_cairan: JenisCairan;
    volume_sampel: number;
    status_pengajuan: 'proses_validasi' | 'diterima' | 'ditolak';
    metode_pengambilan: 'diantar' | 'diambil';
    lokasi: string;
}

interface Pembayaran {
    id: number;
    id_order: string;
    form_pengajuan: Pengajuan;
    total_biaya: number;
    tanggal_pembayaran: string;
    metode_pembayaran: 'transfer' | 'tunai';
    status_pembayaran: 'diproses' | 'selesai' | 'gagal';
    updated_at: string;
    keterangan?: string | null;
}

interface PilihPengajuan {
    id: number | string;
    kode_pengajuan?: string;
    status_pengajuan?: string;
    pembayaran?: {
        status?: string;
        updated_at?: string;
    };
    jadwal?: {
        status?: string;
        created_at?: string;
        updated_at?: string;
    };
    pengujian?: {
        status?: string;
        created_at?: string;
    };
    hasil_uji?: {
        status?: string;
        updated_at?: string;
    };
}

const props = defineProps<{
    statusList: StatusItem[];
    statistik: Statistik;
    pengajuan: Pengajuan[];
    pembayaran: Pembayaran[];
    pilihPengajuan: PilihPengajuan | null;
}>();

const statusFilter = ref<string>('');

const filteredPengajuan = computed(() => {
    if (!statusFilter.value) return props.pengajuan;
    return props.pengajuan.filter(
        (item) => item.status_pengajuan === statusFilter.value
    );
});

// Hitung tahapan aktif untuk alur DLH dari permohonan yang dipilih
const currentStepIndex = computed(() => {
    if (!props.pilihPengajuan) return 0;
    if (props.pilihPengajuan.hasil_uji?.status === 'selesai') return 5;
    if (props.pilihPengajuan.pengujian?.status) return 3;
    if (props.pilihPengajuan.jadwal?.status) return 2;
    if (props.pilihPengajuan.pembayaran?.status === 'selesai') return 2;
    if (props.pilihPengajuan.status_pengajuan === 'diterima') return 1;
    return 0;
});

function formatRupiah(amount: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(amount);
}

function formatDate(dateStr?: string): string {
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
    <Head title="Dashboard Pemohon Pengujian" />

    <AppShell>
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Layanan Publik
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            DLH Kabupaten Karanganyar
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Status Pengujian & Layanan Sampel
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Pantau progres verifikasi berkas, jadwal pengambilan contoh uji, dan lembar hasil uji (LHU).
                    </p>
                </div>

                <div>
                    <v-btn
                        component="a"
                        href="/customer/pengajuan"
                        color="primary"
                        variant="elevated"
                        elevation="1"
                        rounded="lg"
                        prepend-icon="mdi-plus"
                        class="text-none font-semibold text-xs"
                    >
                        Buat Pengajuan Baru
                    </v-btn>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <StatKpiCard
                    label="Menunggu Verifikasi"
                    :value="statistik?.proses ?? 0"
                    icon="mdi-clock-outline"
                    color="bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400"
                    description="Berkas sedang diperiksa staf administrasi"
                />

                <StatKpiCard
                    label="Pengajuan Diterima"
                    :value="statistik?.diterima ?? 0"
                    icon="mdi-check-circle-outline"
                    color="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                    description="Lanjut ke tahap pembayaran dan sampling"
                />

                <StatKpiCard
                    label="Perlu Perbaikan / Ditolak"
                    :value="statistik?.ditolak ?? 0"
                    icon="mdi-alert-circle-outline"
                    color="bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400"
                    description="Periksa catatan revisi di detail pengajuan"
                />
            </div>

            <!-- Workflow Tracker -->
            <WorkflowTracker :current-step-index="currentStepIndex" />

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Daftar Pengajuan & Pembayaran (2 Kolom di Desktop) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Tabel Pengajuan -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                                    Riwayat Pengajuan Sampel
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Pilih kode pengajuan untuk memantau detail alur uji di samping.
                                </p>
                            </div>

                            <div class="w-full sm:w-48">
                                <v-select
                                    v-model="statusFilter"
                                    :items="[
                                        { title: 'Semua Status', value: '' },
                                        { title: 'Belum Terverifikasi', value: 'proses_validasi' },
                                        { title: 'Diterima', value: 'diterima' },
                                        { title: 'Ditolak', value: 'ditolak' },
                                    ]"
                                    item-title="title"
                                    item-value="value"
                                    density="compact"
                                    variant="outlined"
                                    hide-details
                                    rounded="lg"
                                    class="text-xs"
                                />
                            </div>
                        </div>

                        <EmptyState
                            v-if="filteredPengajuan.length === 0"
                            title="Belum Ada Pengajuan"
                            description="Anda belum memiliki permohonan pengujian sampel laboratorium yang sesuai filter."
                            icon="mdi-file-document-outline"
                            action-label="Ajukan Sampel Sekarang"
                            action-href="/customer/pengajuan"
                        />

                        <!-- List Permohonan Responsif -->
                        <div v-else class="space-y-3">
                            <div
                                v-for="item in filteredPengajuan"
                                :key="item.id"
                                class="border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 transition hover:border-emerald-400 dark:hover:border-emerald-600 bg-slate-50/40 dark:bg-slate-900/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                                :class="{ 'ring-2 ring-emerald-500/40 border-emerald-500': pilihPengajuan?.id === item.id }"
                            >
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <Link
                                            :href="route('customer.dashboard', { id: item.id })"
                                            preserve-scroll
                                            class="font-mono text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline"
                                        >
                                            {{ item.kode_pengajuan }}
                                        </Link>
                                        <span
                                            class="text-[11px] font-semibold px-2 py-0.5 rounded-full"
                                            :class="{
                                                'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800': item.status_pengajuan === 'proses_validasi',
                                                'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800': item.status_pengajuan === 'diterima',
                                                'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800': item.status_pengajuan === 'ditolak',
                                            }"
                                        >
                                            {{ item.status_pengajuan === 'proses_validasi' ? 'Validasi' : item.status_pengajuan === 'diterima' ? 'Diterima' : 'Ditolak' }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-medium text-slate-700 dark:text-slate-300">
                                        {{ item.kategori?.nama || 'Sampel Air' }} : {{ item.jenis_cairan?.nama || '-' }} ({{ item.volume_sampel }} Liter)
                                    </p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                        Pengambilan: {{ item.metode_pengambilan === 'diambil' ? 'Dijemput PPCU ke lokasi' : 'Diantar sendiri ke laboratorium' }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                    <Link
                                        :href="route('customer.pengajuan.detail', item.uuid || item.id)"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700 transition"
                                    >
                                        <v-icon size="14">mdi-eye-outline</v-icon>
                                        Rincian
                                    </Link>

                                    <Link
                                        v-if="item.status_pengajuan === 'proses_validasi'"
                                        :href="route('customer.pengajuan.edit', item.uuid || item.id)"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 transition"
                                    >
                                        <v-icon size="14">mdi-pencil-outline</v-icon>
                                        Ubah
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Tagihan & Pembayaran -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                                    Tagihan & Retribusi Pengujian
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Bukti bayar retribusi laboratorium sesuai tarif Perda Kabupaten Karanganyar.
                                </p>
                            </div>
                        </div>

                        <EmptyState
                            v-if="props.pembayaran.length === 0"
                            title="Belum Ada Tagihan Aktif"
                            description="Tagihan retribusi pengujian akan terbit setelah berkas pengajuan Anda diverifikasi dan diterima."
                            icon="mdi-cash-remove"
                        />

                        <div v-else class="space-y-3">
                            <div
                                v-for="p in props.pembayaran"
                                :key="p.id"
                                class="border border-slate-200/80 dark:border-slate-800 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/40 dark:bg-slate-900/60"
                            >
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs font-bold text-slate-700 dark:text-slate-300">
                                            #{{ p.id_order }}
                                        </span>
                                        <span
                                            class="text-[11px] font-semibold px-2 py-0.5 rounded-full capitalize"
                                            :class="{
                                                'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800': p.status_pembayaran === 'selesai',
                                                'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800': p.status_pembayaran === 'diproses',
                                                'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800': p.status_pembayaran === 'gagal',
                                            }"
                                        >
                                            {{ p.status_pembayaran === 'selesai' ? 'Lunas' : p.status_pembayaran === 'diproses' ? 'Diproses' : 'Belum Lunas' }}
                                        </span>
                                    </div>
                                    <div class="text-base font-extrabold text-slate-900 dark:text-slate-100">
                                        {{ formatRupiah(p.total_biaya) }}
                                    </div>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                        Metode: {{ p.metode_pembayaran || 'Transfer Bank' }} | Tanggal: {{ formatDate(p.updated_at) }}
                                    </p>
                                </div>

                                <div class="shrink-0 self-end sm:self-center">
                                    <Link
                                        v-if="p.status_pembayaran !== 'selesai' && p.status_pembayaran !== 'diproses'"
                                        :href="route('customer.pembayaran.show', p.id)"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold bg-emerald-700 hover:bg-emerald-800 text-white transition shadow-sm"
                                    >
                                        <v-icon size="16">mdi-credit-card-outline</v-icon>
                                        Bayar Sekarang
                                    </Link>

                                    <span
                                        v-else-if="p.status_pembayaran === 'diproses'"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300"
                                    >
                                        <v-icon size="14">mdi-timer-sand</v-icon>
                                        Menunggu Konfirmasi
                                    </span>

                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300"
                                    >
                                        <v-icon size="14">mdi-check</v-icon>
                                        Lunas Terverifikasi
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Status Detail Pengujian Terpilih (1 Kolom di Desktop) -->
                <div class="lg:col-span-1">
                    <div class="sticky top-20 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                        <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                <v-icon color="primary" size="20">mdi-timeline-clock-outline</v-icon>
                                Riwayat Status
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Progres pendaftaran yang sedang dipantau
                            </p>
                        </div>

                        <div v-if="props.statusList && props.statusList.length > 0" class="relative pl-6 space-y-5">
                            <!-- Garis Vertikal -->
                            <div class="absolute left-2.5 top-2.5 bottom-2.5 w-0.5 bg-slate-200 dark:bg-slate-800" />

                            <div
                                v-for="(item, index) in props.statusList"
                                :key="index"
                                class="relative flex items-start gap-3"
                            >
                                <div
                                    class="absolute -left-6 top-0.5 w-5 h-5 rounded-full flex items-center justify-center z-10 transition"
                                    :class="item.status ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-400'"
                                >
                                    <v-icon v-if="item.status" size="12">mdi-check</v-icon>
                                    <div v-else class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500" />
                                </div>

                                <div class="space-y-0.5">
                                    <p
                                        class="text-xs font-bold leading-tight"
                                        :class="item.status ? 'text-slate-900 dark:text-slate-100' : 'text-slate-400 dark:text-slate-500'"
                                    >
                                        {{ item.label }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                        {{ item.tanggal || 'Menunggu giliran' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-6 text-xs text-slate-400 dark:text-slate-500">
                            Pilih salah satu nomor pengajuan di samping untuk melihat riwayat status lengkap.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>
