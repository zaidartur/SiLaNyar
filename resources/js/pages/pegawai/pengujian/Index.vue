<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
    pengujian: any[],
    filter: {
        status: string,
        tanggal: string
    }
}>()

const status = ref(props.filter.status)
const tanggal = ref(props.filter.tanggal)

const handleFilter = () => {
    window.location.href = `/pegawai/pengujian?status=${status.value}&tanggal=${tanggal.value}`
}
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl text-black font-bold">DAFTAR PENGUJIAN</h1>
                <Link :href="route('pegawai.pengujian.create')"
                    class="bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2">
                <span>+</span> Tambah
                </Link>
            </div>

            <!-- Filter -->
            <div class="mb-6 flex gap-4 items-end">
                <!-- Status Filter -->
                <div class="flex flex-col">
                    <label for="status" class="mb-1 text-sm font-medium text-gray-700">Status</label>
                    <select id="status" v-model="status"
                        class="rounded bg-green-600 text-white border-gray-300 px-2 py-1" @change="handleFilter">
                        <option disabled value="">Pilih Status</option>
                        <option value="selesai">Selesai</option>
                        <option value="diproses">Diproses</option>
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
                            <th class="px-6 py-3">ID Pengujian</th>
                            <th class="px-6 py-3">ID Form Pengajuan</th>
                            <th class="px-6 py-3">Nama Teknisi</th>
                            <th class="px-6 py-3">Tanggal Pengujian</th>
                            <th class="px-6 py-3">Jam Mulai</th>
                            <th class="px-6 py-3">Jam Selesai</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in pengujian" :key="item.id"
                            :class="{ 'bg-gray-200': index % 2 !== 0, 'border-4 border-blue-500': index === 3 }">
                            <td class="px-6 py-4">DJ-001</td>
                            <td class="px-6 py-4">DP-002</td>
                            <td class="px-6 py-4">Hartono</td>
                            <td class="px-6 py-4">15/04/2025</td>
                            <td class="px-6 py-4">08:00 WIB</td>
                            <td class="px-6 py-4">09:00 WIB</td>
                            <td class="px-6 py-4">
                                <span :class="{
                                    'px-2 py-1 rounded text-white': true,
                                    'bg-green-500': item.status === 'selesai',
                                    'bg-orange-400': item.status === 'diproses'
                                }">
                                    {{ item.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.pengujian.show', item.id)" class="text-blue-500">
                                    <span>👁️</span>
                                    </Link>
                                    <Link :href="route('pegawai.pengujian.edit', item.id)" class="text-yellow-500">
                                    <span>✏️</span>
                                    </Link>
                                    <Link :href="route('pegawai.pengujian.destroy', item.id)" method="delete"
                                        class="text-red-500" as="button" type="button">
                                    <span>🗑️</span>
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