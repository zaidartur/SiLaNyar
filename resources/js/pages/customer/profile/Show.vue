<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head } from '@inertiajs/vue3';

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
    <CustomerLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                        Biodata Pengguna
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Profil Pengguna
                    </h1>
                </div>

                <v-btn
                    component="a"
                    href="/customer/dashboard"
                    variant="outlined"
                    rounded="lg"
                    size="small"
                    prepend-icon="mdi-arrow-left"
                    class="text-none font-semibold text-xs"
                >
                    Kembali
                </v-btn>
            </div>

            <!-- Profile Info Card -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-5">
                <div class="flex items-center gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-700 text-white flex items-center justify-center text-2xl font-black shadow shrink-0">
                        {{ props.user?.nama?.charAt(0).toUpperCase() || 'U' }}
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ props.user?.nama || '-' }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">NIK: {{ props.user?.nik || '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Nama Lengkap</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ props.user?.nama || '-' }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Alamat Email</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ props.user?.email || '-' }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Nomor WhatsApp / HP</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ props.user?.no_wa || '-' }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Tanggal Lahir</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ props.user?.tgl_lahir || '-' }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">Kecamatan / Kelurahan</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.user?.kecamatan || '-' }} / {{ props.user?.kelurahan || '-' }}
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-slate-400 dark:text-slate-500">RT / RW / Kode Pos</span>
                        <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ props.user?.rt || '-' }} / {{ props.user?.rw || '-' }} ({{ props.user?.kode_pos || '-' }})
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 sm:col-span-2">
                        <span class="text-slate-400 dark:text-slate-500">Alamat Domisili Lengkap</span>
                        <p class="font-medium text-slate-900 dark:text-slate-100 mt-0.5">{{ props.user?.alamat || '-' }}</p>
                    </div>
                </div>
            </v-card>
        </div>
    </CustomerLayout>
</template>
