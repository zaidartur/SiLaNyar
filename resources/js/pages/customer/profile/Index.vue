<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head } from '@inertiajs/vue3';
import moment from 'moment';
import { defineProps, ref } from 'vue';
import TambahInstansi from './TambahInstansi.vue';

defineProps({
    user: {
        type: Object,
        required: true,
    },
    lastLoginTime: {
        type: String,
        required: true,
    },
    instansi: {
        type: Array,
        default: () => [],
    },
});

const formatLastLogin = (date) => {
    if (!date) return '-';
    moment.locale('id');
    return moment(date).format('DD MMMM YYYY, HH:mm [WIB]');
};

const showModal = ref(false);

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const editInstansi = (id: number) => {
    console.log('Editing instansi with id:', id);
    // TODO: Implement edit functionality
};

const deleteInstansi = (id: number) => {
    console.log('Deleting instansi with id:', id);
    // TODO: Implement delete functionality
};
</script>

<template>
    <Head title="Profile" />
    <CustomerLayout>
        <div class="mx-auto max-w-4xl">
            <!-- Header Profile -->
            <div class="mb-4 rounded-lg border border-gray-300 bg-white p-2 shadow-sm">
                <h1 class="text-xl font-bold text-gray-800">Profile Pengguna</h1>
                <p class="text-sm text-gray-500">Terakhir Login: {{ formatLastLogin(lastLoginTime) }}</p>
            </div>

            <!-- Profile Card -->
            <div class="mb-4 rounded-lg border border-gray-300 bg-white p-2 shadow-sm">
                <!-- Avatar Section -->
                <div class="flex flex-col items-center border-gray-100 p-8">
                    <div class="mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-customDarkGreen">
                        <span class="text-3xl font-bold text-white">
                            {{ user?.nama?.charAt(0).toUpperCase() || 'U' }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">{{ user?.nama }}</h2>
                    <span class="mt-2 inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm text-green-800"> Pengguna Aktif </span>
                </div>

                <!-- Profile Details -->
                <div class="p-6">
                    <div class="grid gap-6">
                        <!-- Personal Information -->
                        <div class="space-y-4 rounded-lg border border-gray-300 bg-white p-4 shadow-sm">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Nama Lengkap</p>
                                    <p class="font-medium">{{ user?.nama }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium">{{ user?.email }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Kontak Pribadi</p>
                                    <p class="font-medium">{{ user?.no_wa }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Alamat Pribadi</p>
                                    <p class="font-medium">{{ user?.alamat }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Related Institutions -->
                        <div>
                            <h3 class="mb-4 text-lg font-semibold">Instansi Terkait</h3>
                            <div class="grid gap-3">
                                <!-- List Instansi -->
                                <div v-if="instansi.length > 0">
                                    <div v-for="item in instansi" :key="item.id" class="rounded-lg border border-gray-200 bg-white shadow-sm">
                                        <div class="flex items-center justify-between p-3">
                                            <div>
                                                <p class="font-medium">{{ item.nama }}</p>
                                                <p class="text-sm text-gray-500">{{ item.tipe }}</p>
                                                <div class="mt-1">
                                                    <span
                                                        :class="{
                                                            'rounded-full px-2 py-1 text-xs': true,
                                                            'bg-yellow-100 text-yellow-800': item.status_verifikasi === 'pending',
                                                            'bg-green-100 text-green-800': item.status_verifikasi === 'terverifikasi',
                                                            'bg-red-100 text-red-800': item.status_verifikasi === 'ditolak',
                                                        }"
                                                    >
                                                        {{ item.status_verifikasi }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <button class="p-1 text-gray-400 hover:text-gray-600" @click="editInstansi(item.id)">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                        />
                                                    </svg>
                                                </button>
                                                <button class="p-1 text-red-400 hover:text-red-600" @click="deleteInstansi(item.id)">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12"
                                                        />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Empty State -->
                                <div v-else class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                                    <p class="text-gray-500">Belum ada instansi yang terdaftar</p>
                                </div>

                                <!-- Tombol Tambah Instansi -->
                                <button
                                    @click="openModal"
                                    class="flex items-center justify-center gap-2 rounded-lg border-2 border-dashed border-gray-300 p-3 hover:border-customDarkGreen hover:text-customDarkGreen"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span>Tambah Instansi</span>
                                </button>

                                <TambahInstansi v-if="showModal" @close="closeModal" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 border-gray-100 p-6">
                    <button class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700">Edit Profile</button>
                    <button class="rounded-lg bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">Ubah Sandi</button>
                    <button class="rounded-lg bg-red-500 px-4 py-2 text-white hover:bg-red-600">Hapus Akun</button>
                </div>
            </div>
        </div>

        <!-- <div class="profile p-4 border-2 border-gray-700 rounded-lg dark:border-gray-700">
      <h1>Profil Saya</h1>
      <ul>
        <li><strong>NIK:</strong> {{ user.nik }}</li>
        <li><strong>Nama:</strong> {{ user.nama }}</li>
        <li><strong>Tanggal Lahir:</strong> {{ user.tgl_lahir }}</li>
        <li><strong>Provinsi:</strong> {{ user.provinsi }}</li>
        <li><strong>Kab/Kota:</strong> {{ user.kab_kota }}</li>
        <li><strong>Kecamatan:</strong> {{ user.kecamatan }}</li>
        <li><strong>Kelurahan:</strong> {{ user.kelurahan }}</li>
        <li><strong>RT:</strong> {{ user.rt }}</li>
        <li><strong>RW:</strong> {{ user.rw }}</li>
        <li><strong>Kode Pos:</strong> {{ user.kode_pos }}</li>
        <li><strong>Alamat:</strong> {{ user.alamat }}</li>
        <li><strong>Email:</strong> {{ user.email }}</li>
        <li><strong>No WA:</strong> {{ user.no_wa }}</li>
        <li><strong>Username:</strong> {{ user.username }}</li>
        <li><strong>ID:</strong> {{ user.id }}</li>
      </ul>
    </div> -->
    </CustomerLayout>
</template>

<style scoped>
.profile {
    max-width: 600px;
    margin: 0 auto;
}
</style>
