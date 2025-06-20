<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'

interface User {
    id: number
    nama: string
}

interface Instansi {
    id: number
    nama: string
    user: User
}

interface JenisCairan {
    id: number
    nama: string
}

interface Parameter {
    id: number
    nama_parameter: string
}

interface Kategori {
    id: number
    nama: string
}

interface Pengajuan {
    id: number
    kode_pengajuan: string
    volume_sampel: number
    status_pengajuan: string
    metode_pengambilan: string
    lokasi: string
    instansi: Instansi
    kategori: Kategori
    jenis_cairan: JenisCairan
    parameter: Parameter[]
}

const props = defineProps<{
    pengajuan: Pengajuan
}>()
</script>

<template>
    <AdminLayout>
        <div class="p-6 max-w-4xl mx-auto bg-white rounded shadow">
            <h1 class="text-2xl font-bold mb-6">Detail Pengajuan</h1>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <h2 class="font-semibold">Kode Pengajuan</h2>
                    <p>{{ props.pengajuan.kode_pengajuan }}</p>
                </div>
                <div>
                    <h2 class="font-semibold">Status Pengajuan</h2>
                    <p class="capitalize">{{ props.pengajuan.status_pengajuan }}</p>
                </div>
                <div>
                    <h2 class="font-semibold">Nama Pemohon</h2>
                    <p>{{ props.pengajuan.instansi.user.nama }}</p>
                </div>
                <div>
                    <h2 class="font-semibold">Nama Instansi</h2>
                    <p>{{ props.pengajuan.instansi.nama }}</p>
                </div>
                <div>
                    <h2 class="font-semibold">Jenis Cairan</h2>
                    <p>{{ props.pengajuan.jenis_cairan.nama }}</p>
                </div>
                <div>
                    <h2 class="font-semibold">Kategori</h2>
                    <p>{{ props.pengajuan.kategori.nama }}</p>
                </div>
                <div>
                    <h2 class="font-semibold">Volume Sampel</h2>
                    <p>{{ props.pengajuan.volume_sampel }} ml</p>
                </div>
                <div>
                    <h2 class="font-semibold">Metode Pengambilan</h2>
                    <p>{{ props.pengajuan.metode_pengambilan }}</p>
                </div>
                <div class="col-span-2">
                    <h2 class="font-semibold">Lokasi</h2>
                    <p>{{ props.pengajuan.lokasi }}</p>
                </div>
            </div>

            <div>
                <h2 class="font-semibold mb-2">Parameter yang Diuji</h2>
                <ul class="list-disc list-inside text-black">
                    <li v-for="param in props.pengajuan.parameter" :key="param.id">
                        {{ param.nama_parameter }}
                    </li>
                </ul>
            </div>

            <!-- Button Kembali -->
            <div class="mt-8">
                <Link href="/pegawai/pengajuan"
                    class="inline-block rounded bg-gray-300 px-4 py-2 font-semibold text-black hover:bg-gray-400">
                Kembali ke Daftar Pengajuan
                </Link>
            </div>
        </div>
    </AdminLayout>
</template>
