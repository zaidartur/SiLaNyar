<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import LogoutConfirmDialog from '@/components/dialogs/LogoutConfirmDialog.vue';
import { useLogoutConfirm } from '@/composables/useLogoutConfirm';

const { openLogoutDialog } = useLogoutConfirm();
</script>

<template>
    <Head title="Konfirmasi Keluar Akun - SiLaNyar" />

    <div class="min-h-screen flex items-center justify-center p-4 bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors">
        <div class="w-full max-w-md space-y-6">
            <!-- Logout Card -->
            <v-card rounded="2xl" elevation="2" class="p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 text-center space-y-6">
                <div class="mx-auto h-20 w-20 rounded-2xl bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 flex items-center justify-center shadow-inner">
                    <v-icon size="40">mdi-logout-variant</v-icon>
                </div>

                <div class="space-y-1.5">
                    <h1 class="text-xl font-black text-slate-900 dark:text-slate-100">
                        Keluar dari Akun SiLaNyar?
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Anda akan mengakhiri sesi aktif saat ini. Untuk mengakses layanan kembali, Anda perlu login ulang via SSO SAKTI Karanganyar.
                    </p>
                </div>

                <!-- Vuetify Prompt Alert Box -->
                <v-alert
                    type="warning"
                    variant="tonal"
                    density="compact"
                    rounded="lg"
                    class="text-xs text-left border border-amber-200 dark:border-amber-800"
                >
                    <template #prepend>
                        <v-icon size="18" color="warning">mdi-alert-circle-outline</v-icon>
                    </template>
                    <span>Peringatan: Pastikan semua data pekerjaan telah tersimpan sebelum keluar.</span>
                </v-alert>

                <div class="space-y-3 pt-2">
                    <button
                        type="button"
                        @click="openLogoutDialog"
                        class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-bold text-xs shadow-md transition cursor-pointer"
                    >
                        <v-icon size="18">mdi-logout</v-icon>
                        <span>Keluar Akun (Konfirmasi)</span>
                    </button>

                    <Link
                        href="/dashboard"
                        class="w-full inline-flex items-center justify-center py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs transition"
                    >
                        Batal, Tetap di Dashboard
                    </Link>
                </div>
            </v-card>
        </div>

        <!-- Global Vuetify Prompt Dialog -->
        <LogoutConfirmDialog />
    </div>
</template>
