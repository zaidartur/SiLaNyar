<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

interface User {
    id: number;
    nama: string;
}

interface FormPengajuan {
    id: number;
    kode_pengajuan: string;
}

interface Kategori {
    id: number;
    nama: string;
}

interface Pengujian {
    id: number;
    kode_pengujian?: string;
    form_pengajuan: FormPengajuan;
    user?: User;
    tanggal_uji: string;
    jam_mulai: string;
    jam_selesai: string;
    kategori?: Kategori;
    status: string;
}

interface PageProps {
    auth?: {
        permissions?: string[];
    };
}

const props = defineProps<{
    pengujian: Pengujian[];
    filter: {
        status?: string;
        tanggal?: string;
    };
    unscheduled_pengajuan?: any[];
    userRole: string;
}>();

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-';
    const date = new Date(tanggalStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const status = ref(props.filter?.status ?? '');
const tanggal = ref(props.filter?.tanggal ?? '');
const search = ref('');

const handleFilter = () => {
    router.get(
        '/pegawai/pengujian',
        {
            status: status.value || undefined,
            tanggal_uji: tanggal.value || undefined,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

const resetFilter = () => {
    status.value = '';
    tanggal.value = '';
    router.get('/pegawai/pengujian', {}, { preserveState: true, replace: true });
};

watch([status, tanggal], () => {
    handleFilter();
});

const page = usePage<PageProps>();
const permissions = page.props.auth?.permissions ?? [];

const can = (permission: string): boolean => {
    return permissions.includes(permission);
};

// Delete Dialog
const showDeleteModal = ref(false);
const deletingId = ref<number | null>(null);
const isDeleting = ref(false);

const openDeleteModal = (id: number) => {
    deletingId.value = id;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingId.value = null;
};

const confirmDelete = () => {
    if (!deletingId.value) return;
    isDeleting.value = true;

    router.delete(`/pegawai/pengujian/${deletingId.value}`, {
        onSuccess: () => {
            isDeleting.value = false;
            closeDeleteModal();
        },
        onError: () => {
            isDeleting.value = false;
        },
    });
};

const filteredPengujian = computed(() => {
    if (!search.value) return props.pengujian || [];
    const q = search.value.toLowerCase();
    return (props.pengujian || []).filter((item) =>
        item.kode_pengujian?.toLowerCase().includes(q) ||
        item.form_pengajuan?.kode_pengajuan?.toLowerCase().includes(q) ||
        item.user?.nama?.toLowerCase().includes(q) ||
        item.kategori?.nama?.toLowerCase().includes(q)
    );
});
</script>

<template>
    <Head title="Daftar Pengujian Laboratorium" />

    <AdminLayout title="Daftar Pengujian Laboratorium">
        <div class="space-y-6">
            <!-- Header Halaman & Aksi -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Pengujian Laboratorium
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredPengujian.length }} Data
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Jadwal pelaksanaan uji analitis sampel, penugasan teknisi, dan status progres
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Status Filter -->
                    <select
                        v-model="status"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    >
                        <option value="">Semua Status</option>
                        <option value="selesai">Selesai</option>
                        <option value="diproses">Diproses</option>
                    </select>

                    <!-- Tanggal Filter -->
                    <input
                        type="date"
                        v-model="tanggal"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    />

                    <!-- Reset Filter Button -->
                    <button
                        v-if="status || tanggal"
                        type="button"
                        @click="resetFilter"
                        class="px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 transition"
                    >
                        Reset
                    </button>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-56">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <v-icon size="16">mdi-magnify</v-icon>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari pengujian/teknisi..."
                            class="w-full pl-8 pr-3 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                        />
                    </div>

                    <!-- Tombol Tambah Pengujian -->
                    <Link
                        v-if="can('tambah pengujian')"
                        href="/pegawai/pengujian/create"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-xs font-bold px-4 py-2.5 shadow-sm transition"
                    >
                        <v-icon size="16">mdi-plus-circle-outline</v-icon>
                        <span>Tambah Pengujian</span>
                    </Link>
                </div>
            </div>

            <!-- Warning Box untuk Unscheduled Pengajuan (Khusus Admin) -->
            <div
                v-if="userRole === 'admin' && unscheduled_pengajuan && unscheduled_pengajuan.length > 0"
                class="p-4 rounded-2xl border border-amber-300 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/50 text-amber-900 dark:text-amber-200 text-xs space-y-1.5"
            >
                <div class="flex items-center gap-2 font-bold text-amber-800 dark:text-amber-300">
                    <v-icon size="18" color="warning">mdi-alert-circle-outline</v-icon>
                    <span>Ada {{ unscheduled_pengajuan.length }} sampel yang belum dijadwalkan pengujian:</span>
                </div>
                <div class="flex flex-wrap gap-2 pt-1">
                    <span
                        v-for="item in unscheduled_pengajuan"
                        :key="item.id"
                        class="px-2.5 py-1 rounded-lg bg-amber-100 dark:bg-amber-900/60 font-mono text-[11px] font-semibold border border-amber-300 dark:border-amber-700"
                    >
                        {{ item.kode_pengajuan }} ({{ item.instansi?.nama || 'Pribadi' }})
                    </span>
                </div>
            </div>

            <!-- Tabel Data Pengujian Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Kode Pengujian</th>
                                <th class="py-3.5 px-5">Kode Pengajuan</th>
                                <th class="py-3.5 px-5">Teknisi Penguji</th>
                                <th class="py-3.5 px-5">Tanggal Uji</th>
                                <th class="py-3.5 px-5">Waktu Jam</th>
                                <th class="py-3.5 px-5">Kategori</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="item in filteredPengujian"
                                :key="item.id"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ item.kode_pengujian || `UJI-${item.id}` }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 font-mono font-medium text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                    {{ item.form_pengajuan?.kode_pengajuan || '-' }}
                                </td>
                                <td class="py-3.5 px-5 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ item.user?.nama || '-' }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300 whitespace-nowrap font-medium">
                                    {{ formatTanggal(item.tanggal_uji) }}
                                </td>
                                <td class="py-3.5 px-5 font-mono text-slate-600 dark:text-slate-400 whitespace-nowrap text-[11px]">
                                    {{ item.jam_mulai || '-' }} - {{ item.jam_selesai || '-' }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[11px] font-medium border border-slate-200 dark:border-slate-700">
                                        {{ item.kategori?.nama || '-' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border',
                                            item.status === 'selesai'
                                                ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800'
                                                : 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800'
                                        ]"
                                    >
                                        {{ item.status === 'selesai' ? 'Selesai' : 'Diproses' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <Link
                                            :href="`/pegawai/pengujian/${item.uuid || item.id}/detail`"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                                            title="Lihat Detail"
                                        >
                                            <v-icon size="15">mdi-eye-outline</v-icon>
                                        </Link>
                                        <template v-if="userRole !== 'teknisi'">
                                            <Link
                                                v-if="can('edit pengujian') && item.status !== 'selesai'"
                                                :href="`/pegawai/pengujian/${item.uuid || item.id}/edit`"
                                                class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:hover:bg-amber-900/60 dark:text-amber-300 transition"
                                                title="Ubah Pengujian"
                                            >
                                                <v-icon size="15">mdi-pencil-outline</v-icon>
                                            </Link>
                                            <button
                                                v-if="can('hapus pengujian')"
                                                type="button"
                                                @click="openDeleteModal(item.uuid || item.id)"
                                                class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-700 dark:bg-red-950/50 dark:hover:bg-red-900/60 dark:text-red-300 transition cursor-pointer"
                                                title="Hapus Pengujian"
                                            >
                                                <v-icon size="15">mdi-trash-can-outline</v-icon>
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="filteredPengujian.length === 0">
                                <td colspan="8" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-flask-outline</v-icon>
                                    <p class="font-medium text-xs">Tidak ada data pengujian laboratorium ditemukan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card>

            <!-- Modal Konfirmasi Hapus -->
            <v-dialog v-model="showDeleteModal" max-width="440" persistent>
                <v-card rounded="2xl" class="p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-center space-y-4">
                    <div class="w-14 h-14 mx-auto rounded-full bg-red-100 dark:bg-red-950 flex items-center justify-center text-red-600 dark:text-red-400">
                        <v-icon size="32">mdi-alert-octagon-outline</v-icon>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                            Konfirmasi Hapus Pengujian
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">
                            Apakah Anda yakin ingin menghapus data pengujian ini? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>

                    <div class="flex items-center justify-center gap-3 pt-2">
                        <button
                            type="button"
                            @click="closeDeleteModal"
                            class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            :disabled="isDeleting"
                            @click="confirmDelete"
                            class="px-4 py-2 rounded-xl text-xs font-bold bg-red-600 hover:bg-red-700 active:bg-red-800 text-white shadow-sm transition disabled:opacity-50"
                        >
                            {{ isDeleting ? 'Menghapus...' : 'Ya, Hapus' }}
                        </button>
                    </div>
                </v-card>
            </v-dialog>
        </div>
    </AdminLayout>
</template>
