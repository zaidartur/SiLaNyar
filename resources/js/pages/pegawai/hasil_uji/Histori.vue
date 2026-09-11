<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const { hasil_uji, histori } = defineProps<{
    hasil_uji: any;
    histori: any[];
}>();

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-';
    const date = new Date(tanggalStr);
    return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Histori Perubahan Hasil Uji" />
    <AdminLayout>
        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Audit Trail LHU
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            #HU-{{ hasil_uji.id.toString().padStart(4, '0') }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Riwayat Perubahan Hasil Uji
                    </h1>
                </div>

                <v-btn
                    component="a"
                    :href="route('pegawai.hasil_uji.index')"
                    variant="outlined"
                    rounded="lg"
                    size="small"
                    prepend-icon="mdi-arrow-left"
                    class="text-none font-semibold text-xs"
                >
                    Kembali
                </v-btn>
            </div>

            <!-- Card Metadata -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Nomor LHU</span>
                        <p class="font-bold text-emerald-700 dark:text-emerald-400 mt-0.5 text-sm">
                            HU-{{ hasil_uji.id.toString().padStart(4, '0') }}
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Status Terkini</span>
                        <div class="mt-1">
                            <v-chip size="small" variant="tonal" color="primary" class="font-bold uppercase">
                                {{ hasil_uji.status }}
                            </v-chip>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Tanggal Dibuat</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">
                            {{ formatTanggal(hasil_uji.created_at) }}
                        </p>
                    </div>
                </div>

                <!-- Tabel Riwayat -->
                <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden mt-4">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold">
                                <th class="px-4 py-2.5 text-center w-12">No</th>
                                <th class="px-4 py-2.5 text-left">Status</th>
                                <th class="px-4 py-2.5 text-left">Waktu Pembaruan</th>
                                <th class="px-4 py-2.5 text-left">Diperbarui Oleh</th>
                                <th class="px-4 py-2.5 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr
                                v-for="(item, idx) in histori"
                                :key="item.id"
                                class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"
                            >
                                <td class="px-4 py-2.5 text-center text-slate-500">{{ idx + 1 }}</td>
                                <td class="px-4 py-2.5">
                                    <v-chip size="x-small" variant="tonal" color="primary" class="font-bold uppercase">
                                        {{ item.status }}
                                    </v-chip>
                                </td>
                                <td class="px-4 py-2.5 font-medium text-slate-900 dark:text-slate-100">{{ formatTanggal(item.created_at) }}</td>
                                <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400">{{ item.diupdate_oleh ?? '-' }}</td>
                                <td class="px-4 py-2.5 text-center">
                                    <v-btn
                                        component="a"
                                        :href="route('pegawai.hasil_uji.riwayat.show', item.id)"
                                        size="x-small"
                                        variant="tonal"
                                        color="primary"
                                        class="text-none font-semibold"
                                    >
                                        Detail
                                    </v-btn>
                                </td>
                            </tr>
                            <tr v-if="!histori || histori.length === 0">
                                <td colspan="5" class="px-4 py-6 text-center text-slate-400">
                                    Belum ada histori perubahan pada hasil uji ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>