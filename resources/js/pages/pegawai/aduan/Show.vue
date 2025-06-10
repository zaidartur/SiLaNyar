<script lang="ts" setup>
import { Link, useForm } from '@inertiajs/vue3'
import { defineProps } from 'vue'

interface Aduan {
    id: number
    status: string
    masalah: string
    perbaikan: string
    created_at: string
    user: {
        nama: string
    }
    hasil_uji?: {
        pengujian?: {
            form_pengajuan?: {
                instansi?: {
                    user?: {
                        nama: string
                    }
                }
            }
        }
    }
}

const props = defineProps<{
    aduan: Aduan
}>()

const form = useForm({
    status: props.aduan.status,
    diverifikasi_oleh: '', // Diisi otomatis di backend
})

function submit(status: string) {
    form.status = status
    form.post(`/pegawai/aduan/verifikasi/${props.aduan.id}`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="p-6 space-y-6">
        <h1 class="text-2xl font-bold">Detail Aduan</h1>

        <div class="p-4 border rounded-md bg-white shadow space-y-2 text-sm">
            <p><strong>ID Aduan:</strong> {{ aduan.id }}</p>
            <p><strong>Pelapor:</strong> {{ aduan.user.nama }}</p>
            <p><strong>Instansi:</strong> {{
                aduan.hasil_uji?.pengujian?.form_pengajuan?.instansi?.user?.nama || '-'
                }}</p>
            <p><strong>Tanggal:</strong> {{ new Date(aduan.created_at).toLocaleDateString() }}</p>
            <p><strong>Subjek:</strong> {{ aduan.masalah }}</p>
            <p><strong>Deskripsi:</strong> {{ aduan.perbaikan }}</p>
            <p><strong>Status:</strong> <span class="text-blue-600">{{ aduan.status }}</span></p>
        </div>

        <div class="flex flex-wrap gap-2">
            <Link href="/pegawai/aduan" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 transition">
            Kembali
            </Link>

            <button @click="submit('diterima_administrasi')"
                class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white">
                Terima Administrasi
            </button>

            <button @click="submit('diterima_pengujian')"
                class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">
                Terima Pengujian
            </button>

            <button @click="submit('ditolak')" class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white">
                Tolak Aduan
            </button>
        </div>
    </div>
</template>
