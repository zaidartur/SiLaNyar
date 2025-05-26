<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Head } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue'
import moment from 'moment'

interface User {
    id: number;
    nik: string;
    nama: string;
    tgl_lahir: string;
    provinsi: string;
    kab_kota: string;
    kecamatan: string;
    kelurahan: string;
    rt: string;
    rw: string;
    kode_pos: string;
    alamat: string;
    email: string;
    no_wa: string;
    username: string;
}

const props = defineProps<{
    user?: User;
}>();

const showEditModal = ref(false);

// Add function to toggle modal
const toggleEditModal = () => {
    showEditModal.value = !showEditModal.value;
}
</script>

<template>
    <Head title="Profile" />
    <AdminLayout>
        <div class="max-w-4xl mx-auto">
            <!-- Header Profile -->
            <div class="mb-4 rounded-lg border border-gray-300 bg-white p-2 shadow-sm">
                <h1 class="text-xl font-bold text-gray-800">Profile Pengguna</h1>
                <p class="text-sm text-gray-500">
                    Terakhir Login: {{ moment(props.user.last_login).format('DD MMMM YYYY, HH:mm') }}</p>
            </div>

            <!-- Profile Card -->
            <div class="mb-4 rounded-lg border border-gray-300 bg-white p-2 shadow-sm">
                <!-- Avatar Section -->
                <div class="flex flex-col items-center border-gray-100 p-8">
                    <div class="mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-customDarkGreen">
                        <span class="text-3xl font-bold text-white">
                            {{ props.user?.nama.charAt(0).toUpperCase() || 'U' }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">{{ props.user?.nama }}</h2>
                    <span
                        class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-sm bg-green-100 text-green-800">
                        Pengguna Aktif
                    </span>
                </div>

                <!-- Profile Details -->
                <div class="p-6">
                    <div class="grid gap-6">
                        <!-- Personal Information -->
                        <div class="space-y-4 rounded-lg border border-gray-300 bg-white p-4 shadow-sm">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Nama Lengkap</p>
                                    <p class="font-medium">{{ props.user?.nama }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium">{{ props.user?.email }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Kontak Pribadi</p>
                                    <p class="font-medium">{{ props.user?.no_wa }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Alamat Pribadi</p>
                                    <p class="font-medium">{{ props.user?.alamat }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 p-6 border-gray-100">
                    <button @click="toggleEditModal" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Edit Profile
                    </button>
                </div>
                <Dialog :open="showEditModal" @update:open="showEditModal = false">
                    <DialogContent
                        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-gradient-to-br from-lime-500 to-green-900 rounded-lg shadow-xl">
                        <DialogHeader>
                            <DialogTitle class="text-center text-2xl font-bold text-gray-300">Edit Profile
                            </DialogTitle>
                            <button @click="toggleEditModal"
                                class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                            </button>
                        </DialogHeader>

                        <div class="flex flex-col items-center space-y-6 p-4">
                            <div class="text-center">
                                <p class="text-xl font-bold text-gray-300 mb-2">
                                    Anda akan di arahkan ke portal
                                    <br />SAKTI Karanganyar
                                </p>
                                <p class="text-sm font-semibold text-gray-300 mb-2">
                                    Fitur edit profil tersedia melalui portal SAKTI Karanganyar.
                                </p>
                                <p class="text-sm italic text-gray-300">
                                    Note:Klik "lanjutkan" untuk melanjutkan ke portal SAKTI Karanganyar.
                                </p>
                            </div>

                            <div class="flex gap-4">
                                <a href="https://sakti.karanganyarkab.go.id/profile"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Lanjutkan
                                </a>
                            </div>
                        </div>
                    </DialogContent>
                </Dialog>
            </div>
        </div>
    </AdminLayout>
</template>
<style scoped>
.profile {
    max-width: 600px;
    margin: 0 auto;
}
</style>
