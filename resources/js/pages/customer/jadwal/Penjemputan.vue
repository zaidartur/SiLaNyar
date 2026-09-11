<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
    jadwal: any[];
}>();

const selectedFilter = ref('all');

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-';
    const date = new Date(tanggalStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const filteredJadwal = computed(() => {
    if (selectedFilter.value === 'all') return props.jadwal || [];
    return (props.jadwal || []).filter((item: any) => item.status === selectedFilter.value);
});
</script>

<template>
    <Head title="Jadwal Penjemputan Sampel" />

    <CustomerLayout title="Jadwal Penjemputan">
        <div class="space-y-6">
            <!-- Navigasi Tab Pengantaran / Penjemputan -->
            <div class="inline-flex p-1 rounded-2xl bg-slate-200/70 dark:bg-slate-800 border border-slate-300/60 dark:border-slate-700">
                <Link
                    href="/customer/jadwal/pengantaran"
                    class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 transition"
                >
                    Pengantaran Mandiri
                </Link>
                <Link
                    href="/customer/jadwal/penjemputan"
                    class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-700 text-white shadow-sm transition"
                >
                    Penjemputan oleh Petugas
                </Link>
            </div>

            <!-- Header Halaman & Filter -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Jadwal Penjemputan Sampel
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            {{ filteredJadwal.length }} Data
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Informasi jadwal kedatangan tim laboratorium DLH ke lokasi instansi Anda
                    </p>
                </div>

                <div class="w-48">
                    <select
                        v-model="selectedFilter"
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    >
                        <option value="all">Semua Status</option>
                        <option value="diproses">Diproses</option>
                        <option value="diterima">Diterima</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Data Penjemputan Modern -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-900 text-white dark:bg-emerald-950 dark:text-emerald-200 border-b border-emerald-800 dark:border-emerald-900 text-xs font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Kode Penjemputan</th>
                                <th class="py-3.5 px-5">Kode Pengajuan</th>
                                <th class="py-3.5 px-5">Waktu Jadwal</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5">Keterangan</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs">
                            <tr
                                v-for="(item, index) in filteredJadwal"
                                :key="item.id || index"
                                class="transition-colors hover:bg-emerald-50/50 dark:hover:bg-slate-800/60"
                            >
                                <td class="py-3.5 px-5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ item.kode_pengambilan || `JEM-${item.id}` }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 font-mono text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                    {{ item.form_pengajuan?.kode_pengajuan }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300 font-medium whitespace-nowrap">
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
                                <td class="py-3.5 px-5 text-slate-600 dark:text-slate-400">
                                    {{ item.keterangan || '-' }}
                                </td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <Link
                                        :href="`/customer/jadwal/${item.uuid || item.id}`"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:hover:bg-emerald-900/60 dark:text-emerald-300 text-xs font-semibold transition"
                                    >
                                        <v-icon size="14">mdi-eye-outline</v-icon>
                                        <span>Detail</span>
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="filteredJadwal.length === 0">
                                <td colspan="6" class="text-center py-12 text-slate-400 dark:text-slate-500">
                                    <v-icon size="36" class="mb-2 text-slate-300 dark:text-slate-600">mdi-truck-delivery-outline</v-icon>
                                    <p class="font-medium text-xs">Tidak ada data jadwal penjemputan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card>
        </div>
    </CustomerLayout>
</template>
