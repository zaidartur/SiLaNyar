<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link, Head } from '@inertiajs/vue3'

interface Parameter {
    id: number
    kode_parameter: string
    nama_parameter: string
    satuan: string
    harga: number
}

const props = defineProps<{
    parameter: Parameter[]
}>()
</script>

<template>
    <Head title="Parameter" />
    <AdminLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-black">PARAMETER</h1>
                <Link :href="route('pegawai.parameter.tambah')" class="flex items-center gap-2 rounded bg-green-600 px-4 py-2 text-white">
                    <span>+</span> Tambah
                </Link>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full rounded-lg bg-white">
                    <thead>
                        <tr class="bg-gray-500 text-white">
                            <th class="px-6 py-3">ID Parameter</th>
                            <th class="px-6 py-3">Nama Parameter</th>
                            <th class="px-6 py-3">Satuan</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.parameter" :key="item.id" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-200'">
                            <td class="px-6 py-4 text-black">{{ item.kode_parameter }}</td>
                            <td class="px-6 py-4 text-black">{{ item.nama_parameter }}</td>
                            <td class="px-6 py-4 text-black">{{ item.satuan }}</td>
                            <td class="px-6 py-4 text-black">{{ Number(item.harga).toLocaleString('id-ID') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.parameter.edit', item.id)" class="text-yellow-500">
                                        <span>✏️</span>
                                    </Link>
                                    <Link
                                        :href="route('pegawai.parameter.destroy', item.id)"
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
