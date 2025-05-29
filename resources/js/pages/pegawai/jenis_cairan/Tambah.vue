<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    nama: '',
    batas_minimum: 0,
    batas_maksimum: null,
})

const submit = () => {
    form.post('/pegawai/jenis-cairan/store')
}
</script>

<template>
    <div class="p-6 max-w-md mx-auto">
        <h1 class="text-xl font-bold mb-4">Tambah Jenis Cairan</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block mb-1">Nama Jenis Cairan</label>
                <input v-model="form.nama" type="text" class="border rounded px-3 py-2 w-full" />
                <div v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</div>
            </div>

            <div>
                <label class="block mb-1">Batas Minimum</label>
                <input v-model="form.batas_minimum" type="text" inputmode="numeric" class="border rounded px-3 py-2 w-full" />
                <div v-if="form.errors.batas_minimum" class="text-red-500 text-sm">{{ form.errors.batas_minimum }}</div>
            </div>

            <div>
                <label class="block mb-1">Batas Maksimum</label>
                <input v-model="form.batas_maksimum" type="text" inputmode="numeric" class="border rounded px-3 py-2 w-full" />
                <div v-if="form.errors.batas_maksimum" class="text-red-500 text-sm">{{ form.errors.batas_maksimum }}</div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </form>
    </div>
</template>
