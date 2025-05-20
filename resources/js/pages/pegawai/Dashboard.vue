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
                    <div class="p-2 rounded-lg">
                        <svg width="86" height="85" viewBox="0 0 86 85" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="86" height="85" rx="10" fill="#81C784" fill-opacity="0.26" />
                            <path
                                d="M22 72C20.35 72 18.9375 71.4125 17.7625 70.2375C16.5875 69.0625 16 67.65 16 66V24C16 22.35 16.5875 20.9375 17.7625 19.7625C18.9375 18.5875 20.35 18 22 18H34.525C35.075 16.25 36.15 14.8125 37.75 13.6875C39.35 12.5625 41.1 12 43 12C45 12 46.7875 12.5625 48.3625 13.6875C49.9375 14.8125 51 16.25 51.55 18H64C65.65 18 67.0625 18.5875 68.2375 19.7625C69.4125 20.9375 70 22.35 70 24V66C70 67.65 69.4125 69.0625 68.2375 70.2375C67.0625 71.4125 65.65 72 64 72H22ZM22 66H64V24H58V33H28V24H22V66ZM43 24C43.85 24 44.5625 23.7125 45.1375 23.1375C45.7125 22.5625 46 21.85 46 21C46 20.15 45.7125 19.4375 45.1375 18.8625C44.5625 18.2875 43.85 18 43 18C42.15 18 41.4375 18.2875 40.8625 18.8625C40.2875 19.4375 40 20.15 40 21C40 21.85 40.2875 22.5625 40.8625 23.1375C41.4375 23.7125 42.15 24 43 24Z"
                                fill="#449047" />
                        </svg>

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