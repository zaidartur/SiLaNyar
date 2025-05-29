<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import { Link, Head } from '@inertiajs/vue3'


interface User {
    id: number
    nama: string
}

interface Instansi {
    id: number
    kode_instansi: string
    nama: string
    tipe?: 'swasta' | 'pemerintahan' | 'pribadi'
    alamat: string
    email: string
    no_telepon: string
    posisi_jabatan: string
    departemen_divisi: string
    status_verifikasi?: 'diproses' | 'diterima' | 'ditolak'
    created_at: string
    diverifikasi_oleh: string
    user: User
}

const props = defineProps<{
    instansi: Instansi[]
}>()
</script>

<template>

    <Head title="Instansi" />
    <AdminLayout>
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-black">Instansi</h1>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full rounded-lg bg-white">
                    <thead>
                        <tr class="bg-gray-500 text-white">
                            <th class="px-6 py-3">ID Instansi</th>
                            <th class="px-6 py-3">Nama Instansi</th>
                            <th class="px-6 py-3">Nama PIC / Nama Customer</th>
                            <th class="px-6 py-3">Tipe</th>
                            <th class="px-6 py-3">Alamat</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">No Telepon</th>
                            <th class="px-6 py-3">Jabatan</th>
                            <th class="px-6 py-3">Departemen / Divisi</th>
                            <th class="px-6 py-3">Diverifikasi Oleh</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.instansi" :key="item.id"
                            :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-200'">
                            <td class="px-6 py-4 text-black">{{ item.kode_instansi }}</td>
                            <td class="px-6 py-4 text-black">{{ item.nama }}</td>
                            <td class="px-6 py-4 text-black">{{ item.user?.nama }}</td>
                            <td class="px-6 py-4 text-black">{{ item.tipe }}</td>
                            <td class="px-6 py-4 text-black">{{ item.alamat }}</td>
                            <td class="px-6 py-4 text-black">{{ item.email }}</td>
                            <td class="px-6 py-4 text-black">{{ item.no_telepon }}</td>
                            <td class="px-6 py-4 text-black">{{ item.posisi_jabatan }}</td>
                            <td class="px-6 py-4 text-black">{{ item.departemen_divisi }}</td>
                            <td class="px-6 py-4 text-black">{{ item.diverifikasi_oleh ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <Link :href="route('pegawai.instansi.detail', item.id)" class="text-blue-500">
                                    <span>👁️</span>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
