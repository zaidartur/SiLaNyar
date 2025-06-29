<script setup lang="ts">
/* eslint-disable */
import { Head, useForm, Link } from '@inertiajs/vue3'
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue' // Sesuaikan jika Anda menggunakan layout

interface Pengajuan {
    id: number
    // Tambahkan properti lain dari pengajuan jika perlu ditampilkan
    // contoh: kode_pengajuan: string;
}

interface Pembayaran {
    id: number // ID dari model Pembayaran
    id_order: string
    total_biaya: number
    metode_pembayaran: 'transfer' | 'tunai' // Akan selalu 'transfer' di halaman ini berdasarkan controller
    // Tambahkan properti lain dari pembayaran jika perlu ditampilkan
}

const props = defineProps<{
    pengajuan: Pengajuan
    pembayaran: Pembayaran // Dijamin ada dan metode = 'transfer' oleh controller
    errors: Record<string, string> // Tipe untuk errors dari Inertia
}>()

const form = useForm({
    // metode_pembayaran dikirim ke controller 'process'
    // dan harus 'transfer' untuk halaman ini.
    metode_pembayaran: 'transfer' as const,
    bukti_pembayaran: null as File | null,
})

function submit() {
    form.post(route('customer.pembayaran.process', props.pengajuan.id), {
        onError: () => {
        },
    })
}
</script>

<template>
    <CustomerLayout>

        <Head title="Upload Bukti Pembayaran" />
        <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6">
                    <h1 class="text-2xl font-bold text-white text-center">Upload Bukti Pembayaran</h1>
                </div>

                <div class="p-6 space-y-6">
                    <div class="border-b pb-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Detail Tagihan</h2>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p><strong>ID Order:</strong> #{{ props.pembayaran.id_order }}</p>
                            <p><strong>Total Biaya:</strong> Rp{{ props.pembayaran.total_biaya.toLocaleString('id-ID')
                                }}</p>
                            <p><strong>Metode:</strong> {{ props.pembayaran.metode_pembayaran.toUpperCase() }}</p>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-md">
                        <p class="text-sm text-blue-800 font-medium">Informasi Rekening Pembayaran:</p>
                        <ul class="list-disc list-inside text-sm text-blue-700 mt-1 space-y-1">
                            <li><strong>Bank Central Asia (BCA):</strong> 123-456-7890 a.n. SiLaNyar Lab</li>
                            <li><strong>Bank Mandiri:</strong> 098-765-4321 a.n. SiLaNyar Lab</li>
                        </ul>
                        <p class="text-xs text-blue-600 mt-2">Pastikan Anda mentransfer sesuai dengan total biaya yang
                            tertera.</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">
                                File Bukti Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="bukti_pembayaran"
                                @input="form.bukti_pembayaran = ($event.target as HTMLInputElement).files?.[0] || null"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                accept="image/jpeg,image/png,image/jpg" required />
                            <p v-if="form.progress" class="text-xs text-gray-500 mt-1">
                                Mengunggah: {{ form.progress.percentage }}%
                            </p>
                            <p v-if="props.errors.bukti_pembayaran" class="text-xs text-red-600 mt-1">
                                {{ props.errors.bukti_pembayaran }}
                            </p>
                            <p v-if="props.errors.metode_pembayaran" class="text-xs text-red-600 mt-1">
                                {{ props.errors.metode_pembayaran }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t">
                            <Link :href="route('customer.pembayaran.show', props.pengajuan.id)"
                                class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline">
                            &laquo; Kembali ke Detail
                            </Link>
                            <button type="submit" :disabled="form.processing"
                                class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ form.processing ? 'Memproses...' : 'Unggah & Konfirmasi Pembayaran' }}
                            </button>
                        </div>
                        <div v-if="props.errors.id_order"
                            class="mt-2 text-xs text-red-600 p-3 bg-red-50 border border-red-200 rounded-md">
                            {{ props.errors.id_order }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>