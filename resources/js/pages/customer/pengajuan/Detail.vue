<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import { Head } from '@inertiajs/vue3'
import { defineProps, computed } from 'vue'

interface User {
    id: number
    nama: string
}

interface Instansi {
    id: number
    nama: string
    harga?: number
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
    total_biaya?: number
}

const totalBiaya = computed(() =>
    Array.isArray(props.pengajuan.parameter)
        ? props.pengajuan.parameter.reduce((sum, param) => sum + Number(param.harga ?? 0), 0)
        : 0
)

const props = defineProps<{
    pengajuan: Pengajuan
}>()
</script>

<template>

    <Head title="Detail Pengajuan" />
    <CustomerLayout>
        <div class="p-6 max-w-3xl mx-auto bg-white rounded-xl shadow-lg">
            <div class="flex items-center gap-3 mb-6">
                <svg class="text-green-600" width="32" height="32" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="12" fill="#059669" fill-opacity="0.1" />
                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#059669" />
                </svg>
                <h1 class="text-2xl font-bold text-customDarkGreen">Detail Pengajuan</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 rounded-lg p-4 flex flex-col gap-1 shadow-lg">
                    <span class="text-gray-500 text-xs">Kode Pengajuan</span>
                    <span class="font-semibold text-lg">{{ props.pengajuan.kode_pengajuan }}</span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex flex-col gap-1 shadow-lg">
                    <span class="text-gray-500 text-xs">Status Pengajuan</span>
                    <span class="capitalize font-semibold text-lg flex items-center gap-2">
                        <span v-if="props.pengajuan.status_pengajuan === 'selesai'"
                            class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Selesai
                        </span>
                        <span v-else-if="props.pengajuan.status_pengajuan === 'proses_validasi'"
                            class="inline-flex items-center px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            Proses
                        </span>
                        <span v-else-if="props.pengajuan.status_pengajuan === 'ditolak'"
                            class="inline-flex items-center px-2 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Ditolak
                        </span>
                    </span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex flex-col gap-1 shadow-lg">
                    <span class="text-gray-500 text-xs">Nama Pemohon</span>
                    <span class="font-semibold">{{ props.pengajuan.instansi.user.nama }}</span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex flex-col gap-1 shadow-lg">
                    <span class="text-gray-500 text-xs">Nama Instansi</span>
                    <span class="font-semibold">{{ props.pengajuan.instansi.nama }}</span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex flex-col gap-1 shadow-lg">
                    <span class="text-gray-500 text-xs">Jenis Cairan</span>
                    <span class="font-semibold">{{ props.pengajuan.jenis_cairan.nama }}</span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex flex-col gap-1 shadow-lg">
                    <span class="text-gray-500 text-xs">Kategori</span>
                    <span class="font-semibold">{{ props.pengajuan.kategori?.nama ?? '-' }}</span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex flex-col gap-1 shadow-lg">
                    <span class="text-gray-500 text-xs">Volume Sampel</span>
                    <span class="font-semibold">{{ props.pengajuan.volume_sampel }} ml</span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex flex-col gap-1 shadow-lg">
                    <span class="text-gray-500 text-xs">Metode Pengambilan</span>
                    <span class="font-semibold capitalize">{{ props.pengajuan.metode_pengambilan }}</span>
                </div>
                <div class="md:col-span-2 bg-gray-50 rounded-lg p-4 flex flex-col gap-1 shadow-lg">
                    <span class="text-gray-500 text-xs">Lokasi</span>
                    <span class="font-semibold">{{ props.pengajuan.lokasi }}</span>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-4 shadow-lg">
                <h2 class="font-semibold mb-2 text-green-700 flex items-center gap-2">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#059669" />
                    </svg>
                    Parameter yang Diuji
                </h2>
                <ul class="list-disc list-inside text-black ml-2">
                    <li v-for="param in props.pengajuan.parameter" :key="param.id">
                        {{ param.nama_parameter }}
                        <span class="ml-2 text-xs text-gray-500">
                            Rp {{ Number(param.harga ?? 0).toLocaleString('id-ID') }}
                        </span>
                    </li>
                    <li v-if="!props.pengajuan.parameter.length" class="text-gray-400">Tidak ada parameter</li>
                </ul>
            </div>
            <div class="mt-8 flex justify-end">
                <a href="/customer/dashboard"
                    class="inline-flex items-center px-4 py-2 bg-customDarkGreen hover:bg-green-800 text-white text-sm font-semibold rounded-lg shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </CustomerLayout>
</template>
