<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

interface User {
    id: number;
    nama: string;
}

interface Instansi {
    id: number;
    nama: string;
    user: User;
}

interface Kategori {
    id: number;
    nama: string;
}

interface Jadwal {
    status: string;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    instansi: Instansi;
    kategori: Kategori;
    jadwal?: Jadwal | null;
}

const props = defineProps<{
    form_pengajuan: Pengajuan[];
    user: User[];
}>();

const form = useForm({
    id_form_pengajuan: null as number | null,
    id_user: null as number | null,
    id_kategori: null as number | null,
    tanggal_mulai: '',
    tanggal_selesai: '',
    jam_mulai: '',
    jam_selesai: '',
});

const todayDate = computed(() => {
    const today = new Date();
    return today.toISOString().split('T')[0];
});

const selectedPengajuan = computed(() =>
    props.form_pengajuan.find((f) => f.id === form.id_form_pengajuan)
);

watch(
    () => form.id_form_pengajuan,
    () => {
        if (selectedPengajuan.value) {
            form.id_kategori = selectedPengajuan.value.kategori.id;
        } else {
            form.id_kategori = null;
        }
    }
);

const submit = () => {
    form.post('/pegawai/pengujian/store');
};
</script>

<template>
    <Head title="Tambah Jadwal Pengujian Sampel" />
    <AdminLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                        Penugasan Laboratorium
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Tambah Jadwal Pengujian Sampel
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
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Form Pengajuan -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Pilih Pengajuan Sampel <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.id_form_pengajuan"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[form.errors.id_form_pengajuan ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        >
                            <option :value="null" disabled>-- Pilih Pengajuan Masuk --</option>
                            <option
                                v-for="item in form_pengajuan.filter(f => !f.jadwal || f.jadwal.status === 'diterima')"
                                :key="item.id"
                                :value="item.id"
                            >
                                {{ item.kode_pengajuan }} - {{ item.instansi?.nama }} ({{ item.kategori?.nama }})
                            </option>
                        </select>
                        <p v-if="form.errors.id_form_pengajuan" class="text-xs text-rose-600 mt-1 font-medium">
                            {{ form.errors.id_form_pengajuan }}
                        </p>
                    </div>

                    <!-- Teknisi Penguji -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Pilih Teknisi Analis Laboratorium <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.id_user"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[form.errors.id_user ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        >
                            <option :value="null" disabled>-- Pilih Teknisi --</option>
                            <option v-for="u in user" :key="u.id" :value="u.id">
                                {{ u.nama }}
                            </option>
                        </select>
                        <p v-if="form.errors.id_user" class="text-xs text-rose-600 mt-1 font-medium">
                            {{ form.errors.id_user }}
                        </p>
                    </div>

                    <!-- Tanggal Mulai & Tanggal Selesai -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Tanggal Mulai Pengujian <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="date"
                                v-model="form.tanggal_mulai"
                                :min="todayDate"
                                required
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                :class="[form.errors.tanggal_mulai ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                            />
                            <p v-if="form.errors.tanggal_mulai" class="text-xs text-rose-600 mt-1 font-medium">
                                {{ form.errors.tanggal_mulai }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Tanggal Selesai Pengujian <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="date"
                                v-model="form.tanggal_selesai"
                                :min="form.tanggal_mulai || todayDate"
                                required
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                :class="[form.errors.tanggal_selesai ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                            />
                            <p v-if="form.errors.tanggal_selesai" class="text-xs text-rose-600 mt-1 font-medium">
                                {{ form.errors.tanggal_selesai }}
                            </p>
                        </div>
                    </div>

                    <!-- Jam Mulai & Jam Selesai -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Jam Mulai Pengujian <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="time"
                                v-model="form.jam_mulai"
                                required
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                :class="[form.errors.jam_mulai ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                            />
                            <p v-if="form.errors.jam_mulai" class="text-xs text-rose-600 mt-1 font-medium">
                                {{ form.errors.jam_mulai }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                                Jam Selesai Pengujian <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="time"
                                v-model="form.jam_selesai"
                                required
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                :class="[form.errors.jam_selesai ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                            />
                            <p v-if="form.errors.jam_selesai" class="text-xs text-rose-600 mt-1 font-medium">
                                {{ form.errors.jam_selesai }}
                            </p>
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
                            prepend-icon="mdi-calendar-check"
                            :loading="form.processing"
                            class="text-none font-semibold text-xs px-6"
                        >
                            Simpan Jadwal Pengujian
                        </v-btn>
                    </div>
                </form>
            </v-card>
        </div>
    </AdminLayout>
</template>
