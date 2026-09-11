<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps<{
    hasil_uji: any[];
    unscheduled_pengujian?: any[];
    userRole: string;
}>();

const page = usePage();
const permissions =
    (page.props.auth && Array.isArray((page.props.auth as any).permissions))
        ? ((page.props.auth as any).permissions as string[])
        : [];

const can = (permission: string): boolean => {
    return permissions.includes(permission);
};

const search = ref('');
const statusFilter = ref('');

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-';
    const date = new Date(tanggalStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const statusLabel = (status: string) => {
    const labels: Record<string, string> = {
        draf: 'Draf',
        revisi: 'Revisi',
        proses_review: 'Proses Review',
        proses_peresmian: 'Proses Peresmian',
        selesai: 'Selesai',
    };
    return labels[status] ?? status;
};

const getStatusBadge = (st: string) => {
    switch (st) {
        case 'selesai':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800';
        case 'proses_review':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800';
        case 'proses_peresmian':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-950/80 dark:text-blue-300 border-blue-300 dark:border-blue-800';
        case 'revisi':
            return 'bg-red-100 text-red-800 dark:bg-red-950/80 dark:text-red-300 border-red-300 dark:border-red-800';
        default:
            return 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-300 dark:border-slate-700';
    }
};

const showDeleteModal = ref(false);
const deletingHasilUji = ref<any | null>(null);
const isDeleting = ref(false);

const openDeleteModal = (item: any) => {
    deletingHasilUji.value = item;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingHasilUji.value = null;
};

const handleDelete = () => {
    if (!deletingHasilUji.value) return;
    isDeleting.value = true;

    router.delete(`/pegawai/hasiluji/${deletingHasilUji.value.id}`, {
        onSuccess: () => {
            isDeleting.value = false;
            closeDeleteModal();
        },
        onError: () => {
            isDeleting.value = false;
        },
    });
};

const filteredHasilUji = computed(() => {
    return (props.hasil_uji || []).filter((item) => {
        const matchesSearch =
            !search.value ||
            String(item.id).includes(search.value) ||
            item.pengujian?.form_pengajuan?.instansi?.nama?.toLowerCase().includes(search.value.toLowerCase()) ||
            item.pengujian?.form_pengajuan?.instansi?.user?.nama?.toLowerCase().includes(search.value.toLowerCase()) ||
            item.pengujian?.user?.nama?.toLowerCase().includes(search.value.toLowerCase());

        const matchesStatus = !statusFilter.value || item.status === statusFilter.value;
        return matchesSearch && matchesStatus;
    });
});
</script>

<template>
    <Head title="Lembar Hasil Uji (LHU)" />

    <AdminLayout title="Lembar Hasil Uji (LHU)">
        <div class="space-y-6">
            <!-- Header Halaman & Aksi -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Lembar Hasil Uji (LHU)
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredHasilUji.length }} Data
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Sertifikasi dan laporan resmi hasil analisis laboratorium lingkungan hidup
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Status Filter -->
                    <select
                        v-model="statusFilter"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    >
                        <option value="">Semua Status</option>
                        <option value="draf">Draf</option>
                        <option value="proses_review">Proses Review</option>
                        <option value="proses_peresmian">Proses Peresmian</option>
                        <option value="revisi">Revisi</option>
                        <option value="selesai">Selesai</option>
                    </select>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-56">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <v-icon size="16">mdi-magnify</v-icon>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari pemohon/teknisi..."
                            class="w-full pl-8 pr-3 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                        />
                    </div>

                    <!-- Tombol Tambah Hasil Uji -->
                    <Link
                        v-if="can('tambah hasil uji')"
                        href="/pegawai/hasiluji/create"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-xs font-bold px-4 py-2.5 shadow-sm transition"
                    >
                        <v-icon size="16">mdi-plus-circle-outline</v-icon>
                        <span>Tambah Hasil Uji</span>
                    </Link>
                </div>
            </div>

            <!-- Warning Box untuk Pengujian Selesai Belum Ada Hasil Uji -->
            <div
                v-if="userRole === 'teknisi' && unscheduled_pengujian && unscheduled_pengujian.filter(item => item.status === 'selesai').length > 0"
                class="p-4 rounded-2xl border border-amber-300 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/50 text-amber-900 dark:text-amber-200 text-xs space-y-1.5"
            >
                <div class="flex items-center gap-2 font-bold text-amber-800 dark:text-amber-300">
                    <v-icon size="18" color="warning">mdi-alert-circle-outline</v-icon>
                    <span>Ada {{ unscheduled_pengujian.filter(item => item.status === 'selesai').length }} pengujian selesai yang belum dibuatkan LHU:</span>
                </div>
                <div class="flex flex-wrap gap-2 pt-1">
                    <span
                        v-for="item in unscheduled_pengujian.filter(item => item.status === 'selesai')"
                        :key="item.id"
                        class="px-2.5 py-1 rounded-lg bg-amber-100 dark:bg-amber-900/60 font-mono text-[11px] font-semibold border border-amber-300 dark:border-amber-700"
                    >
                        {{ item.kode_pengujian || `UJI-${item.id}` }} - {{ item.form_pengajuan?.instansi?.nama || 'Pribadi' }}
                    </span>
                </div>
            </div>

            <!-- Tabel Data Hasil Uji Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">ID LHU</th>
                                <th class="py-3.5 px-5">Instansi & Pemohon</th>
                                <th class="py-3.5 px-5">Teknisi Penguji</th>
                                <th class="py-3.5 px-5">Tanggal Uji</th>
                                <th class="py-3.5 px-5">Status Validasi</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="item in filteredHasilUji"
                                :key="item.id"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        LHU-{{ String(item.id).padStart(4, '0') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5">
                                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ item.pengujian?.form_pengajuan?.instansi?.nama || 'Pribadi' }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ item.pengujian?.form_pengajuan?.instansi?.user?.nama || '-' }}</div>
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300">
                                    {{ item.pengujian?.user?.nama || '-' }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                    {{ formatTanggal(item.pengujian?.tanggal_uji) }}
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span
                                        :class="getStatusBadge(item.status)"
                                        class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border"
                                    >
                                        {{ statusLabel(item.status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <Link
                                            :href="`/pegawai/hasiluji/${item.id}`"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                                            title="Detail LHU"
                                        >
                                            <v-icon size="15">mdi-eye-outline</v-icon>
                                        </Link>
                                        <Link
                                            :href="`/pegawai/hasiluji/${item.id}/riwayat`"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-purple-50 hover:bg-purple-100 text-purple-700 dark:bg-purple-950/50 dark:hover:bg-purple-900/60 dark:text-purple-300 transition"
                                            title="Riwayat Status"
                                        >
                                            <v-icon size="15">mdi-history</v-icon>
                                        </Link>
                                        <Link
                                            v-if="can('edit hasil uji')"
                                            :href="`/pegawai/hasiluji/${item.id}/edit`"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:hover:bg-amber-900/60 dark:text-amber-300 transition"
                                            title="Ubah LHU"
                                        >
                                            <v-icon size="15">mdi-pencil-outline</v-icon>
                                        </Link>
                                        <button
                                            type="button"
                                            @click="openDeleteModal(item)"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-700 dark:bg-red-950/50 dark:hover:bg-red-900/60 dark:text-red-300 transition cursor-pointer"
                                            title="Hapus LHU"
                                        >
                                            <v-icon size="15">mdi-trash-can-outline</v-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="filteredHasilUji.length === 0">
                                <td colspan="6" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-file-document-outline</v-icon>
                                    <p class="font-medium text-xs">Tidak ada data Lembar Hasil Uji ditemukan.</p>
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
                            Konfirmasi Hapus LHU
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">
                            Apakah Anda yakin ingin menghapus Lembar Hasil Uji ini?
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
                            @click="handleDelete"
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
