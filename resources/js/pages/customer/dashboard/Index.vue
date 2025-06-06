<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

interface StatusItem {
    label: string;
    status: boolean;
    tanggal: string;
}

interface Statistik {
    proses: number;
    ditolak: number;
    diterima: number;
}

interface Kategori {
    id: number;
    nama: string;
}

interface JenisCairan {
    id: number;
    nama: string;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    kategori: Kategori;
    jenis_cairan: JenisCairan;
    volume_sampel: number;
    status_pengajuan: 'proses_validasi' | 'diterima' | 'ditolak';
    metode_pengambilan: 'diantar' | 'diambil';
    lokasi: string;
}

interface Pembayaran {
    id: number;
    id_order: string;
    form_pengajuan: Pengajuan;
    total_biaya: number;
    tanggal_pembayaran: string;
    metode_pembayaran: 'transfer' | 'tunai';
    status_pembayaran: 'diproses' | 'selesai' | 'gagal';
    updated_at: string;
}

interface PilihPengajuan {
    id: number | string;
    status_pengajuan?: string;
    pembayaran?: {
        status?: string;
        updated_at?: string;
    };
    jadwal?: {
        status?: string;
        created_at?: string;
        updated_at?: string;
    };
    pengujian?: {
        status?: string;
        created_at?: string;
    };
    hasil_uji?: {
        status?: string;
        updated_at?: string;
    };
}

// const showStepPembayaran = ref(false)

const props = defineProps<{
    statusList: StatusItem[];
    statistik: Statistik;
    pengajuan: Pengajuan[];
    pembayaran: Pembayaran[];
    pilihPengajuan: PilihPengajuan | null;
}>();
</script>

<template>
    <Head title="Dashboard" />
    <CustomerLayout>
        <div class="mx-auto max-w-7xl space-y-6 p-6">
            <!-- Dashboard Header -->
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <h1 class="border-b p-4 text-2xl font-semibold">Dashboard Pengajuan</h1>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
                <div class="space-y-6 lg:col-span-3">
                    <!-- Status Cards -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <h3 class="font-medium text-yellow-500">Belum Terverifikasi</h3>
                            <p class="mt-2 text-3xl font-bold">{{ props.statistik?.proses ?? 0 }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <h3 class="font-medium text-red-500">Ditolak</h3>
                            <p class="mt-2 text-3xl font-bold">{{ props.statistik?.ditolak ?? 0 }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <h3 class="font-medium text-green-500">Diterima</h3>
                            <p class="mt-2 text-3xl font-bold">{{ props.statistik?.diterima ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b p-4">
                            <div class="relative">
                                <button class="rounded-md border px-4 py-2 text-sm">
                                    Filter
                                    <span class="ml-2">▼</span>
                                </button>
                            </div>
                        </div>
                        <div class="relative overflow-x-auto">
                            <table class="w-full table-auto border">
                                <thead class="bg-green-50">
                                    <tr>
                                        <th class="border px-4 py-3 text-left text-sm font-bold text-green-700">ID</th>
                                        <th class="border px-4 py-3 text-left text-sm font-bold text-green-700">Jenis Cairan</th>
                                        <th class="border px-4 py-3 text-left text-sm font-bold text-green-700">Volume Sampel</th>
                                        <th class="border px-4 py-3 text-left text-sm font-bold text-green-700">Kategori</th>
                                        <th class="border px-4 py-3 text-left text-sm font-bold text-green-700">Metode Pengambilan</th>
                                        <th class="border px-4 py-3 text-left text-sm font-bold text-green-700">Lokasi</th>
                                        <th class="border px-4 py-3 text-left text-sm font-bold text-green-700">Status</th>
                                        <th class="border px-4 py-3 text-left text-sm font-bold text-green-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="item in props.pengajuan" :key="item.id">
                                        <td class="px-6 py-4 text-black">
                                            <Link
                                                :href="route('customer.dashboard', { id: item.id })"
                                                class="text-blue-600 hover:underline"
                                                preserve-scroll
                                            >
                                                {{ item.kode_pengajuan }}
                                            </Link>
                                        </td>
                                        <td class="border px-4 py-3 text-sm">{{ item.jenis_cairan?.nama }}</td>
                                        <td class="border px-4 py-3 text-sm">{{ item.volume_sampel }}</td>
                                        <td class="border px-4 py-3 text-sm">{{ item.kategori?.nama }}</td>
                                        <td class="border px-4 py-3 text-sm">{{ item.metode_pengambilan }}</td>
                                        <td class="border px-4 py-3 text-sm">{{ item.lokasi }}</td>
                                        <td class="border px-4 py-3 text-sm">
                                            <span
                                                :class="{
                                                    'bg-yellow-100 text-yellow-600': item.status_pengajuan === 'proses_validasi',
                                                    'bg-red-100 text-red-600': item.status_pengajuan === 'ditolak',
                                                    'bg-green-100 text-green-600': item.status_pengajuan === 'diterima',
                                                }"
                                                class="rounded-full px-2 py-1 text-xs"
                                            >
                                                {{ item.status_pengajuan }}
                                            </span>
                                        </td>
                                        <td class="border px-4 py-3 text-sm">
                                            <div class="flex space-x-2">
                                                <Link :href="route('customer.pengajuan.detail', item.id)" class="text-blue-500">
                                                    <span>👁️</span>
                                                </Link>
                                                <Link :href="route('customer.pengajuan.edit', item.id)" class="text-yellow-500">
                                                    <span>✏️</span>
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Ringkasan Pembayaran Card -->
                    <div class="rounded bg-white p-4 shadow">
                        <h2 class="mb-4 text-lg font-semibold">Rincian Pembayaran</h2>

                        <div v-if="props.pembayaran.length > 0">
                            <table class="w-full table-auto border">
                                <thead class="bg-green-50 text-left">
                                    <tr>
                                        <th class="border p-2">Kode</th>
                                        <th class="border p-2">Total Bayar</th>
                                        <th class="border p-2">Status</th>
                                        <th class="border p-2">Metode</th>
                                        <th class="border p-2">Tanggal</th>
                                        <th class="border p-2">Aksi</th>
                                        <!-- Tambahkan kolom aksi -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="p in props.pembayaran" :key="p.id" class="hover:bg-gray-50">
                                        <td class="border p-2">#{{ p.id_order }}</td>
                                        <td class="border p-2">Rp {{ p.total_biaya.toLocaleString() }}</td>
                                        <td
                                            class="border p-2 capitalize"
                                            :class="p.status_pembayaran === 'selesai' ? 'text-green-600' : 'text-red-600'"
                                        >
                                            {{ p.status_pembayaran }}
                                        </td>
                                        <td class="border p-2">{{ p.metode_pembayaran ?? '-' }}</td>
                                        <td class="border p-2">{{ new Date(p.updated_at).toLocaleDateString('id-ID') }}</td>
                                        <td class="border p-2">
                                            <Link
                                                v-if="p.status_pembayaran !== 'selesai'"
                                                :href="route('customer.pembayaran.show', p.id)"
                                                class="rounded bg-green-500 px-3 py-1 text-xs text-white hover:bg-green-600"
                                            >
                                                Bayar
                                            </Link>
                                            <span v-else class="text-xs text-gray-400">Lunas</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-gray-500">Belum ada pembayaran atau status pengajuan belum diterima!</div>
                    </div>
                </div>

                <!-- Right Section - Status Pengujian -->
                <div class="lg:col-span-1">
                    <div class="sticky top-4 rounded-lg border border-gray-200 bg-white p-4 shadow-lg">
                        <h2 class="mb-4 flex items-center text-lg font-semibold">
                            <svg
                                width="27"
                                height="28"
                                viewBox="0 0 27 28"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                            >
                                <rect width="27" height="28" fill="url(#pattern0_741_1283)" />
                                <defs>
                                    <pattern id="pattern0_741_1283" patternContentUnits="objectBoundingBox" width="1" height="1">
                                        <use xlink:href="#image0_741_1283" transform="matrix(0.01 0 0 0.00964286 0 0.0178571)" />
                                    </pattern>
                                    <image
                                        id="image0_741_1283"
                                        width="100"
                                        height="100"
                                        preserveAspectRatio="none"
                                        xlink:href="data:image/png;base64,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"
                                    />
                                </defs>
                            </svg>
                            Status Pengujian
                        </h2>
                        <div class="space-y-6">
                            <div class="relative">
                                <div class="absolute left-5 top-5 w-0.5 bg-gray-200" :style="{ height: `calc(100% - 2rem)` }"></div>

                                <div class="space-y-6">
                                    <div v-for="(item, index) in props.statusList" :key="index" class="flex items-center">
                                        <div
                                            :class="[
                                                'z-10 flex h-10 w-10 items-center justify-center rounded-full',
                                                item.status ? 'bg-green-200' : 'bg-gray-200',
                                            ]"
                                        >
                                            <div :class="['h-4 w-4 rounded-full', item.status ? 'bg-green-600' : 'bg-gray-400']"></div>
                                        </div>
                                        <div class="ml-4">
                                            <p :class="['font-medium', item.status ? 'text-green-600' : 'text-gray-600']">
                                                {{ item.label }}
                                            </p>
                                            <p class="text-sm text-gray-500">{{ item.tanggal }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
