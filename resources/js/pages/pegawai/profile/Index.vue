<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import moment from 'moment';
import { ref } from 'vue';

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
    last_login?: string;
}

const props = defineProps<{
    user?: User;
}>();

const showEditModal = ref(false);

const toggleEditModal = () => {
    showEditModal.value = !showEditModal.value;
};
</script>

<template>
    <Head title="Profil Pegawai" />

    <AdminLayout title="Profil Saya">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                        Profil Akun
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Profil Pegawai Laboratorium
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Kelola data identitas akun pegawai dan sinkronisasi SSO SAKTI Karanganyar.
                    </p>
                </div>

                <div v-if="props.user?.last_login" class="text-xs text-slate-500 dark:text-slate-400 self-start sm:self-auto">
                    Terakhir masuk: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ moment(props.user.last_login).format('DD MMM YYYY, HH:mm') }}</span>
                </div>
            </div>

            <!-- Profile Summary Card -->
            <v-card rounded="2xl" elevation="1" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 sm:p-8 space-y-6">
                <!-- Avatar & Identity Banner -->
                <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100 dark:border-slate-800 text-center sm:text-left">
                    <div class="h-20 w-20 rounded-2xl bg-emerald-800 text-white flex items-center justify-center text-3xl font-black shadow-md border-2 border-emerald-600/30">
                        {{ props.user?.nama ? props.user.nama.charAt(0).toUpperCase() : 'P' }}
                    </div>

                    <div class="space-y-1.5 flex-1">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                            <h2 class="text-xl font-black text-slate-900 dark:text-slate-100">
                                {{ props.user?.nama || 'Pegawai Laboratorium' }}
                            </h2>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                <v-icon size="14">mdi-check-decagram</v-icon>
                                Pegawai Aktif
                            </span>
                        </div>

                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Username: <span class="font-mono font-semibold text-slate-700 dark:text-slate-300">{{ props.user?.username || '-' }}</span>
                            <span v-if="props.user?.nik" class="ml-3">
                                NIK: <span class="font-mono font-semibold text-slate-700 dark:text-slate-300">{{ props.user.nik }}</span>
                            </span>
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="toggleEditModal"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white transition shadow-sm cursor-pointer"
                    >
                        <v-icon size="16">mdi-account-edit-outline</v-icon>
                        <span>Edit Profil SSO</span>
                    </button>
                </div>

                <!-- Personal Information Grid -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <v-icon color="primary" size="18">mdi-account-details-outline</v-icon>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">
                            Informasi Kontak & Pribadi
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">Nama Lengkap</span>
                            <p class="font-bold text-slate-900 dark:text-slate-100">
                                {{ props.user?.nama || '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">Alamat Email</span>
                            <p class="font-semibold text-slate-800 dark:text-slate-200">
                                {{ props.user?.email || '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">Nomor WhatsApp</span>
                            <p class="font-semibold text-emerald-700 dark:text-emerald-400">
                                {{ props.user?.no_wa || '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">Tanggal Lahir</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200">
                                {{ props.user?.tgl_lahir || '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 sm:col-span-2">
                            <span class="text-slate-500 dark:text-slate-400">Nomor Induk Kependudukan (NIK)</span>
                            <p class="font-mono font-medium text-slate-800 dark:text-slate-200">
                                {{ props.user?.nik || '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Address Grid -->
                <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <v-icon color="primary" size="18">mdi-map-marker-outline</v-icon>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">
                            Domisili & Tempat Tinggal
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 sm:col-span-3">
                            <span class="text-slate-500 dark:text-slate-400">Alamat Lengkap</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200">
                                {{ props.user?.alamat || '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">RT / RW</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200">
                                {{ props.user?.rt || '-' }} / {{ props.user?.rw || '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">Kelurahan / Desa</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200">
                                {{ props.user?.kelurahan || '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">Kecamatan</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200">
                                {{ props.user?.kecamatan || '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">Kabupaten / Kota</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200">
                                {{ props.user?.kab_kota || '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">Provinsi</span>
                            <p class="font-medium text-slate-800 dark:text-slate-200">
                                {{ props.user?.provinsi || '-' }}
                            </p>
                        </div>

                        <div class="space-y-1 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500 dark:text-slate-400">Kode Pos</span>
                            <p class="font-mono font-medium text-slate-800 dark:text-slate-200">
                                {{ props.user?.kode_pos || '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </v-card>

            <!-- Modal Sinkronisasi SAKTI SSO -->
            <v-dialog v-model="showEditModal" max-width="480">
                <v-card rounded="2xl" class="border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 text-center space-y-4">
                    <div class="mx-auto h-16 w-16 rounded-2xl bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 flex items-center justify-center">
                        <v-icon size="36">mdi-shield-account-outline</v-icon>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                            Portal SAKTI Karanganyar
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Data akun pegawai dan profil terintegrasi secara terpusat dengan Single Sign-On (SSO) SAKTI Kabupaten Karanganyar. Pembaruan data pribadi dapat dilakukan langsung melalui portal SAKTI.
                        </p>
                    </div>

                    <div class="pt-2 flex items-center justify-center gap-3">
                        <button
                            type="button"
                            @click="toggleEditModal"
                            class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition"
                        >
                            Tutup
                        </button>
                        <a
                            href="https://sakti.karanganyarkab.go.id/profile"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl text-xs font-bold bg-emerald-700 hover:bg-emerald-800 text-white transition shadow-sm"
                        >
                            <span>Buka Portal SAKTI</span>
                            <v-icon size="16">mdi-open-in-new</v-icon>
                        </a>
                    </div>
                </v-card>
            </v-dialog>
        </div>
    </AdminLayout>
</template>
