<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    kode_instansi: string;
    nama: string;
    tipe?: 'swasta' | 'pemerintahan' | 'pribadi';
    alamat: string;
    email: string;
    no_telepon: string;
    posisi_jabatan: string;
    departemen_divisi: string;
    status_verifikasi?: 'diproses' | 'diterima' | 'ditolak';
    created_at: string;
    diverifikasi_oleh: string;
    user: User;
}

const props = defineProps<{
    instansi: Instansi[];
}>();

const search = ref('');

const filteredInstansi = computed(() => {
    if (!search.value) return props.instansi || [];
    const q = search.value.toLowerCase();
    return (props.instansi || []).filter((item) =>
        item.kode_instansi?.toLowerCase().includes(q) ||
        item.nama?.toLowerCase().includes(q) ||
        item.user?.nama?.toLowerCase().includes(q) ||
        item.email?.toLowerCase().includes(q)
    );
});
</script>

<template>
    <Head title="Data Instansi & Pelanggan" />

    <AdminLayout title="Data Instansi">
        <div class="space-y-6">
            <!-- Header Halaman & Aksi -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Instansi Pelanggan
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredInstansi.length }} Instansi
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Daftar profil instansi pemerintah, swasta, dan perorangan pengguna layanan laboratorium
                    </p>
                </div>

                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <v-icon size="16">mdi-magnify</v-icon>
                    </span>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari instansi/PIC..."
                        class="w-full pl-8 pr-3 py-2 rounded-xl text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    />
                </div>
            </div>

            <!-- Tabel Data Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Kode Instansi</th>
                                <th class="py-3.5 px-5">Nama Instansi</th>
                                <th class="py-3.5 px-5">PIC / Customer</th>
                                <th class="py-3.5 px-5">Tipe</th>
                                <th class="py-3.5 px-5">Kontak</th>
                                <th class="py-3.5 px-5">Jabatan / Divisi</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="item in filteredInstansi"
                                :key="item.id"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ item.kode_instansi }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5">
                                    <div class="font-semibold text-slate-900 dark:text-slate-100">{{ item.nama }}</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-1">{{ item.alamat || '-' }}</div>
                                </td>
                                <td class="py-3.5 px-5 text-slate-800 dark:text-slate-200 font-medium">
                                    {{ item.user?.nama || '-' }}
                                </td>
                                <td class="py-3.5 px-5 whitespace-nowrap capitalize">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[11px] font-medium border border-slate-200 dark:border-slate-700">
                                        {{ item.tipe || 'Pribadi' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300">
                                    <div>{{ item.no_telepon || '-' }}</div>
                                    <div class="text-[11px] text-slate-500">{{ item.email || '-' }}</div>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300">
                                    <div>{{ item.posisi_jabatan || '-' }}</div>
                                    <div class="text-[11px] text-slate-500">{{ item.departemen_divisi || '-' }}</div>
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <Link
                                        :href="`/pegawai/instansi/${item.uuid || item.id}`"
                                        class="w-7 h-7 rounded-lg inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                                        title="Detail Instansi"
                                    >
                                        <v-icon size="15">mdi-eye-outline</v-icon>
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="filteredInstansi.length === 0">
                                <td colspan="7" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-domain</v-icon>
                                    <p class="font-medium text-xs">Tidak ada data instansi ditemukan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>
