<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { defineProps } from 'vue'
import { Head } from '@inertiajs/vue3'

defineProps<{
    statistik: {
        pengajuan: number,
        jadwal: number,
        pengujian: number,
        hasil_uji: number
    }
}>()

</script>

<template>
    <Head title="Dashboard" />
    <AdminLayout>
        <div class="bg-[#D9D9D9] h-full p-6 rounded-xl">
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
                        <h2 class="text-6xl font-bold text-green-700">{{ statistik.pengajuan }}</h2>
                        <p class="text-sm text-gray-400 mt-2">Total Pengajuan Masuk</p>
                    </div>
                </div>

                <!-- Card Jadwal -->
                <div class="bg-white p-8 rounded-xl shadow-md flex items-center gap-6 min-h-[120px]">
                    <div class="p-2 rounded-lg">
                        <svg width="86" height="85" viewBox="0 0 86 85" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="86" height="85" rx="10" fill="#81C784" fill-opacity="0.26" />
                            <mask id="mask0_1417_4574" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="7" y="5"
                                width="72" height="72">
                                <rect x="7" y="5" width="72" height="72" fill="#D9D9D9" />
                            </mask>
                            <g mask="url(#mask0_1417_4574)">
                                <path
                                    d="M43 47C42.15 47 41.4375 46.7125 40.8625 46.1375C40.2875 45.5625 40 44.85 40 44C40 43.15 40.2875 42.4375 40.8625 41.8625C41.4375 41.2875 42.15 41 43 41C43.85 41 44.5625 41.2875 45.1375 41.8625C45.7125 42.4375 46 43.15 46 44C46 44.85 45.7125 45.5625 45.1375 46.1375C44.5625 46.7125 43.85 47 43 47ZM31 47C30.15 47 29.4375 46.7125 28.8625 46.1375C28.2875 45.5625 28 44.85 28 44C28 43.15 28.2875 42.4375 28.8625 41.8625C29.4375 41.2875 30.15 41 31 41C31.85 41 32.5625 41.2875 33.1375 41.8625C33.7125 42.4375 34 43.15 34 44C34 44.85 33.7125 45.5625 33.1375 46.1375C32.5625 46.7125 31.85 47 31 47ZM55 47C54.15 47 53.4375 46.7125 52.8625 46.1375C52.2875 45.5625 52 44.85 52 44C52 43.15 52.2875 42.4375 52.8625 41.8625C53.4375 41.2875 54.15 41 55 41C55.85 41 56.5625 41.2875 57.1375 41.8625C57.7125 42.4375 58 43.15 58 44C58 44.85 57.7125 45.5625 57.1375 46.1375C56.5625 46.7125 55.85 47 55 47ZM43 59C42.15 59 41.4375 58.7125 40.8625 58.1375C40.2875 57.5625 40 56.85 40 56C40 55.15 40.2875 54.4375 40.8625 53.8625C41.4375 53.2875 42.15 53 43 53C43.85 53 44.5625 53.2875 45.1375 53.8625C45.7125 54.4375 46 55.15 46 56C46 56.85 45.7125 57.5625 45.1375 58.1375C44.5625 58.7125 43.85 59 43 59ZM31 59C30.15 59 29.4375 58.7125 28.8625 58.1375C28.2875 57.5625 28 56.85 28 56C28 55.15 28.2875 54.4375 28.8625 53.8625C29.4375 53.2875 30.15 53 31 53C31.85 53 32.5625 53.2875 33.1375 53.8625C33.7125 54.4375 34 55.15 34 56C34 56.85 33.7125 57.5625 33.1375 58.1375C32.5625 58.7125 31.85 59 31 59ZM55 59C54.15 59 53.4375 58.7125 52.8625 58.1375C52.2875 57.5625 52 56.85 52 56C52 55.15 52.2875 54.4375 52.8625 53.8625C53.4375 53.2875 54.15 53 55 53C55.85 53 56.5625 53.2875 57.1375 53.8625C57.7125 54.4375 58 55.15 58 56C58 56.85 57.7125 57.5625 57.1375 58.1375C56.5625 58.7125 55.85 59 55 59ZM22 71C20.35 71 18.9375 70.4125 17.7625 69.2375C16.5875 68.0625 16 66.65 16 65V23C16 21.35 16.5875 19.9375 17.7625 18.7625C18.9375 17.5875 20.35 17 22 17H25V11H31V17H55V11H61V17H64C65.65 17 67.0625 17.5875 68.2375 18.7625C69.4125 19.9375 70 21.35 70 23V65C70 66.65 69.4125 68.0625 68.2375 69.2375C67.0625 70.4125 65.65 71 64 71H22ZM22 65H64V35H22V65Z"
                                    fill="#2E7D32" />
                            </g>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-2xl text-gray-500">Jadwal</p>
                        <h2 class="text-6xl font-bold text-green-700">{{ statistik.jadwal }}</h2>
                        <p class="text-sm text-gray-400 mt-2">Jadwal Aktif</p>
                    </div>
                </div>

                <!-- Card Pengujian -->
                <div class="bg-white p-8 rounded-xl shadow-md flex items-center gap-6 min-h-[120px]">
                    <div class="p-2 rounded-lg">
                        <svg width="86" height="85" viewBox="0 0 86 85" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="86" height="85" rx="10" fill="#81C784" fill-opacity="0.26" />
                            <mask id="mask0_485_32" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="7" y="3"
                                width="72" height="72">
                                <rect x="7" y="3" width="72" height="72" fill="#D9D9D9" />
                            </mask>
                            <g mask="url(#mask0_485_32)">
                            </g>
                            <path
                                d="M22.0001 66C19.4501 66 17.6376 64.8625 16.5626 62.5875C15.4876 60.3125 15.7501 58.2 17.3501 56.25L34.0001 36V18H31.0001C30.1501 18 29.4376 17.7125 28.8626 17.1375C28.2876 16.5625 28.0001 15.85 28.0001 15C28.0001 14.15 28.2876 13.4375 28.8626 12.8625C29.4376 12.2875 30.1501 12 31.0001 12H55.0001C55.8501 12 56.5626 12.2875 57.1376 12.8625C57.7126 13.4375 58.0001 14.15 58.0001 15C58.0001 15.85 57.7126 16.5625 57.1376 17.1375C56.5626 17.7125 55.8501 18 55.0001 18H52.0001V36L68.6501 56.25C70.2501 58.2 70.5126 60.3125 69.4376 62.5875C68.3626 64.8625 66.5501 66 64.0001 66H22.0001Z"
                                fill="#2E7D32" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-2xl text-gray-500">Pengujian</p>
                        <h2 class="text-6xl font-bold text-green-700">{{ statistik.pengujian }}</h2>
                        <p class="text-sm text-gray-400 mt-2">Total Pengujian Masuk</p>
                    </div>
                </div>

                <!-- Card Hasil Uji -->
                <div class="bg-white p-8 rounded-xl shadow-md flex items-center gap-6 min-h-[120px]">
                    <div class="p-2 rounded-lg">
                        <svg width="86" height="85" viewBox="0 0 86 85" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="86" height="85" rx="10" fill="#81C784" fill-opacity="0.26" />
                            <mask id="mask0_1417_4573" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="7" y="6"
                                width="72" height="72">
                                <rect x="7" y="6" width="72" height="72" fill="#D9D9D9" />
                            </mask>
                            <g mask="url(#mask0_1417_4573)">
                                <path
                                    d="M25 72C23.35 72 21.9375 71.4125 20.7625 70.2375C19.5875 69.0625 19 67.65 19 66V18C19 16.35 19.5875 14.9375 20.7625 13.7625C21.9375 12.5875 23.35 12 25 12H49L67 30V66C67 67.65 66.4125 69.0625 65.2375 70.2375C64.0625 71.4125 62.65 72 61 72H25ZM46 33H61L46 18V33Z"
                                    fill="#2E7D32" />
                            </g>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-2xl text-gray-500">Hasil Uji</p>
                        <h2 class="text-6xl font-bold text-green-700">{{ statistik.hasil_uji }}</h2>
                        <p class="text-sm text-gray-400 mt-2">Laporan Hasil Uji</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
