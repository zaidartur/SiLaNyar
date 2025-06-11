<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import BarChart from '@/components/BarChart.vue'
import { ref } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'

interface LaporanKeuanganItem {
    id: number
    tanggal_pembayaran: string | null
    total_biaya: number
    form_pengajuan?: {
        kode_pengajuan?: string
        instansi?: {
            nama?: string
        }
    }
}

interface DiagramData {
    label: string[]
    data: {
        label: string
        backgroundColor: string
        borderColor: string
        data: number[]
        fill: boolean
        tension: number
    }
}

const page = usePage()
const filter = ref({ ...page.props.filter })

const laporanKeuangan = page.props.laporan_keuangan as LaporanKeuanganItem[] || []
const totalPemasukan = page.props.total_pemasukan as number || 0
const diagram = page.props.diagram as DiagramData || { label: [], data: {} }
const tahunTersedia = page.props.tahunTersedia as number[] || []

function submitFilter() {
    // Kirim filter ke backend (gunakan Inertia form jika ada)
    // Contoh:
    // Inertia.get(route('pegawai.laporan-keuangan.index'), filter.value)
}
</script>

<template>
    <Head title="Laporan Keuangan" />
    <AdminLayout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Laporan Keuangan</h1>

        <!-- Filter -->
        <form @submit.prevent="submitFilter" class="mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm">Periode</label>
                <select v-model="filter.periode" class="border rounded px-2 py-1">
                    <option value="semua">Semua</option>
                    <option value="bulanan">Bulanan</option>
                    <option value="tahunan">Tahunan</option>
                    <option value="rentang_tanggal">Rentang Tanggal</option>
                </select>
            </div>
            <div v-if="filter.periode === 'bulanan'">
                <label class="block text-sm">Bulan</label>
                <select v-model="filter.bulan" class="border rounded px-2 py-1">
                    <option v-for="b in 12" :key="b" :value="b">{{ b }}</option>
                </select>
            </div>
            <div v-if="filter.periode === 'bulanan'">
                <label class="block text-sm">Tahun</label>
                <select v-model="filter.tahun_bulanan" class="border rounded px-2 py-1">
                    <option v-for="t in tahunTersedia" :key="t" :value="t">{{ t }}</option>
                </select>
            </div>
            <div v-if="filter.periode === 'tahunan'">
                <label class="block text-sm">Tahun</label>
                <select v-model="filter.tahun_tahunan" class="border rounded px-2 py-1">
                    <option v-for="t in tahunTersedia" :key="t" :value="t">{{ t }}</option>
                </select>
            </div>
            <div v-if="filter.periode === 'rentang_tanggal'">
                <label class="block text-sm">Tanggal Mulai</label>
                <input type="date" v-model="filter.tanggal_mulai" class="border rounded px-2 py-1" />
            </div>
            <div v-if="filter.periode === 'rentang_tanggal'">
                <label class="block text-sm">Tanggal Akhir</label>
                <input type="date" v-model="filter.tanggal_akhir" class="border rounded px-2 py-1" />
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Terapkan</button>
        </form>

        <!-- Total Pemasukan -->
        <div class="mb-6">
            <span class="font-semibold">Total Pemasukan:</span>
            <span class="text-lg text-green-700 font-bold">Rp {{ totalPemasukan.toLocaleString('id-ID') }}</span>
        </div>

        <!-- Diagram -->
        <div class="mb-8 bg-white rounded shadow p-4" style="height: 350px;">
            <BarChart :data="{
                labels: diagram.label,
                datasets: [{
                    ...diagram.data,
                    backgroundColor: 'rgba(46, 125, 50, 0.6)',
                    borderColor: 'rgba(46, 125, 50, 1)',
                    borderWidth: 3
                }]
            }" :options="{ responsive: true, maintainAspectRatio: false }" />


        </div>

        <!-- Tabel Laporan -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Kode Pengajuan</th>
                        <th class="px-4 py-2 border">Instansi</th>
                        <th class="px-4 py-2 border">Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in laporanKeuangan" :key="item.id">
                        <td class="px-4 py-2 border">
                            {{ item.tanggal_pembayaran ? new Date(item.tanggal_pembayaran).toLocaleDateString('id-ID') :
                            '-' }}
                        </td>
                        <td class="px-4 py-2 border">{{ item.form_pengajuan?.kode_pengajuan || '-' }}</td>
                        <td class="px-4 py-2 border">{{ item.form_pengajuan?.instansi?.nama || '-' }}</td>
                        <td class="px-4 py-2 border text-right">
                            Rp {{ Number(item.total_biaya).toLocaleString('id-ID') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </AdminLayout>
</template>