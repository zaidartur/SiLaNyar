<script lang="ts" setup>
import { useForm, usePage } from '@inertiajs/vue3';
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
const form_pengajuan = props.form_pengajuan as Pengajuan[];
const user = props.user as User[];

const form = useForm({
    id_form_pengajuan: '',
    id_user: '',
    waktu_pengambilan: null,
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
    <div class="h-screen w-full bg-white lg:grid lg:grid-cols-3">
        <!-- Left Side - Logo Section -->
        <div class="hidden h-screen flex-col bg-customDarkGreen lg:col-span-1 lg:flex lg:items-center lg:justify-center">
            <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="mx-auto h-48 w-auto object-contain" />
            <div class="mt-6 text-center text-white">
                <h2 class="mb-2 border-b border-white pb-2 text-2xl font-bold">SiLanYar</h2>
                <p class="text-sm">Sistem Laboratorium Karanganyar</p>
            </div>
        </div>

        <!-- Right Side - Form Section -->
        <div class="flex h-screen items-start justify-center overflow-y-auto bg-white lg:col-span-2">
            <form @submit.prevent="submit" class="mx-auto grid w-full max-w-xl gap-6 p-6 md:p-12">
                <div class="grid gap-2 text-center">
                    <h1 class="text-3xl font-bold">Tambah Jadwal Pengambilan</h1>
                </div>

                <div class="grid gap-4">
                    <!-- Kode Form Pengajuan -->
                    <div class="grid gap-2">
                        <label for="id_form_pengajuan" class="font-semibold">Kode Form Pengajuan</label>
                        <select id="id_form_pengajuan" v-model="form.id_form_pengajuan" required class="w-full rounded border px-3 py-2">
                            <option value="">Pilih Kode Form Pengajuan</option>
                            <option v-for="fp in form_pengajuan" :key="fp.id" :value="fp.id">{{ fp.kode_pengajuan }} - {{ fp.instansi.nama }}</option>
                        </select>
                        <span v-if="form.errors.id_form_pengajuan" class="text-sm text-red-600">
                            {{ form.errors.id_form_pengajuan }}
                        </span>
                        <div v-if="form_pengajuan.length === 0" class="rounded border border-yellow-200 bg-yellow-50 p-2 text-sm text-yellow-600">
                            Tidak ada form pengajuan yang tersedia. Semua pengajuan dengan metode "diambil" sudah memiliki jadwal atau belum ada
                            pengajuan dengan metode "diambil".
                        </div>
                    </div>

                    <!-- Nama Teknisi -->
                    <div class="grid gap-2">
                        <label for="id_user" class="font-semibold">Nama Teknisi</label>
                        <select id="id_user" v-model="form.id_user" required class="w-full rounded border px-3 py-2">
                            <option value="">Pilih Teknisi</option>
                            <option v-for="u in user" :key="u.id" :value="u.id">
                                {{ u.nama }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_user" class="text-sm text-red-600">
                            {{ form.errors.id_user }}
                        </span>
                    </div>

                    <!-- Waktu Pengambilan -->
                    <div class="grid gap-2">
                        <label for="waktu_pengambilan" class="font-semibold">Waktu Pengambilan</label>
                        <input
                            id="waktu_pengambilan"
                            type="date"
                            v-model="form.waktu_pengambilan"
                            :min="todayDate"
                            required
                            class="w-full rounded border px-3 py-2"
                        />
                        <span v-if="form.errors.waktu_pengambilan" class="text-sm text-red-600">
                            {{ form.errors.waktu_pengambilan }}
                        </span>
                        <span class="text-xs text-gray-500"> Minimal tanggal hari ini ({{ todayDate }}) </span>
                    </div>

                    <!-- Keterangan -->
                    <div class="grid gap-2">
                        <label for="keterangan" class="font-semibold">Keterangan <span class="text-xs text-gray-400">(Opsional)</span></label>
                        <textarea id="keterangan" v-model="form.keterangan" class="w-full rounded border px-3 py-2" rows="3"></textarea>
                        <span v-if="form.errors.keterangan" class="text-sm text-red-600">
                            {{ form.errors.keterangan }}
                        </span>
                    </div>
                </div>

                <button type="submit" class="mb-8 w-full rounded bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700">Simpan</button>
            </form>
        </div>
    </div>
</template>
