<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'

interface User {
    id: number
    nama: string
}

interface Instansi {
    id: number
    nama: string
    user: User
}

interface Pengajuan {
    id: number
    kode_pengajuan: string
    metode_pengambilan: string
    lokasi: string
    instansi: Instansi
}

interface Jadwal {
    id: number
    kode_pengambilan: string
    form_pengajuan: Pengajuan
    user: User
    waktu_pengambilan: string
    status: 'diproses' | 'selesai'
    keterangan: string
}

const props = defineProps<{
    jadwal: Jadwal
}>()

const formatTanggal = (tanggalStr: string) => {
    const date = new Date(tanggalStr)
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}
</script>

<template>
    <AdminLayout>
        <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold text-black">Detail Jadwal Pengambilan</h1>

            <div class="grid grid-cols-2 gap-4 bg-white p-4 rounded shadow">
                <div><strong>Kode Pengambilan:</strong> {{ props.jadwal.kode_pengambilan }}</div>
                <div><strong>Kode Pengajuan:</strong> {{ props.jadwal.form_pengajuan?.kode_pengajuan }}</div>
                <div><strong>Nama Pemohon:</strong> {{ props.jadwal.form_pengajuan?.instansi?.user?.nama }}</div>
                <div><strong>Nama Instansi:</strong> {{ props.jadwal.form_pengajuan?.instansi?.nama }}</div>
                <div><strong>Metode Pengambilan:</strong> {{ props.jadwal.form_pengajuan?.metode_pengambilan }}</div>
                <div><strong>Waktu Pengambilan:</strong> {{ formatTanggal(props.jadwal.waktu_pengambilan) }}</div>
                <div><strong>Status:</strong> {{ props.jadwal.status }}</div>
                <div><strong>Keterangan:</strong> {{ props.jadwal.keterangan }}</div>
            </div>
        </div>
    </AdminLayout>
</template>
