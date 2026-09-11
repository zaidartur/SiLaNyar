<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    nama: string;
    user: User;
}

interface JenisCairan {
    id: number;
    nama: string;
}

interface Parameter {
    id: number;
    nama_parameter: string;
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
}

const props = defineProps<{
    pengajuan: Pengajuan[];
    filter: {
        status?: string;
    };
}>();

const status = ref(props.filter?.status ?? '');
const search = ref('');

const handleFilter = () => {
    router.get(
        '/pegawai/pengajuan',
        {
            status: status.value || undefined,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

watch(status, () => {
    handleFilter();
});

const filteredList = computed(() => {
    if (!search.value) return props.pengajuan || [];
    const q = search.value.toLowerCase();
    return (props.pengajuan || []).filter((item) =>
        item.kode_pengajuan?.toLowerCase().includes(q) ||
        item.instansi?.user?.nama?.toLowerCase().includes(q) ||
        item.instansi?.nama?.toLowerCase().includes(q) ||
        item.kategori?.nama?.toLowerCase().includes(q)
    );
});

const getStatusBadge = (st: string) => {
    switch (st) {
        case 'diterima':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800';
        case 'ditolak':
            return 'bg-red-100 text-red-800 dark:bg-red-950/80 dark:text-red-300 border-red-300 dark:border-red-800';
        default:
            return 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800';
    }
};

const formatStatusText = (st: string) => {
    if (st === 'proses_validasi') return 'Proses Validasi';
    if (st === 'diterima') return 'Diterima';
    if (st === 'ditolak') return 'Ditolak';
    return st || 'Menunggu';
};
</script>

<template>
    <Head title="Daftar Pengajuan Uji Sampel" />

    <AdminLayout title="Daftar Pengajuan Uji">
        <div class="space-y-6">
            <!-- Header Halaman & Filter -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Daftar Pengajuan Uji
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredList.length }} Data
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Monitoring permohonan pengujian sampel lingkungan dari pemohon dan instansi
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Filter Status Dropdown -->
                    <div class="w-44">
                        <select
                            v-model="status"
                            class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                        >
                            <option value="">Semua Status</option>
                            <option value="proses_validasi">Proses Validasi</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-60">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <v-icon size="18">mdi-magnify</v-icon>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari pemohon/kode..."
                            class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                        />
                    </div>
                </div>
            </div>

            <!-- Tabel Data Pengajuan Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Kode Pengajuan</th>
                                <th class="py-3.5 px-5">Pemohon & Instansi</th>
                                <th class="py-3.5 px-5">Kategori / Sampel</th>
                                <th class="py-3.5 px-5">Volume</th>
                                <th class="py-3.5 px-5">Metode</th>
                                <th class="py-3.5 px-5">Status</th>
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
                                        {{ item.kode_pengajuan }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5">
                                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ item.instansi?.user?.nama || '-' }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ item.instansi?.nama || 'Pribadi' }}</div>
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">{{ item.kategori?.nama || '-' }}</div>
                                    <span class="text-[10px] text-emerald-700 dark:text-emerald-400">{{ item.jenis_cairan?.nama || '' }}</span>
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300 font-mono">
                                    {{ item.volume_sampel }} L
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300">
                                    <span class="capitalize px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[11px] font-medium border border-slate-200 dark:border-slate-700">
                                        {{ item.metode_pengambilan }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span
                                        :class="getStatusBadge(item.status_pengajuan)"
                                        class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border"
                                    >
                                        {{ formatStatusText(item.status_pengajuan) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <Link
                                            :href="`/pegawai/pengajuan/${item.uuid || item.id}/detail`"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:hover:bg-emerald-900/60 dark:text-emerald-300 text-xs font-semibold transition"
                                        >
                                            <v-icon size="14">mdi-eye-outline</v-icon>
                                            <span>Detail</span>
                                        </Link>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="filteredList.length === 0">
                                <td colspan="7" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-clipboard-text-search-outline</v-icon>
                                    <p class="font-medium text-xs">Tidak ada data pengajuan yang sesuai.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>
