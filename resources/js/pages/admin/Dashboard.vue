<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { defineProps } from 'vue'

defineProps<{
    totalUsers: number
    totalSamples: number
}>()
</script>

<template>
    <AdminLayout>
        <div class="bg-[#D9D9D9] min-h-screen p-6 rounded-xl">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8">
                <!-- Card Pengajuan -->
                <div class="bg-white p-8 rounded-xl shadow-md flex items-center gap-6 min-h-[120px]">
                    <div class="bg-[#81C784]/[0.26] p-2 rounded-lg">
                        <img src="/storage/assetsadmin/pengajuan2.png" class="w-10 h-10" />
                    </div>
                    <div>
                        <p class="font-bold text-2xl text-gray-500">Pengajuan</p>
                        <h2 class="text-6xl font-bold text-green-700">24</h2>
                        <p class="text-sm text-gray-400 mt-2">Total Pengajuan Masuk</p>
                    </div>
                </div>

                <!-- Card Jadwal -->
                <div class="bg-white p-8 rounded-xl shadow-md flex items-center gap-6 min-h-[120px]">
                    <div class="bg-[#81C784]/[0.26] p-2 rounded-lg">
                        <img src="/storage/assetsadmin/jadwal2.png" class="w-10 h-10" />
                    </div>
                    <div>
                        <p class="font-bold text-2xl text-gray-500">Jadwal</p>
                        <h2 class="text-6xl font-bold text-green-700">12</h2>
                        <p class="text-sm text-gray-400 mt-2">Jadwal Aktif</p>
                    </div>
                </div>

                <!-- Card Pengujian -->
                <div class="bg-white p-8 rounded-xl shadow-md flex items-center gap-6 min-h-[120px]">
                    <div class="bg-[#81C784]/[0.26] p-2 rounded-lg">
                        <img src="/storage/assetsadmin/pengujian2.png" class="w-10 h-10" />
                    </div>
                    <div>
                        <p class="font-bold text-2xl text-gray-500">Pengujian</p>
                        <h2 class="text-6xl font-bold text-green-700">10</h2>
                        <p class="text-sm text-gray-400 mt-2">Total Pengujian Masuk</p>
                    </div>
                </div>

                <!-- Card Hasil Uji -->
                <div class="bg-white p-8 rounded-xl shadow-md flex items-center gap-6 min-h-[120px]">
                    <div class="bg-[#81C784]/[0.26] p-2 rounded-lg">
                        <img src="/storage/assetsadmin/hasiluji2.png" class="w-10 h-10" />
                    </div>
                    <div>
                        <p class="font-bold text-2xl text-gray-500">Hasil Uji</p>
                        <h2 class="text-6xl font-bold text-green-700">9</h2>
                        <p class="text-sm text-gray-400 mt-2">Laporan Hasil Uji</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>