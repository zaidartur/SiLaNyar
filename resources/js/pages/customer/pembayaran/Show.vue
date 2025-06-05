<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

interface Parameter {
  id: number
  nama_parameter: string
  satuan: string
  harga: number
}

interface Kategori {
  id: number
  nama: string
  harga: number
  parameter: Parameter[]
  subkategori: any[] // Sesuaikan jika struktur subkategori diperlukan
}

interface Pengajuan {
  id: number
  status_pengajuan: string
  metode_pengambilan: string
  // Tambahkan properti lain dari pengajuan jika digunakan di template
}

const props = defineProps({
  pengajuan: Object as () => Pengajuan,
  pembayaran: Object as () => { status_pembayaran: string; total_biaya: number; metode_pembayaran: string } | null,
  metodePembayaran: Array as () => string[],
  detailPembayaran: Object as () => { kategori: Kategori; parameter: Parameter[] },
})
</script>
<template>
  <Head title="Detail Pembayaran" />
  <div class="max-w-4xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold mb-4">Detail Pembayaran</h1>

    <!-- Informasi Pengajuan -->
    <div class="bg-white shadow p-4 rounded-lg mb-6">
      <h2 class="text-lg font-semibold mb-2">Informasi Pengajuan</h2>
      <p><strong>Status:</strong> {{ props.pengajuan?.status_pengajuan }}</p>
      <p><strong>Metode Pengantaran:</strong> {{ props.pengajuan?.metode_pengambilan }}</p>
      <p><strong>Kategori:</strong> {{ props.detailPembayaran?.kategori.nama }}</p>
    </div>

    <!-- Parameter yang Diuji -->
    <div class="bg-white shadow p-4 rounded-lg mb-6">
      <h2 class="text-lg font-semibold mb-2">Parameter Pengujian</h2>
      <ul class="list-disc list-inside">
        <li v-for="parameter in props.detailPembayaran?.parameter" :key="parameter.id">
          {{ parameter.nama_parameter }} ({{ parameter.satuan }})
        </li>
      </ul>
    </div>

    <!-- Informasi Pembayaran -->
    <div class="bg-white shadow p-4 rounded-lg mb-6">
      <h2 class="text-lg font-semibold mb-2">Status Pembayaran</h2>

      <template v-if="pembayaran">
        <p><strong>Status:</strong> {{ pembayaran.status_pembayaran }}</p>
        <p><strong>Total:</strong> Rp{{ pembayaran.total_biaya.toLocaleString('id-ID') }}</p>
        <p><strong>Metode:</strong> {{ pembayaran.metode_pembayaran }}</p>
      </template>
      <template v-else>
        <p class="text-red-600">Belum ada data pembayaran</p>
      </template>
    </div>

    <!-- Pilihan Metode Pembayaran -->
    <div class="bg-white shadow p-4 rounded-lg mb-6">
      <h2 class="text-lg font-semibold mb-2">Pilih Metode Pembayaran</h2>
      <ul class="flex space-x-4">
        <li v-for="metode in metodePembayaran" :key="metode">
          <template v-if="metode === 'transfer'">
            <Link
              :href="route('customer.pembayaran.upload', props.pengajuan.id)"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
              {{ metode.toUpperCase() }}
            </Link>
          </template>
          <template v-else>
            <button
              class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
              @click="alert('Metode pembayaran tunai akan diproses oleh petugas saat pengantaran/pengambilan sampel.')"
            >
              {{ metode.toUpperCase() }}
            </button>
          </template>
        </li>
      </ul>
    </div>

    <!-- Navigasi -->
    <div class="mt-6">
      <Link href="/customer/dashboard" class="text-blue-600 hover:underline">← Kembali ke Dashboard</Link>
    </div>
  </div>
</template>
