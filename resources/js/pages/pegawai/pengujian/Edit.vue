<script setup lang="ts">
/* eslint-disable */
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface Kategori {
    id: number;
    nama: string;
}

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    nama: string;
    user: User;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    instansi: Instansi;
}

interface Pengujian {
    id: number;
    kode_pengujian: string;
    form_pengajuan: Pengajuan;
    user: User;
    kategori: Kategori;
    tanggal_uji: string;
    jam_mulai: string;
    jam_selesai: string;
    status: 'diproses' | 'selesai';
}

const props = defineProps<{
    pengujian: Pengujian;
    kategoriList: any[];
    userList: any[];
    pengajuanList: any[];
    userRole: string;
}>();

const formatDateForInput = (dateString: string) => {
    if (!dateString) return '';
    return dateString.split(' ')[0];
};

const form = useForm({
    id_form_pengajuan: props.pengujian.form_pengajuan.id,
    id_kategori: props.pengujian.kategori.id,
    id_user: props.pengujian.user.id,
    tanggal_uji: formatDateForInput(props.pengujian.tanggal_uji),
    jam_mulai: props.pengujian.jam_mulai,
    jam_selesai: props.pengujian.jam_selesai,
});

const submit = () => {
    form.put(route('pegawai.pengujian.update', props.pengujian.id));
};
</script>

<template>
    <Head title="Edit Jadwal Pengujian" />
    <AdminLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                            Pengujian Laboratorium
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            #{{ props.pengujian.kode_pengujian }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Edit Jadwal Pengujian Sampel
                    </h1>
                </div>

                <v-btn
                    component="a"
                    href="/pegawai/pengujian"
                    variant="outlined"
                    rounded="lg"
                    size="small"
                    prepend-icon="mdi-arrow-left"
                    class="text-none font-semibold text-xs"
                >
                    Kembali
                </v-btn>
            </div>

            <!-- Form Card -->
            <v-card variant="outlined" rounded="xl" class="border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6">
                <!-- Status Banner -->
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-center justify-between text-xs mb-5">
                    <span class="text-slate-500 dark:text-slate-400">Status Saat Ini:</span>
                    <v-chip
                        size="small"
                        variant="tonal"
                        :color="props.pengujian.status === 'selesai' ? 'success' : 'warning'"
                        class="font-bold uppercase"
                    >
                        {{ props.pengujian.status === 'diproses' ? 'Diproses' : 'Selesai' }}
                    </v-chip>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Form Pengajuan -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Pengajuan Sampel <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.id_form_pengajuan"
                            :disabled="props.userRole !== 'admin'"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:opacity-50"
                            :class="[form.errors.id_form_pengajuan ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        >
                            <option value="">-- Pilih Pengajuan --</option>
                            <option v-for="pengajuan in pengajuanList" :key="pengajuan.id" :value="pengajuan.id">
                                {{ pengajuan.kode_pengajuan }} - {{ pengajuan.instansi?.nama }}
                            </option>
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Kategori Baku Mutu <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.id_kategori"
                            :disabled="props.userRole !== 'admin'"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:opacity-50"
                            :class="[form.errors.id_kategori ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        >
                            <option value="">-- Pilih Kategori --</option>
                            <option v-for="kategori in kategoriList" :key="kategori.id" :value="kategori.id">
                                {{ kategori.nama }}
                            </option>
                        </select>
                    </div>

                    <!-- Teknisi -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Teknisi Penguji <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.id_user"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[form.errors.id_user ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        >
                            <option value="">-- Pilih Teknisi --</option>
                            <option v-for="user in userList" :key="user.id" :value="user.id">
                                {{ user.nama }}
                            </option>
                        </select>
                    </div>

                    <!-- Tanggal Pengujian -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Tanggal Pengujian <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="date"
                            v-model="form.tanggal_uji"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[form.errors.tanggal_uji ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        />
                    </div>

                    <!-- Jam Mulai & Selesai -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Jam Mulai <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="time"
                                v-model="form.jam_mulai"
                                required
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Jam Selesai <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="time"
                                v-model="form.jam_selesai"
                                required
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                            />
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                        <v-btn
                            component="a"
                            href="/pegawai/pengujian"
                            variant="text"
                            size="small"
                            class="text-none text-xs"
                        >
                            Batal
                        </v-btn>

                        <v-btn
                            type="submit"
                            color="primary"
                            rounded="lg"
                            prepend-icon="mdi-content-save-edit"
                            :loading="form.processing"
                            class="text-none font-semibold text-xs px-6"
                        >
                            Simpan Perubahan
                        </v-btn>
                    </div>
                </form>
            </v-card>
        </div>
    </AdminLayout>
</template>
