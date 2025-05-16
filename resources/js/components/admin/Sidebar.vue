<script setup lang="ts">
import { ref } from 'vue'

const isSidebarOpen = ref(true)
const isDaftarOpen = ref(false)
const isKategoriOpen = ref(false)

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}

const toggleDaftar = () => {
    isDaftarOpen.value = !isDaftarOpen.value
}

const toggleKategori = () => {
    isKategoriOpen.value = !isKategoriOpen.value
}
</script>

<template>
    <button @click="toggleSidebar" class="lg:hidden fixed top-4 left-4 z-30 p-2 bg-green-800 rounded-md">
        <span alt="Menu" class="w-6 h-6">≡</span>
    </button>

    <aside :class="['fixed lg:static z-10 bg-green-800 text-white min-h-screen transition-all duration-300',
        isSidebarOpen ? 'w-64 left-0' : '-left-64 lg:w-64']">
        <div class="flex items-center px-4 font-bold text-xl mt-4 mb-6">
            <img src="/storage/assetsadmin/logodlh.png" alt="Logo" class="w-12 h-12" />
            <span class="ml-3">SiLaNyar</span>
        </div>
        <nav class="space-y-10 font-bold text-xl">
            <div>
                <a href="/pegawai/dashboard" class="flex items-center gap-3 py-3 px-3 rounded hover:bg-green-700">
                    <img src="/storage/assetsadmin/beranda.png" alt="Dashboard" class="w-6 h-6" />
                    <span>Beranda</span>
                </a>

                <!-- Daftar Dropdown -->
                <div class="space-y-1">
                    <button @click="toggleDaftar"
                        class="w-full flex items-center justify-between gap-3 py-3 px-3 hover:bg-green-700 rounded">
                        <div class="flex items-center gap-3">
                            <img src="/storage/assetsadmin/daftar.png" alt="Daftar" class="w-6 h-6" />
                            <span>Daftar</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 fill-white inline ml-3" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M11.99997 18.1669a2.38 2.38 0 0 1-1.68266-.69733l-9.52-9.52a2.38 2.38 0 1 1 3.36532-3.36532l7.83734 7.83734 7.83734-7.83734a2.38 2.38 0 1 1 3.36532 3.36532l-9.52 9.52a2.38 2.38 0 0 1-1.68266.69734z"
                                clip-rule="evenodd" data-original="#000000" />
                        </svg>
                    </button>
                    <div v-if="isDaftarOpen" class="pl-8 space-y-1">
                        <a href="/pegawai/jadwal"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                            <span>Jadwal Pengambilan</span>
                        </a>
                        <a href="/pegawai/pengajuan" class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                            <span>Pengajuan</span>
                        </a>
                        <a href="/pegawai/pengujian" class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                            <span>Pengujian</span>
                        </a>
                    </div>
                </div>

                <!-- Kategori Dropdown -->
                <div class="space-y-1">
                    <button @click="toggleKategori"
                        class="w-full flex items-center justify-between gap-3 py-3 px-3 hover:bg-green-700 rounded">
                        <div class="flex items-center gap-3">
                            <img src="/storage/assetsadmin/kategori.png" alt="Kategori" class="w-6 h-6" />
                            <span>Kategori</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 fill-white inline ml-3" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M11.99997 18.1669a2.38 2.38 0 0 1-1.68266-.69733l-9.52-9.52a2.38 2.38 0 1 1 3.36532-3.36532l7.83734 7.83734 7.83734-7.83734a2.38 2.38 0 1 1 3.36532 3.36532l-9.52 9.52a2.38 2.38 0 0 1-1.68266.69734z"
                                clip-rule="evenodd" data-original="#000000" />
                        </svg>
                    </button>
                    <div v-if="isKategoriOpen" class="pl-8 space-y-1">
                        <a href="/pegawai/parameter" class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                            <span>Parameter</span>
                        </a>
                        <a href="/admin/detail-kategori"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                            <span>Detail Kategori</span>
                        </a>
                        <a href="/admin/jenis-cairan"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                            <span>Jenis Cairan</span>
                        </a>
                    </div>
                </div>

                <a href="/admin/daftarpelanggan" class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                    <img src="/storage/assetsadmin/daftarpelanggan.png" alt="Daftar Customer" class="w-6 h-6" />
                    <span>Daftar Pelanggan</span>
                </a>
                <a href="/admin/hasil-uji" class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                    <img src="/storage/assetsadmin/hasiluji.png" alt="Hasil Uji" class="w-6 h-6" />
                    <span>Hasil Uji</span>
                </a>
                <a href="/admin/profile" class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                    <img src="/storage/assetsadmin/profile.png" alt="Profile" class="w-6 h-6" />
                    <span>Profile</span>
                </a>
                <a href="/logout" class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                    <img src="/storage/assetsadmin/logout.png" alt="LogOut" class="w-6 h-6" />
                    <span>LogOut</span>
                </a>
            </div>
        </nav>
    </aside>

    <div v-if="isSidebarOpen" @click="toggleSidebar" class="fixed inset-0 bg-black bg-opacity-50 z-0 lg:hidden">
    </div>
</template>


<style scoped>
@media (max-width: 1024px) {
    aside {
        position: fixed;
        top: 0;
        height: 100vh;
        overflow-y: auto;
    }
}

aside {
    font-family: 'Lato', sans-serif;
}

.text-xl {
    font-family: 'Lato', sans-serif;
}
</style>