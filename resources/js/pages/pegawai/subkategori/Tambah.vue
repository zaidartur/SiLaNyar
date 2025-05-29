<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

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

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
        @click.self="closeModal">
        <div
            class="w-full max-w-4xl mx-auto overflow-hidden rounded-2xl shadow-lg lg:grid lg:min-h-[600px] lg:grid-cols-3 bg-white">
            <div class="hidden bg-customDarkGreen lg:col-span-1 lg:flex lg:items-center lg:justify-center flex-col">
                <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="w-auto h-48 object-contain mx-auto" />
                <div class="text-center text-white mt-6">
                    <h2 class="text-2xl font-bold mb-2 border-b border-white pb-2">SiLanYar</h2>
                    <p class="text-sm">Sistem Laboratoruim Karanganyar</p>
                </div>
            </div>

            <div class="flex items-center justify-center p-12 lg:col-span-2 bg-white">
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid gap-2 text-center">
                        <h1 class="text-xl font-bold mb-4">Tambah Sub Kategori</h1>
                    </div>
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <label class="block mb-1">Nama Sub Kategori</label>
                            <input v-model="form.nama" type="text" class="border rounded px-3 py-2 w-full" />
                            <div v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</div>
                        </div>

                        <div class="grid gap-2">
                            <label class="block mb-1">Parameter dan baku Mutu</label>
                            <div v-for="(param, index) in form.parameter" :key="param.id" class="mb-2">
                                <input type="checkbox" v-model="param.checked" :id="'param-' + param.id" />
                                <label class="block text-sm font-semibold">{{ props.parameter[index].nama_parameter
                                    }}</label>
                                <input v-model="param.baku_mutu" type="text" class="w-48 rounded border px-3 py-2"
                                    :disabled="!param.checked" placeholder="Baku Mutu" />
                                <div class="text-red-500 text-sm">
                                    {{ param.checked ? (form.errors as any)[`parameter.${index}.baku_mutu`] : '' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>