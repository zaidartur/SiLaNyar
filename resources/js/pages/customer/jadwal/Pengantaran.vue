<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps<{
    jadwalAntarTerbaru: any[];
}>()

const selectedFilter = ref('all');

const filteredJadwal = computed(() => {
    if (selectedFilter.value === 'all') return props.jadwalAntarTerbaru;
    return props.jadwalAntarTerbaru.filter(
        item => item.status === selectedFilter.value
    );
});

const formatTanggal = (tanggalStr: string) => {
    if (!tanggalStr) return '-'
    const date = new Date(tanggalStr)
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    })
}
</script>

<template>

    <Head title="Jadwal Pengantaran" />
    <CustomerLayout>
        <div class="mx-auto max-w-6xl p-4">
            <!-- Navigasi Antar/Jemput -->
            <div class="mb-4 flex gap-2">
                <Link href="/customer/jadwal/pengantaran"
                    class="rounded-lg bg-customDarkGreen px-4 py-2 font-semibold text-white hover:bg-green-800">
                Pengantaran
                </Link>
                <Link href="/customer/jadwal/penjemputan"
                    class="rounded-lg bg-gray-100 px-4 py-2 font-semibold text-customDarkGreen hover:bg-gray-200">
                Penjemputan
                </Link>
            </div>

            <!-- Penting Notice -->
            <div class="bg-yellow-50 border-l-8 border-yellow-400 p-4 mb-4 rounded-lg">
                <p class="text-sm text-yellow-700">
                    <span class="font-bold">Penting:</span> Untuk pengantaran sampel ke DLH, silakan bawa sampel ke
                    Gedung A Dinas Lingkungan Hidup pada tanggal yang telah ditentukan. Jam operasional 08:00 - 15:00
                    WIB.
                </p>
            </div>

            <!-- Header -->
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Jadwal Pengantaran</h1>
                <div class="mb-4 flex items-center justify-between">
                    <select v-model="selectedFilter" class="rounded-md border px-4 py-2 text-sm">
                        <option value="all">Semua Status</option>
                        <option value="diproses">Diproses</option>
                        <option value="diterima">Diterima</option>
                    </select>
                </div>
            </div>

            <!-- Table Jadwal Pengantaran -->
            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full overflow-hidden rounded-xl border bg-white shadow">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium uppercase text-white">ID
                                Pengantar</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium uppercase text-white">Kode
                                Pengajuan</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium uppercase text-white">Nama
                                Instansi</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium uppercase text-white">Nama
                                Pemohon</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium uppercase text-white">Metode
                                Pengambilan</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium uppercase text-white">Waktu
                                Pengantaran</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium uppercase text-white">Keterangan
                            </th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium uppercase text-white">Status</th>
                            <th class="px-3 md:px-6 py-3 text-left text-xs font-medium uppercase text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-if="!filteredJadwal.length">
                            <td colspan="9" class="py-4 text-center text-gray-400">Tidak ada data pengantaran.</td>
                        </tr>
                        <tr v-for="(item, index) in filteredJadwal" :key="item.id || index">
                            <td class="px-3 md:px-6 py-4">{{ item.kode_pengambilan }}</td>
                            <td class="px-3 md:px-6 py-4">{{ item.form_pengajuan?.kode_pengajuan }}</td>
                            <td class="px-3 md:px-6 py-4">{{ item.form_pengajuan?.instansi?.nama }}</td>
                            <td class="px-3 md:px-6 py-4">{{ item.form_pengajuan?.instansi?.user?.nama }}</td>
                            <td class="px-3 md:px-6 py-4">{{ item.form_pengajuan?.metode_pengambilan }}</td>
                            <td class="px-3 md:px-6 py-4">{{ formatTanggal(item.waktu_pengambilan) }}</td>
                            <td class="px-3 md:px-6 py-4">{{ item.keterangan ? item.keterangan : '-' }}</td>
                            <td class="px-3 md:px-6 py-4">
                                <span :class="[
                                    'rounded px-2 py-1 text-xs font-semibold',
                                    item.status === 'diterima'
                                        ? 'bg-green-500 text-white'
                                        : item.status === 'terkonfirmasi'
                                            ? 'bg-blue-500 text-white'
                                            : 'bg-yellow-500 text-white',
                                ]">
                                    {{ item.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="`/customer/jadwal/${item.id}?from=pengantaran`" class="text-blue-500">
                                    <span>👁️</span>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </CustomerLayout>
</template>