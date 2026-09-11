<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Permission {
    id: number;
    name: string;
}

const props = defineProps<{
    permission: Permission[];
}>();

const search = ref('');
const currentPage = ref(1);
const pageSize = 12;

const filteredPermission = computed(() => {
    if (!search.value) return props.permission || [];
    return (props.permission || []).filter((item: Permission) =>
        item.name.toLowerCase().includes(search.value.toLowerCase())
    );
});

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredPermission.value.length / pageSize))
);

const paginatedPermission = computed(() => {
    const start = (currentPage.value - 1) * pageSize;
    return filteredPermission.value.slice(start, start + pageSize);
});
</script>

<template>
    <Head title="Daftar Hak Akses Sistem (Permissions)" />

    <AdminLayout title="Daftar Hak Akses">
        <div class="space-y-6">
            <!-- Header Halaman & Aksi -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Hak Akses Sistem (Permissions)
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredPermission.length }} Izin
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Daftar permission granular bawaan sistem otorisasi aplikasi SiLaNyar
                    </p>
                </div>

                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <v-icon size="16">mdi-magnify</v-icon>
                    </span>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari hak akses..."
                        class="w-full pl-8 pr-3 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    />
                </div>
            </div>

            <!-- Tabel Data Permissions Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="w-20 py-3.5 px-5">No</th>
                                <th class="py-3.5 px-5">Nama Permission / Kunci Izin</th>
                                <th class="py-3.5 px-5">Deskripsi Singkat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="(perm, index) in paginatedPermission"
                                :key="perm.id"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5 font-mono text-slate-400">
                                    {{ (currentPage - 1) * pageSize + index + 1 }}
                                </td>
                                <td class="py-3.5 px-5">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ perm.name }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 dark:text-slate-400">
                                    Izin akses untuk modul {{ perm.name.replace(/^(lihat|tambah|edit|hapus)\s+/i, '') }}
                                </td>
                            </tr>

                            <tr v-if="paginatedPermission.length === 0">
                                <td colspan="3" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-key-remove</v-icon>
                                    <p class="font-medium text-xs">Tidak ada data permission ditemukan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        Menampilkan halaman <strong>{{ currentPage }}</strong> dari <strong>{{ totalPages }}</strong> (Total {{ filteredPermission.length }} izin)
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
        </div>
    </AdminLayout>
</template>
