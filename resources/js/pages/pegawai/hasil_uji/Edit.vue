<script setup lang="ts">
/* eslint-disable */
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    hasil_uji: any;
    pengujian: any;
    parameter: {
        id: number;
        nama: string;
        satuan: string | null;
        baku_mutu: string | null;
        nilai: string | null;
        keterangan: string | null;
    }[];
}>();

const form = useForm({
    hasil: props.parameter.map((param) => ({
        id_parameter: param.id,
        nilai: param.nilai ?? '',
        keterangan: param.keterangan ?? '',
    })),
});

const submit = () => {
    form.put(route('pegawai.hasil_uji.update', props.hasil_uji.id));
};
</script>

<template>
    <Head title="Edit Hasil Pengujian Sampel" />
    <AdminLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Koreksi Analisis
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            #HU-{{ props.hasil_uji.id }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Edit Nilai Hasil Pengujian
                    </h1>
                </div>

                <v-btn
                    component="a"
                    href="/pegawai/hasiluji"
                    variant="outlined"
                    rounded="lg"
                    size="small"
                    prepend-icon="mdi-arrow-left"
                    class="text-none font-semibold text-xs"
                >
                    Kembali
                </v-btn>
            </div>

            <!-- Card Ringkasan Pengujian -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Kode Pengujian</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ pengujian.kode_pengujian }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Instansi Pemohon</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ pengujian.form_pengajuan?.instansi?.nama ?? '-' }}</p>
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400 dark:text-slate-500">Kategori Baku Mutu</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ pengujian.form_pengajuan?.kategori?.nama ?? '-' }}</p>
                    </div>
                </div>

                <!-- Form Tabel Parameter -->
                <form @submit.prevent="submit" class="space-y-4 pt-2">
                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold">
                                    <th class="px-4 py-2.5 text-left">Nama Parameter</th>
                                    <th class="px-4 py-2.5 text-center w-24">Satuan</th>
                                    <th class="px-4 py-2.5 text-left w-40">Nilai Hasil Uji *</th>
                                    <th class="px-4 py-2.5 text-left w-48">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr
                                    v-for="(param, index) in props.parameter"
                                    :key="param.id"
                                    class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"
                                >
                                    <td class="px-4 py-2.5 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ param.nama }}
                                    </td>
                                    <td class="px-4 py-2.5 text-center text-slate-500">
                                        {{ param.satuan || '-' }}
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <input
                                            type="text"
                                            v-model="form.hasil[index].nilai"
                                            required
                                            class="w-full rounded border px-2.5 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                                        />
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <input
                                            type="text"
                                            v-model="form.hasil[index].keterangan"
                                            class="w-full rounded border px-2.5 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                        <v-btn
                            component="a"
                            href="/pegawai/hasiluji"
                            variant="text"
                            size="small"
                            class="text-none text-xs"
                        >
                            Batal
                        </v-btn>

                        <v-btn
                            type="submit"
                            color="primary"
                            rounded="lg"
                            prepend-icon="mdi-content-save-edit"
                            :loading="form.processing"
                            class="text-none font-semibold text-xs px-6"
                        >
                            Simpan Perubahan
                        </v-btn>
                    </div>
                </form>
            </v-card>
        </div>
    </AdminLayout>
</template>