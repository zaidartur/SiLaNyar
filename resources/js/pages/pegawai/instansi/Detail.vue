<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

interface User {
    id: number
    nama: string
}

interface Instansi {
    id: number
    kode_instansi: string
    nama: string
    tipe?: 'swasta' | 'pemerintahan' | 'pribadi'
    alamat: string
    wilayah?: string
    desa_kelurahan?: string
    email: string
    no_telepon: string
    posisi_jabatan: string
    departemen_divisi: string
    status_verifikasi?: 'diproses' | 'diterima' | 'ditolak'
    created_at: string
    diverifikasi_oleh: string | null
    user: User
}

const props = defineProps<{
    instansi: Instansi
}>()

const form = useForm({
    status_verifikasi: '' as 'diterima' | 'ditolak'
})

const verifikasi = (status: 'diterima' | 'ditolak') => {
    form.status_verifikasi = status
    form.put(`/pegawai/instansi/${props.instansi.id}/edit`, {
        onSuccess: () => {
            form.reset()
        }
    })
}
</script>

<template>

    <Head title="Detail Instansi" />
    <AdminLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold text-black">Detail Instansi</h1>

            <div class="bg-white rounded-lg shadow p-6">
                <table class="w-full text-left table-auto">
                    <tbody>
                        <tr>
                            <th class="px-4 py-2">Kode Instansi</th>
                            <td class="px-4 py-2">{{ props.instansi.kode_instansi }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Nama Instansi</th>
                            <td class="px-4 py-2">{{ props.instansi.nama }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Nama PIC / Customer</th>
                            <td class="px-4 py-2">{{ props.instansi.user?.nama }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Tipe</th>
                            <td class="px-4 py-2">{{ props.instansi.tipe }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Alamat</th>
                            <td class="px-4 py-2">{{ props.instansi.alamat }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Wilayah</th>
                            <td class="px-4 py-2">{{ props.instansi.wilayah }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Desa / Kelurahan</th>
                            <td class="px-4 py-2">{{ props.instansi.desa_kelurahan }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Email</th>
                            <td class="px-4 py-2">{{ props.instansi.email }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">No Telepon</th>
                            <td class="px-4 py-2">{{ props.instansi.no_telepon }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Jabatan</th>
                            <td class="px-4 py-2">{{ props.instansi.posisi_jabatan }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Departemen / Divisi</th>
                            <td class="px-4 py-2">{{ props.instansi.departemen_divisi }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Status Verifikasi</th>
                            <td class="px-4 py-2 capitalize">{{ props.instansi.status_verifikasi }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Diverifikasi Oleh</th>
                            <td class="px-4 py-2">{{ props.instansi.diverifikasi_oleh ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-2">Tanggal Dibuat</th>
                            <td class="px-4 py-2">{{ new Date(props.instansi.created_at).toLocaleString() }}</td>
                        </tr>
                    </tbody>
                    <div v-if="props.instansi.status_verifikasi === 'diproses'" class="flex gap-4 mt-4">
                        <button @click="verifikasi('diterima')"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                            :disabled="form.processing">
                            ✔️ Terima
                        </button>
                        <button @click="verifikasi('ditolak')"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                            :disabled="form.processing">
                            ❌ Tolak
                        </button>
                    </div>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
