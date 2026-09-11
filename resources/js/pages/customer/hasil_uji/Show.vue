<script setup lang="ts">
/* eslint-disable */
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head } from '@inertiajs/vue3';

interface ParameterPengujian {
    id_parameter: number;
    nama_parameter: string;
    satuan: string | null;
    nilai: string | null;
    baku_mutu: string | null;
    keterangan: string | null;
}

interface HasilUji {
    id: number;
    status: string;
    created_at: string;
    pengujian: {
        kode_pengujian: string;
        form_pengajuan: {
            kode_pengajuan: string;
            kategori: {
                nama: string;
            };
            instansi: {
                nama: string;
                user: {
                    nama: string;
                };
            };
        };
        user: {
            nama: string;
        };
    };
}

const props = defineProps<{
    hasil_uji: HasilUji;
    parameter_pengujian: ParameterPengujian[];
}>();

const statusLabels: Record<string, string> = {
    draf: 'Draf',
    revisi: 'Revisi',
    proses_review: 'Proses Review',
    proses_peresmian: 'Proses Peresmian',
    selesai: 'Selesai',
};

function kembali() {
    window.history.back();
}

function bukaPDF() {
    window.open(route('hasil_uji.convert', props.hasil_uji.id), '_blank');
}
</script>

<template>
    <Head title="Detail Hasil Uji Laboratorium" />
    <CustomerLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Sertifikat Hasil Uji
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            #HU-{{ props.hasil_uji.id.toString().padStart(4, '0') }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Laporan Hasil Pengujian (LHU)
                    </h1>
                </div>

                <div class="flex items-center gap-2">
                    <v-btn
                        color="primary"
                        rounded="lg"
                        size="small"
                        prepend-icon="mdi-file-pdf-box"
                        @click="bukaPDF"
                        class="text-none font-semibold text-xs"
                    >
                        Cetak LHU (PDF)
                    </v-btn>

                    <v-btn
                        variant="outlined"
                        rounded="lg"
                        size="small"
                        prepend-icon="mdi-arrow-left"
                        @click="kembali"
                        class="text-none font-semibold text-xs"
                    >
                        Kembali
                    </v-btn>
                </div>
            </div>

            <!-- Card Ringkasan Hasil Uji -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Nomor LHU</span>
                        <p class="font-extrabold text-emerald-700 dark:text-emerald-400 mt-0.5 text-sm">
                            HU-{{ props.hasil_uji.id.toString().padStart(4, '0') }}
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Kode Pengujian</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">
                            {{ props.hasil_uji.pengujian?.kode_pengujian || '-' }}
                        </p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Status Validasi</span>
                        <div class="mt-1">
                            <v-chip
                                size="small"
                                variant="tonal"
                                :color="props.hasil_uji.status === 'selesai' ? 'success' : 'warning'"
                                class="font-bold uppercase"
                            >
                                {{ statusLabels[props.hasil_uji.status] ?? props.hasil_uji.status }}
                            </v-chip>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Tanggal Pengujian</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">
                            {{ new Date(props.hasil_uji.created_at).toLocaleDateString('id-ID') }}
                        </p>
                    </div>
                </div>

                <!-- Info Instansi & Teknisi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-2 border-t border-slate-100 dark:border-slate-800">
                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Instansi Pemohon:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.hasil_uji.pengujian?.form_pengajuan?.instansi?.nama || '-' }}
                        </p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Penanggung Jawab:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.hasil_uji.pengujian?.form_pengajuan?.instansi?.user?.nama || '-' }}
                        </p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Kategori Baku Mutu:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.hasil_uji.pengujian?.form_pengajuan?.kategori?.nama || '-' }}
                        </p>
                    </div>

                    <div class="p-3 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                        <span class="text-slate-400">Teknisi Analis:</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.hasil_uji.pengujian?.user?.nama || '-' }}
                        </p>
                    </div>
                </div>

                <!-- Tabel Parameter Analisis -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                        Hasil Analisis Parameter Laboratorium
                    </h3>

                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold">
                                    <th class="px-4 py-2.5 text-center w-12">No</th>
                                    <th class="px-4 py-2.5 text-left">Parameter Uji</th>
                                    <th class="px-4 py-2.5 text-center">Hasil Nilai</th>
                                    <th class="px-4 py-2.5 text-center">Satuan</th>
                                    <th class="px-4 py-2.5 text-center">Baku Mutu</th>
                                    <th class="px-4 py-2.5 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr
                                    v-for="(item, idx) in props.parameter_pengujian"
                                    :key="item.id_parameter"
                                    class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"
                                >
                                    <td class="px-4 py-2.5 text-center text-slate-500">{{ idx + 1 }}</td>
                                    <td class="px-4 py-2.5 font-semibold text-slate-900 dark:text-slate-100">{{ item.nama_parameter }}</td>
                                    <td class="px-4 py-2.5 text-center font-extrabold text-emerald-700 dark:text-emerald-400">{{ item.nilai ?? '-' }}</td>
                                    <td class="px-4 py-2.5 text-center text-slate-500">{{ item.satuan ?? '-' }}</td>
                                    <td class="px-4 py-2.5 text-center text-slate-600 dark:text-slate-400 font-medium">{{ item.baku_mutu ?? '-' }}</td>
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-400">{{ item.keterangan ?? '-' }}</td>
                                </tr>
                                <tr v-if="!props.parameter_pengujian || props.parameter_pengujian.length === 0">
                                    <td colspan="6" class="px-4 py-4 text-center text-slate-400">
                                        Tidak ada data parameter uji.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </v-card>
        </div>
    </CustomerLayout>
</template>
