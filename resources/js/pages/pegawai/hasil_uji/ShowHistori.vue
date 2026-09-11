<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const { histori, data_parameter } = defineProps<{ histori: any; data_parameter: any[] }>();

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
    <Head title="Detail Snapshot Histori Hasil Uji" />
    <AdminLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Snapshot Riwayat
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            Log #{{ histori.id }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Detail Snapshot Hasil Uji
                    </h1>
                </div>

                <v-btn
                    component="a"
                    :href="route('pegawai.hasil_uji.riwayat', histori.hasil_uji?.id || '')"
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
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Status Saat Log</span>
                        <div class="mt-1">
                            <v-chip size="small" variant="tonal" color="primary" class="font-bold uppercase">
                                {{ histori.status }}
                            </v-chip>
                        </div>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Waktu Log</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-xs">{{ formatTanggal(histori.created_at) }}</p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Diperbarui Oleh</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-xs">{{ histori.diupdate_oleh ?? '-' }}</p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Nomor LHU</span>
                        <p class="font-bold text-emerald-700 dark:text-emerald-400 mt-0.5 text-xs">
                            HU-{{ histori.hasil_uji?.id?.toString().padStart(4, '0') ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-2 border-t border-slate-100 dark:border-slate-800">
                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Instansi Pemohon:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ histori.hasil_uji?.pengujian?.form_pengajuan?.instansi?.nama ?? '-' }}</p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Kategori Baku Mutu:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ histori.hasil_uji?.pengujian?.form_pengajuan?.kategori?.nama ?? '-' }}</p>
                    </div>
                </div>

                <!-- Parameter Table -->
                <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                        Nilai Parameter pada Snapshot Ini
                    </h3>

                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold">
                                    <th class="px-4 py-2.5 text-center w-12">No</th>
                                    <th class="px-4 py-2.5 text-left">Nama Parameter</th>
                                    <th class="px-4 py-2.5 text-center">Nilai</th>
                                    <th class="px-4 py-2.5 text-center">Satuan</th>
                                    <th class="px-4 py-2.5 text-center">Baku Mutu</th>
                                    <th class="px-4 py-2.5 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr
                                    v-for="(item, idx) in data_parameter"
                                    :key="item.id || idx"
                                    class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"
                                >
                                    <td class="px-4 py-2.5 text-center text-slate-500">{{ idx + 1 }}</td>
                                    <td class="px-4 py-2.5 font-semibold text-slate-900 dark:text-slate-100">{{ item.nama_parameter }}</td>
                                    <td class="px-4 py-2.5 text-center font-extrabold text-emerald-700 dark:text-emerald-400">{{ item.nilai ?? '-' }}</td>
                                    <td class="px-4 py-2.5 text-center text-slate-500">{{ item.satuan ?? '-' }}</td>
                                    <td class="px-4 py-2.5 text-center text-slate-600 dark:text-slate-400">{{ item.baku_mutu ?? '-' }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400">{{ item.keterangan ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>