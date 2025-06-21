<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
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
    <Head title="Detail Pengajuan" />
    <AdminLayout>
        <div class="p-6 max-w-4xl mx-auto bg-white rounded shadow">
            <h1 class="text-2xl font-bold mb-6">Detail Pengajuan</h1>

            <!-- Detail Pengajuan -->
            <div v-if="props.pengajuan"
                class="mb-6 rounded-xl bg-gradient-to-br from-gray-50 to-white p-6 shadow flex flex-col gap-4">
                <h3 class="mb-4 flex items-center gap-2 text-lg font-bold text-blue-900">
                    <span class="inline-block w-6 text-blue-400">📄</span>
                    Detail Pengajuan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-6 text-blue-400">🔢</span>
                        <span class="font-semibold text-gray-700">Kode Pengajuan:</span>
                        <span class="ml-1 text-gray-900">{{ props.pengajuan.kode_pengajuan }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-6 text-blue-400">📄</span>
                        <span class="font-semibold text-gray-700">Status Pengajuan:</span>
                        <span class="ml-1 rounded-full px-3 py-1 text-xs font-bold" :class="{
                            'bg-yellow-100 text-yellow-700 border border-yellow-300': props.pengajuan.status_pengajuan === 'diproses',
                            'bg-green-100 text-green-700 border border-green-300': props.pengajuan.status_pengajuan === 'diterima',
                            'bg-red-100 text-red-700 border border-red-300': props.pengajuan.status_pengajuan === 'ditolak',
                        }">
                            {{ props.pengajuan.status_pengajuan.replace('_', ' ').toUpperCase() }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-6 text-blue-400">👤</span>
                        <span class="font-semibold text-gray-700">Nama Pemohon:</span>
                        <span class="ml-1 text-gray-900">{{ props.pengajuan.instansi.user.nama }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-6 text-blue-400">🏢</span>
                        <span class="font-semibold text-gray-700">Nama Instansi:</span>
                        <span class="ml-1 text-gray-900">{{ props.pengajuan.instansi.nama }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-6 text-blue-400">💧</span>
                        <span class="font-semibold text-gray-700">Jenis Cairan:</span>
                        <span class="ml-1 text-gray-900">{{ props.pengajuan.jenis_cairan.nama }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-6 text-blue-400">📦</span>
                        <span class="font-semibold text-gray-700">Kategori:</span>
                        <span class="ml-1 text-gray-900">{{ props.pengajuan.kategori.nama }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-6 text-blue-400">⚗️</span>
                        <span class="font-semibold text-gray-700">Volume Sampel:</span>
                        <span class="ml-1 text-gray-900">{{ props.pengajuan.volume_sampel }} ml</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-6 text-blue-400">🚚</span>
                        <span class="font-semibold text-gray-700">Metode Pengambilan:</span>
                        <span class="ml-1 text-gray-900 capitalize">{{ props.pengajuan.metode_pengambilan }}</span>
                    </div>
                    <div class="flex items-center gap-2 md:col-span-2">
                        <span class="inline-block w-6 text-blue-400">📍</span>
                        <span class="font-semibold text-gray-700">Lokasi:</span>
                        <span class="ml-1 text-gray-900">{{ props.pengajuan.lokasi }}</span>
                    </div>
                </div>
            </div>

            <!-- Parameter yang Diuji -->
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
