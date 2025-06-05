<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

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
    subkategori: any[]
}

interface Pengajuan {
    id: number
    status_pengajuan: string
    metode_pengambilan: string
    volume_sample: string
    lokasi_pengambilan: string
    jenis_sample: string
}

const buktiPembayaran = ref<File | null>(null)
const syarat = ref(false)
const error = ref('')
const loading = ref(false)

const form = useForm({
    metode_pembayaran: 'transfer',
    bukti_pembayaran: null as File | null,
})

function handleFileChange(e: Event) {
    const files = (e.target as HTMLInputElement).files
    if (files && files.length > 0) {
        buktiPembayaran.value = files[0]
        form.bukti_pembayaran = files[0]
    }
}

function handleDrop(e: DragEvent) {
    e.preventDefault()
    if (e.dataTransfer && e.dataTransfer.files.length > 0) {
        buktiPembayaran.value = e.dataTransfer.files[0]
        form.bukti_pembayaran = e.dataTransfer.files[0]
    }
}

function handleDragOver(e: DragEvent) {
    e.preventDefault()
}

function submitPembayaran() {
    error.value = ''
    if (!syarat.value) {
        error.value = 'Anda harus menyetujui syarat dan ketentuan.'
        return
    }
    if (!buktiPembayaran.value) {
        error.value = 'Silakan upload bukti transfer.'
        return
    }
    loading.value = true
    form.post(route('customer.pembayaran.process', props.pengajuan.id), {
        forceFormData: true,
        onFinish: () => loading.value = false,
        onError: (err) => {
            error.value = Object.values(err).join(', ')
        }
    })
}

const props = defineProps<{
    pengajuan: Pengajuan
    pembayaran: { status_pembayaran: string; total_biaya: number; metode_pembayaran: string } | null
    metodePembayaran: string[]
    detailPembayaran: { kategori: Kategori; parameter: Parameter[] }
    pengajuanBerhasil?: boolean
}>()
</script>

<template>

    <Head title="Detail Pembayaran" />
    <div class="space-y-6 max-w-2xl mx-auto py-6">
        <!-- Notifikasi Pengajuan -->
        <div v-if="pengajuanBerhasil" class="rounded border border-green-300 bg-green-100 p-4">
            <b>Pengajuan berhasil dikirim!</b>
        </div>

        <!-- Ringkasan Pengajuan -->
        <div class="rounded border-l-4 border-green-600 bg-white shadow p-4">
            <div class="flex items-center mb-2">
                <span class="text-green-600 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4 -4" />
                    </svg>
                </span>
                <h2 class="font-bold text-green-700 text-lg">Ringkasan Pengajuan</h2>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>
                    <div class="text-gray-500">Jenis Sample</div>
                    <div class="font-semibold">{{ pengajuan.jenis_sample }}</div>
                </div>
                <div>
                    <div class="text-gray-500">Volume/Berat Sample</div>
                    <div class="font-semibold">{{ pengajuan.volume_sample }}</div>
                </div>
                <div>
                    <div class="text-gray-500">Metode Pengambilan</div>
                    <div class="font-semibold">{{ pengajuan.metode_pengambilan }}</div>
                </div>
                <div>
                    <div class="text-gray-500">Lokasi Pengambilan</div>
                    <div class="font-semibold">{{ pengajuan.lokasi_pengambilan }}</div>
                </div>
            </div>
        </div>

        <!-- Parameter Pengujian -->
        <div class="rounded border-l-4 border-green-600 bg-white shadow p-4">
            <div class="flex items-center mb-2">
                <span class="text-green-600 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4 -4" />
                    </svg>
                </span>
                <h2 class="font-bold text-green-700 text-lg">Parameter Pengujian & Rincian Biaya</h2>
            </div>
            <table class="w-full text-sm mb-2">
                <thead>
                    <tr class="bg-green-50">
                        <th class="text-left p-2">Parameter</th>
                        <th class="text-right p-2">Harga (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="param in detailPembayaran.parameter" :key="param.id">
                        <td class="p-2">{{ param.nama_parameter }} <span v-if="param.satuan">({{ param.satuan }})</span>
                        </td>
                        <td class="p-2 text-right">{{ param.harga.toLocaleString('id-ID') }}</td>
                    </tr>
                    <tr class="font-bold border-t">
                        <td class="p-2">Total Biaya</td>
                        <td class="p-2 text-right">
                            {{ pembayaran ? pembayaran.total_biaya.toLocaleString('id-ID') : 0 }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Metode Pembayaran -->
        <div class="rounded border-l-4 border-green-600 bg-white shadow p-4">
            <div class="flex items-center mb-2">
                <span class="text-green-600 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4 -4" />
                    </svg>
                </span>
                <h2 class="font-bold text-green-700 text-lg">Metode Pembayaran</h2>
            </div>
            <!-- Flex hanya untuk logo dan deskripsi -->
            <div class="flex items-center bg-blue-50 border border-blue-200 rounded p-3 mb-3">
                <img src="@/components/assets/customer/BankJateng.png" alt="Bank Jateng"
                    class="h-10 w-28 object-contain mr-3" />
                <div>
                    <div class="font-semibold">Transfer Bank Jateng</div>
                    <div class="text-xs text-gray-500">Pembayaran melalui transfer bank ke rekening Bank Jateng</div>
                </div>
            </div>
            <!-- Langkah-langkah pembayaran -->
            <div class="bg-green-50 border border-green-200 rounded p-3 mb-2">
                <div class="font-semibold mb-2">Langkah-langkah Pembayaran via Transfer Bank Jateng</div>
                <ol class="space-y-2 text-sm">
                    <li class="flex items-start">
                        <span
                            class="flex items-center justify-center h-6 w-6 rounded-full bg-green-500 text-white mr-2 font-bold">1</span>
                        Salin nomor rekening tujuan: <b>3-001-12345-6</b> atas nama <b>DINAS PERTANIAN KAB.
                            KARANGANYAR</b>.
                    </li>
                    <li class="flex items-start">
                        <span
                            class="flex items-center justify-center h-6 w-6 rounded-full bg-green-500 text-white mr-2 font-bold">2</span>
                        Lakukan transfer dari rekening Bank Jateng Anda atau melalui ATM/Mobile Banking/Internet Banking
                        Bank Jateng.
                    </li>
                    <li class="flex items-start">
                        <span
                            class="flex items-center justify-center h-6 w-6 rounded-full bg-green-500 text-white mr-2 font-bold">3</span>
                        Transfer sebesar <b>Rp. {{ pembayaran ? pembayaran.total_biaya.toLocaleString('id-ID') : 0
                            }}</b>.
                    </li>
                    <li class="flex items-start">
                        <span
                            class="flex items-center justify-center h-6 w-6 rounded-full bg-green-500 text-white mr-2 font-bold">4</span>
                        Pada kolom keterangan/berita transfer, tuliskan kode pengajuan: <b>{{ pengajuan.id }}</b>
                    </li>
                    <li class="flex items-start">
                        <span
                            class="flex items-center justify-center h-6 w-6 rounded-full bg-green-500 text-white mr-2 font-bold">5</span>
                        Simpan bukti transfer sebagai bukti pembayaran.
                    </li>
                    <li class="flex items-start">
                        <span
                            class="flex items-center justify-center h-6 w-6 rounded-full bg-green-500 text-white mr-2 font-bold">6</span>
                        Upload bukti transfer melalui halaman konfirmasi pembayaran yang akan muncul setelah menekan
                        tombol "Bayar".
                    </li>
                    <li class="flex items-start">
                        <span
                            class="flex items-center justify-center h-6 w-6 rounded-full bg-green-500 text-white mr-2 font-bold">7</span>
                        Tim SiLaNyar akan memverifikasi pembayaran Anda dalam waktu 1x24 jam kerja.
                    </li>
                </ol>
                <div class="mt-3 text-xs text-yellow-700 bg-yellow-100 border border-yellow-300 rounded p-2">
                    <b>Catatan Penting:</b> Pembayaran harus dilakukan dalam waktu 24 jam sejak pengajuan. Jika tidak,
                    pengajuan akan otomatis dibatalkan oleh sistem.
                </div>
            </div>
            <!-- Area Upload Bukti Transfer -->
            <div class="flex flex-col items-center border border-dashed border-green-400 rounded p-6 bg-green-50 mb-2"
                @drop="handleDrop" @dragover="handleDragOver" style="cursor:pointer" @click="$refs.fileInput.click()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-400 mb-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="text-green-700 font-semibold">Klik atau seret Bukti Transfer di sini</span>
                <span class="text-xs text-gray-500">Format file yang didukung: JPG, PNG, PDF. Maks. 5 MB</span>
                <input type="file" ref="fileInput" class="hidden" accept="image/jpeg,image/png,application/pdf"
                    @change="handleFileChange" />
                <span v-if="buktiPembayaran" class="mt-2 text-xs text-green-700">
                    File: {{ buktiPembayaran.name }}
                </span>
            </div>
            <!-- Checkbox -->
            <div class="flex items-start mb-2">
                <input type="checkbox" id="syarat" class="mt-1 mr-2" v-model="syarat" />
                <label for="syarat" class="text-xs text-gray-700">
                    Saya menyatakan bahwa semua informasi yang diberikan adalah benar dan akurat. Saya juga menyetujui
                    syarat dan ketentuan yang berlaku untuk pengujian sampel ini.
                </label>
            </div>
            <!-- Error -->
            <div v-if="error" class="text-red-600 text-xs mb-2">{{ error }}</div>
            <!-- Tombol Aksi -->
            <div class="flex justify-end space-x-2">
                <button class="px-4 py-2 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-100"
                    type="button" @click="$inertia.visit(route('customer.dashboard'))">Batal</button>
                <button class="px-4 py-2 rounded bg-green-600 text-white font-semibold hover:bg-green-700"
                    :disabled="loading" @click="submitPembayaran" type="button">
                    <span v-if="loading">Memproses...</span>
                    <span v-else>Konfirmasi Pembayaran</span>
                </button>
            </div>
        </div>
    </div>
</template>