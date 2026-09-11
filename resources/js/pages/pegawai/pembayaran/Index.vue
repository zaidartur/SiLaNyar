<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    nama: string;
    user: User;
}

interface FormPengajuan {
    id: number;
    kode_pengajuan: string;
    instansi: Instansi;
}

interface Pembayaran {
    id: number;
    id_order: string;
    total_biaya: number;
    tanggal_pembayaran: string | null;
    metode_pembayaran: string;
    status_pembayaran: string;
    bukti_pembayaran: string | null;
    form_pengajuan: FormPengajuan;
}

const props = defineProps<{
    pembayaran: Pembayaran[];
}>();

const search = ref('');
const statusFilter = ref('');

function formatTanggal(tanggal: string | null) {
    if (!tanggal) return '-';
    const date = new Date(tanggal);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}

function lihatDetail(id: number) {
    router.visit(`/pegawai/pembayaran/${id}`);
}

const filteredList = computed(() => {
    return (props.pembayaran || []).filter((item) => {
        const matchesSearch =
            !search.value ||
            item.id_order?.toLowerCase().includes(search.value.toLowerCase()) ||
            item.form_pengajuan?.kode_pengajuan?.toLowerCase().includes(search.value.toLowerCase()) ||
            item.form_pengajuan?.instansi?.user?.nama?.toLowerCase().includes(search.value.toLowerCase()) ||
            item.form_pengajuan?.instansi?.nama?.toLowerCase().includes(search.value.toLowerCase());

        const matchesStatus = !statusFilter.value || item.status_pembayaran === statusFilter.value;
        return matchesSearch && matchesStatus;
    });
});

const getStatusBadge = (st: string) => {
    switch (st?.toLowerCase()) {
        case 'selesai':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800';
        case 'gagal':
            return 'bg-red-100 text-red-800 dark:bg-red-950/80 dark:text-red-300 border-red-300 dark:border-red-800';
        default:
            return 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800';
    }
};
</script>

<template>
    <Head title="Verifikasi Pembayaran Retribusi" />

    <AdminLayout title="Verifikasi Pembayaran">
        <div class="space-y-6">
            <!-- Header Halaman & Filter -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Verifikasi Pembayaran Retribusi
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredList.length }} Data
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Pemeriksaan bukti pembayaran transfer dan validasi retribusi pengujian sampel
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Status Filter -->
                    <select
                        v-model="statusFilter"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="selesai">Selesai</option>
                        <option value="gagal">Gagal</option>
                    </select>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-60">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <v-icon size="16">mdi-magnify</v-icon>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari order/pemohon..."
                            class="w-full pl-8 pr-3 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                        />
                    </div>
                </div>
            </div>

            <!-- Tabel Data Pembayaran Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">ID Order</th>
                                <th class="py-3.5 px-5">Kode Pengajuan</th>
                                <th class="py-3.5 px-5">Pemohon & Instansi</th>
                                <th class="py-3.5 px-5">Total Biaya</th>
                                <th class="py-3.5 px-5">Tanggal Bayar</th>
                                <th class="py-3.5 px-5">Metode</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5">Bukti</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="item in filteredList"
                                :key="item.id"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ item.id_order }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 font-mono text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                    {{ item.form_pengajuan?.kode_pengajuan || '-' }}
                                </td>
                                <td class="py-3.5 px-5">
                                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ item.form_pengajuan?.instansi?.user?.nama || '-' }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ item.form_pengajuan?.instansi?.nama || 'Pribadi' }}</div>
                                </td>
                                <td class="py-3.5 px-5 font-mono font-bold text-emerald-700 dark:text-emerald-400 whitespace-nowrap">
                                    {{ Number(item.total_biaya).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }) }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                    {{ formatTanggal(item.tanggal_pembayaran) }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300 capitalize">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[11px] font-medium border border-slate-200 dark:border-slate-700">
                                        {{ item.metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span
                                        :class="getStatusBadge(item.status_pembayaran)"
                                        class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border uppercase"
                                    >
                                        {{ item.status_pembayaran }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <a
                                        v-if="item.bukti_pembayaran"
                                        :href="`/storage/${item.bukti_pembayaran}`"
                                        target="_blank"
                                        class="inline-flex items-center gap-1 text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 font-semibold"
                                    >
                                        <v-icon size="14">mdi-paperclip</v-icon>
                                        <span>Lihat Bukti</span>
                                    </a>
                                    <span v-else class="text-slate-400">-</span>
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <button
                                        type="button"
                                        @click="lihatDetail(item.id)"
                                        class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition cursor-pointer"
                                        title="Detail Pembayaran"
                                    >
                                        <v-icon size="15">mdi-eye-outline</v-icon>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="filteredList.length === 0">
                                <td colspan="9" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-cash-remove</v-icon>
                                    <p class="font-medium text-xs">Tidak ada data pembayaran ditemukan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>
