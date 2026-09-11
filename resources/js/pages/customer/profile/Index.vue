<script setup lang="ts">
import EditInstansi from '@/components/form/customer/profile/EditInstansi.vue';
import TambahInstansi from '@/components/form/customer/profile/TambahInstansi.vue';
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head } from '@inertiajs/vue3';
import moment from 'moment';
import { ref } from 'vue';

interface Instansi {
    id: number;
    nama: string;
    jabatan: string;
    tipe?: 'swasta' | 'pemerintahan' | 'pribadi';
    status_verifikasi?: 'diproses' | 'diterima' | 'ditolak';
}

interface User {
    id: number;
    nama: string;
    email: string;
    no_wa: string;
    alamat: string;
    instansi?: Instansi;
    last_login?: string;
}

interface Kecamatan {
    id: number;
    kode: string;
    nama: string;
}

interface Desa {
    id: number;
    kode: string;
    kode_desa: string;
    full_kode: string;
    nama: string;
}

const props = defineProps<{
    user: User;
    instansi: Instansi[];
    kecamatan: Kecamatan[];
    desa: Desa[];
}>();

const showModal = ref(false);
const openModal = () => { showModal.value = true; };
const closeModal = () => { showModal.value = false; };

const showEditInstansiModal = ref(false);
const instansiEditData = ref<any | null>(null);

function editInstansi(id: number) {
    const found = props.instansi.find((i) => i.id === id);
    if (found) {
        instansiEditData.value = { ...found };
        showEditInstansiModal.value = true;
    }
}

function afterEditInstansi() {
    showEditInstansiModal.value = false;
    instansiEditData.value = null;
}

const showEditProfileModal = ref(false);
const toggleEditProfileModal = () => {
    showEditProfileModal.value = !showEditProfileModal.value;
};
</script>

<template>
    <Head title="Profil Pengguna & Instansi" />

    <CustomerLayout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                        Akun Pemohon
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Profil Pelanggan & Instansi
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Kelola data identitas pengguna dan badan usaha / instansi pemohon uji laboratorium.
                    </p>
                </div>

                <div v-if="user?.last_login" class="text-xs text-slate-500 dark:text-slate-400 self-start sm:self-auto">
                    Terakhir masuk: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ moment(user.last_login).format('DD MMM YYYY, HH:mm') }}</span>
                </div>
            </div>

            <!-- Profile Summary Card -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 space-y-6">
                <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100 dark:border-slate-800 text-center sm:text-left">
                    <div class="w-20 h-20 rounded-2xl bg-emerald-700 text-white flex items-center justify-center text-3xl font-black shadow-md shrink-0">
                        {{ user?.nama?.charAt(0).toUpperCase() || 'U' }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <h2 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 truncate">
                                {{ user?.nama }}
                            </h2>
                            <v-chip color="success" size="small" variant="tonal" class="font-bold w-fit mx-auto sm:mx-0">
                                Pengguna Aktif SAKTI
                            </v-chip>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            Akun terintegrasi Single Sign-On Pemerintah Kabupaten Karanganyar
                        </p>
                    </div>

                    <v-btn
                        color="primary"
                        variant="tonal"
                        rounded="lg"
                        prepend-icon="mdi-pencil-outline"
                        @click="toggleEditProfileModal"
                        class="text-none font-semibold text-xs"
                    >
                        Edit Profil SAKTI
                    </v-btn>
                </div>

                <!-- Personal Information Grid -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                        Informasi Kontak Pribadi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 dark:text-slate-500">Nama Lengkap</span>
                            <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ user?.nama || '-' }}</p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 dark:text-slate-500">Alamat Email</span>
                            <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ user?.email || '-' }}</p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 dark:text-slate-500">Nomor WhatsApp / Seluler</span>
                            <p class="font-bold text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ user?.no_wa || '-' }}</p>
                        </div>

                        <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <span class="text-slate-400 dark:text-slate-500">Alamat Domisili</span>
                            <p class="font-medium text-slate-900 dark:text-slate-100 mt-0.5 text-sm">{{ user?.alamat || '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Instansi Terkait Section -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Daftar Instansi / Perusahaan Terdaftar ({{ props.instansi.length }})
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Instansi yang dapat dipilih saat melakukan permohonan pengujian sampel.
                            </p>
                        </div>

                        <v-btn
                            color="primary"
                            rounded="lg"
                            size="small"
                            prepend-icon="mdi-plus"
                            @click="openModal"
                            class="text-none font-semibold text-xs"
                        >
                            Tambah Instansi
                        </v-btn>
                    </div>

                    <div v-if="props.instansi.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div
                            v-for="item in props.instansi"
                            :key="item.id"
                            class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 hover:border-emerald-400 transition-all flex items-start justify-between gap-3"
                        >
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-bold text-slate-900 dark:text-slate-100 truncate text-sm">
                                        {{ item.nama }}
                                    </p>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Jabatan: <span class="text-slate-700 dark:text-slate-300 font-semibold">{{ item.jabatan || '-' }}</span>
                                </p>
                                <div class="flex items-center gap-2 mt-2">
                                    <v-chip size="x-small" variant="tonal" color="primary" class="font-bold uppercase">
                                        {{ item.tipe || 'Instansi' }}
                                    </v-chip>
                                    <v-chip
                                        size="x-small"
                                        variant="tonal"
                                        :color="item.status_verifikasi === 'diterima' ? 'success' : item.status_verifikasi === 'ditolak' ? 'error' : 'warning'"
                                        class="font-semibold uppercase"
                                    >
                                        {{ item.status_verifikasi || 'Diproses' }}
                                    </v-chip>
                                </div>
                            </div>

                            <v-btn
                                icon="mdi-pencil-outline"
                                variant="text"
                                size="small"
                                color="primary"
                                @click="editInstansi(item.id)"
                            />
                        </div>
                    </div>

                    <div v-else class="p-8 text-center rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-800/20">
                        <v-icon icon="mdi-office-building-outline" size="36" color="primary" class="mb-2" />
                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100">Belum Ada Instansi Terdaftar</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">
                            Klik tombol "Tambah Instansi" untuk mendaftarkan nama instansi Anda sebelum mengajukan sampel uji.
                        </p>
                    </div>
                </div>
            </v-card>

            <!-- Dialog Modal Tambah & Edit Instansi -->
            <TambahInstansi v-if="showModal" :kecamatan="props.kecamatan" :desa="props.desa" @close="closeModal" />
            <EditInstansi
                v-if="showEditInstansiModal"
                v-model="showEditInstansiModal"
                :instansi="instansiEditData"
                :kecamatan="props.kecamatan"
                :desa="props.desa"
                @submit="afterEditInstansi"
                @close="showEditInstansiModal = false"
            />

            <!-- Modal Redirect Edit Profile SAKTI -->
            <v-dialog v-model="showEditProfileModal" max-width="480">
                <v-card rounded="xl" class="bg-white dark:bg-slate-900 p-6 text-center space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 mx-auto flex items-center justify-center">
                        <v-icon icon="mdi-open-in-new" size="28" />
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                            Pusat Data Profil SAKTI
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">
                            Data akun Anda terhubung langsung dengan Single Sign-On SAKTI Pemkab Karanganyar. Perubahan data diri dilakukan melalui portal resmi SAKTI.
                        </p>
                    </div>

                    <div class="flex justify-center gap-3 pt-2">
                        <v-btn variant="outlined" rounded="lg" size="small" @click="showEditProfileModal = false" class="text-none">
                            Batal
                        </v-btn>
                        <v-btn
                            component="a"
                            href="https://sakti.karanganyarkab.go.id/profile"
                            target="_blank"
                            color="primary"
                            rounded="lg"
                            size="small"
                            append-icon="mdi-arrow-top-right"
                            class="text-none font-semibold"
                        >
                            Buka Portal SAKTI
                        </v-btn>
                    </div>
                </v-card>
            </v-dialog>
        </div>
    </CustomerLayout>
</template>
