<script setup lang="ts">
/* eslint-disable */
import { useForm } from '@inertiajs/vue3';
// import { ref } from 'vue'

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

const submit = () => {
    const filterParam = form.parameter.filter((p) => p.checked);

    if (filterParam.length === 0) {
        alert('Pilih minimal satu parameter!');
        return;
    }

    form.parameter = filterParam;
    form.post('/pegawai/subkategori/store');
};
</script>

<template>
    <div class="h-screen w-full bg-white lg:grid lg:grid-cols-3">
        <!-- Left Side - Logo Section -->
        <div class="hidden h-screen flex-col bg-customDarkGreen lg:col-span-1 lg:flex lg:items-center lg:justify-center">
            <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="mx-auto h-48 w-auto object-contain" />
            <div class="mt-6 text-center text-white">
                <h2 class="mb-2 border-b border-white pb-2 text-2xl font-bold">SiLanYar</h2>
                <p class="text-sm">Sistem Laboratoruim Karanganyar</p>
            </div>
        </div>

        <!-- Right Side - Form Section -->
        <div class="flex h-screen items-start justify-center overflow-y-auto bg-white p-12 lg:col-span-2">
            <form @submit.prevent="submit" class="mx-auto grid w-full max-w-xl gap-6 p-6 md:p-12">
                <div class="grid gap-2 text-center">
                    <h1 class="text-3xl font-bold">Tambah Sub Kategori</h1>
                </div>
                <div class="grid gap-4">
                    <!-- Nama Sub Kategori -->
                    <div class="grid gap-2">
                        <label for="nama" class="font-semibold">Nama Sub Kategori</label>
                        <input
                            id="nama"
                            v-model="form.nama"
                            type="text"
                            placeholder="Masukkan nama subkategori"
                            class="w-full rounded border px-3 py-2"
                            required
                        />
                        <span v-if="form.errors.nama" class="text-sm text-red-600">
                            {{ form.errors.nama }}
                        </span>
                    </div>

                    <!-- Parameter dan Baku Mutu -->
                    <div class="grid gap-2">
                        <label class="font-semibold">Parameter dan Baku Mutu</label>
                        <div v-for="(param, index) in form.parameter" :key="param.id" class="mb-2 flex items-center gap-2">
                            <input type="checkbox" v-model="param.checked" :id="'param-' + param.id" />
                            <label :for="'param-' + param.id" class="text-sm font-semibold">
                                {{ props.parameter[index].nama_parameter }}
                            </label>
                            <input
                                v-model="param.baku_mutu"
                                type="text"
                                class="w-48 rounded border px-3 py-2"
                                :disabled="!param.checked"
                                placeholder="Baku Mutu"
                            />
                            <div class="text-sm text-red-500">
                                {{ param.checked ? (form.errors as any)[`parameter.${index}.baku_mutu`] : '' }}
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="mb-8 w-full rounded bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700">Simpan</button>
            </form>
        </div>
    </div>
</template>
