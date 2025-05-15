<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
    parameter: any[],
    filter: {
        status: string,
        tanggal: string
    }
}>()

const status = ref(props.filter.status)
const tanggal = ref(props.filter.tanggal)

const handleFilter = () => {
    window.location.href = `/pegawai/parameter?status=${status.value}&tanggal=${tanggal.value}`
}
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl text-black font-bold">PARAMETER</h1>
                <Link :href="route('pegawai.parameter.tambah')"
                    class="bg-green-600 text-white px-4 py-2 rounded flex items-center gap-2">
                <span>+</span> Tambah
                </Link>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg">
                    <thead>
                        <tr class="bg-gray-500 text-white">
                            <th class="px-6 py-3">ID Parameter</th>
                            <th class="px-6 py-3">Nama Parameter</th>
                            <th class="px-6 py-3">Satuan</th>
                            <th class="px-6 py-3">Baku Mutu</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.parameter" :key="item.id"
                            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-200'">
                            <td class="px-6 py-4 text-black">PR-{{ String(item.id).padStart(3, '0') }}</td>
                            <td class="px-6 py-4 text-black">{{ item.nama_parameter }}</td>
                            <td class="px-6 py-4 text-black">{{ item.satuan }}</td>
                            <td class="px-6 py-4 text-black">{{ item.baku_mutu }}</td>
                            <td class="px-6 py-4 text-black">{{ Number(item.harga).toLocaleString('id-ID') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.parameter.edit', item.id)" class="text-yellow-500">
                                    <span>✏️</span>
                                    </Link>
                                    <Link :href="route('pegawai.parameter.destroy', item.id)" method="delete"
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