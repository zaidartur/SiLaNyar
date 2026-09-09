<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    statistik: {
        totalPengujian: number;
        dalamProses: number;
    };
    pengujian?: any[];
}>();
</script>

<template>
    <Head title="Dashboard Penyelia Laboratorium" />
    <AdminLayout>
        <div class="bg-[#D9D9D9] min-h-screen w-full mx-auto p-4 sm:p-6 lg:p-8 rounded-xl space-y-8">
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Penyelia Laboratorium</h1>
                    <p class="text-gray-500 text-sm mt-1">
                        Pengawasan alur analisis pengujian, pembagian parameter sampel ke Analis, dan kendali baku mutu uji laboratorium.
                    </p>
                </div>
                <div>
                    <Link href="/pegawai/pengujian" class="bg-customDarkGreen text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-800 transition">
                        Alokasi Uji Lab
                    </Link>
                </div>
            </div>

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-16 h-16 bg-purple-100 text-purple-700 rounded-xl flex items-center justify-center font-bold text-3xl">
                        🧪
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Total Pengujian Terdistribusi</p>
                        <h3 class="text-4xl font-extrabold text-purple-800">{{ statistik.totalPengujian }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Total sampel dalam daftar analisis pengujian</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-16 h-16 bg-yellow-100 text-yellow-700 rounded-xl flex items-center justify-center font-bold text-3xl">
                        ⏳
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Pengujian Berjalan (In Progress)</p>
                        <h3 class="text-4xl font-extrabold text-yellow-800">{{ statistik.dalamProses }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Sedang dianalisis oleh analis laboratorium</p>
                    </div>
                </div>
            </div>

            <!-- Tanggung Jawab Penyelia -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Tugas Pokok Penyelia</h3>
                <ul class="list-disc list-inside space-y-2 text-sm text-gray-600">
                    <li>Menerima sampel yang telah dinyatakan sesuai dari Pengendali Teknis.</li>
                    <li>Membagi beban kerja analisis parameter baku mutu kepada masing-masing Analis.</li>
                    <li>Memastikan integritas pengujian kimia/fisika berjalan tepat waktu dan sesuai metode baku.</li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>
