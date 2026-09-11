<script lang="ts" setup>
import { Head, Link } from '@inertiajs/vue3';
import AppShell from '@/layouts/AppShell.vue';
import StatKpiCard from '@/components/ui/StatKpiCard.vue';
import EmptyState from '@/components/ui/EmptyState.vue';

defineProps<{
    customer: Array<{ id: number; nama?: string; name?: string; email: string }>;
    pegawai: Array<{ id: number; nama?: string; name?: string; email: string }>;
}>();
</script>

<template>
    <Head title="Dashboard Super Administrator" />

    <AppShell>
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300">
                            Super Administrator
                        </span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">
                            Kendali Sistem & Otoritas Pengguna
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Manajemen Pengguna & Konfigurasi Akses
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Pemeliharaan akun pegawai, pengaturan hak akses peran, serta monitoring seluruh pemohon terdaftar.
                    </p>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <v-btn
                        component="a"
                        href="/superadmin/users"
                        color="primary"
                        variant="elevated"
                        elevation="1"
                        rounded="lg"
                        prepend-icon="mdi-account-cog-outline"
                        class="text-none font-semibold text-xs"
                    >
                        Kelola Akun Pengguna
                    </v-btn>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <StatKpiCard
                    label="Pelanggan Terdaftar"
                    :value="customer.length"
                    icon="mdi-account-group-outline"
                    color="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400"
                    description="Akun pemohon layanan pengujian lab"
                />

                <StatKpiCard
                    label="Pegawai & Pejabat DLH"
                    :value="pegawai.length"
                    icon="mdi-badge-account-horizontal-outline"
                    color="bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400"
                    description="Staf operasional berlisensi sistem"
                />

                <div class="bg-gradient-to-br from-slate-800 to-slate-950 text-white rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-300">
                                Keamanan & Hak Akses
                            </span>
                            <v-icon size="20" color="white">mdi-shield-lock-outline</v-icon>
                        </div>
                        <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                            Pemberian hak akses dilakukan berjenjang sesuai kewenangan jabatan pada SK Kepala Dinas.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-700/60 flex items-center justify-between text-xs text-slate-300">
                        <span>Role-Based Access Control (RBAC)</span>
                        <v-icon size="16">mdi-key-variant</v-icon>
                    </div>
                </div>
            </div>

            <!-- Quick Navigation to RBAC -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <Link
                    href="/superadmin/users"
                    class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 hover:border-emerald-500 dark:hover:border-emerald-500 bg-white dark:bg-slate-900 transition flex items-center gap-3"
                >
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <v-icon size="20">mdi-account-multiple-outline</v-icon>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Manajemen Pengguna</h4>
                        <p class="text-[11px] text-slate-400">Daftar semua pengguna</p>
                    </div>
                </Link>

                <Link
                    href="/superadmin/role"
                    class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 hover:border-blue-500 dark:hover:border-blue-500 bg-white dark:bg-slate-900 transition flex items-center gap-3"
                >
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <v-icon size="20">mdi-badge-account-outline</v-icon>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Kelola Role Jabatan</h4>
                        <p class="text-[11px] text-slate-400">Atur peran operasional</p>
                    </div>
                </Link>

                <Link
                    href="/superadmin/permission"
                    class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 hover:border-purple-500 dark:hover:border-purple-500 bg-white dark:bg-slate-900 transition flex items-center gap-3"
                >
                    <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-400 flex items-center justify-center shrink-0">
                        <v-icon size="20">mdi-key-outline</v-icon>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Kelola Hak Akses (Permission)</h4>
                        <p class="text-[11px] text-slate-400">Konfigurasi otorisasi modul</p>
                    </div>
                </Link>
            </div>

            <!-- Tabel Customer & Pegawai -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tabel Customer -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                <v-icon color="primary" size="20">mdi-account-group-outline</v-icon>
                                Pelanggan / Pemohon Terbaru
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Akun pemohon pengujian sampel laboratorium
                            </p>
                        </div>
                    </div>

                    <EmptyState
                        v-if="customer.length === 0"
                        title="Belum Ada Pelanggan"
                        description="Belum ada data pelanggan yang terdaftar dalam sistem."
                        icon="mdi-account-alert-outline"
                    />

                    <div v-else class="overflow-x-auto max-h-96">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 uppercase font-bold text-[11px] border-b border-slate-200 dark:border-slate-700 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3">Nama Pemohon</th>
                                    <th class="px-4 py-3">Email</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr
                                    v-for="item in customer"
                                    :key="item.id"
                                    class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition"
                                >
                                    <td class="px-4 py-3 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ item.nama || item.name || '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                                        {{ item.email }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel Pegawai -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                <v-icon color="primary" size="20">mdi-badge-account-horizontal-outline</v-icon>
                                Pegawai & Staf DLH Terdaftar
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Akun operasional laboratorium lingkungan
                            </p>
                        </div>
                    </div>

                    <EmptyState
                        v-if="pegawai.length === 0"
                        title="Belum Ada Pegawai"
                        description="Belum ada data pegawai yang terdaftar dalam sistem."
                        icon="mdi-account-alert-outline"
                    />

                    <div v-else class="overflow-x-auto max-h-96">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 uppercase font-bold text-[11px] border-b border-slate-200 dark:border-slate-700 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3">Nama Pegawai</th>
                                    <th class="px-4 py-3">Email Kedinasan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr
                                    v-for="item in pegawai"
                                    :key="item.id"
                                    class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition"
                                >
                                    <td class="px-4 py-3 font-semibold text-slate-900 dark:text-slate-100">
                                        {{ item.nama || item.name || '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                                        {{ item.email }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>