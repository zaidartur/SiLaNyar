<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link, Head } from '@inertiajs/vue3'

interface Parameter {
    id: number
    kode_parameter: string
    nama_parameter: string
    satuan: string
    harga: number
    pivot: {
        baku_mutu: string
    }
}

interface SubKategori {
    id: number
    kode_subkategori: string
    nama: string
    parameter: Parameter[]
}

const props = defineProps<{
    subkategori: SubKategori[]
}>()
</script>

<template>

    <Head title="SubKategori" />
    <AdminLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-black">SUBKATEGORI</h1>
                <Link href="/pegawai/subkategori/create"
                    class="flex items-center gap-2 rounded bg-green-600 px-4 py-2 text-white">
                <span>+</span> Tambah
                </Link>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full bg-white divide-y divide-gray-300">
                    <thead>
                        <tr class="bg-gray-500 text-white text-left text-sm font-semibold uppercase tracking-wider">
                            <th class="px-6 py-3">ID Sub Kategori</th>
                            <th class="px-6 py-3">Nama Sub Kategori</th>
                            <th class="px-6 py-3">Nama Parameter (Baku Mutu)</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="(item, index) in props.subkategori" :key="item.id"
                            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-200'">
                            <td class="px-6 py-4 text-black">{{ item.kode_subkategori }}</td>
                            <td class="px-6 py-4 text-black">{{ item.nama }}</td>
                            <td class="px-6 py-4 text-black">
                                <ul class="list-disc ml-4">
                                    <li v-for="param in item.parameter" :key="param.id">
                                        {{ param.nama_parameter }} ({{ param.pivot.baku_mutu }})
                                    </li>
                                </ul>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.subkategori.edit', item.id)" class="text-yellow-500">
                                    <span>✏</span>
                                    </Link>
                                    <Link :href="route('pegawai.subkategori.destroy', item.id)" method="delete"
                                        class="text-red-500" as="button" type="button">
                                    <span>🗑</span>
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