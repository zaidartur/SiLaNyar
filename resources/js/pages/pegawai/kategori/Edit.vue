<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface Parameter {
    id: number
    kode_parameter: string
    nama_parameter: string
    satuan: string
    harga: number
    pivot?: {
        baku_mutu: string
    }
}

interface SubKategori {
    id: number
    kode_subkategori: string
    nama: string
}

interface Kategori {
    id: number
    kode_kategori: string
    nama: string
    harga: number
    subkategori: SubKategori[]
    parameter: Parameter[]
}

const props = defineProps<{
    kategori: Kategori
    subkategori: SubKategori[]
    parameter: Parameter[]
}>()

const form = useForm({
    nama: props.kategori.nama,
    harga: props.kategori.harga,
    subkategori: props.kategori.subkategori?.map(sub => sub.id) ?? [],
    parameter: props.parameter.map(param => ({
        id: param.id,
        nama_parameter: param.nama_parameter,
        checked: !!param.pivot,
        baku_mutu: param.pivot?.baku_mutu ?? ''
    }))
})

const submit = () => {
    const filterParam = form.parameter.filter(p => p.checked)

    if (form.subkategori.length === 0 && filterParam.length === 0) {
        alert('Pilih minimal satu subkategori atau parameter!');
        return;
    }

    form.parameter = filterParam
    form.put(`/pegawai/kategori/${props.kategori.id}/edit`)
}
</script>


<template>
    <div class="p-6 max-w-md mx-auto">
        <h1 class="text-xl font-bold mb-4">Edit Kategori</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block mb-1">Nama Kategori</label>
                <input v-model="form.nama" type="text" class="border rounded px-3 py-2 w-full" />
                <div v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</div>
            </div>

            <div>
                <label class="block mb-1">Harga</label>
                <input v-model="form.harga" type="text" inputmode="numeric" class="border rounded px-3 py-2 w-full" />
                <div v-if="form.errors.harga" class="text-red-500 text-sm">{{ form.errors.harga }}</div>
            </div>

            <div>
                <label class="block mb-1">Subkategori</label>
                <div v-for="sub in props.subkategori" :key="sub.id" class="mb-2">
                    <input type="checkbox" :value="sub.id" v-model="form.subkategori" :id="'sub-' + sub.id" />
                    <label class="block text-sm font-semibold">{{ sub.nama }}</label>
                </div>
                <div class="text-red-500 text-sm">{{ form.errors.subkategori }}</div>
            </div>

            <div>
                <label class="block mb-1">Parameter dan baku Mutu</label>
                <div v-for="(param, index) in form.parameter" :key="param.id" class="mb-2">
                    <input type="checkbox" v-model="param.checked" :id="'param-' + param.id" />
                    <label class="block text-sm font-semibold">{{ param.nama_parameter }}</label>
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