<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    statistik: {
        tugasPengambilan: number;
        selesai: number;
    };
    pengambilan?: any[];
}>();
</script>

<template>
    <Head title="Dashboard Petugas Pengambil Contoh Uji (PPCU)" />
    <AdminLayout>
        <div class="bg-[#D9D9D9] min-h-screen w-full mx-auto p-4 sm:p-6 lg:p-8 rounded-xl space-y-8">
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Petugas Pengambil Contoh Uji (PPCU)</h1>
                    <p class="text-gray-500 text-sm mt-1">
                        Operasional penjemputan sampel di lapangan, pelacakan posisi geospasial, dan serah terima contoh uji ke laboratorium.
                    </p>
                </div>
                <div>
                    <Link href="/pegawai/pengambilan" class="bg-customDarkGreen text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-800 transition">
                        Lihat Tugas Pengambilan
                    </Link>
                </div>
            </div>

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-16 h-16 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center font-bold text-3xl">
                        📍
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Total Tugas Penjemputan</p>
                        <h3 class="text-4xl font-extrabold text-blue-800">{{ statistik.tugasPengambilan }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Jadwal penugasan lapangan dari Pengendali Teknis</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md flex items-center gap-4">
                    <div class="w-16 h-16 bg-green-100 text-green-700 rounded-xl flex items-center justify-center font-bold text-3xl">
                        ✅
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Sampel Tiba di Lab</p>
                        <h3 class="text-4xl font-extrabold text-customDarkGreen">{{ statistik.selesai }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Contoh uji yang telah diserahterimakan di loket</p>
                    </div>
                </div>
            </div>

            <!-- Panduan Tugas PPCU -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Ketentuan Lapangan PPCU</h3>
                <ul class="list-disc list-inside space-y-2 text-sm text-gray-600">
                    <li>PPCU mengambil contoh uji langsung ke titik lokasi pemohon sesuai jadwal yang ditetapkan.</li>
                    <li>Posisi pergerakan petugas dapat dipantau melalui koordinat GPS ke sistem.</li>
                    <li>Sampel yang diambil langsung oleh PPCU otomatis terjamin kesesuaian prosedurnya dan tidak ditolak di loket penerimaan lab (*Kecuali yang Mengambil Petugas*).</li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>
