<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

const redirectToSSO = () => {
    window.location.href = '/login/sakti';
};
</script>

<template>
    <Head title="Masuk Akun - SSO SAKTI Karanganyar" />

    <div class="min-h-screen flex items-center justify-center p-4 bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors">
        <div class="w-full max-w-md space-y-6">
            <!-- Header Brand -->
            <div class="text-center space-y-2">
                <Link href="/" class="inline-flex items-center gap-2.5 group">
                    <img
                        src="/images/logo-dlh.png"
                        alt="Logo DLH Karanganyar"
                        class="h-14 w-auto object-contain transition group-hover:scale-105"
                        onerror="this.style.display='none'"
                    />
                    <div class="text-left">
                        <span class="block text-2xl font-black tracking-tight text-emerald-800 dark:text-emerald-400">SiLaNyar</span>
                        <span class="block text-xs font-semibold text-slate-500 dark:text-slate-400 -mt-1">DLH Kab. Karanganyar</span>
                    </div>
                </Link>
                <h1 class="text-2xl font-black text-slate-900 dark:text-slate-100 tracking-tight">
                    Masuk Akun Pemohon Uji
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                    Akses layanan pengujian sampel laboratorium lingkungan melalui Single Sign-On (SSO) Terpadu
                </p>
            </div>

            <!-- Login Gateway Card -->
            <v-card rounded="2xl" elevation="2" class="p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 text-center space-y-6">
                <div class="mx-auto h-20 w-20 rounded-2xl bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shadow-inner">
                    <v-icon size="42">mdi-shield-account-outline</v-icon>
                </div>

                <div class="space-y-1.5">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                        SSO SAKTI Karanganyar
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Gunakan akun resmi SAKTI Pemerintah Kabupaten Karanganyar untuk otentikasi aman tanpa perlu kata sandi terpisah.
                    </p>
                </div>

                <!-- Tombol SSO SAKTI -->
                <button
                    type="button"
                    @click="redirectToSSO"
                    class="w-full inline-flex items-center justify-center gap-2.5 py-3 px-5 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-bold text-sm shadow-md transition transform hover:-translate-y-0.5 cursor-pointer"
                >
                    <v-icon size="20">mdi-login-variant</v-icon>
                    <span>Masuk dengan Akun SAKTI</span>
                </button>

                <!-- Shortcut Internal Pegawai -->
                <div class="pt-5 border-t border-slate-100 dark:border-slate-800 text-center space-y-2">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Petugas / Staf Laboratorium DLH?
                    </p>
                    <Link
                        href="/login-pegawai"
                        class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline"
                    >
                        <span>Login ke Portal Pegawai</span>
                        <v-icon size="14">mdi-arrow-right</v-icon>
                    </Link>
                </div>
            </v-card>

            <!-- Back to Home -->
            <div class="text-center">
                <Link
                    href="/"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition"
                >
                    <v-icon size="16">mdi-arrow-left</v-icon>
                    <span>Kembali ke Beranda</span>
                </Link>
            </div>
        </div>
    </div>
</template>
