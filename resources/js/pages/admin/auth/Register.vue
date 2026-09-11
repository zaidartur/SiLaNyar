<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

declare const route: any;

const form = useForm({
    nama: '',
    email: '',
    no_telepon: '',
    jabatan: '',
    jenis_kelamin: 'laki-laki',
    password: '',
    password_confirmation: '',
    id_role: '',
});

const showPassword = ref(false);

const handleSubmit = () => {
    form.post(route('pegawai.registrasi'));
};
</script>

<template>
    <Head title="Registrasi Pegawai - SiLaNyar" />

    <div class="min-h-screen flex items-center justify-center p-4 py-8 bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors">
        <div class="w-full max-w-xl space-y-6">
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
                    Registrasi Akun Pegawai
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Pendaftaran staf internal operasional laboratorium lingkungan
                </p>
            </div>

            <!-- Form Card -->
            <v-card rounded="2xl" elevation="2" class="p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                <form @submit.prevent="handleSubmit" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Nama Lengkap & Gelar <span class="text-emerald-600">*</span>
                            </label>
                            <input
                                v-model="form.nama"
                                id="nama"
                                type="text"
                                required
                                placeholder="Contoh: Budi Santoso, S.Si"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                            />
                            <div v-if="form.errors.nama" class="mt-1 text-xs text-rose-500 font-medium">
                                {{ form.errors.nama }}
                            </div>
                        </div>

                        <!-- Email Resmi -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Alamat Email <span class="text-emerald-600">*</span>
                            </label>
                            <input
                                v-model="form.email"
                                id="email"
                                type="email"
                                required
                                placeholder="email@karanganyarkab.go.id"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                            />
                            <div v-if="form.errors.email" class="mt-1 text-xs text-rose-500 font-medium">
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <!-- No Telepon -->
                        <div>
                            <label for="no_telepon" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Nomor WhatsApp / Telepon <span class="text-emerald-600">*</span>
                            </label>
                            <input
                                v-model="form.no_telepon"
                                id="no_telepon"
                                type="tel"
                                required
                                placeholder="081234567890"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                            />
                            <div v-if="form.errors.no_telepon" class="mt-1 text-xs text-rose-500 font-medium">
                                {{ form.errors.no_telepon }}
                            </div>
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label for="jabatan" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Jabatan / Posisi <span class="text-emerald-600">*</span>
                            </label>
                            <input
                                v-model="form.jabatan"
                                id="jabatan"
                                type="text"
                                required
                                placeholder="Contoh: Analis Kimia Muda"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                            />
                            <div v-if="form.errors.jabatan" class="mt-1 text-xs text-rose-500 font-medium">
                                {{ form.errors.jabatan }}
                            </div>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label for="jenis_kelamin" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Jenis Kelamin <span class="text-emerald-600">*</span>
                            </label>
                            <select
                                v-model="form.jenis_kelamin"
                                id="jenis_kelamin"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                            >
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                            <div v-if="form.errors.jenis_kelamin" class="mt-1 text-xs text-rose-500 font-medium">
                                {{ form.errors.jenis_kelamin }}
                            </div>
                        </div>

                        <!-- ID Role -->
                        <div>
                            <label for="id_role" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Kode / ID Peran <span class="text-emerald-600">*</span>
                            </label>
                            <input
                                v-model="form.id_role"
                                id="id_role"
                                type="text"
                                required
                                placeholder="Contoh: analis / ppcu"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                            />
                            <div v-if="form.errors.id_role" class="mt-1 text-xs text-rose-500 font-medium">
                                {{ form.errors.id_role }}
                            </div>
                        </div>

                        <!-- Kata Sandi -->
                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Kata Sandi <span class="text-emerald-600">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    v-model="form.password"
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    placeholder="Minimal 8 karakter"
                                    class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                                >
                                    <v-icon size="16">{{ showPassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline' }}</v-icon>
                                </button>
                            </div>
                            <div v-if="form.errors.password" class="mt-1 text-xs text-rose-500 font-medium">
                                {{ form.errors.password }}
                            </div>
                        </div>

                        <!-- Konfirmasi Kata Sandi -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Konfirmasi Kata Sandi <span class="text-emerald-600">*</span>
                            </label>
                            <input
                                v-model="form.password_confirmation"
                                id="password_confirmation"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                placeholder="Ulangi kata sandi"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 transition"
                            />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white font-bold text-xs shadow-md transition disabled:opacity-50 cursor-pointer"
                        >
                            <v-icon v-if="!form.processing" size="18">mdi-account-plus</v-icon>
                            <span>{{ form.processing ? 'Mendaftarkan Akun...' : 'Daftarkan Akun Pegawai' }}</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 text-center space-y-2">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Sudah memiliki akun terdaftar?
                    </p>
                    <Link
                        href="/login-pegawai"
                        class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline"
                    >
                        <span>Masuk ke Portal Pegawai</span>
                        <v-icon size="14">mdi-arrow-right</v-icon>
                    </Link>
                </div>
            </v-card>
        </div>
    </div>
</template>