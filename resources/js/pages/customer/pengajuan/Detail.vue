<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

// interface User {
//     id: number
//     nama: string
// }

interface Instansi {
    id: number;
    nama: string;
    harga?: number;
}

interface JenisCairan {
    id: number;
    nama: string;
}

interface Parameter {
    id: number;
    nama_parameter: string;
}

interface Kategori {
    id: number;
    nama: string;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    volume_sampel: number;
    status_pengajuan: string;
    metode_pengambilan: string;
    lokasi: string;
    instansi: Instansi;
    kategori: Kategori;
    jenis_cairan: JenisCairan;
    parameter: Parameter[];
    total_biaya?: number;
}

// const totalBiaya = computed(() =>
//     Array.isArray(props.pengajuan.parameter)
//         ? props.pengajuan.parameter.reduce((sum, param) => sum + Number(param.harga ?? 0), 0)
//         : 0
// )

const props = defineProps<{
    pengajuan: Pengajuan;
}>();
</script>

<template>

    <Head title="Detail Pengajuan" />
    <CustomerLayout>
        <div class="mx-auto max-w-3xl rounded-xl bg-white p-6 shadow-lg">
            <div class="mb-6 flex items-center gap-3">
                <svg class="text-green-600" width="32" height="32" fill="none" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="12" fill="#059669" fill-opacity="0.1" />
                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#059669" />
                </svg>
                <h1 class="text-2xl font-bold text-customDarkGreen">Detail Pengajuan</h1>
            </div>

            <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="flex flex-col gap-1 rounded-lg bg-gray-50 p-4 shadow-lg">
                    <span class="text-xs text-gray-500">Kode Pengajuan</span>
                    <span class="text-lg font-semibold">{{ props.pengajuan.kode_pengajuan }}</span>
                </div>
                <div class="flex flex-col gap-1 rounded-lg bg-gray-50 p-4 shadow-lg">
                    <span class="text-xs text-gray-500">Status Pengajuan</span>
                    <span class="flex items-center gap-2 text-lg font-semibold capitalize">
                        <span v-if="props.pengajuan.status_pengajuan === 'diterima'"
                            class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-sm font-semibold text-green-700">
                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            diterima
                        </span>
                        <span v-else-if="props.pengajuan.status_pengajuan === 'proses_validasi'"
                            class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-sm font-semibold text-yellow-700">
                            <svg class="mr-1 h-4 w-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            Proses
                        </span>
                        <span v-else-if="props.pengajuan.status_pengajuan === 'ditolak'"
                            class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-sm font-semibold text-red-700">
                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Ditolak
                        </span>
                    </span>
                </div>
                <div class="flex flex-col gap-1 rounded-lg bg-gray-50 p-4 shadow-lg">
                    <span class="text-xs text-gray-500">Nama Pemohon</span>
                    <span class="font-semibold">{{ props.pengajuan.instansi.user.nama }}</span>
                </div>
                <div class="flex flex-col gap-1 rounded-lg bg-gray-50 p-4 shadow-lg">
                    <span class="text-xs text-gray-500">Nama Instansi</span>
                    <span class="font-semibold">{{ props.pengajuan.instansi.nama }}</span>
                </div>
                <div class="flex flex-col gap-1 rounded-lg bg-gray-50 p-4 shadow-lg">
                    <span class="text-xs text-gray-500">Jenis Cairan</span>
                    <span class="font-semibold">{{ props.pengajuan.jenis_cairan.nama }}</span>
                </div>
                <div class="flex flex-col gap-1 rounded-lg bg-gray-50 p-4 shadow-lg">
                    <span class="text-xs text-gray-500">Kategori</span>
                    <span class="font-semibold">{{ props.pengajuan.kategori?.nama ?? '-' }}</span>
                </div>
                <div class="flex flex-col gap-1 rounded-lg bg-gray-50 p-4 shadow-lg">
                    <span class="text-xs text-gray-500">Volume Sampel</span>
                    <span class="font-semibold">{{ props.pengajuan.volume_sampel }} ml</span>
                </div>
                <div class="flex flex-col gap-1 rounded-lg bg-gray-50 p-4 shadow-lg">
                    <span class="text-xs text-gray-500">Metode Pengambilan</span>
                    <span class="font-semibold capitalize">{{ props.pengajuan.metode_pengambilan }}</span>
                </div>
                <div class="flex flex-col gap-1 rounded-lg bg-gray-50 p-4 shadow-lg md:col-span-2">
                    <span class="text-xs text-gray-500">Lokasi</span>
                    <span class="font-semibold">{{ props.pengajuan.lokasi }}</span>
                </div>
            </div>

            <div class="rounded-lg bg-green-50 p-4 shadow-lg">
                <h2 class="mb-2 flex items-center gap-2 font-semibold text-green-700">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#059669" />
                    </svg>
                    Parameter yang Diuji
                </h2>
                <ul class="ml-2 list-inside list-disc text-black">
                    <li v-for="param in props.pengajuan.parameter" :key="param.id">
                        {{ param.nama_parameter }}
                        <span class="ml-2 text-xs text-gray-500"> Rp {{ Number(param.harga ?? 0).toLocaleString('id-ID')
                            }} </span>
                    </li>
                    <li v-if="!props.pengajuan.parameter.length" class="text-gray-400">Tidak ada parameter</li>
                </ul>
                <div class="mt-4 flex justify-end font-semibold text-green-800">
                    Total Harga: Rp {{
                        props.pengajuan.parameter
                            .reduce((total, param) => total + Number(param.harga ?? 0), 0)
                            .toLocaleString('id-ID')
                    }}
                </div>
            </div>
            <div class="mt-8 flex justify-end">
                <a href="/customer/dashboard"
                    class="inline-flex items-center rounded-lg bg-customDarkGreen px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-green-800">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </CustomerLayout>
</template>
