<script lang="ts" setup>
import { useForm, usePage } from '@inertiajs/vue3'

interface User {
    id: number
    nama: string
}
interface Instansi {
    id: number
    nama: string
}

interface Pengajuan {
    id: number
    kode_pengajuan: string
    instansi: Instansi
}

const { props } = usePage();
const form_pengajuan = props.form_pengajuan as Pengajuan[]
const user = props.user as User[]

const form = useForm({
    id_form_pengajuan: '',
    id_user: '',
    waktu_pengambilan: null,
    keterangan: ''
})

const submit = () => {
    form.post('/pegawai/pengambilan/store')
}
</script>

<template>
    <div class="p-6 max-w-md mx-auto">
        <h1 class="text-xl font-bold mb-4">Tambah Jadwal Pengambilan</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label>Kode Form Pengajuan</label>
                <select v-model="form.id_form_pengajuan" class="w-full border rounded">
                    <option value="">Pilih Kode Form Pengajuan</option>
                    <option v-for="fp in form_pengajuan" :key="fp.id" :value="fp.id">
                        {{ fp.kode_pengajuan }} - {{ fp.instansi.nama }}
                    </option>
                </select>
            </div>

            <div>
                <label>Nama Teknisi</label>
                <select v-model="form.id_user" class="w-full border rounded">
                    <option value="">Pilih Teknisi</option>
                    <option v-for="u in user" :key="u.id" :value="u.id">
                        {{ u.nama }}
                    </option>
                </select>
            </div>

            <div>
                <label>Waktu Pengambilan</label>
                <input type="date" v-model="form.waktu_pengambilan" class="w-full border rounded" />
            </div>

            <div>
                <label>Keterangan (Opsional)</label>
                <textarea v-model="form.keterangan" class="w-full border rounded" rows="3"></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </form>
    </div>
</template>
