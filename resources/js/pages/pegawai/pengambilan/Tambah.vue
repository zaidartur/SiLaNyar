<script lang="ts" setup>
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm, usePage, Head } from '@inertiajs/vue3';
import { computed } from 'vue';

interface User {
    id: number;
    nama: string;
}
interface Instansi {
    id: number;
    nama: string;
}

interface Pengajuan {
    id: number;
    kode_pengajuan: string;
    instansi: Instansi;
}

const { props } = usePage();
const form_pengajuan = (props.form_pengajuan as Pengajuan[]) || [];
const userList = (props.user as User[]) || [];

const form = useForm({
    id_form_pengajuan: '',
    id_user: '',
    waktu_pengambilan: null as string | null,
    keterangan: '',
});

const todayDate = computed(() => {
    const today = new Date();
    return today.toISOString().split('T')[0];
});

const submit = () => {
    form.post('/pegawai/pengambilan/store');
};
</script>

<template>
    <Head title="Tambah Jadwal Pengambilan Sampel" />
    <AdminLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                        Agenda PPCU
                    </span>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mt-1">
                        Tambah Jadwal Pengambilan Sampel
                    </h1>
                </div>

                <v-btn
                    component="a"
                    href="/pegawai/pengambilan"
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
                            <option value="" disabled>-- Pilih Pengajuan Masuk --</option>
                            <option v-for="item in form_pengajuan" :key="item.id" :value="item.id">
                                {{ item.kode_pengajuan }} - {{ item.instansi?.nama }}
                            </option>
                        </select>
                        <p v-if="form.errors.id_form_pengajuan" class="text-xs text-rose-600 mt-1 font-medium">
                            {{ form.errors.id_form_pengajuan }}
                        </p>
                    </div>

                    <!-- Petugas PPCU / Pengambil -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Petugas Pengambil Sampel (PPCU) <span class="text-rose-500">*</span>
                        </label>
                        <select
                            v-model="form.id_user"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[form.errors.id_user ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        >
                            <option value="" disabled>-- Pilih Petugas PPCU --</option>
                            <option v-for="u in userList" :key="u.id" :value="u.id">
                                {{ u.nama }}
                            </option>
                        </select>
                        <p v-if="form.errors.id_user" class="text-xs text-rose-600 mt-1 font-medium">
                            {{ form.errors.id_user }}
                        </p>
                    </div>

                    <!-- Waktu Pengambilan -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Jadwal Tanggal & Waktu Pengambilan <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="date"
                            v-model="form.waktu_pengambilan"
                            :min="todayDate"
                            required
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            :class="[form.errors.waktu_pengambilan ? 'border-rose-500' : 'border-slate-300 dark:border-slate-700']"
                        />
                        <p v-if="form.errors.waktu_pengambilan" class="text-xs text-rose-600 mt-1 font-medium">
                            {{ form.errors.waktu_pengambilan }}
                        </p>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-300">
                            Catatan / Keterangan Penugasan (Opsional)
                        </label>
                        <textarea
                            v-model="form.keterangan"
                            rows="3"
                            placeholder="Catatan koordinasi titik pengambilan sampel..."
                            class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 border-slate-300 dark:border-slate-700"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                        <v-btn
                            component="a"
                            href="/pegawai/pengambilan"
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
                            Jadwalkan Pengambilan
                        </v-btn>
                    </div>
                </form>
            </v-card>
        </div>
    </AdminLayout>
</template>
