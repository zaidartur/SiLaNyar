<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

interface JenisCairan {
    id: number,
    kode_jenis_cairan: string,
    nama: string,
    batas_minimum: number,
    batas_maksimum: number,
}

const props = defineProps<{
    jenis_cairan: JenisCairan[]
}>()
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-black">Daftar Jenis Cairan</h1>
                <a href="/pegawai/jenis-cairan/create"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Tambah</a>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full rounded-lg bg-white">
                    <thead>
                        <tr class="bg-gray-500 text-white">
                            <th class="px-6 py-3">ID Jenis Cairan</th>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Batas Minimum</th>
                            <th class="px-6 py-3">Batas Maksimum</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item) in props.jenis_cairan" :key="item.id">
                            <td class="px-6 py-4 text-black">{{ item.kode_jenis_cairan }}</td>
                            <td class="px-6 py-4 text-black">{{ item.nama }}</td>
                            <td class="px-6 py-4 text-black">{{ item.batas_minimum }}</td>
                            <td class="px-6 py-4 text-black">{{ item.batas_maksimum }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="`/pegawai/jenis-cairan/edit/${item.id}`" class="text-yellow-500">
                                    <span>✏️</span>
                                    </Link>
                                    <Link
                                        :href="`/pegawai/jenis-cairan/${item.id})`"
                                        method="delete"
                                        class="text-red-500"
                                        as="button"
                                        type="button"
                                    >
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
