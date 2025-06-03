<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

interface Parameter {
    id: number
    kode_parameter: string
    nama_parameter: string
    satuan: string
    harga: number
}

const props = defineProps<{
    parameter: Parameter[]
}>()

const form = useForm({
    nama: '',
    parameter: props.parameter.map(param => ({
        id: param.id,
        checked: false,
        baku_mutu: ''
    }))
})

const submit = () => {
    const filterParam = form.parameter.filter(p => p.checked)

    if (filterParam.length === 0) {
        alert('Pilih minimal satu parameter!');
        return;
    }

    form.parameter = filterParam
    form.post('/pegawai/subkategori/store')
}
</script>

<template>
    <div class="w-full h-screen lg:grid lg:grid-cols-3 bg-white">
            <!-- Left Side - Logo Section -->
            <div class="hidden bg-customDarkGreen lg:col-span-1 lg:flex lg:items-center lg:justify-center flex-col h-screen">
                <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="w-auto h-48 object-contain mx-auto" />
                <div class="text-center text-white mt-6">
                    <h2 class="text-2xl font-bold mb-2 border-b border-white pb-2">SiLanYar</h2>
                    <p class="text-sm">Sistem Laboratoruim Karanganyar</p>
                </div>
            </div>

            <!-- Right Side - Form Section -->
            <div class="flex items-start justify-center p-12 lg:col-span-2 bg-white overflow-y-auto h-screen">
                <form @submit.prevent="submit" class="w-full max-w-xl mx-auto grid gap-6 p-6 md:p-12">
                    <div class="grid gap-2 text-center">
                        <h1 class="text-3xl font-bold">Tambah Sub Kategori</h1>
                    </div>
                    <div class="grid gap-4">
                        <!-- Nama Sub Kategori -->
                        <div class="grid gap-2">
                            <label for="nama" class="font-semibold">Nama Sub Kategori</label>
                            <input id="nama" v-model="form.nama" type="text" placeholder="Masukkan nama subkategori"
                                class="border rounded px-3 py-2 w-full" required />
                            <span v-if="form.errors.nama" class="text-sm text-red-600">
                                {{ form.errors.nama }}
                            </span>
                        </div>

                        <!-- Parameter dan Baku Mutu -->
                        <div class="grid gap-2">
                            <label class="font-semibold">Parameter dan Baku Mutu</label>
                            <div v-for="(param, index) in form.parameter" :key="param.id"
                                class="flex items-center mb-2 gap-2">
                                <input type="checkbox" v-model="param.checked" :id="'param-' + param.id" />
                                <label :for="'param-' + param.id" class="text-sm font-semibold">
                                    {{ props.parameter[index].nama_parameter }}
                                </label>
                                <input v-model="param.baku_mutu" type="text" class="w-48 rounded border px-3 py-2"
                                    :disabled="!param.checked" placeholder="Baku Mutu" />
                                <div class="text-red-500 text-sm">
                                    {{ param.checked ? (form.errors as any)[`parameter.${index}.baku_mutu`] : '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors mb-8">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
</template>