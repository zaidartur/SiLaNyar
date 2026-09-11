<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Pengajuan Sampel - Belum Ada Instansi" />
    <CustomerLayout>
        <div class="max-w-2xl mx-auto py-12">
            <v-card
                variant="outlined"
                rounded="xl"
                class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 sm:p-12 text-center"
            >
                <div class="w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 mx-auto flex items-center justify-center mb-4">
                    <v-icon icon="mdi-office-building-alert-outline" size="32" />
                </div>

                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">
                    Instansi Belum Terdaftar
                </h1>

                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 max-w-md mx-auto mt-2 leading-relaxed">
                    Untuk mengajukan permohonan pengujian sampel laboratorium, Anda wajib melengkapi atau menghubungkan data instansi / perusahaan terlebih dahulu pada profil Anda.
                </p>

                <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <v-btn
                        component="a"
                        href="/customer/profile/show"
                        color="primary"
                        rounded="lg"
                        prepend-icon="mdi-account-plus-outline"
                        class="text-none font-semibold text-xs px-6"
                    >
                        Buka Profil & Tambah Instansi
                    </v-btn>

                    <v-btn
                        component="a"
                        href="/customer/dashboard"
                        variant="outlined"
                        rounded="lg"
                        class="text-none font-semibold text-xs px-6"
                    >
                        Kembali ke Beranda
                    </v-btn>
                </div>
            </v-card>
        </div>
    </CustomerLayout>
</template>
