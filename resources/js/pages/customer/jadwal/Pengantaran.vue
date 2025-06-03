<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import ScrollArea from '@/components/ui/scroll-area/ScrollArea.vue'
import { DropdownMenu, DropdownMenuUserDashboard, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

const filterOptions = ref([
    { label: 'Belum Dijadwalkan', value: 'belum dijadwalkan' },
    { label: 'Diproses', value: 'diproses' },
    { label: 'Selesai', value: 'selesai' }
])

const selectedFilter = ref('all')

const handleFilterChange = (value: string) => {
    selectedFilter.value = value
}

// Data untuk informasi pengantaran
const sampelInfo = ref({
    pengantaran: {
        tanggal: 'Selasa, 22 April 2025',
        idPengajuan: 'SMPOL-25042201',
        jenisSampel: 'Universal',
        status: 'Terkonfirmasi'
    }
})

// Data untuk tabel jadwal pengantaran
const scheduleData = ref([
    {
        tanggal: '22 APR',
        hari: 'Selasa',
        waktu: '09:50 WIB',
        kegiatan: 'Pengantaran Sampel ke DLH',
        lokasi: 'Gedung A',
        status: 'Terkonfirmasi'
    }
])
</script>

<template>

    <Head title="Jadwal Pengantaran" />
    <CustomerLayout>
        <div class="max-w-6xl mx-auto p-4">
            <!-- Navigasi Antar/Jemput -->
            <div class="flex gap-2 mb-4">
                <Link href="/customer/jadwal/pengantaran" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                Pengantaran
                </Link>
                <Link href="/customer/jadwal/penjemputan" class="px-4 py-2 rounded-lg"
                    :class="{ 'bg-customDarkGreen text-white': true }">
                Penjemputan
                </Link>
            </div>
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Jadwal Pengantaran</h1>
                <DropdownMenu>
                    <DropdownMenuTrigger :as-child="true">
                        <Button variant="ghost"
                            class="flex items-center gap-2 px-4 py-2 bg-white border rounded-lg hover:bg-gray-50">
                            <span>Filter</span>
                            <i class="fas fa-chevron-down"></i>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuUserDashboard align="end" class="w-56 z-50">
                        <div class="py-1">
                            <button v-for="option in filterOptions" :key="option.value"
                                @click="handleFilterChange(option.value)"
                                class="w-full px-4 py-2 text-left hover:bg-customDarkGreen flex items-center gap-2 rounded-lg"
                                :class="{ 'bg-customDarkGreen': selectedFilter === option.value }">
                                <i v-if="selectedFilter === option.value" class="fas fa-check text-green-500 w-4"></i>
                                <span v-else class="w-4"></span>
                                {{ option.label }}
                            </button>
                        </div>
                    </DropdownMenuUserDashboard>
                </DropdownMenu>
            </div>

            <!-- Informasi Pengantaran -->
            <div class="bg-blue-50 border-l-8 border-blue-600 rounded-lg p-4 mb-4">
                <h2 class="text-lg font-semibold text-blue-600 mb-4">Informasi Pengantaran Sampel ke DLH</h2>
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
                        <p class="text-sm text-gray-600">Tanggal Pengantaran</p>
                        <p class="font-medium">{{ sampelInfo.pengantaran.tanggal }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
                        <p class="text-sm text-gray-600">ID Pengajuan</p>
                        <p class="font-medium">{{ sampelInfo.pengantaran.idPengajuan }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
                        <p class="text-sm text-gray-600">Jenis Sampel</p>
                        <p class="font-medium">{{ sampelInfo.pengantaran.jenisSampel }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                            {{ sampelInfo.pengantaran.status }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Penting Notice -->
            <div class="bg-yellow-50 border-l-8 border-yellow-400 p-4 mb-4 rounded-lg">
                <p class="text-sm text-yellow-700">
                    <span class="font-bold">Penting:</span> Untuk pengantaran sampel ke DLH, silakan bawa sampel ke
                    Gedung A Dinas Lingkungan Hidup pada tanggal yang telah ditentukan. Jam operasional 08:00 - 15:00
                    WIB.
                </p>
            </div>

            <!-- Table Jadwal -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <ScrollArea class="h-[400px]">
                    <table class="w-full">
                        <thead class="bg-customDarkGreen">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="(item, index) in scheduleData" :key="index">
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ item.tanggal }}</div>
                                    <div class="text-sm text-gray-500">{{ item.hari }}</div>
                                </td>
                                <td class="px-6 py-4">{{ item.waktu }}</td>
                                <td class="px-6 py-4">{{ item.kegiatan }}</td>
                                <td class="px-6 py-4">{{ item.lokasi }}</td>
                                <td class="px-6 py-4">
                                    <span :class="{
                                        'px-3 py-1 rounded-full text-sm': true,
                                        'bg-green-100 text-green-700': item.status === 'Terkonfirmasi'
                                    }">
                                        {{ item.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </ScrollArea>
            </div>
        </div>
    </CustomerLayout>
</template>