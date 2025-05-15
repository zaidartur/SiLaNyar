<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
    pengajuan: any[],
    filter: {
        status: string,
        tanggal: string
    }
}>()

const status = ref(props.filter.status)
const tanggal = ref(props.filter.tanggal)

const handleFilter = () => {
    window.location.href = `/pegawai/pengajuan?status=${status.value}&tanggal=${tanggal.value}`
}
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl text-black font-bold">DAFTAR PENGAJUAN</h1>
            </div>

            <!-- Filter -->
            <div class="mb-6 flex gap-4 items-end">
                <!-- Status Filter -->
                <div class="flex flex-col">
                    <label for="status" class="mb-1 text-sm font-medium text-gray-700">Status</label>
                    <select id="status" v-model="status"
                        class="rounded bg-green-600 text-white border-gray-300 px-2 py-1" @change="handleFilter">
                        <option disabled value="">Pilih Status</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="verifikasi">Proses Verifikasi</option>
                    </select>
                </div>

                <!-- Tanggal Filter -->
                <div class="flex flex-col">
                    <label for="tanggal" class="mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                    <input id="tanggal" type="date" v-model="tanggal"
                        class="rounded bg-green-600 text-white border-gray-300 px-2 py-1" @change="handleFilter">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg">
                    <thead>
                        <tr class="bg-gray-500 text-white">
                            <th class="px-6 py-3">ID Pengajuan</th>
                            <th class="px-6 py-3">Nama Pelanggan</th>
                            <th class="px-6 py-3">Kategori</th>
                            <th class="px-6 py-3">Metode Pengambilan</th>
                            <th class="px-6 py-3">Volume</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in pengajuan" :key="item.id" :class="{ 'bg-gray-200': index % 2 !== 0 }">
                            <td class="px-6 py-4">DP-001</td>
                            <td class="px-6 py-4">PT Sejahtra Abadi</td>
                            <td class="px-6 py-4">Industri Cat/Tinta</td>
                            <td class="px-6 py-4">Diambil</td>
                            <td class="px-6 py-4">100 mL</td>
                            <td class="px-6 py-4">
                                <span :class="{
                                    'px-2 py-1 rounded text-gray-800': true,
                                    'bg-green-200': item.status === 'diproses',
                                    'bg-yellow-200': item.status === 'verifikasi'
                                }">
                                    {{ item.status === 'verifikasi' ? 'Proses Verifikasi' : item.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.pengajuan.show', item.id)" class="text-blue-500">
                                    <span>👁️</span>
                                    </Link>
                                    <Link :href="route('pegawai.pengajuan.edit', item.id)" class="text-yellow-500">
                                    <span>✏️</span>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>