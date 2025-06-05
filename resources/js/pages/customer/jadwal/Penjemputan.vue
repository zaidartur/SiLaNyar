<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import ScrollArea from '@/components/ui/scroll-area/ScrollArea.vue'
import { DropdownMenu, DropdownMenuUserDashboard, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps<{
    jadwalAmbilTerbaru: any[]
}>()

const filterOptions = ref([
    { label: 'Belum Dijadwalkan', value: 'belum dijadwalkan' },
    { label: 'Diproses', value: 'diproses' },
    { label: 'Selesai', value: 'selesai' }
])

const selectedFilter = ref('all')

const handleFilterChange = (value: string) => {
    selectedFilter.value = value
}

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-'
    const date = new Date(tanggalStr)
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}
const formatWaktu = (tanggalStr: string) => {
    if (!tanggalStr) return '-'
    const date = new Date(tanggalStr)
    return date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>

<template>

    <Head title="Jadwal Penjemputan" />
    <CustomerLayout>
        <div class="max-w-6xl mx-auto p-4">
            <!-- Navigasi Antar/Jemput -->
            <div class="flex gap-2 mb-4">
                <Link href="/customer/jadwal/pengantaran" class="px-4 py-2 rounded-lg font-semibold"
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

            <!-- Table Jadwal Penjemputan -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <ScrollArea class="h-[400px]">
                    <table class="w-full">
                        <thead class="bg-customDarkGreen">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">ID Penjemputan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Kode Pengajuan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Nama Instansi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Nama Pemohon
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Metode
                                    Pengambilan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Waktu
                                    Penjemputan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-if="!props.jadwalAmbilTerbaru || props.jadwalAmbilTerbaru.length === 0">
                                <td colspan="9" class="text-center text-gray-400 py-4">Tidak ada data penjemputan.</td>
                            </tr>
                            <tr v-for="(item, index) in props.jadwalAmbilTerbaru" :key="item.id || index">
                                <td class="px-6 py-4">{{ item.kode_pengambilan }}</td>
                                <td class="px-6 py-4">{{ item.form_pengajuan?.kode_pengajuan }}</td>
                                <td class="px-6 py-4">{{ item.form_pengajuan?.instansi?.nama }}</td>
                                <td class="px-6 py-4">{{ item.form_pengajuan?.instansi?.user?.nama }}</td>
                                <td class="px-6 py-4">{{ item.form_pengajuan?.metode_pengambilan }}</td>
                                <td class="px-6 py-4">{{ formatTanggal(item.waktu_pengambilan) }}</td>
                                <td class="px-6 py-4">{{ item.keterangan }}</td>
                                <td class="px-6 py-4">
                                    <span :class="[
                                        'px-2 py-1 rounded text-xs font-semibold',
                                        item.status === 'selesai' ? 'bg-green-500 text-white'
                                            : 'bg-yellow-500 text-white'
                                    ]">
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