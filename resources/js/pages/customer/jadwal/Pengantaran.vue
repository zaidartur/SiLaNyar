<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
// import ScrollArea from '@/components/ui/scroll-area/ScrollArea.vue'
// import { DropdownMenu, DropdownMenuUserDashboard, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
// import { Button } from '@/components/ui/button'
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    nama: string;
    user: User;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    metode_pengambilan: string;
    lokasi: string;
    instansi: Instansi;
}

interface Jadwal {
    id: number;
    kode_pengambilan: string;
    form_pengajuan: Pengajuan;
    user: User;
    waktu_pengambilan: string;
    status: 'diproses' | 'selesai' | 'terkonfirmasi';
    keterangan: string;
}

const props = defineProps<{
    jadwal?: Jadwal[];
    filter?: {
        status?: string;
        tanggal?: string;
    };
}>();

const status = ref(props.filter?.status ?? '');
const tanggal = ref(props.filter?.tanggal ?? '');

// Aman dari error filter jika jadwal undefined
const jadwalPengantaran = computed(() => (props.jadwal ?? []).filter((j) => j.form_pengajuan?.metode_pengambilan === 'diantar'));

const formatTanggal = (tanggalStr: string) => {
    const date = new Date(tanggalStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

const handleFilter = () => {
    window.location.href = `/customer/jadwal/pengantaran?status=${status.value}&tanggal=${tanggal.value}`;
};
</script>

<template>
    <Head title="Jadwal Pengantaran" />
    <CustomerLayout>
        <div class="mx-auto max-w-6xl p-4">
            <!-- Navigasi Antar/Jemput -->
            <div class="mb-4 flex gap-2">
                <Link href="/customer/jadwal/pengantaran" class="rounded-lg bg-customDarkGreen px-4 py-2 font-semibold text-white hover:bg-green-800">
                    Pengantaran
                </Link>
                <Link
                    href="/customer/jadwal/penjemputan"
                    class="rounded-lg bg-gray-100 px-4 py-2 font-semibold text-customDarkGreen hover:bg-gray-200"
                >
                    Penjemputan
                </Link>
            </div>

            <!-- Penting Notice -->
            <div class="mb-4 rounded-lg border-l-8 border-yellow-400 bg-yellow-50 p-4">
                <p class="text-sm text-yellow-700">
                    <span class="font-bold">Penting:</span> Untuk pengantaran sampel ke DLH, silakan bawa sampel ke Gedung A Dinas Lingkungan Hidup
                    pada tanggal yang telah ditentukan. Jam operasional 08:00 - 15:00 WIB.
                </p>
            </div>

            <!-- Filter -->
            <div class="mb-6 flex items-end gap-4">
                <div class="flex flex-col">
                    <label for="status" class="mb-1 text-sm font-medium text-gray-700">Status</label>
                    <select
                        id="status"
                        v-model="status"
                        class="rounded border-gray-300 bg-customDarkGreen px-2 py-1 text-white"
                        @change="handleFilter"
                    >
                        <option disabled value="">Pilih Status</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="terkonfirmasi">Terkonfirmasi</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="tanggal" class="mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                    <input
                        id="tanggal"
                        type="date"
                        v-model="tanggal"
                        class="rounded border-gray-300 bg-customDarkGreen px-2 py-1 text-white"
                        @change="handleFilter"
                    />
                </div>
            </div>

            <!-- Table Jadwal Pengantaran -->
            <div class="overflow-x-auto">
                <table class="min-w-full overflow-hidden rounded-xl border bg-white shadow">
                    <thead>
                        <tr class="bg-customDarkGreen text-white">
                            <th class="rounded-tl-xl px-4 py-3 text-left font-semibold">ID Pengantar</th>
                            <th class="px-4 py-3 text-left font-semibold">Kode Pengajuan</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Instansi</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Pemohon</th>
                            <th class="px-4 py-3 text-left font-semibold">Metode Pengambilan</th>
                            <th class="px-4 py-3 text-left font-semibold">Waktu Pengantaran</th>
                            <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="rounded-tr-xl px-4 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in jadwalPengantaran" :key="item.id" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                            <td class="px-4 py-3">{{ item.kode_pengambilan }}</td>
                            <td class="px-4 py-3">{{ item.form_pengajuan?.kode_pengajuan }}</td>
                            <td class="px-4 py-3">{{ item.form_pengajuan?.instansi?.nama }}</td>
                            <td class="px-4 py-3">{{ item.form_pengajuan?.instansi?.user?.nama }}</td>
                            <td class="px-4 py-3">{{ item.form_pengajuan?.metode_pengambilan }}</td>
                            <td class="px-4 py-3">{{ formatTanggal(item.waktu_pengambilan) }}</td>
                            <td class="px-4 py-3">{{ item.keterangan }}</td>
                            <td class="px-4 py-3">
                                <span
                                    :class="[
                                        'rounded px-2 py-1 text-xs font-semibold',
                                        item.status === 'selesai'
                                            ? 'bg-green-500 text-white'
                                            : item.status === 'terkonfirmasi'
                                              ? 'bg-blue-500 text-white'
                                              : 'bg-yellow-500 text-white',
                                    ]"
                                >
                                    {{ item.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <Link
                                        :href="`/customer/jadwal/pengantaran/${item.id}`"
                                        method="get"
                                        class="text-blue-600 hover:text-blue-800"
                                        as="button"
                                        type="button"
                                        title="Lihat"
                                    >
                                        <span>👁️</span>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="jadwalPengantaran.length === 0">
                            <td colspan="9" class="py-4 text-center text-gray-400">Tidak ada data pengantaran.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </CustomerLayout>
</template>
