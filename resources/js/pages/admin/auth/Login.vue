<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

declare const route: any;

const form = useForm({
    email: '',
    password: '',
    remember: false,
    role: 'admin',
});

const showPassword = ref(false);

const handleSubmit = () => {
    form.post(route('pegawai.login'));
};
</script>

<template>
    <Head title="Login Pegawai & Admin - SiLaNyar" />

    <div class="min-h-screen flex items-center justify-center p-4 bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors">
        <!-- Main Card Container -->
        <div class="w-full max-w-md space-y-6">
            <!-- Header Brand -->
            <div class="text-center space-y-2">
                <Link href="/" class="inline-flex items-center gap-2.5 group">
                    <img
                        src="/images/logo-dlh.png"
                        alt="Logo DLH Karanganyar"
                        class="h-12 w-auto object-contain transition group-hover:scale-105"
                        onerror="this.style.display='none'"
                    />
                    <div class="text-left">
                        <span class="block text-xl font-black tracking-tight text-emerald-800 dark:text-emerald-400">SiLaNyar</span>
                        <span class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 -mt-1">DLH Kab. Karanganyar</span>
                    </div>
                </Link>
                <h1 class="text-2xl font-black text-slate-900 dark:text-slate-100 tracking-tight">
                    Portal Internal Pegawai
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Masuk ke panel manajemen pengujian laboratorium dan administrasi retribusi
                </p>
            </div>

            <!-- Form Card -->
            <v-card rounded="2xl" elevation="2" class="p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                <form @submit.prevent="handleSubmit" class="space-y-4">
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Alamat Email Resmi <span class="text-emerald-600">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <v-icon size="18">mdi-email-outline</v-icon>
                            </span>
                            <input
                                v-model="form.email"
                                id="email"
                                type="email"
                                required
                                autocomplete="email"
                                placeholder="nama@karanganyarkab.go.id"
                                class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                            />
                        </div>
                        <div v-if="form.errors.email" class="mt-1.5 text-xs text-rose-500 font-medium">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Kata Sandi <span class="text-emerald-600">*</span>
                            </label>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <v-icon size="18">mdi-lock-outline</v-icon>
                            </span>
                            <input
                                v-model="form.password"
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi akun..."
                                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                            >
                                <v-icon size="18">{{ showPassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline' }}</v-icon>
                            </button>
                        </div>
                        <div v-if="form.errors.password" class="mt-1.5 text-xs text-rose-500 font-medium">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-600 dark:text-slate-400 select-none">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 dark:bg-slate-800"
                            />
                            <span>Ingat sesi saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-bold text-xs shadow-md transition disabled:opacity-50 cursor-pointer"
                        >
                            <v-icon v-if="!form.processing" size="18">mdi-login</v-icon>
                            <span>{{ form.processing ? 'Memverifikasi Kredensial...' : 'Masuk Portal Pegawai' }}</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 text-center space-y-2">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Bukan pegawai laboratorium?
                    </p>
                    <Link
                        href="/login"
                        class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline"
                    >
                        <span>Login Pemohon / Customer via SSO SAKTI</span>
                        <v-icon size="14">mdi-arrow-right</v-icon>
                    </Link>
                </div>
            </v-card>

            <!-- Footer Copyright -->
            <p class="text-center text-[11px] text-slate-400 dark:text-slate-500">
                &copy; {{ new Date().getFullYear() }} Dinas Lingkungan Hidup Kab. Karanganyar
            </p>
        </div>
    </div>
</template>