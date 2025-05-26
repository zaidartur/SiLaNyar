<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import TambahParameter from '@/components/form/admin/parameter/Tambah.vue'
import EditParameter from '@/components/form/admin/parameter/Edit.vue'
import { ref } from 'vue'
import { Link, Head } from '@inertiajs/vue3'

const props = defineProps<{
    parameter: any[];
    filter: {
        status: string;
        tanggal: string;
    };
}>();

// Modal Tambah
const showTambahModal = ref(false)
const openTambahModal = () => (showTambahModal.value = true)
const closeTambahModal = () => (showTambahModal.value = false)

// Modal Edit
const showEditModal = ref(false)
const editingParameter = ref(null)
const openEditModal = (item: any) => {
    editingParameter.value = item
    showEditModal.value = true
}
const closeEditModal = () => {
    showEditModal.value = false
    editingParameter.value = null
}
</script>

<template>

    <Head title="Parameter" />
    <AdminLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-black">PARAMETER</h1>
                <button @click="openTambahModal"
                    class="flex items-center gap-2 rounded bg-green-600 px-4 py-2 text-white">
                    <span>+</span> Tambah
                </button>
                <TambahParameter v-if="showTambahModal" @close="closeTambahModal" />
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-lg shadow-md">
                <table class="min-w-full bg-white divide-y divide-gray-300">
                    <thead>
                        <tr class="bg-gray-600 text-white text-left text-sm font-semibold uppercase tracking-wider">
                            <th class="px-6 py-3">ID Parameter</th>
                            <th class="px-6 py-3">Nama Parameter</th>
                            <th class="px-6 py-3">Satuan</th>
                            <th class="px-6 py-3">Harga</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.parameter" :key="item.id" :class="[
                            index % 2 === 0 ? 'bg-white' : 'bg-gray-100',
                            'hover:bg-gray-200 transition-colors'
                        ]">
                            <td class="px-6 py-4 text-gray-800 whitespace-nowrap">PR-{{ String(item.id).padStart(3, '0')
                                }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ item.nama_parameter }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ item.satuan }}</td>
                            <td class="px-6 py-4 text-gray-800">
                                {{ Number(item.harga).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <Button @click="openEditModal(item)"
                                        class="text-yellow-600 hover:text-yellow-800 transition-colors" title="Edit">
                                        ✏️
                                    </Button>
                                    <Link :href="route('pegawai.parameter.destroy', item.id)" method="delete"
                                        as="button" type="button"
                                        class="text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                                    🗑️
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <EditParameter v-if="showEditModal" :parameter="editingParameter" @close="closeEditModal" />
        </div>
    </AdminLayout>
</template>
