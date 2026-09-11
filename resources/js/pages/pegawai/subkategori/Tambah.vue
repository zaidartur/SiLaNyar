<script setup lang="ts">
/* eslint-disable */
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Parameter {
    id: number;
    kode_parameter: string;
    nama_parameter: string;
    satuan: string;
    harga: number;
}

const props = defineProps<{
    parameter: Parameter[];
}>();

const form = useForm({
    nama: '',
    parameter: props.parameter.map((param) => ({
        id: param.id,
        checked: false,
        baku_mutu: '',
    })),
});

const errorMessage = ref('');

const submit = () => {
    errorMessage.value = '';
    const filterParam = form.parameter.filter((p) => p.checked);

    if (filterParam.length === 0) {
        errorMessage.value = 'Silakan pilih minimal satu parameter untuk sub-kategori ini.';
        return;
    }

    form.parameter = filterParam;
    form.post('/pegawai/subkategori/store');
};
</script>

<template>
    <Head title="Tambah Sub-Kategori Sampel" />
    <AdminLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                        Klasifikasi Baku Mutu
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Tambah Sub-Kategori Baru
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

            <!-- Form Card -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Nama Sub-Kategori <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            v-model="form.nama"
                            placeholder="Contoh: Air Limbah Domestik, Air Badan Air Kelas II"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                        />
                        <p v-if="form.errors.nama" class="text-xs text-rose-600 mt-1 font-medium">
                            {{ form.errors.nama }}
                        </p>
                    </div>

                    <!-- Parameter List & Baku Mutu -->
                    <div>
                        <label class="block text-xs font-semibold mb-2 text-slate-700 dark:text-slate-300">
                            Parameter yang Berlaku & Nilai Baku Mutu Acuan <span class="text-rose-500">*</span>
                        </label>

                        <v-alert v-if="errorMessage" type="error" variant="tonal" rounded="lg" density="compact" class="text-xs mb-3">
                            {{ errorMessage }}
                        </v-alert>

                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold">
                                        <th class="px-4 py-2.5 text-center w-12">Pilih</th>
                                        <th class="px-4 py-2.5 text-left">Nama Parameter</th>
                                        <th class="px-4 py-2.5 text-center">Satuan</th>
                                        <th class="px-4 py-2.5 text-left">Standar Baku Mutu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <tr
                                        v-for="(param, idx) in props.parameter"
                                        :key="param.id"
                                        class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40"
                                    >
                                        <td class="px-4 py-2.5 text-center">
                                            <input
                                                type="checkbox"
                                                v-model="form.parameter[idx].checked"
                                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                            />
                                        </td>
                                        <td class="px-4 py-2.5 font-medium text-slate-900 dark:text-slate-100">
                                            {{ param.nama_parameter }}
                                        </td>
                                        <td class="px-4 py-2.5 text-center text-slate-500">
                                            {{ param.satuan || '-' }}
                                        </td>
                                        <td class="px-4 py-2.5">
                                            <input
                                                type="text"
                                                v-model="form.parameter[idx].baku_mutu"
                                                :disabled="!form.parameter[idx].checked"
                                                placeholder="Contoh: 6 - 9, maks 50 mg/L"
                                                class="w-full rounded border px-2.5 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-emerald-500 border-slate-200 dark:border-slate-700 disabled:opacity-40"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                        <v-btn
                            component="a"
                            href="/pegawai/subkategori"
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
                            class="text-none font-semibold text-xs px-6"
                        >
                            Simpan Sub-Kategori
                        </v-btn>
                    </div>
                </form>
            </v-card>
        </div>
    </AdminLayout>
</template>
