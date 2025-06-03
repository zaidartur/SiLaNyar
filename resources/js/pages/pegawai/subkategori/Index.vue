<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link, Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog'

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

// Modal Delete
const showDeleteModal = ref(false)
const deletingSubkategori = ref<SubKategori | null>(null)

const openDeleteModal = (item: SubKategori) => {
    deletingSubkategori.value = item
    showDeleteModal.value = true
}
const closeDeleteModal = () => {
    showDeleteModal.value = false
    deletingSubkategori.value = null
}

const handleDelete = () => {
    if (!deletingSubkategori.value) return

    router.delete(`/pegawai/subkategori/${deletingSubkategori.value.id}`, {
        onSuccess: () => {
            closeDeleteModal()
        },
    })
}
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
                                    <span>✏️</span>
                                    </Link>
                                    <button @click="openDeleteModal(item)" class="text-red-500" type="button">
                                        <span>🗑️</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Delete Confirmation Modal -->
            <Dialog :open="showDeleteModal" @update:open="closeDeleteModal">
                <DialogContent class="w-full max-w-md bg-white rounded-lg shadow-xl p-6">
                    <DialogHeader>
                        <DialogTitle class="text-center text-xl font-bold text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg width="64" height="64" viewBox="0 0 94 94" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M53.4152 15.4982C52.7777 14.3582 51.8477 13.4088 50.721 12.748C49.5943 12.0871 48.3118 11.7388 47.0056 11.7388C45.6994 11.7388 44.4169 12.0871 43.2902 12.748C42.1635 13.4088 41.2335 14.3582 40.596 15.4982L12.6721 65.4474C12.0475 66.5647 11.7257 67.8257 11.7387 69.1057C11.7516 70.3856 12.0989 71.6399 12.7461 72.7442C13.3932 73.8485 14.3178 74.7645 15.4281 75.4014C16.5384 76.0383 17.7959 76.3739 19.0758 76.3749H74.9118C76.1923 76.3749 77.4505 76.04 78.5616 75.4036C79.6727 74.7671 80.5981 73.8512 81.246 72.7467C81.8938 71.6422 82.2416 70.3875 82.2549 69.1071C82.2681 67.8267 81.9463 66.5651 81.3215 65.4474L53.4152 15.4982ZM51.406 60.2187C51.406 61.3873 50.9417 62.5081 50.1154 63.3344C49.2891 64.1607 48.1683 64.6249 46.9997 64.6249C45.8311 64.6249 44.7104 64.1607 43.884 63.3344C43.0577 62.5081 42.5935 61.3873 42.5935 60.2187C42.5935 59.0501 43.0577 57.9293 43.884 57.103C44.7104 56.2767 45.8311 55.8124 46.9997 55.8124C48.1683 55.8124 49.2891 56.2767 50.1154 57.103C50.9417 57.9293 51.406 59.0501 51.406 60.2187ZM44.0622 46.9999V32.3124C44.0622 31.5334 44.3717 30.7862 44.9226 30.2353C45.4735 29.6844 46.2206 29.3749 46.9997 29.3749C47.7788 29.3749 48.526 29.6844 49.0768 30.2353C49.6277 30.7862 49.9372 31.5334 49.9372 32.3124V46.9999C49.9372 47.779 49.6277 48.5262 49.0768 49.0771C48.526 49.628 47.7788 49.9374 46.9997 49.9374C46.2206 49.9374 45.4735 49.628 44.9226 49.0771C44.3717 48.5262 44.0622 47.779 44.0622 46.9999Z"
                                        fill="#E94235" />
                                </svg>
                                Peringatan !
                            </div>
                        </DialogTitle>
                    </DialogHeader>

                    <div class="text-center">
                        <p class="font-bold text-gray-900">
                            HAPUS SUBKATEGORI {{ deletingSubkategori?.kode_subkategori }}
                        </p>
                        <p class="text-gray-600">
                            Apakah Anda yakin ingin menghapus subkategori
                            <span class="font-bold">{{ deletingSubkategori?.nama }}</span>?
                        </p>
                    </div>

                    <div class="mt-6 flex justify-center gap-4">
                        <button @click="closeDeleteModal"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button @click="handleDelete"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Hapus
                        </button>
                    </div>
                </DialogContent>
            </Dialog>
        </div>
    </AdminLayout>
</template>