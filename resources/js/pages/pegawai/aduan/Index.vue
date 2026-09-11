<script lang="ts" setup>
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Aduan {
    id: number;
    masalah: string;
    perbaikan: string;
    terkait: string;
    status: string;
    created_at: string;
    user: {
        nama: string;
    };
    hasil_uji?: {
        id: number;
        kode?: string;
        pengujian?: {
            form_pengajuan?: {
                instansi?: {
                    nama: string;
                    user?: {
                        nama: string;
                    };
                };
            };
        };
    };
}

const props = defineProps<{
    aduan: Aduan[];
}>();

const search = ref('');
const statusFilter = ref('');

const filteredAduan = computed(() => {
    return (props.aduan || []).filter((item) => {
        const matchesSearch =
            !search.value ||
            item.masalah.toLowerCase().includes(search.value.toLowerCase()) ||
            item.user.nama.toLowerCase().includes(search.value.toLowerCase()) ||
            (item.hasil_uji?.kode && item.hasil_uji.kode.toLowerCase().includes(search.value.toLowerCase()));

        const matchesStatus = !statusFilter.value || item.status === statusFilter.value;
        return matchesSearch && matchesStatus;
    });
});

const getStatusBadge = (st: string) => {
    switch (st) {
        case 'ditolak':
            return 'bg-red-100 text-red-800 dark:bg-red-950/80 dark:text-red-300 border-red-300 dark:border-red-800';
        case 'diterima_administrasi':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300 border-blue-300 dark:border-blue-800';
        case 'diterima_pengujian':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800';
        default:
            return 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800';
    }
};

const formatTanggal = (dateStr: string) => {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};
</script>

<template>
    <Head title="Daftar Aduan & Keluhan Pelanggan" />

    <AdminLayout title="Verifikasi Aduan">
        <div class="space-y-6">
            <!-- Header Halaman & Filter -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Verifikasi Aduan Pelanggan
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredAduan.length }} Aduan
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Daftar keluhan hasil uji atau administrasi yang memerlukan tindak lanjut laboratorium
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Status Filter -->
                    <select
                        v-model="statusFilter"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    >
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="diterima_administrasi">Diterima Administrasi</option>
                        <option value="diterima_pengujian">Diterima Pengujian</option>
                        <option value="ditolak">Ditolak</option>
                    </select>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-60">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <v-icon size="16">mdi-magnify</v-icon>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari pelapor/subjek..."
                            class="w-full pl-8 pr-3 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                        />
                    </div>
                </div>
            </div>

            <!-- Grid Kartu Aduan Modern -->
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                <v-card
                    v-for="item in filteredAduan"
                    :key="item.id"
                    rounded="2xl"
                    elevation="1"
                    class="p-5 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-4 flex flex-col justify-between"
                >
                    <div class="space-y-3">
                        <!-- Top Header Status -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono font-bold text-xs px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                #{{ item.id }}
                            </span>
                            <span
                                :class="getStatusBadge(item.status)"
                                class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border capitalize"
                            >
                                {{ item.status.replaceAll('_', ' ') }}
                            </span>
                        </div>

                        <!-- Info Aduan -->
                        <div class="space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                            <div>
                                <span class="font-semibold text-slate-900 dark:text-slate-100">Pelapor:</span>
                                <span class="ml-1.5">{{ item.user.nama }}</span>
                            </div>
                            <div v-if="item.hasil_uji?.pengujian?.form_pengajuan?.instansi?.nama">
                                <span class="font-semibold text-slate-900 dark:text-slate-100">Instansi:</span>
                                <span class="ml-1.5">{{ item.hasil_uji.pengujian.form_pengajuan.instansi.nama }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-slate-900 dark:text-slate-100">Tanggal:</span>
                                <span class="ml-1.5">{{ formatTanggal(item.created_at) }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-slate-900 dark:text-slate-100">Terkait:</span>
                                <span class="ml-1.5 px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-[10px] font-bold border border-emerald-200 dark:border-emerald-800 capitalize">
                                    {{ item.terkait }}
                                </span>
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 space-y-1">
                            <p class="font-bold text-xs text-slate-900 dark:text-slate-100 line-clamp-1">
                                {{ item.masalah }}
                            </p>
                            <p class="text-[11px] text-slate-600 dark:text-slate-400 line-clamp-2">
                                {{ item.perbaikan }}
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Aksi Detail -->
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                        <Link
                            :href="`/pegawai/aduan/${item.id}`"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:hover:bg-emerald-900/60 dark:text-emerald-300 text-xs font-semibold transition"
                        >
                            <span>Tindak Lanjut</span>
                            <v-icon size="14">mdi-arrow-right</v-icon>
                        </Link>
                    </div>
                </v-card>

                <div v-if="filteredAduan.length === 0" class="col-span-full text-center py-12 text-slate-400 dark:text-slate-500">
                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-comment-check-outline</v-icon>
                    <p class="font-medium text-xs">Tidak ada aduan pelanggan saat ini.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
