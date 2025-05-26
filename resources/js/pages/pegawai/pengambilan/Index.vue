<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
    jadwal: any[],
    filter: {
        status: string,
        tanggal: string
    }
}>()

const status = ref(props.filter.status)
const tanggal = ref(props.filter.tanggal)

const handleFilter = () => {
    window.location.href = `/pegawai/jadwal?status=${status.value}&tanggal=${tanggal.value}`
}
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl text-black font-bold">Daftar Jadwal Pengambilan</h1>
                <Link :href="route('pegawai.jadwal.create')" class="bg-customDarkGreen text-white px-4 py-2 rounded">
                Tambah Jadwal
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
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
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
                            <th class="px-6 py-3 rounded-tl-lg">ID Jadwal</th>
                            <th class="px-6 py-3">ID Form Pengajuan</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Petugas</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Catatan</th>
                            <th class="px-6 py-3 rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in jadwal" :key="item.id">
                            <td class="px-6 py-4 border">JD-001</td>
                            <td class="px-6 py-4 border">DP-001</td>
                            <td class="px-6 py-4 border">15/04/2025</td>
                            <td class="px-6 py-4 border">Beni</td>
                            <td class="px-6 py-4 border">
                                <span :class="{
                                    'px-2 py-1 rounded text-white': true,
                                    'bg-green-500': item.status === 'selesai',
                                    'bg-yellow-500': item.status === 'diproses',
                                    'bg-red-500': item.status === 'dibatalkan'
                                }">
                                    {{ item.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border">-</td>
                            <td class="px-6 py-4 border rounded-br-lg">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.jadwal.show', item.id)"
                                        class="bg-blue-500 text-white px-2 py-1 rounded">
                                    <span>👁️</span>
                                    </Link>
                                    <Link :href="route('pegawai.jadwal.edit', item.id)"
                                        class="bg-yellow-500 text-white px-2 py-1 rounded">
                                    <span>✏️</span>
                                    </Link>
                                    <Link :href="route('pegawai.jadwal.destroy', item.id)" method="delete"
                                        class="bg-red-500 text-white px-2 py-1 rounded" as="button" type="button">
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