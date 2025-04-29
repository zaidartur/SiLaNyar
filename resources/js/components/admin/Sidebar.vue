<script setup lang="ts">
import { ref } from 'vue'

const isSidebarOpen = ref(true)

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}
</script>

<template>
    <!-- Tombol Toggle untuk Mobile -->
    <button @click="toggleSidebar" class="lg:hidden fixed top-4 left-4 z-30 p-2 bg-green-800 rounded-md">
        <span alt="Menu" class="w-6 h-6">≡</span>
    </button>

    <!-- Sidebar dengan transisi -->
    <aside :class=" [ 'fixed lg:static z-10 bg-green-800 text-white min-h-screen p-4 transition-all duration-300' ,
            isSidebarOpen ? 'w-64 left-0' : '-left-64 lg:w-64' ]">
            <h2 class="text-lg font-bold mb-8 lg:pt-0 pt-12">DLH<br />Karanganyar</h2>

            <nav class="space-y-6 text-sm">
                <div>
                    <p class="text-gray-300 uppercase font-bold mb-2">Utama</p>
                    <a href="/admin/dashboard" class="flex items-center gap-3 py-2 px-3 rounded bg-green-700">
                        <img src="/storage/assetsadmin/Dashboard.png" alt="Dashboard" class="w-5 h-5" />
                        <span>Dashboard</span>
                    </a>
                </div>

                <div>
                    <p class="text-gray-300 uppercase font-bold mb-2">Laboratorium</p>
                    <a href="/admin/jadwal" class="flex items-center gap-3 py-2 px-3 hover:bg-green-700 rounded">
                        <img src="/storage/assetsadmin/Jadwal.png" alt="Jadwal" class="w-5 h-5" />
                        <span>Jadwal</span>
                    </a>
                    <a href="/admin/pengambilan" class="flex items-center gap-3 py-2 pl-10 hover:bg-green-700 rounded">
                        <div class="w-2 h-2 rounded-full bg-black"></div>
                        <span>Pengambilan</span>
                    </a>
                    <a href="/admin/pengujian" class="flex items-center gap-3 py-2 pl-10 hover:bg-green-700 rounded">
                        <div class="w-2 h-2 rounded-full bg-black"></div>
                        <span>Pengujian</span>
                    </a>
                    <a href="/admin/jadwal" class="flex items-center gap-3 py-2 px-3 hover:bg-green-700 rounded">
                        <img src="/storage/assetsadmin/Daftar.png" alt="Daftar" class="w-5 h-5" />
                        <span>Daftar</span>
                    </a>
                    <a href="/admin/daftar/pengajuan"
                        class="flex items-center gap-3 py-2 pl-10 hover:bg-green-700 rounded">
                        <div class="w-2 h-2 rounded-full bg-black"></div>
                        <span>Pengajuan</span>
                    </a>
                    <a href="/admin/daftar/pengujian"
                        class="flex items-center gap-3 py-2 pl-10 hover:bg-green-700 rounded">
                        <div class="w-2 h-2 rounded-full bg-black"></div>
                        <span>Pengujian</span>
                    </a>
                </div>

                <div>
                    <p class="text-gray-300 uppercase font-bold mb-2">Data</p>
                    <a href="/admin/kategori" class="flex items-center gap-3 py-2 px-3 hover:bg-green-700 rounded">
                        <img src="/storage/assetsadmin/Kategori.png" alt="Kategori" class="w-5 h-5" />
                        <span>Kategori</span>
                    </a>
                    <a href="/admin/parameter" class="flex items-center gap-3 py-2 pl-10 hover:bg-green-700 rounded">
                        <div class="w-2 h-2 rounded-full bg-black"></div>
                        <span>Parameter</span>
                    </a>
                    <a href="/admin/detailkategori"
                        class="flex items-center gap-3 py-2 pl-10 hover:bg-green-700 rounded">
                        <div class="w-2 h-2 rounded-full bg-black"></div>
                        <span>Detail Kategori</span>
                    </a>
                    <a href="/admin/hasil-uji" class="flex items-center gap-3 py-2 px-3 hover:bg-green-700 rounded">
                        <img src="/storage/assetsadmin/HasilUji.png" alt="Hasil Uji" class="w-5 h-5" />
                        <span>Hasil Uji</span>
                    </a>
                    <a href="/admin/customer" class="flex items-center gap-3 py-2 px-3 hover:bg-green-700 rounded">
                        <img src="/storage/assetsadmin/DaftarCustomer.png" alt="Daftar Customer" class="w-5 h-5" />
                        <span>Daftar Customer</span>
                    </a>
                </div>

                <div>
                    <p class="text-gray-300 uppercase font-bold mb-2">Pengaturan</p>
                    <a href="/admin/profile" class="flex items-center gap-3 py-2 px-3 hover:bg-green-700 rounded">
                        <img src="/storage/assetsadmin/UserMale.png" alt="Profile" class="w-5 h-5" />
                        <span>Profile</span>
                    </a>
                    <a href="/logout" class="flex items-center gap-3 py-2 px-3 hover:bg-green-700 rounded">
                        <img src="/storage/assetsadmin/LogOut.png" alt="LogOut" class="w-5 h-5" />
                        <span>LogOut</span>
                    </a>
                </div>
            </nav>
            </aside>

            <!-- Overlay untuk mobile -->
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
</style>