<script setup lang="ts">
/* eslint-disable */
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head } from '@inertiajs/vue3';

interface ParameterPengujian {
    id_parameter: number;
    nama_parameter: string;
    satuan: string | null;
    nilai: string | null;
    baku_mutu: string | null;
    keterangan: string | null;
}

interface HasilUji {
    id: number;
    status: string;
    created_at: string;
    pengujian: {
        kode_pengujian: string;
        form_pengajuan: {
            kode_pengajuan: string;
            kategori: {
                nama: string;
            };
            instansi: {
                nama: string;
                user: {
                    nama: string;
                };
            };
        };
        user: {
            nama: string;
        };
    };
}

const props = defineProps<{
    hasil_uji: HasilUji;
    parameter_pengujian: ParameterPengujian[];
}>();

const statusLabels: Record<string, string> = {
    draf: 'Draf',
    revisi: 'Revisi',
    proses_review: 'Proses Review',
    proses_peresmian: 'Proses Peresmian',
    selesai: 'Selesai',
};

function kembali() {
    window.history.back();
}

function bukaPDF() {
    window.open(route('hasil_uji.convert', props.hasil_uji.id), '_blank')
}
</script>

<template>
    <CustomerLayout>
        <div class="min-h-screen bg-gray-200 py-8">

            <Head title="Detail Hasil Uji" />

            <div class="mx-auto max-w-4xl space-y-8 py-8">
                <h1
                    class="mb-1 inline-block w-fit border-b-2 border-customDarkGreen pb-2 text-3xl font-bold text-customDarkGreen">
                    Detail Hasil Uji
                </h1>

                <!-- Informasi Umum -->
                <div class="mb-4 rounded-xl border bg-gray-50 p-6 shadow-lg">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-2 md:grid-cols-2">
                        <div>
                            <span class="font-bold text-customDarkGreen">Kode Hasil Uji:</span>
                            <span class="ml-2">HU-{{ hasil_uji.id.toString().padStart(4, '0') }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Kode Pengujian:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.kode_pengujian }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Status:</span>
                            <span class="ml-2">{{ statusLabels[hasil_uji.status] ?? hasil_uji.status }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Tanggal Dibuat:</span>
                            <span class="ml-2">{{ new Date(hasil_uji.created_at).toLocaleString() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pengajuan -->
                <div class="mb-4 rounded-xl border bg-gray-50 p-6 shadow-lg">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-2 md:grid-cols-2">
                        <div>
                            <span class="font-bold text-customDarkGreen">Kode Pengajuan:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.kode_pengajuan }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Instansi:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.instansi.nama }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Penanggung Jawab Instansi:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.instansi.user.nama }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Kategori:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.form_pengajuan.kategori.nama }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-customDarkGreen">Teknisi:</span>
                            <span class="ml-2">{{ hasil_uji.pengujian.user.nama }}</span>
                        </div>
                    </div>
                </div>

                <!-- Parameter Pengujian -->
                <div class="rounded-xl border bg-white p-6 shadow">
                    <h2 class="mb-4 text-xl font-semibold text-customDarkGreen">Parameter Pengujian</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full rounded border border-gray-200 text-sm">
                            <thead class="bg-customDarkGreen text-white">
                                <tr>
                                    <th class="border px-4 py-2">No</th>
                                    <th class="border px-4 py-2">Parameter</th>
                                    <th class="border px-4 py-2">Nilai</th>
                                    <th class="border px-4 py-2">Satuan</th>
                                    <th class="border px-4 py-2">Baku Mutu</th>
                                    <th class="border px-4 py-2">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in parameter_pengujian" :key="item.id_parameter"
                                    class="hover:bg-gray-50">
                                    <td class="border px-4 py-2 text-center">{{ index + 1 }}</td>
                                    <td class="border px-4 py-2">{{ item.nama_parameter }}</td>
                                    <td class="border px-4 py-2">{{ item.nilai ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ item.satuan ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ item.baku_mutu ?? '-' }}</td>
                                    <td class="border px-4 py-2">{{ item.keterangan ?? '-' }}</td>
                                </tr>
                                <tr v-if="parameter_pengujian.length === 0">
                                    <td class="border px-4 py-2 text-center" colspan="6">Tidak ada parameter.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end mt-8">
                        <button @click="bukaPDF"
                            class="flex items-center px-6 py-3 bg-customDarkGreen hover:bg-green-600 text-white font-semibold rounded-lg shadow transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Download PDF
                        </button>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button @click="kembali"
                        class="rounded bg-customDarkGreen px-4 py-2 font-semibold text-white">Kembali</button>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
