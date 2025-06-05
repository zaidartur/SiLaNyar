<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'

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
    metode_pengambilan: string
}

interface Jadwal {
    id: number
    form_pengajuan: Pengajuan
    user: User
    waktu_pengambilan: string
    status: 'diproses' | 'selesai'
    keterangan: string
}

const props = defineProps<{
    jadwal: Jadwal
}>()

const metode = props.jadwal.form_pengajuan?.metode_pengambilan

const formatDateForInput = (dateString: string) => {
    const date = new Date(dateString)
    return date.toISOString().split('T')[0]
}

const form = useForm({
    waktu_pengambilan: formatDateForInput(props.jadwal.waktu_pengambilan),
    status: props.jadwal.status,
    keterangan: props.jadwal.keterangan || '',
})

const submit = () => {
    // Validasi sebelum submit
    if (metode === 'diantar') {
        // Hanya status yang boleh diubah
        form.put(`/pegawai/pengambilan/${props.jadwal.id}/edit`, {
            only: ['status']
        })
    } else if (metode === 'diambil') {
        // Status dan keterangan boleh diubah
        form.put(`/pegawai/pengambilan/${props.jadwal.id}/edit`, {
            only: ['status', 'keterangan']
        })
    }
}
</script>

<template>
    <AdminLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold text-black">Edit Jadwal Pengambilan</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Kode Form Pengajuan</label>
                    <input :value="props.jadwal.form_pengajuan?.kode_pengajuan" class="w-full" disabled />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengambil/Penerima</label>
                    <input :value="props.jadwal.user?.nama"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600" disabled />
                </div>

                <div>
                    <label class="block text-sm font-medium">Waktu Pengambilan</label>
                    <input type="date" v-model="form.waktu_pengambilan"
                        class="w-full rounded border border-gray-300 p-2"
                        :disabled="metode === 'diantar' || metode === 'diambil'" />
                    <div v-if="form.errors.waktu_pengambilan" class="text-red-500 text-sm mt-1">
                        {{ form.errors.waktu_pengambilan }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select v-model="form.status" class="w-full rounded border border-gray-300 p-2">
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                    <div v-if="form.errors.status" class="text-red-500 text-sm mt-1">
                        {{ form.errors.status }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Keterangan</label>
                    <textarea v-model="form.keterangan" class="w-full rounded border border-gray-300 p-2"
                        :disabled="metode === 'diantar'"></textarea>
                    <div v-if="form.errors.keterangan" class="text-red-500 text-sm mt-1">
                        {{ form.errors.keterangan }}
                    </div>
                </div>

                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
            </form>
        </div>
    </AdminLayout>
</template>