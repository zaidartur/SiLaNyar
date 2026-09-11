<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    subkategori: {
        id: number;
        nama: string;
        parameter: {
            id: number;
            kode_parameter: string;
            nama_parameter: string;
            satuan: string;
            pivot: {
                baku_mutu: string;
            };
        }[];
    };
}>();
</script>

<template>
    <Head title="Detail Sub-Kategori Sampel" />
    <AdminLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                        Klasifikasi Baku Mutu
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Detail Sub-Kategori: {{ subkategori.nama }}
                    </h1>
                </div>

                <v-btn
                    component="a"
                    href="/pegawai/subkategori"
                    variant="outlined"
                    rounded="lg"
                    size="small"
                    prepend-icon="mdi-arrow-left"
                    class="text-none font-semibold text-xs"
                >
                    Kembali
                </v-btn>
            </div>

            <!-- Card Informasi Parameter -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">{{ subkategori.nama }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Daftar parameter uji dan acuan baku mutu yang ditetapkan</p>
                    </div>
                    <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400">
                        {{ subkategori.parameter?.length || 0 }} parameter
                    </span>
                </div>

                <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold">
                                <th class="px-4 py-2.5 text-left w-32">Kode Parameter</th>
                                <th class="px-4 py-2.5 text-left">Nama Parameter</th>
                                <th class="px-4 py-2.5 text-center w-28">Satuan</th>
                                <th class="px-4 py-2.5 text-left w-48">Nilai Baku Mutu Acuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr
                                v-for="param in subkategori.parameter"
                                :key="param.id"
                                class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"
                            >
                                <td class="px-4 py-2.5 font-mono text-slate-500">{{ param.kode_parameter }}</td>
                                <td class="px-4 py-2.5 font-semibold text-slate-900 dark:text-slate-100">{{ param.nama_parameter }}</td>
                                <td class="px-4 py-2.5 text-center text-slate-500">{{ param.satuan || '-' }}</td>
                                <td class="px-4 py-2.5">
                                    <v-chip size="x-small" variant="tonal" color="primary" class="font-bold">
                                        {{ param.pivot?.baku_mutu || '-' }}
                                    </v-chip>
                                </td>
                            </tr>
                            <tr v-if="!subkategori.parameter || subkategori.parameter.length === 0">
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400">
                                    Belum ada parameter yang didaftarkan pada sub-kategori ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>