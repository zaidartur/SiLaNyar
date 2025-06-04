<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'

interface Kategori {
    id: number
    nama: string
}

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
    instansi: Instansi
}

interface Pengujian {
    id: number
    kode_pengujian: string
    form_pengajuan: Pengajuan
    user: User
    kategori: Kategori
    tanggal_uji: string
    jam_mulai: string
    jam_selesai: string
    status: 'diproses' | 'selesai'
}

const props = defineProps<{
    pengujian: Pengujian
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
                <div><strong>Kode Pengujian:</strong> {{ props.pengujian.kode_pengujian }}</div>
                <div><strong>Nama Instansi:</strong> {{ props.pengujian.form_pengajuan.instansi.nama ?? '-' }}</div>
                <div><strong>Nama Pemohon:</strong> {{ props.pengujian.form_pengajuan.instansi.user.nama ?? '-' }}</div>
                <div><strong>Nama Teknisi:</strong> {{ props.pengujian.user.nama ?? '-' }}</div>
                <div><strong>Nama Kategori:</strong> {{ props.pengujian.kategori.nama ?? '-' }}</div>
                <div><strong>Tanggal Pengujian:</strong> {{ formatTanggal(props.pengujian.tanggal_uji) ?? '-' }}</div>
                <div><strong>Jam Mulai:</strong> {{ props.pengujian.jam_mulai ?? '-' }}</div>
                <div><strong>Jam Selesai:</strong> {{ props.pengujian.jam_selesai ?? '-' }}</div>
                <div><strong>Status:</strong> {{ props.pengujian.status }}</div>
            </div>
        </div>
    </AdminLayout>
</template>
