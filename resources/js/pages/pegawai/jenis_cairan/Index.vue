<script setup lang="ts">
import EditJenisCairan from '@/components/form/admin/jenis_cairan/Edit.vue';
import TambahJenisCairan from '@/components/form/admin/jenis_cairan/Tambah.vue';
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface JenisCairan {
    id: number;
    kode_jenis_cairan: string;
    nama: string;
    batas_minimum: number;
    batas_maksimum: number;
}

const props = defineProps<{
    jenis_cairan: JenisCairan[];
}>();

const search = ref('');
const currentPage = ref(1);
const pageSize = 10;

const filteredJenisCairan = computed(() => {
    if (!search.value) return props.jenis_cairan || [];
    return (props.jenis_cairan || []).filter((item: JenisCairan) =>
        item.nama.toLowerCase().includes(search.value.toLowerCase()) ||
        item.kode_jenis_cairan.toLowerCase().includes(search.value.toLowerCase())
    );
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredJenisCairan.value.length / pageSize))
);

const paginatedJenisCairan = computed(() => {
    const start = (currentPage.value - 1) * pageSize;
    return filteredJenisCairan.value.slice(start, start + pageSize);
});

// Modal Tambah
const showTambahModal = ref(false);
const openTambahModal = () => (showTambahModal.value = true);
const closeTambahModal = () => (showTambahModal.value = false);

// Modal Edit
const showEditModal = ref(false);
const editingJenisCairan = ref<JenisCairan | null>(null);
const openEditModal = (item: JenisCairan) => {
    editingJenisCairan.value = item;
    showEditModal.value = true;
};
const closeEditModal = () => {
    showEditModal.value = false;
    editingJenisCairan.value = null;
};

// Modal Delete
const showDeleteModal = ref(false);
const deletingJenisCairan = ref<JenisCairan | null>(null);
const openDeleteModal = (item: JenisCairan) => {
    deletingJenisCairan.value = item;
    showDeleteModal.value = true;
};
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingJenisCairan.value = null;
};

const isDeleting = ref(false);
const handleDelete = () => {
    if (!deletingJenisCairan.value) return;
    isDeleting.value = true;

    router.delete(`/pegawai/jenis-cairan/${deletingJenisCairan.value.uuid || deletingJenisCairan.value.id}`, {
        onSuccess: () => {
            isDeleting.value = false;
            closeDeleteModal();
        },
        onError: () => {
            isDeleting.value = false;
        },
    });
};
</script>

<template>
    <Head title="Master Jenis Cairan" />

    <AdminLayout title="Master Data Jenis Cairan">
        <div class="space-y-6">
            <!-- Header Halaman & Aksi -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Jenis Cairan Sampel
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredJenisCairan.length }} Data
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Kriteria ambang batas volume cairan minimum dan maksimum pengujian laboratorium
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <!-- Search Input -->
                    <div class="relative w-full sm:w-64">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <v-icon size="18">mdi-magnify</v-icon>
                        </span>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari jenis cairan..."
                            class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                        />
                    </div>

                    <!-- Tombol Tambah -->
                    <button
                        type="button"
                        @click="openTambahModal"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-xs font-bold px-4 py-2.5 shadow-sm transition cursor-pointer"
                    >
                        <v-icon size="16">mdi-plus-circle-outline</v-icon>
                        <span>Tambah Jenis Cairan</span>
                    </button>
                    <TambahJenisCairan v-if="showTambahModal" @close="closeTambahModal" />
                </div>
            </div>

            <!-- Tabel Data Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Kode Jenis Cairan</th>
                                <th class="py-3.5 px-5">Nama Cairan</th>
                                <th class="py-3.5 px-5">Batas Minimum (ml/L)</th>
                                <th class="py-3.5 px-5">Batas Maksimum (ml/L)</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="item in paginatedJenisCairan"
                                :key="item.id"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ item.kode_jenis_cairan }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ item.nama }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300">
                                    <span class="px-2.5 py-1 rounded bg-slate-100 dark:bg-slate-800 font-mono font-medium border border-slate-200 dark:border-slate-700">
                                        {{ item.batas_minimum }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300">
                                    <span class="px-2.5 py-1 rounded bg-slate-100 dark:bg-slate-800 font-mono font-medium border border-slate-200 dark:border-slate-700">
                                        {{ item.batas_maksimum !== null && item.batas_maksimum !== undefined ? item.batas_maksimum : '-' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <v-btn
                                            icon
                                            size="x-small"
                                            variant="tonal"
                                            color="warning"
                                            title="Ubah Jenis Cairan"
                                            @click="openEditModal(item)"
                                        >
                                            <v-icon size="16">mdi-pencil-outline</v-icon>
                                        </v-btn>
                                        <v-btn
                                            icon
                                            size="x-small"
                                            variant="tonal"
                                            color="error"
                                            title="Hapus Jenis Cairan"
                                            @click="openDeleteModal(item)"
                                        >
                                            <v-icon size="16">mdi-trash-can-outline</v-icon>
                                        </v-btn>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="paginatedJenisCairan.length === 0">
                                <td colspan="5" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-water-outline</v-icon>
                                    <p class="font-medium text-xs">Tidak ada data jenis cairan ditemukan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        Menampilkan halaman <strong>{{ currentPage }}</strong> dari <strong>{{ totalPages }}</strong> (Total {{ filteredJenisCairan.length }} data)
                    </span>

                    <v-pagination
                        v-model="currentPage"
                        :length="totalPages"
                        :total-visible="5"
                        density="compact"
                        rounded="lg"
                        color="primary"
                        active-color="primary"
                        class="text-xs"
                    />
                </div>
            </v-card>

            <EditJenisCairan v-if="showEditModal" :jenis_cairan="editingJenisCairan" @close="closeEditModal" />

            <!-- Modal Konfirmasi Hapus -->
            <v-dialog v-model="showDeleteModal" max-width="440" persistent>
                <v-card rounded="2xl" class="p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-center space-y-4">
                    <div class="w-14 h-14 mx-auto rounded-full bg-red-100 dark:bg-red-950 flex items-center justify-center text-red-600 dark:text-red-400">
                        <v-icon size="32">mdi-alert-octagon-outline</v-icon>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                            Konfirmasi Hapus Jenis Cairan
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">
                            Apakah Anda yakin ingin menghapus
                            <strong class="text-slate-900 dark:text-slate-100">{{ deletingJenisCairan?.nama }}</strong>
                            ({{ deletingJenisCairan?.kode_jenis_cairan }})?
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
