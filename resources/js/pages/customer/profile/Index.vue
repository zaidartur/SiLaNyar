<script setup lang="ts">
import EditInstansi from '@/components/form/customer/profile/EditInstansi.vue';
import TambahInstansi from '@/components/form/customer/profile/TambahInstansi.vue';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
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
    instansi: Instansi;
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

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

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
    // opsional: reload data instansi
}

// const showDeleteInstansiModal = ref(false);
// const deletingInstansi = ref<any | null>(null);

// function openDeleteInstansiModal(item) {
//     deletingInstansi.value = item;
//     showDeleteInstansiModal.value = true;
// }
// function closeDeleteInstansiModal() {
//     showDeleteInstansiModal.value = false;
//     deletingInstansi.value = null;
// }
// function handleDeleteInstansi() {
//     if (!deletingInstansi.value) return;
//     router.delete(`/customer/profile/instansi/${deletingInstansi.value.id}`, {
//         onSuccess: () => {
//             closeDeleteInstansiModal();
//         },
//     });
// }

const showEditProfileModal = ref(false);

const toggleEditProfileModal = () => {
    showEditProfileModal.value = !showEditProfileModal.value;
};
</script>

<template>
    <Head title="Profile" />

    <CustomerLayout>
        <div class="mx-auto max-w-4xl">
            <!-- Header Profile -->
            <div class="mb-4 rounded-lg border border-gray-300 bg-white p-2 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Profile Pengguna</h1>
                        <p class="text-sm text-gray-500">Terakhir Login: {{ moment(user?.last_login).format('DD MMMM YYYY, HH:mm') }}</p>
                    </div>
                </div>
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
                    <h2 class="text-xl font-bold text-gray-800">{{ props.user.nama }}</h2>
                    <span class="mt-2 inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm text-green-800"> Pengguna Aktif </span>
                </div>

                <!-- Profile Details -->
                <div class="p-6">
                    <div class="grid gap-6">
                        <!-- Personal Information -->
                        <div class="space-y-4 rounded-lg border border-gray-300 bg-gray-100 p-4 shadow-sm">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Nama Lengkap</p>
                                    <p class="font-medium dark:text-black">{{ user?.nama }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium dark:text-black">{{ user?.email }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Kontak Pribadi</p>
                                    <p class="font-medium dark:text-black">{{ user?.no_wa }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Alamat Pribadi</p>
                                    <p class="font-medium dark:text-black">{{ user?.alamat }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Related Institutions -->
                        <div>
                            <h3 class="mb-4 text-lg font-semibold dark:text-black">Instansi Terkait</h3>
                            <div class="grid gap-3">
                                <div v-if="props.instansi.length > 0">
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div
                                            v-for="item in props.instansi"
                                            :key="item.id"
                                            class="flex flex-col justify-between rounded-lg border border-l-8 border-l-customDarkGreen bg-gray-100 shadow-md"
                                        >
                                            <div class="flex items-center justify-between p-3">
                                                <div>
                                                    <p class="font-bold">{{ item.nama }}</p>
                                                    <p class="text-sm font-bold text-gray-500">{{ item.tipe }}</p>
                                                </div>
                                                <div class="flex gap-2">
                                                    <button class="p-1 text-gray-400 hover:text-gray-600" @click="editInstansi(item.id)">
                                                        <svg
                                                            width="20"
                                                            height="23"
                                                            viewBox="0 0 20 23"
                                                            fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        >
                                                            <rect width="20" height="23" fill="url(#pattern0_1273_906)" />
                                                            <defs>
                                                                <pattern
                                                                    id="pattern0_1273_906"
                                                                    patternContentUnits="objectBoundingBox"
                                                                    width="1"
                                                                    height="1"
                                                                >
                                                                    <use
                                                                        xlink:href="#image0_1273_906"
                                                                        transform="matrix(0.01 0 0 0.00869565 0 0.0652174)"
                                                                    />
                                                                </pattern>
                                                                <image
                                                                    id="image0_1273_906"
                                                                    width="100"
                                                                    height="100"
                                                                    preserveAspectRatio="none"
                                                                    xlink:href="data:image/png;base64,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"
                                                                />
                                                            </defs>
                                                        </svg>
                                                    </button>
                                                    <!-- <button class="p-1 text-red-400 hover:text-red-600" @click="openDeleteInstansiModal(item)">
                                                        <svg
                                                            width="25"
                                                            height="25"
                                                            viewBox="0 0 25 25"
                                                            fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        >
                                                            <rect width="25" height="25" fill="url(#pattern0_1272_903)" />
                                                            <defs>
                                                                <pattern
                                                                    id="pattern0_1272_903"
                                                                    patternContentUnits="objectBoundingBox"
                                                                    width="1"
                                                                    height="1"
                                                                >
                                                                    <use xlink:href="#image0_1272_903" transform="scale(0.01)" />
                                                                </pattern>
                                                                <image
                                                                    id="image0_1272_903"
                                                                    width="100"
                                                                    height="100"
                                                                    preserveAspectRatio="none"
                                                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAD0UlEQVR4nO3dz28MYRzH8W/8Cg64uEi4+ZFe/CgNOs/OoE5OrgRR1EF2Ni7O/gERJyG4II4uleDm4m/g6iRUG3QlttvnI51mpWXV7DPP7nx3n887+d6a6ez31dl2Zw4VYYwxxhhjjDHGGOsojMp5GLlXaEblPNfuIURyBUYsjKDgWERSJYoODBBFz5WB4FFw7cQupMkFpMcmnObMnsc4vc3i9DZ0aSzO7n7mfH7Za6vsVP/2h4nhtajGD5AmFmmCAR+LanJ/4TWL1pAmtxUsCr2d+JZoDLV4C6rxz/IXlPR2Fl7zxNhm0RZqlSOlLyctDeWwaAu1OCl9MWlJU4sT0RZBlEUQZSGNI6TJ9LKpJjPtp1LHtQiqp1qp//P8/3ydaRyJ9hDJ1i58yoaKiWSr9FsEURZBlEUQZRFEWQRRFkGURRBlEURZBFEWQZRFEGURRFkEURZBlEUQZRFkUEFObvT3YMnXsUJ9QGWvn0Tj4wfYK4cKL9GO78+ONX/jFEGcMWa/odFooDHzuRBKhjH1cfFY9dniKKFdIXYpRmscUZZhtKYoSkggth2GI0pbDB8ooYDYlTA6RFkRoyhKCCD24t7/Y7Rm+hPs5eF/H+vywexrch1r9lv2vQny5yLjVWhOPsy3xBWulFxXxpJpvnoCxKsJ0g0U2wuMUN6yiqLYXmEEB2IcUL5OozEz1RuMIEGMA0qvMIIFMf5RvGAEDWL8oXjDCB7EFEfxikEQKXSleMcgiPxehB0/gEb9e36QH3XYq4f9YhBEFjEu7uvoc8ayzykTIwTx+QjXumJ0CyXkv7JsUYxuoIQKYn1h+EYJEcT6xvCJEhqI7RCj+fopmpOPeocSEojtFKP1OcPlLrErSigg1hWjdYxeoYQAYs8N5X/suoAx+SgD+OtYGUoHb18Lj4PPDRHkr0UeX4e5N8/droy2KPmulLm3L4CxDQRxRWnmvTeVA8UJI5S3rDwozU5vFK6A4owRHIhpj+J817YNSoZxYr3buQUJYpajFL6FvgSlMEawIEayxc3fqbX/a8oBJTtWUYygQYzSIYiUj0AQKX/xBJHyl00QBQs2BCl/qYYg5S/SEKT85RmC8HOIxjAim0r/STZdmhHZJP0YIpkdwN8f36VfQySvBxDkpfRrMHKp9AUazzMq49KvYVjWwsj70pdovM07xLJG+jlUZP+A/C75gaNyUAYhGDkOI18ULBVOE8kUKnJMBimMyg5E8gSRNPsIogkjj2Fkuwxq2YOrxX/HfROR3C38b7mN51k8p5vZOfbjgyjGGGOMMcYYY4yJv34B7C6z42N0SaQAAAAASUVORK5CYII="
                                                                />
                                                            </defs>
                                                        </svg>
                                                    </button> -->
                                                </div>
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
                                        <path
                                            class="dark:text-customDarkGreen"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                        />
                                    </svg>
                                    <span class="dark:text-customDarkGreen">Tambah Instansi</span>
                                </button>

                                <TambahInstansi v-if="showModal" :kecamatan="props.kecamatan" :desa="props.desa" @close="closeModal" />
                                <EditInstansi v-model="showEditInstansiModal" :instansi="instansiEditData" :kecamatan="props.kecamatan" :desa="props.desa" @submit="afterEditInstansi" />
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 border-gray-100 p-6">
                            <button @click="toggleEditProfileModal" class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                Edit Profile
                            </button>
                        </div>

                        <!-- Modal Delete Instansi -->
                        <!-- <Dialog :open="showDeleteInstansiModal" @update:open="closeDeleteInstansiModal">
                            <DialogContent
                                class="fixed left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 transform rounded-lg bg-white p-6 shadow-xl"
                            >
                                <DialogHeader>
                                    <DialogTitle class="text-center text-xl font-bold text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg width="64" height="64" viewBox="0 0 94 94" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M53.4152 15.4982C52.7777 14.3582 51.8477 13.4088 50.721 12.748C49.5943 12.0871 48.3118 11.7388 47.0056 11.7388C45.6994 11.7388 44.4169 12.0871 43.2902 12.748C42.1635 13.4088 41.2335 14.3582 40.596 15.4982L12.6721 65.4474C12.0475 66.5647 11.7257 67.8257 11.7387 69.1057C11.7516 70.3856 12.0989 71.6399 12.7461 72.7442C13.3932 73.8485 14.3178 74.7645 15.4281 75.4014C16.5384 76.0383 17.7959 76.3739 19.0758 76.3749H74.9118C76.1923 76.3749 77.4505 76.04 78.5616 75.4036C79.6727 74.7671 80.5981 73.8512 81.246 72.7467C81.8938 71.6422 82.2416 70.3875 82.2549 69.1071C82.2681 67.8267 81.9463 66.5651 81.3215 65.4474L53.4152 15.4982ZM51.406 60.2187C51.406 61.3873 50.9417 62.5081 50.1154 63.3344C49.2891 64.1607 48.1683 64.6249 46.9997 64.6249C45.8311 64.6249 44.7104 64.1607 43.884 63.3344C43.0577 62.5081 42.5935 61.3873 42.5935 60.2187C42.5935 59.0501 43.0577 57.9293 43.884 57.103C44.7104 56.2767 45.8311 55.8124 46.9997 55.8124C48.1683 55.8124 49.2891 56.2767 50.1154 57.103C50.9417 57.9293 51.406 59.0501 51.406 60.2187ZM44.0622 46.9999V32.3124C44.0622 31.5334 44.3717 30.7862 44.9226 30.2353C45.4735 29.6844 46.2206 29.3749 46.9997 29.3749C47.7788 29.3749 48.526 29.6844 49.0768 30.2353C49.6277 30.7862 49.9372 31.5334 49.9372 32.3124V46.9999C49.9372 47.779 49.6277 48.5262 49.0768 49.0771C48.526 49.628 47.7788 49.9374 46.9997 49.9374C46.2206 49.9374 45.4735 49.628 44.9226 49.0771C44.3717 48.5262 44.0622 47.779 44.0622 46.9999Z"
                                                    fill="#E94235"
                                                />
                                            </svg>
                                            Peringatan !
                                        </div>
                                    </DialogTitle>
                                </DialogHeader>

                                <div class="text-center">
                                    <p class="font-bold text-gray-900">HAPUS INSTANSI</p>
                                    <p class="text-gray-600">
                                        Apakah Anda yakin ingin menghapus instansi
                                        <span class="font-bold">{{ deletingInstansi?.nama }}</span
                                        >?
                                    </p>
                                </div>

                                <div class="mt-6 flex justify-center gap-4">
                                    <button
                                        @click="closeDeleteInstansiModal"
                                        class="rounded-lg bg-gray-200 px-4 py-2 text-gray-800 hover:bg-gray-300"
                                    >
                                        Batal
                                    </button>
                                    <button @click="handleDeleteInstansi" class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700">
                                        Hapus
                                    </button>
                                </div>
                            </DialogContent>
                        </Dialog> -->

                        <!-- Modal Edit Profile -->
                        <Dialog :open="showEditProfileModal" @update:open="showEditProfileModal = false">
                            <DialogContent
                                class="fixed left-1/2 top-1/2 w-full max-w-md -translate-x-1/2 -translate-y-1/2 transform rounded-lg bg-gradient-to-br from-lime-500 to-green-900 shadow-xl"
                            >
                                <DialogHeader>
                                    <DialogTitle class="text-center text-2xl font-bold text-gray-300">Edit Profile </DialogTitle>
                                    <button @click="toggleEditProfileModal" class="absolute right-4 top-4 text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Close</span>
                                    </button>
                                </DialogHeader>

                                <div class="flex flex-col items-center space-y-6 p-4">
                                    <div class="text-center">
                                        <p class="mb-2 text-xl font-bold text-gray-300">
                                            Anda akan di arahkan ke portal
                                            <br />SAKTI Karanganyar
                                        </p>
                                        <p class="mb-2 text-sm font-semibold text-gray-300">
                                            Fitur edit profil tersedia melalui portal SAKTI Karanganyar.
                                        </p>
                                        <p class="text-sm italic text-gray-300">
                                            Note:Klik "lanjutkan" untuk melanjutkan ke portal SAKTI Karanganyar.
                                        </p>
                                    </div>

                                    <div class="flex gap-4">
                                        <a
                                            href="https://sakti.karanganyarkab.go.id/profile"
                                            class="rounded-lg bg-blue-600 px-6 py-2 text-white transition-colors hover:bg-blue-700"
                                        >
                                            Lanjutkan
                                        </a>
                                    </div>
                                </div>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>
