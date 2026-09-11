<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Parameter {
    id: number;
    kode_parameter: string;
    nama_parameter: string;
    satuan: string;
    harga: '';
    baku_mutu?: string;
}

interface SubKategori {
    id: number;
    kode_subkategori: string;
    nama: string;
    parameter: Parameter[];
}

const props = defineProps<{
    subkategori: SubKategori[];
}>();

const search = ref('');
const currentPage = ref(1);
const pageSize = 8;

const filteredSubkategori = computed(() => {
    if (!search.value) return props.subkategori || [];
    return (props.subkategori || []).filter((item: SubKategori) =>
        item.nama.toLowerCase().includes(search.value.toLowerCase()) ||
        item.kode_subkategori.toLowerCase().includes(search.value.toLowerCase())
    );
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredSubkategori.value.length / pageSize))
);

const paginatedSubkategori = computed(() => {
    const start = (currentPage.value - 1) * pageSize;
    return filteredSubkategori.value.slice(start, start + pageSize);
});

// Modal Delete
const showDeleteModal = ref(false);
const deletingSubKategori = ref<SubKategori | null>(null);

const openDeleteModal = (item: SubKategori) => {
    deletingSubKategori.value = item;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    deletingSubKategori.value = null;
};

const isDeleting = ref(false);
const handleDelete = () => {
    if (!deletingSubKategori.value) return;
    isDeleting.value = true;

    router.delete(`/pegawai/subkategori/${deletingSubKategori.value.uuid || deletingSubKategori.value.id}`, {
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
    <Head title="Master Sub Kategori Sampel" />

    <AdminLayout title="Master Data Sub Kategori">
        <div class="space-y-6">
            <!-- Header Halaman & Aksi -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Sub Kategori Sampel
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredSubkategori.length }} Data
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Daftar sub-klasifikasi sampel pengujian dan parameter baku mutu spesifik
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
                            placeholder="Cari subkategori..."
                            class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                        />
                    </div>

                    <!-- Tombol Tambah Subkategori -->
                    <Link
                        href="/pegawai/subkategori/create"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-xs font-bold px-4 py-2.5 shadow-sm transition"
                    >
                        <v-icon size="16">mdi-plus-circle-outline</v-icon>
                        <span>Tambah Sub Kategori</span>
                    </Link>
                </div>
            </div>

            <!-- Tabel Data Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Kode Sub Kategori</th>
                                <th class="py-3.5 px-5">Nama Sub Kategori</th>
                                <th class="py-3.5 px-5">Parameter Baku Mutu</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="item in paginatedSubkategori"
                                :key="item.id"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ item.kode_subkategori }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 font-semibold text-slate-900 dark:text-slate-100">
                                    {{ item.nama }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300 max-w-md">
                                    <div v-if="item.parameter && item.parameter.length > 0" class="flex flex-wrap gap-1">
                                        <span
                                            v-for="param in item.parameter"
                                            :key="param.id"
                                            class="px-1.5 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-[10px] font-medium border border-emerald-200 dark:border-emerald-800"
                                        >
                                            {{ param.nama_parameter }}
                                        </span>
                                    </div>
                                    <span v-else class="text-slate-400">-</span>
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <Link
                                            :href="`/pegawai/subkategori/${item.uuid || item.id}/edit`"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:hover:bg-amber-900/60 dark:text-amber-300 transition"
                                            title="Ubah Sub Kategori"
                                        >
                                            <v-icon size="15">mdi-pencil-outline</v-icon>
                                        </Link>
                                        <button
                                            type="button"
                                            @click="openDeleteModal(item)"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-700 dark:bg-red-950/50 dark:hover:bg-red-900/60 dark:text-red-300 transition cursor-pointer"
                                            title="Hapus Sub Kategori"
                                        >
                                            <v-icon size="15">mdi-trash-can-outline</v-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="paginatedSubkategori.length === 0">
                                <td colspan="4" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-tag-multiple-outline</v-icon>
                                    <p class="font-medium text-xs">Tidak ada data sub kategori ditemukan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        Menampilkan halaman <strong>{{ currentPage }}</strong> dari <strong>{{ totalPages }}</strong> (Total {{ filteredSubkategori.length }} data)
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

            <!-- Modal Konfirmasi Hapus -->
            <v-dialog v-model="showDeleteModal" max-width="440" persistent>
                <v-card rounded="2xl" class="p-6 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-center space-y-4">
                    <div class="w-14 h-14 mx-auto rounded-full bg-red-100 dark:bg-red-950 flex items-center justify-center text-red-600 dark:text-red-400">
                        <v-icon size="32">mdi-alert-octagon-outline</v-icon>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">
                            Konfirmasi Hapus Sub Kategori
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300">
                            Apakah Anda yakin ingin menghapus sub kategori
                            <strong class="text-slate-900 dark:text-slate-100">{{ deletingSubKategori?.nama }}</strong>
                            ({{ deletingSubKategori?.kode_subkategori }})?
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