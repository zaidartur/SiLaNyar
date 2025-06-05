<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

interface ParameterPengujian {
    id_parameter: number
    nama_parameter: string
    satuan: string | null
    nilai: string | null
    baku_mutu: string | null
    keterangan: string | null
}

interface HasilUji {
    id: number
    status: string
    diupdate_oleh: string
    created_at: string
    pengujian: {
        kode_pengujian: string
        form_pengajuan: {
            kode_pengajuan: string
            kategori: {
                nama: string
            }
            instansi: {
                nama: string
                user: {
                    nama: string
                }
            }
        }
        user: {
            nama: string
        }
    }
}

const props = defineProps<{
    hasil_uji: HasilUji
    parameter_pengujian: ParameterPengujian[]
}>()

function bukaPDF() {
    window.open(route('hasil_uji.convert', props.hasil_uji.id), '_blank')
}
</script>

<template>

    <Head title="Detail Hasil Uji" />

    <div class="space-y-6">
        <h1 class="text-2xl font-bold">Detail Hasil Uji</h1>
        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow" @click="bukaPDF">
            Lihat PDF
        </button>

        <!-- Informasi Umum -->
        <div class="bg-white p-4 rounded-xl shadow">
            <p><strong>Kode Hasil Uji:</strong> HU-{{ hasil_uji.id.toString().padStart(4, '0') }}</p>
            <p><strong>Kode Pengujian:</strong> {{ hasil_uji.pengujian.kode_pengujian }}</p>
            <p><strong>Status:</strong> {{ hasil_uji.status }}</p>
            <p><strong>Diupdate Oleh:</strong> {{ hasil_uji.diupdate_oleh }}</p>
            <p><strong>Tanggal Dibuat:</strong> {{ new Date(hasil_uji.created_at).toLocaleString() }}</p>
        </div>

        <!-- Informasi Pengajuan -->
        <div class="bg-white p-4 rounded-xl shadow">
            <p><strong>Kode Pengajuan:</strong> {{ hasil_uji.pengujian.form_pengajuan.kode_pengajuan }}</p>
            <p><strong>Instansi:</strong> {{ hasil_uji.pengujian.form_pengajuan.instansi.nama }}</p>
            <p><strong>Penanggung Jawab Instansi:</strong> {{ hasil_uji.pengujian.form_pengajuan.instansi.user.nama }}
            </p>
            <p><strong>Kategori:</strong> {{ hasil_uji.pengujian.form_pengajuan.kategori.nama }}</p>
            <p><strong>Teknisi:</strong> {{ hasil_uji.pengujian.user.nama }}</p>
        </div>

        <!-- Parameter Pengujian -->
        <div class="bg-white p-4 rounded-xl shadow">
            <h2 class="text-xl font-semibold mb-2">Parameter Pengujian</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="border px-3 py-2">No</th>
                            <th class="border px-3 py-2">Parameter</th>
                            <th class="border px-3 py-2">Nilai</th>
                            <th class="border px-3 py-2">Satuan</th>
                            <th class="border px-3 py-2">Baku Mutu</th>
                            <th class="border px-3 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in parameter_pengujian" :key="item.id_parameter"
                            class="hover:bg-gray-50">
                            <td class="border px-3 py-2">{{ index + 1 }}</td>
                            <td class="border px-3 py-2">{{ item.nama_parameter }}</td>
                            <td class="border px-3 py-2">{{ item.nilai ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ item.satuan ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ item.baku_mutu ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ item.keterangan ?? '-' }}</td>
                        </tr>
                        <tr v-if="parameter_pengujian.length === 0">
                            <td class="border px-3 py-2 text-center" colspan="6">Tidak ada parameter.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
