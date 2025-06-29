<script setup lang="ts">
/* eslint-disable */
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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

// Convert date from YYYY-MM-DD to display format for input
const formatDateForInput = (dateString: string) => {
    if (!dateString) return '';
    return dateString.split(' ')[0]; // Take only the date part if datetime
};

const userRole = props.userRole;

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

    <Head title="Edit Pengujian" />
    <AdminLayout>
        <div class="p-6">
            <div class="rounded-lg bg-white p-6 shadow">
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-800">Edit Pengujian</h1>
                    <Link :href="route('pegawai.pengujian.index')"
                        class="rounded bg-gray-500 px-4 py-2 text-white hover:bg-gray-600">
                    Kembali
                    </Link>
                </div>

                <!-- Current Status Display -->
                <div class="mb-6 rounded-lg bg-gray-50 p-4">
                    <h3 class="mb-2 font-semibold">Informasi Saat Ini</h3>
                    <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                        <div><strong>Kode Pengujian:</strong> {{ props.pengujian.kode_pengujian }}</div>
                        <div>
                            <strong>Status:</strong>
                            <span class="ml-1 inline-block rounded px-2 py-1 text-xs" :class="props.pengujian.status === 'selesai'
                                ? 'bg-green-100 text-green-800'
                                : 'bg-yellow-100 text-yellow-800'">
                                {{ props.pengujian.status === 'diproses' ? 'Diproses' : 'Selesai' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-600">
                        <strong>Catatan:</strong> Untuk mengubah status pengujian, silakan gunakan tombol pada halaman
                        detail.
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Form Pengajuan -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700"> Pengajuan * </label>

                        <select v-model="form.id_form_pengajuan"
                            class="w-full rounded border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500"
                            :disabled="userRole !== 'admin'" required>
                            <option value="">Pilih Pengajuan</option>
                            <option v-for="pengajuan in pengajuanList" :key="pengajuan.id" :value="pengajuan.id">
                                {{ pengajuan.kode_pengajuan }} - {{ pengajuan.instansi?.nama }}
                            </option>
                        </select>
                        <div v-if="form.errors.id_form_pengajuan" class="mt-1 text-sm text-red-500">
                            {{ form.errors.id_form_pengajuan }}
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700"> Kategori * </label>
                        <select v-model="form.id_kategori"
                            class="w-full rounded border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500"
                            :disabled="userRole !== 'admin'" required>
                            <option value="">Pilih Kategori</option>
                            <option v-for="kategori in kategoriList" :key="kategori.id" :value="kategori.id">
                                {{ kategori.nama }}
                            </option>
                        </select>
                        <div v-if="form.errors.id_kategori" class="mt-1 text-sm text-red-500">
                            {{ form.errors.id_kategori }}
                        </div>
                    </div>

                    <!-- Teknisi -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700"> Teknisi * </label>
                        <select v-model="form.id_user"
                            class="w-full rounded border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500"
                            :disabled="userRole !== 'admin'" required>
                            <option value="">Pilih Teknisi</option>
                            <option v-for="user in userList" :key="user.id" :value="user.id">
                                {{ user.nama }}
                            </option>
                        </select>
                        <div v-if="form.errors.id_user" class="mt-1 text-sm text-red-500">
                            {{ form.errors.id_user }}
                        </div>
                    </div>

                    <!-- Tanggal Uji -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700"> Tanggal Pengujian * </label>
                        <input type="date" v-model="form.tanggal_uji"
                            class="w-full rounded border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500"
                            :disabled="userRole !== 'admin'" required />
                        <div v-if="form.errors.tanggal_uji" class="mt-1 text-sm text-red-500">
                            {{ form.errors.tanggal_uji }}
                        </div>
                    </div>

                    <!-- Jam Mulai -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700"> Jam Mulai * </label>
                        <input type="time" v-model="form.jam_mulai"
                            class="w-full rounded border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500"
                            :disabled="userRole === 'admin'" required />
                        <div v-if="form.errors.jam_mulai" class="mt-1 text-sm text-red-500">
                            {{ form.errors.jam_mulai }}
                        </div>
                    </div>

                    <!-- Jam Selesai -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700"> Jam Selesai * </label>
                        <input type="time" v-model="form.jam_selesai"
                            class="w-full rounded border border-gray-300 p-2 focus:ring-2 focus:ring-blue-500"
                            :disabled="userRole === 'admin'" required />
                        <div v-if="form.errors.jam_selesai" class="mt-1 text-sm text-red-500">
                            {{ form.errors.jam_selesai }}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-4">
                        <button type="submit" :disabled="form.processing"
                            class="rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700 disabled:bg-blue-300">
                            {{ form.processing ? 'Memproses...' : 'Update Pengujian' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
