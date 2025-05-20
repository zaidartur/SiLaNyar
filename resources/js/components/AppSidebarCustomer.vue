<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
        type="button"
        class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
        </svg>
    </button>

    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-customDarkGreen dark:bg-gray-800">
            <Link :href="route('dashboard')" class="flex items-center ps-2.5 mb-5">
            <img src="/storage/assetslandingpage/LogoDlh.png" class="h-10 me-3 sm:h-9" alt="Logo" />
            <span class="self-center text-2xl font-bold whitespace-nowrap text-white">SiLaNyar</span>
            </Link>
            <ul class="space-y-2 font-medium">
                <li>
                    <Link :href="route('customer.dashboard')"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700 group">
                    <img src="/storage/assetsdashboard/dashboard.png"
                        class="w-6 h-6 text-gray-500 transition duration-75" alt="Dashboard Icon" />
                    <span class="text-xl font-bold ms-3">Dashboard</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('customer.pengajuan.index')"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700 group">
                    <img src="/storage/assetsdashboard/ujisample.png"
                        class="w-6 h-6 text-gray-500 transition duration-75" alt="Uji Sample Icon" />
                    <span class="text-xl font-bold ms-3">Pengajuan Uji Sample</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('customer.hasil_uji.index')"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700 group">
                    <img src="/storage/assetsdashboard/hasiluji.png"
                        class="w-6 h-6 text-gray-500 transition duration-75" alt="Hasil Uji Icon" />
                    <span class="text-xl font-bold ms-3">Hasil Uji Lab</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('customer.jadwal.index')"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700 group">
                    <img src="/storage/assetsdashboard/Jadwal.png" class="w-6 h-6 text-gray-500 transition duration-75"
                        alt="Jadwal Icon" />
                    <span class="text-xl font-bold ms-3">Jadwal</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('customer.profile')"
                        class="flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-700 dark:hover:bg-gray-700 group">
                    <img src="/storage/assetsdashboard/logouser.png"
                        class="w-6 h-6 text-gray-500 transition duration-75" alt="Profile Icon" />
                    <span class="text-xl font-bold ms-3">Profile</span>
                    </Link>
                </li>
            </ul>
            <div class="absolute bottom-0 left-0 w-full p-4">
                <Link :href="route('dashboard')"
                    class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-700 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                <span class="text-xl font-bold ms-3">Keluar</span>
                </Link>
            </div>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div>
            <!-- Slot untuk konten -->
            <slot></slot>
        </div>
    </div>
</template>