<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'

// Sample data for the table
const tableData = [
    {
        id: 'HU_042-025-1D',
        jenisCatiran: 'Universal',
        kategori: 'Suhu,Ph,DHL',
        tanggal: '22 Apr 2025',
        lokasi: 'Gedung A',
        metodePengambilan: 'DiJemput Petugas',
        aduan: true,
        rating: 5,
        aksi: ''
    }
]

// Pagination state
const currentPage = ref(1)
const totalPages = ref(5)

const handlePageChange = (page: number) => {
    currentPage.value = page
}

</script>

<template>
    <Head title="Hasil Uji Sample" />
    <CustomerLayout>
        <div class="max-w-4xl mx-auto">
            <!-- Header Profile -->
            <div class="mb-4 p-2 bg-white rounded-lg shadow-sm border border-gray-300">
                <h1 class="text-xl font-bold text-gray-800">Hasil Uji Sample </h1>
                <p class="text-sm text-gray-500">Selamat datang di Hasil Uji Sample</p>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-300">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jenis Catiran</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Kategori</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Lokasi</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Metode Pengambilan</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Aduan</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Rating</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in tableData" :key="item.id" class="border-t border-gray-200">
                            <td class="px-4 py-2 text-sm text-gray-900">{{ item.id }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ item.jenisCatiran }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ item.kategori }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ item.tanggal }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ item.lokasi }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ item.metodePengambilan }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                <span v-if="item.aduan" class="text-blue-500">
                                    <i class="fas fa-comment"></i>
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                <span class="text-yellow-400">
                                    <i class="fas fa-star"></i>
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                <button class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-200 flex justify-end">
                    <nav aria-label="Page navigation">
                        <ul class="flex items-center -space-x-px h-10 text-base">
                            <!-- Previous Button -->
                            <li>
                                <a href="#" @click.prevent="currentPage > 1 && handlePageChange(currentPage - 1)"
                                    class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">
                                    <span class="sr-only">Previous</span>
                                    <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 1 1 5l4 4" />
                                    </svg>
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <li v-for="page in totalPages" :key="page">
                                <a href="#" @click.prevent="handlePageChange(page)" :class="[
                                    'flex items-center justify-center px-4 h-10 leading-tight border border-gray-300',
                                    currentPage === page
                                        ? 'z-10 text-white border-customDarkGreen bg-customDarkGreen'
                                        : 'text-gray-500 bg-white hover:bg-customLightGreen hover:text-gray-700'
                                ]" :aria-current="currentPage === page ? 'page' : undefined">
                                    {{ page }}
                                </a>
                            </li>

                            <!-- Next Button -->
                            <li>
                                <a href="#"
                                    @click.prevent="currentPage < totalPages && handlePageChange(currentPage + 1)"
                                    class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">
                                    <span class="sr-only">Next</span>
                                    <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>