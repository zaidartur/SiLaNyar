<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuUserDashboard } from '@/components/ui/dropdown-menu';
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    jadwalAmbilTerbaru: any[];
}>();

const filterOptions = ref([
    { label: 'Belum Dijadwalkan', value: 'belum dijadwalkan' },
    { label: 'Diproses', value: 'diproses' },
    { label: 'Selesai', value: 'selesai' },
]);

const selectedFilter = ref('all');

const handleFilterChange = (value: string) => {
    selectedFilter.value = value;
};

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-';
    const date = new Date(tanggalStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};
// const formatWaktu = (tanggalStr: string) => {
//     if (!tanggalStr) return '-'
//     const date = new Date(tanggalStr)
//     return date.toLocaleTimeString('id-ID', {
//         hour: '2-digit',
//         minute: '2-digit'
//     })
// }
</script>

<template>

    <Head title="Jadwal Penjemputan" />
    <CustomerLayout>
        <div class="mx-auto max-w-6xl p-4">
            <!-- Navigasi Antar/Jemput -->
            <div class="mb-4 flex gap-2">
                <Link href="/customer/jadwal/pengantaran" class="rounded-lg px-4 py-2 font-semibold"
                    :class="['bg-gray-100 text-customDarkGreen', 'hover:bg-gray-200']">
                Pengantaran
                </Link>
                <Link href="/customer/jadwal/penjemputan" class="rounded-lg px-4 py-2 font-semibold"
                    :class="['bg-customDarkGreen text-white', 'hover:bg-green-800']">
                Penjemputan
                </Link>
            </div>
            <!-- Header -->
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Jadwal Penjemputan</h1>
                <DropdownMenu>
                    <DropdownMenuTrigger :as-child="true">
                        <Button variant="ghost"
                            class="flex items-center gap-2 rounded-lg border bg-white px-4 py-2 hover:bg-gray-50">
                            <span>Filter</span>
                            <i class="fas fa-chevron-down"></i>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuUserDashboard align="end" class="bg-customDarkGreen z-40 w-36">
                        <div class="py-1">
                            <button v-for="option in filterOptions" :key="option.value"
                                @click="handleFilterChange(option.value)"
                                class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-left hover:bg-customDarkGreen"
                                :class="{ 'bg-customDarkGreen': selectedFilter === option.value }">
                                <i v-if="selectedFilter === option.value" class="fas fa-check w-4 text-green-500"></i>
                                <span v-else class="w-4"></span>
                                {{ option.label }}
                            </button>
                        </div>
                    </DropdownMenuUserDashboard>
                </DropdownMenu>
            </div>

            <!-- Table Jadwal Penjemputan -->
            <div class="overflow-x-auto">
                <table class="min-w-full overflow-hidden rounded-xl border bg-white shadow">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-white">ID Penjemputan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-white">Kode Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-white">Nama Instansi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-white">Nama Pemohon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-white">Metode Pengambilan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-white">Waktu Penjemputan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-white">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-white">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-if="!props.jadwalAmbilTerbaru || props.jadwalAmbilTerbaru.length === 0">
                            <td colspan="9" class="py-4 text-center text-gray-400">Tidak ada data penjemputan.</td>
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
                                    'rounded px-2 py-1 text-xs font-semibold',
                                    item.status === 'selesai' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white',
                                ]">
                                    {{ item.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="route('customer.jadwal.detail', { id: item.id, from: 'penjemputan' })"
                                        class="text-blue-500">
                                    <span>👁️</span>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </CustomerLayout>
</template>
