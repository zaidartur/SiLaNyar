<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
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

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    metode_pengambilan: string;
    lokasi: string;
    instansi: Instansi;
}

interface Jadwal {
    id: number;
    kode_pengambilan: string;
    form_pengajuan: Pengajuan;
    user: User;
    waktu_pengambilan: string;
    status: 'diproses' | 'diterima';
    keterangan: string;
}

interface AuthUser {
    id: number;
    nama: string;
    role: string;
    permissions: string[];
}

interface AuthProps {
    user: AuthUser;
    permissions: string[];
}

const page = usePage();
const auth = page.props.auth as AuthProps;

const props = defineProps<{
    jadwal: Jadwal[];
    filter: {
        status?: string;
        tanggal?: string;
    };
    unscheduled_pengajuan?: any[];
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
        '/pegawai/pengambilan',
        {
            status: status.value || undefined,
            waktu_pengambilan: tanggal.value || undefined,
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
    router.get('/pegawai/pengambilan', {}, { preserveState: true, replace: true });
};

watch([status, tanggal], () => {
    handleFilter();
});

const can = (permission: string) => {
    return auth.permissions?.includes(permission);
};

const isStatusCompleted = (st: string) => st === 'diterima';

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

    router.delete(`/pegawai/pengambilan/${deletingId.value}`, {
        onSuccess: () => {
            isDeleting.value = false;
            closeDeleteModal();
        },
        onError: () => {
            isDeleting.value = false;
        },
    });
};

const filteredJadwal = computed(() => {
    if (!search.value) return props.jadwal || [];
    const q = search.value.toLowerCase();
    return (props.jadwal || []).filter((item) =>
        item.kode_pengambilan?.toLowerCase().includes(q) ||
        item.form_pengajuan?.kode_pengajuan?.toLowerCase().includes(q) ||
        item.form_pengajuan?.instansi?.nama?.toLowerCase().includes(q) ||
        item.form_pengajuan?.instansi?.user?.nama?.toLowerCase().includes(q)
    );
});
</script>

<template>
    <Head title="Jadwal Pengambilan & Pengantaran Sampel" />

    <AdminLayout title="Jadwal Pengambilan Sampel">
        <div class="space-y-6">
            <!-- Header Halaman & Filter -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Jadwal Pengambilan Sampel
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredJadwal.length }} Data
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Manajemen jadwal petugas penjemputan atau pengantaran contoh uji laboratorium
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Status Filter -->
                    <select
                        v-model="status"
                        class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    >
                        <option value="">Semua Status</option>
                        <option value="diproses">Diproses</option>
                        <option value="diterima">Diterima</option>
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
                            placeholder="Cari jadwal/kode..."
                            class="w-full pl-8 pr-3 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                        />
                    </div>
                </div>
            </div>

            <!-- Tabel Data Pengambilan Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Kode Pengambilan</th>
                                <th class="py-3.5 px-5">Kode Pengajuan</th>
                                <th class="py-3.5 px-5">Pemohon & Instansi</th>
                                <th class="py-3.5 px-5">Metode</th>
                                <th class="py-3.5 px-5">Waktu Jadwal</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="item in filteredJadwal"
                                :key="item.id"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ item.kode_pengambilan || `JAD-${item.id}` }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 font-mono font-medium text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                    {{ item.form_pengajuan?.kode_pengajuan }}
                                </td>
                                <td class="py-3.5 px-5">
                                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ item.form_pengajuan?.instansi?.user?.nama || '-' }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400">{{ item.form_pengajuan?.instansi?.nama || 'Pribadi' }}</div>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300 capitalize">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[11px] font-medium border border-slate-200 dark:border-slate-700">
                                        {{ item.form_pengajuan?.metode_pengambilan || '-' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                    {{ formatTanggal(item.waktu_pengambilan) }}
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border',
                                            item.status === 'diterima'
                                                ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800'
                                                : 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border-amber-300 dark:border-amber-800'
                                        ]"
                                    >
                                        {{ item.status === 'diterima' ? 'Diterima' : 'Diproses' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <Link
                                            :href="`/pegawai/pengambilan/${item.uuid || item.id}`"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                                            title="Lihat Detail"
                                        >
                                            <v-icon size="15">mdi-eye-outline</v-icon>
                                        </Link>
                                        <Link
                                            v-if="can('edit pengambilan') && !isStatusCompleted(item.status)"
                                            :href="`/pegawai/pengambilan/${item.uuid || item.id}/edit`"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:hover:bg-amber-900/60 dark:text-amber-300 transition"
                                            title="Ubah Jadwal"
                                        >
                                            <v-icon size="15">mdi-pencil-outline</v-icon>
                                        </Link>
                                        <button
                                            v-if="can('hapus pengambilan')"
                                            type="button"
                                            @click="openDeleteModal(item.uuid || item.id)"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-700 dark:bg-red-950/50 dark:hover:bg-red-900/60 dark:text-red-300 transition cursor-pointer"
                                            title="Hapus Jadwal"
                                        >
                                            <v-icon size="15">mdi-trash-can-outline</v-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="filteredJadwal.length === 0">
                                <td colspan="7" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-calendar-clock</v-icon>
                                    <p class="font-medium text-xs">Tidak ada jadwal pengambilan sampel.</p>
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
                            Konfirmasi Hapus Jadwal
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">
                            Apakah Anda yakin ingin menghapus jadwal pengambilan ini? Tindakan ini tidak dapat dikembalikan.
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
