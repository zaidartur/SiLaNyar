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

// Data untuk informasi penjemputan
const sampelInfo = ref({
    penjemputan: {
        tanggal: 'Selasa, 22 April 2025',
        idPengajuan: 'SMPOL-25042201',
        jenisSampel: 'Universal',
        status: 'Belum Terjadwalkan',
        lokasi: 'Jl. Kenanga No. 45',
        instansi: 'Perusahaan - PT. Tirta Murni'
    }
})

// Data untuk tabel jadwal penjemputan
const scheduleData = ref([
    {
        tanggal: '23 APR',
        hari: 'Selasa',
        waktu: '13:30 WIB',
        kegiatan: 'Penjemputan Sampel oleh Teknisi',
        lokasi: 'Jl. Kenanga No. 45',
        status: 'Terjadwalkan'
    },
    {
        tanggal: '23 APR',
        hari: 'Selasa',
        waktu: '13:30 WIB',
        kegiatan: 'Penjemputan Sampel oleh Teknisi',
        lokasi: 'Jl. Kenanga No. 45',
        status: 'Terjadwalkan'
    },
    {
        tanggal: '23 APR',
        hari: 'Selasa',
        waktu: '13:30 WIB',
        kegiatan: 'Penjemputan Sampel oleh Teknisi',
        lokasi: 'Jl. Kenanga No. 45',
        status: 'Terjadwalkan'
    },
    {
        tanggal: '23 APR',
        hari: 'Selasa',
        waktu: '13:30 WIB',
        kegiatan: 'Penjemputan Sampel oleh Teknisi',
        lokasi: 'Jl. Kenanga No. 45',
        status: 'Terjadwalkan'
    },
    {
        tanggal: '23 APR',
        hari: 'Selasa',
        waktu: '13:30 WIB',
        kegiatan: 'Penjemputan Sampel oleh Teknisi',
        lokasi: 'Jl. Kenanga No. 45',
        status: 'Terjadwalkan'
    },
    
])
</script>

<template>

    <Head title="Jadwal Penjemputan" />
    <CustomerLayout>
        <div class="max-w-6xl mx-auto p-4">
            <!-- Navigasi Antar/Jemput -->
            <div class="flex gap-2 mb-4">
                <Link href="/customer/pengujian" class="px-4 py-2 rounded-lg font-semibold"
                    :class="['bg-gray-100 text-customDarkGreen', 'hover:bg-gray-200']">
                Pengantaran
                </Link>
                <Link href="/customer/jadwal/penjemputan" class="px-4 py-2 rounded-lg font-semibold"
                    :class="['bg-customDarkGreen text-white', 'hover:bg-green-800']">
                Penjemputan
                </Link>
            </div>
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Jadwal Penjemputan</h1>
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

            <!-- Informasi Penjemputan -->
            <div class="bg-purple-50 border-l-8 border-purple-600 rounded-lg p-4 mb-4">
                <h2 class="text-lg font-semibold text-purple-600 mb-4">Informasi Penjemputan Sampel oleh Teknisi</h2>
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
                        <p class="text-sm text-gray-600">Tanggal Penjemputan</p>
                        <p class="font-medium">{{ sampelInfo.penjemputan.tanggal }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
                        <p class="text-sm text-gray-600">ID Pengajuan</p>
                        <p class="font-medium">{{ sampelInfo.penjemputan.idPengajuan }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
                        <p class="text-sm text-gray-600">Jenis Sampel</p>
                        <p class="font-medium">{{ sampelInfo.penjemputan.jenisSampel }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">
                            {{ sampelInfo.penjemputan.status }}
                        </span>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
                        <p class="text-sm text-gray-600">Lokasi Pengambilan</p>
                        <p class="font-medium">{{ sampelInfo.penjemputan.lokasi }}</p>
                    </div>
                    <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-100">
                        <p class="text-sm text-gray-600">Instansi</p>
                        <p class="font-medium">{{ sampelInfo.penjemputan.instansi }}</p>
                    </div>
                </div>
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
                                        'bg-purple-100 text-purple-700': item.status === 'Terjadwalkan'
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