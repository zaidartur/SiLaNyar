<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import TambahKategori from '@/components/form/admin/kategori/Tambah.vue'
import { Link, Head } from '@inertiajs/vue3'
import { ref } from 'vue'

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
    nama: string
}

interface Kategori {
    id: number
    kode_kategori: string
    nama: string
    harga: number
    parameter: Parameter[]
    subkategori: SubKategori[]
}


const props = defineProps<{
    kategori: Kategori[]
}>()
</script>

<template>

    <Head title="Kategori" />
    <AdminLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-black">KATEGORI</h1>
                <Link href="/pegawai/kategori/create"
                    class="flex items-center gap-2 rounded bg-green-600 px-4 py-2 text-white">
                <span>+</span> Tambah
                </Link>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full bg-white divide-y divide-gray-300">
                    <thead>
                        <tr class="bg-gray-500 text-white text-left text-sm font-semibold uppercase tracking-wider">
                            <th class="px-6 py-3">ID Kategori</th>
                            <th class="px-6 py-3">Nama Kategori</th>
                            <th class="px-6 py-3">Sub Kategori</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Nama Parameter (Baku Mutu)</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="(item, index) in props.kategori" :key="item.id"
                        :class="[
                            index % 2 === 0 ? 'bg-white' : 'bg-gray-50',
                            'hover:bg-gray-100 transition-colors'
                        ]">
                            <td class="px-6 py-4 text-black">{{ item.kode_kategori }}</td>
                            <td class="px-6 py-4 text-black">{{ item.nama }}</td>
                            <td class="px-6 py-4 text-black">
                                <div v-if="item.subkategori && item.subkategori.length > 0">
                                    <div v-for="sub in item.subkategori" :key="sub.id" class="mb-1">
                                        {{ sub.nama }}
                                    </div>
                                </div>
                                <div v-else>-</div>
                            </td>
                            <td class="px-6 py-4 text-black">{{ Number(item.harga).toLocaleString('id-ID') }}</td>
                            <td class="px-6 py-4 text-black">
                                <ul class="list-disc ml-4">
                                    <li v-for="param in item.parameter" :key="param.id">
                                        {{ param.nama_parameter }} ({{ param.baku_mutu }})
                                    </li>
                                </ul>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.kategori.edit', item.id)" class="text-yellow-500">
                                    <span>✏</span>
                                    </Link>
                                    <Link :href="route('pegawai.kategori.destroy', item.id)" method="delete"
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
