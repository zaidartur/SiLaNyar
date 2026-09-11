<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';

interface User {
    id: number;
    nik?: string;
    nama: string;
    tgl_lahir?: string;
    provinsi?: string;
    kab_kota?: string;
    kecamatan?: string;
    kelurahan?: string;
    rt?: string;
    rw?: string;
    kode_pos?: string;
    alamat?: string;
    email: string;
    no_wa?: string;
    username?: string;
}

const props = defineProps<{
    user?: User;
}>();
</script>

<template>
    <Head title="Profil Saya" />

    <AdminLayout title="Profil Saya">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section & Tombol Kembali -->
            <div class="flex items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        @click="router.visit('/pegawai/profile')"
                        class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition"
                        title="Kembali ke profil utama"
                    >
                        <v-icon size="20">mdi-arrow-left</v-icon>
                    </button>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                            Detail Data Pegawai
                        </h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Rincian data identitas kependudukan dan kontak pegawai
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kartu Profil Detail -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div class="h-16 w-16 rounded-2xl bg-emerald-800 text-white flex items-center justify-center text-2xl font-black shadow-md border-2 border-emerald-600/30">
                        {{ props.user?.nama ? props.user.nama.charAt(0).toUpperCase() : 'P' }}
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                            {{ props.user?.nama || 'Pegawai Laboratorium' }}
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                            ID Pegawai: #{{ props.user?.id || '-' }} &bull; Username: {{ props.user?.username || '-' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">NIK (Nomor Induk Kependudukan)</span>
                        <p class="font-mono font-bold text-slate-900 dark:text-slate-100">{{ props.user?.nik || '-' }}</p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Email</span>
                        <p class="font-semibold text-slate-800 dark:text-slate-200">{{ props.user?.email || '-' }}</p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Nomor WhatsApp</span>
                        <p class="font-semibold text-emerald-700 dark:text-emerald-400">{{ props.user?.no_wa || '-' }}</p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Tanggal Lahir</span>
                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ props.user?.tgl_lahir || '-' }}</p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 sm:col-span-2">
                        <span class="text-slate-500 dark:text-slate-400">Alamat Rumah</span>
                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ props.user?.alamat || '-' }}</p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">RT / RW</span>
                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ props.user?.rt || '-' }} / {{ props.user?.rw || '-' }}</p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Kelurahan / Desa</span>
                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ props.user?.kelurahan || '-' }}</p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Kecamatan</span>
                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ props.user?.kecamatan || '-' }}</p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Kabupaten / Kota</span>
                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ props.user?.kab_kota || '-' }}</p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Provinsi</span>
                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ props.user?.provinsi || '-' }}</p>
                    </div>

                    <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <span class="text-slate-500 dark:text-slate-400">Kode Pos</span>
                        <p class="font-mono font-medium text-slate-800 dark:text-slate-200">{{ props.user?.kode_pos || '-' }}</p>
                    </div>
                </div>
            </v-card>
        </div>
    </AdminLayout>
</template>
