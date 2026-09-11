<script setup lang="ts">
/* eslint-disable */
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

interface Parameter {
    id: number;
    nama: string;
    satuan: string;
    baku_mutu: string | null;
}

interface Pengujian {
    id: number;
    kode_pengujian: string;
    status: string;
    form_pengajuan: {
        kode_pengajuan: string;
        instansi: {
            nama: string;
        };
    };
}

const props = defineProps<{
    pengujianList: Pengujian[];
    pilihPengujian: Pengujian | null;
    parameter: Parameter[];
}>();

const form = useForm({
    id_pengujian: props.pilihPengujian?.id ?? null,
    hasil: props.parameter.map((param) => ({
        id_parameter: param.id,
        nilai: '',
        keterangan: '',
    })),
});

const pengujianSelesai = computed(() =>
    props.pengujianList.filter((item) => item.status === 'selesai')
);

watch(
    () => form.id_pengujian,
    (newVal) => {
        if (newVal !== props.pilihPengujian?.id) {
            window.location.href = `?id_pengujian=${newVal}`;
        }
    }
);

const submit = () => {
    form.post('/pegawai/hasiluji/store');
};
</script>

<template>
    <Head title="Input Hasil Pengujian Sampel" />
    <AdminLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                        Hasil Analisis
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Input Hasil Uji Laboratorium
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

            <!-- Form Card -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Pilih Pengujian Selesai -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Pilih Pengujian yang Telah Selesai Dianalisis <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.id_pengujian"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[form.errors.id_pengujian ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        >
                            <option :value="null" disabled>-- Pilih Nomor Pengujian --</option>
                            <option v-for="item in pengujianSelesai" :key="item.id" :value="item.id">
                                {{ item.kode_pengujian }} - {{ item.form_pengajuan.instansi.nama }}
                            </option>
                        </select>
                        <p v-if="form.errors.id_pengujian" class="text-xs text-rose-600 mt-1 font-medium">
                            {{ form.errors.id_pengujian }}
                        </p>
                    </div>

                    <!-- Parameter & Hasil Input Grid -->
                    <div v-if="props.parameter.length > 0" class="pt-2">
                        <label class="block text-xs font-semibold mb-2 text-slate-700 dark:text-slate-300">
                            Isi Nilai Hasil Analisis per Parameter ({{ props.parameter.length }} parameter):
                        </label>

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
                                                placeholder="Nilai angka/hasil"
                                                class="w-full rounded border px-2.5 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                                            />
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <input
                                                type="text"
                                                v-model="form.hasil[index].keterangan"
                                                placeholder="Opsional (misal: < LOQ)"
                                                class="w-full rounded border px-2.5 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-else-if="form.id_pengujian" class="p-6 text-center text-slate-400 text-xs">
                        Memuat data parameter pengujian...
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
                            prepend-icon="mdi-content-save"
                            :loading="form.processing"
                            :disabled="!props.parameter.length"
                            class="text-none font-semibold text-xs px-6"
                        >
                            Simpan Hasil Uji
                        </v-btn>
                    </div>
                </form>
            </v-card>
        </div>
    </AdminLayout>
</template>
