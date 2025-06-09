<script lang="ts" setup>
import { Link } from '@inertiajs/vue3'
import { defineProps } from 'vue'

interface Aduan {
    id: number
    masalah: string
    perbaikan: string
    status: string
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
    aduan: Aduan[]
}>()
</script>

<template>
    <div class="p-6 space-y-6">
        <h1 class="text-2xl font-bold">Daftar Aduan</h1>

        <div class="space-y-4">
            <div v-for="item in aduan" :key="item.id" class="p-4 border rounded-lg shadow-sm bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 text-sm">
                    <div><strong>ID Aduan:</strong> {{ item.id }}</div>
                    <div><strong>Nama Pelapor:</strong> {{ item.user.nama }}</div>
                    <div>
                        <strong>Instansi:</strong>
                        {{ item.hasil_uji?.pengujian?.form_pengajuan?.instansi?.user?.nama || '-' }}
                    </div>
                    <div><strong>Tanggal:</strong> {{ new Date(item.created_at).toLocaleDateString() }}</div>
                    <div><strong>Subjek:</strong> {{ item.masalah }}</div>
                    <div><strong>Deskripsi:</strong> {{ item.perbaikan }}</div>
                    <div><strong>Status:</strong> <span class="text-blue-600">{{ item.status }}</span></div>
                </div>

                <div class="mt-4">
                    <Link :href="`/pegawai/aduan/${item.id}`"
                        class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Lihat Detail
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
