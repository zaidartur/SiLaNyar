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
</script>

<template>
    <div class="p-6 max-w-md mx-auto">
        <h1 class="text-xl font-bold mb-4">Tambah Sub Kategori</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block mb-1">Nama Sub Kategori</label>
                <input v-model="form.nama" type="text" class="border rounded px-3 py-2 w-full" />
                <div v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</div>
            </div>

            <div>
                <label class="block mb-1">Parameter dan baku Mutu</label>
                <div v-for="(param, index) in form.parameter" :key="param.id" class="mb-2">
                    <input type="checkbox" v-model="param.checked" :id="'param-' + param.id" />
                    <label class="block text-sm font-semibold">{{ props.parameter[index].nama_parameter }}</label>
                    <input v-model="param.baku_mutu" type="text" class="w-48 rounded border px-3 py-2"
                        :disabled="!param.checked" placeholder="Baku Mutu" />
                    <div class="text-red-500 text-sm">
                        {{ param.checked ? (form.errors as any)[`parameter.${index}.baku_mutu`] : '' }}
                    </div>
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </form>
    </div>
</template>