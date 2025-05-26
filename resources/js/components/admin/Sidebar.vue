<script setup lang="ts">
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const isSidebarOpen = ref(true)
const isDaftarOpen = ref(false)
const isKategoriOpen = ref(false)

const page = usePage()
const permissions = page.props.auth.permissions as string[]

const can = (permission: string): boolean => {
    return permissions.includes(permission)
}

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}

const toggleDaftar = () => {
    isDaftarOpen.value = !isDaftarOpen.value
}

const toggleKategori = () => {
    isKategoriOpen.value = !isKategoriOpen.value
}

</script>

<template>
    <button @click="toggleSidebar" class="lg:hidden fixed top-4 left-4 z-30 p-2 bg-green-800 rounded-md">
        <span alt="Menu" class="w-6 h-6">≡</span>
    </button>

    <aside :class="['fixed lg:static z-10 bg-green-800 text-white min-h-screen transition-all duration-300 ease-in-out',
        isSidebarOpen ? 'w-64 left-0' : '-left-64 lg:w-64']">
        <div class="flex items-center px-4 font-bold text-xl mt-4 mb-6">
            <img src="/assets/assetsadmin/logodlh.png" alt="Logo" class="w-12 h-12" />
            <span class="ml-3">SiLaNyar</span>
        </div>

        <nav class="space-y-1 font-bold text-xl">
            <a href="/pegawai/dashboard" class="flex items-center gap-3 py-3 px-3 rounded hover:bg-green-700">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0_1549_885" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="40"
                        height="40">
                        <rect y="0.326172" width="40" height="39.3477" fill="#D9D9D9" />
                    </mask>
                    <g mask="url(#mask0_1549_885)">
                        <path
                            d="M6.6665 34.7554V15.0816L19.9998 5.24463L33.3332 15.0816V34.7554H23.3332V23.279H16.6665V34.7554H6.6665Z"
                            fill="white" />
                    </g>
                </svg>
                <span>Beranda</span>
            </a>

            <!-- Menu Permission -->
            <a v-if="can('kelola permission')" href="/superadmin/permission"
                class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0_1621_1859" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="40"
                        height="40">
                        <rect y="0.326134" width="40" height="39.3477" fill="#D9D9D9" />
                    </mask>
                    <g mask="url(#mask0_1621_1859)">
                        <path
                            d="M28.3333 36.3949C26.0278 36.3949 24.0625 35.5956 22.4375 33.9971C20.8125 32.3986 20 30.4654 20 28.1974C20 25.9295 20.8125 23.9963 22.4375 22.3978C24.0625 20.7992 26.0278 20 28.3333 20C30.6389 20 32.6042 20.7992 34.2292 22.3978C35.8542 23.9963 36.6667 25.9295 36.6667 28.1974C36.6667 30.4654 35.8542 32.3986 34.2292 33.9971C32.6042 35.5956 30.6389 36.3949 28.3333 36.3949ZM20 36.3949C16.1389 35.4385 12.9514 33.2594 10.4375 29.8574C7.92361 26.4555 6.66666 22.6778 6.66666 18.5245V8.52358L20 3.60511L33.3333 8.52358V17.8277C32.6111 17.4725 31.7986 17.1992 30.8958 17.0079C29.9931 16.8167 29.1389 16.721 28.3333 16.721C25.1111 16.721 22.3611 17.8413 20.0833 20.082C17.8056 22.3226 16.6667 25.0278 16.6667 28.1974C16.6667 29.8916 16.9931 31.4218 17.6458 32.788C18.2986 34.1543 19.125 35.3429 20.125 36.3539C20.0972 36.3539 20.0764 36.3607 20.0625 36.3744C20.0486 36.3881 20.0278 36.3949 20 36.3949ZM28.3333 28.1974C29.0278 28.1974 29.6181 27.9584 30.1042 27.4802C30.5903 27.002 30.8333 26.4213 30.8333 25.7382C30.8333 25.0551 30.5903 24.4744 30.1042 23.9963C29.6181 23.5181 29.0278 23.279 28.3333 23.279C27.6389 23.279 27.0486 23.5181 26.5625 23.9963C26.0764 24.4744 25.8333 25.0551 25.8333 25.7382C25.8333 26.4213 26.0764 27.002 26.5625 27.4802C27.0486 27.9584 27.6389 28.1974 28.3333 28.1974ZM28.3333 33.1159C29.1944 33.1159 29.9861 32.9178 30.7083 32.5216C31.4306 32.1254 32.0139 31.5994 32.4583 30.9436C31.8472 30.5884 31.1944 30.3151 30.5 30.1238C29.8056 29.9326 29.0833 29.8369 28.3333 29.8369C27.5833 29.8369 26.8611 29.9326 26.1667 30.1238C25.4722 30.3151 24.8194 30.5884 24.2083 30.9436C24.6528 31.5994 25.2361 32.1254 25.9583 32.5216C26.6806 32.9178 27.4722 33.1159 28.3333 33.1159Z"
                            fill="white" />
                    </g>
                </svg>
                <span>Permission</span>
            </a>

            <!-- Menu Role -->
            <a v-if="can('kelola role')" href="/superadmin/role"
                class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0_1549_918" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="40"
                        height="40">
                        <rect y="0.326172" width="40" height="39.3477" fill="#D9D9D9" />
                    </mask>
                    <g mask="url(#mask0_1549_918)">
                        <path
                            d="M20 21.6395C21.6111 21.6395 22.9861 21.0794 24.125 19.959C25.2639 18.8387 25.8333 17.4861 25.8333 15.9013C25.8333 14.3165 25.2639 12.9639 24.125 11.8436C22.9861 10.7233 21.6111 10.1631 20 10.1631C18.3889 10.1631 17.0139 10.7233 15.875 11.8436C14.7361 12.9639 14.1667 14.3165 14.1667 15.9013C14.1667 17.4861 14.7361 18.8387 15.875 19.959C17.0139 21.0794 18.3889 21.6395 20 21.6395ZM8.33333 34.7554C7.41667 34.7554 6.63194 34.4344 5.97917 33.7922C5.32639 33.1501 5 32.3782 5 31.4764V8.52361C5 7.62189 5.32639 6.84996 5.97917 6.20783C6.63194 5.5657 7.41667 5.24463 8.33333 5.24463H31.6667C32.5833 5.24463 33.3681 5.5657 34.0208 6.20783C34.6736 6.84996 35 7.62189 35 8.52361V31.4764C35 32.3782 34.6736 33.1501 34.0208 33.7922C33.3681 34.4344 32.5833 34.7554 31.6667 34.7554H8.33333ZM8.33333 31.4764H31.6667V29.591C30.1667 28.1428 28.4236 27.002 26.4375 26.1686C24.4514 25.3352 22.3056 24.9185 20 24.9185C17.6944 24.9185 15.5486 25.3352 13.5625 26.1686C11.5764 27.002 9.83333 28.1428 8.33333 29.591V31.4764Z"
                            fill="white" />
                    </g>
                </svg>
                <span>Role</span>
            </a>

            <!-- Menu User -->
            <a v-if="can('kelola user')" href="/superadmin/users"
                class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0_1549_918" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="40"
                        height="40">
                        <rect y="0.326172" width="40" height="39.3477" fill="#D9D9D9" />
                    </mask>
                    <g mask="url(#mask0_1549_918)">
                        <path
                            d="M20 21.6395C21.6111 21.6395 22.9861 21.0794 24.125 19.959C25.2639 18.8387 25.8333 17.4861 25.8333 15.9013C25.8333 14.3165 25.2639 12.9639 24.125 11.8436C22.9861 10.7233 21.6111 10.1631 20 10.1631C18.3889 10.1631 17.0139 10.7233 15.875 11.8436C14.7361 12.9639 14.1667 14.3165 14.1667 15.9013C14.1667 17.4861 14.7361 18.8387 15.875 19.959C17.0139 21.0794 18.3889 21.6395 20 21.6395ZM8.33333 34.7554C7.41667 34.7554 6.63194 34.4344 5.97917 33.7922C5.32639 33.1501 5 32.3782 5 31.4764V8.52361C5 7.62189 5.32639 6.84996 5.97917 6.20783C6.63194 5.5657 7.41667 5.24463 8.33333 5.24463H31.6667C32.5833 5.24463 33.3681 5.5657 34.0208 6.20783C34.6736 6.84996 35 7.62189 35 8.52361V31.4764C35 32.3782 34.6736 33.1501 34.0208 33.7922C33.3681 34.4344 32.5833 34.7554 31.6667 34.7554H8.33333ZM8.33333 31.4764H31.6667V29.591C30.1667 28.1428 28.4236 27.002 26.4375 26.1686C24.4514 25.3352 22.3056 24.9185 20 24.9185C17.6944 24.9185 15.5486 25.3352 13.5625 26.1686C11.5764 27.002 9.83333 28.1428 8.33333 29.591V31.4764Z"
                            fill="white" />
                    </g>
                </svg>
                <span>User</span>
            </a>

            <!-- Menu Daftar -->
            <div class="space-y-1" v-if="can('lihat pengambilan') || can('lihat pengajuan') || can('lihat pengujian')">
                <button @click="toggle('daftar')"
                    class="w-full flex items-center justify-between gap-3 py-3 px-3 hover:bg-green-700 rounded">
                    <div class="flex items-center gap-3">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <mask id="mask0_1549_891" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                                width="40" height="40">
                                <rect y="0.326172" width="40" height="39.3477" fill="#D9D9D9" />
                            </mask>
                            <g mask="url(#mask0_1549_891)">
                                <path
                                    d="M20.0002 36.3948C17.6946 36.3948 15.7293 35.5955 14.1043 33.997C12.4793 32.3985 11.6668 30.4653 11.6668 28.1973V13.4419C10.7502 13.4419 9.96544 13.1208 9.31266 12.4787C8.65989 11.8366 8.3335 11.0647 8.3335 10.1629V6.88396C8.3335 5.98224 8.65989 5.21031 9.31266 4.56818C9.96544 3.92605 10.7502 3.60498 11.6668 3.60498H28.3335C29.2502 3.60498 30.0349 3.92605 30.6877 4.56818C31.3404 5.21031 31.6668 5.98224 31.6668 6.88396V10.1629C31.6668 11.0647 31.3404 11.8366 30.6877 12.4787C30.0349 13.1208 29.2502 13.4419 28.3335 13.4419V28.1973C28.3335 30.4653 27.521 32.3985 25.896 33.997C24.271 35.5955 22.3057 36.3948 20.0002 36.3948ZM20.0002 33.1158C21.3891 33.1158 22.5696 32.6376 23.5418 31.6812C24.5141 30.7249 25.0002 29.5636 25.0002 28.1973H20.0002V24.9183H25.0002V21.6394H20.0002V18.3604H25.0002V13.4419H15.0002V28.1973C15.0002 29.5636 15.4863 30.7249 16.4585 31.6812C17.4307 32.6376 18.6113 33.1158 20.0002 33.1158Z"
                                    fill="white" />
                            </g>
                        </svg>
                        <span>Daftar</span>
                    </div>
                    <svg class="w-3 fill-white inline ml-3" viewBox="0 0 24 24">
                        <path d="M7 10l5 5 5-5H7z" />
                    </svg>
                </button>

                <Transition name="slide">
                    <div v-if="toggles.daftar" class="pl-8 space-y-1">
                        <Link v-if="can('lihat pengambilan')" :href="route('pegawai.pengambilan.index')"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                        <span>Pengambilan</span>
                        </Link>
                        <Link v-if="can('lihat pengajuan')" :href="route('pegawai.pengajuan.index')"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                        <span>Pengajuan</span>
                        </Link>
                        <Link v-if="can('lihat pengujian')" :href="route('pegawai.pengujian.index')"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                        <span>Pengujian</span>
                        </Link>
                    </div>
                </Transition>
            </div>

            <!-- Menu Kategori -->
            <div class="space-y-1"
                v-if="can('kelola parameter') || can('kelola kategori') || can('kelola jenis cairan')">
                <button @click="toggle('kategori')"
                    class="w-full flex items-center justify-between gap-3 py-3 px-3 hover:bg-green-700 rounded">
                    <div class="flex items-center gap-3">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <mask id="mask0_1549_900" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                                width="40" height="40">
                                <rect y="0.326172" width="40" height="39.3477" fill="#D9D9D9" />
                            </mask>
                            <g mask="url(#mask0_1549_900)">
                                <path
                                    d="M8.3335 34.7554V8.52361C8.3335 7.62189 8.65989 6.84996 9.31266 6.20783C9.96544 5.5657 10.7502 5.24463 11.6668 5.24463H28.3335C29.2502 5.24463 30.0349 5.5657 30.6877 6.20783C31.3404 6.84996 31.6668 7.62189 31.6668 8.52361V34.7554L20.0002 29.837L8.3335 34.7554Z"
                                    fill="white" />
                            </g>
                        </svg>
                        <span>Kategori</span>
                    </div>
                    <svg class="w-3 fill-white inline ml-3" viewBox="0 0 24 24">
                        <path d="M7 10l5 5 5-5H7z" />
                    </svg>
                </button>

                <Transition name="slide">
                    <div v-if="toggles.kategori" class="pl-8 space-y-1">

                        <a v-if="can('kelola parameter')" href="/pegawai/parameter"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                            <span>Parameter</span>
                            </Link>
                            <Link v-if="can('kelola kategori')" href="/pegawai/kategori"
                                class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                            <span>Detail Kategori</span>
                        </a>
                        <Link v-if="can('kelola jenis cairan')" href="/pegawai/jenis_cairan"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                        <span>Jenis Cairan</span>
                        </Link>
                    </div>
                </Transition>
            </div>

            <a v-if="can('kelola instansi')" href="/pegawai/instansi"
                class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0_1549_908" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="40"
                        height="40">
                        <rect y="0.326172" width="40" height="39.3477" fill="#D9D9D9" />
                    </mask>
                    <g mask="url(#mask0_1549_908)">
                        <path
                            d="M8.33333 34.7554C7.41667 34.7554 6.63194 34.4344 5.97917 33.7922C5.32639 33.1501 5 32.3782 5 31.4764V8.52361C5 7.62189 5.32639 6.84996 5.97917 6.20783C6.63194 5.5657 7.41667 5.24463 8.33333 5.24463H31.6667C32.5833 5.24463 33.3681 5.5657 34.0208 6.20783C34.6736 6.84996 35 7.62189 35 8.52361V31.4764C35 32.3782 34.6736 33.1501 34.0208 33.7922C33.3681 34.4344 32.5833 34.7554 31.6667 34.7554H8.33333ZM11.6667 28.1975H23.3333V24.9185H11.6667V28.1975ZM11.6667 21.6395H28.3333V18.3605H11.6667V21.6395ZM11.6667 15.0816H28.3333V11.8026H11.6667V15.0816Z"
                            fill="white" />
                    </g>
                </svg>
                <span>Daftar Pelanggan</span>
            </a>

            <a v-if="can('lihat hasil uji')" href="/pegawai/hasiluji"
                class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0_1549_913" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="40"
                        height="40">
                        <rect y="0.326172" width="40" height="39.3477" fill="#D9D9D9" />
                    </mask>
                    <g mask="url(#mask0_1549_913)">
                        <path
                            d="M8.33348 34.7554C6.91681 34.7554 5.90987 34.1338 5.31264 32.8905C4.71542 31.6472 4.86126 30.4928 5.75014 29.4271L15.0001 18.3605V8.52361H13.3335C12.8613 8.52361 12.4654 8.36649 12.146 8.05225C11.8265 7.73802 11.6668 7.34864 11.6668 6.88412C11.6668 6.4196 11.8265 6.03022 12.146 5.71598C12.4654 5.40175 12.8613 5.24463 13.3335 5.24463H26.6668C27.139 5.24463 27.5349 5.40175 27.8543 5.71598C28.1738 6.03022 28.3335 6.4196 28.3335 6.88412C28.3335 7.34864 28.1738 7.73802 27.8543 8.05225C27.5349 8.36649 27.139 8.52361 26.6668 8.52361H25.0001V18.3605L34.2501 29.4271C35.139 30.4928 35.2849 31.6472 34.6876 32.8905C34.0904 34.1338 33.0835 34.7554 31.6668 34.7554H8.33348ZM11.6668 29.837H28.3335L22.6668 23.279H17.3335L11.6668 29.837Z"
                            fill="white" />
                    </g>
                </svg>
                <span>Hasil Uji</span>
            </a>
            <!-- menu profile -->
            <a href="/pegawai/profile/show" class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <mask id="mask0_1549_918" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="40"
                        height="40">
                        <rect y="0.326172" width="40" height="39.3477" fill="#D9D9D9" />
                    </mask>
                    <g mask="url(#mask0_1549_918)">
                        <path
                            d="M20 21.6395C21.6111 21.6395 22.9861 21.0794 24.125 19.959C25.2639 18.8387 25.8333 17.4861 25.8333 15.9013C25.8333 14.3165 25.2639 12.9639 24.125 11.8436C22.9861 10.7233 21.6111 10.1631 20 10.1631C18.3889 10.1631 17.0139 10.7233 15.875 11.8436C14.7361 12.9639 14.1667 14.3165 14.1667 15.9013C14.1667 17.4861 14.7361 18.8387 15.875 19.959C17.0139 21.0794 18.3889 21.6395 20 21.6395ZM8.33333 34.7554C7.41667 34.7554 6.63194 34.4344 5.97917 33.7922C5.32639 33.1501 5 32.3782 5 31.4764V8.52361C5 7.62189 5.32639 6.84996 5.97917 6.20783C6.63194 5.5657 7.41667 5.24463 8.33333 5.24463H31.6667C32.5833 5.24463 33.3681 5.5657 34.0208 6.20783C34.6736 6.84996 35 7.62189 35 8.52361V31.4764C35 32.3782 34.6736 33.1501 34.0208 33.7922C33.3681 34.4344 32.5833 34.7554 31.6667 34.7554H8.33333ZM8.33333 31.4764H31.6667V29.591C30.1667 28.1428 28.4236 27.002 26.4375 26.1686C24.4514 25.3352 22.3056 24.9185 20 24.9185C17.6944 24.9185 15.5486 25.3352 13.5625 26.1686C11.5764 27.002 9.83333 28.1428 8.33333 29.591V31.4764Z"
                            fill="white" />
                    </g>
                </svg>
                <span>Profile</span>
            </a>

            <!-- Logout untuk semua role -->
            <Link :href="route('sso.logout')" class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="mask0_1549_923" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="40"
                    height="40">
                    <rect y="0.326172" width="40" height="39.3477" fill="#D9D9D9" />
                </mask>
                <g mask="url(#mask0_1549_923)">
                    <path
                        d="M33.5833 21.6395H13.3333V18.3605H33.5833L31 15.8193L33.3333 13.4421L40 20L33.3333 26.558L31 24.1807L33.5833 21.6395ZM25 15.0816V8.52361H8.33333V31.4764H25V24.9185H28.3333V31.4764C28.3333 32.3782 28.0069 33.1501 27.3542 33.7922C26.7014 34.4344 25.9167 34.7554 25 34.7554H8.33333C7.41667 34.7554 6.63194 34.4344 5.97917 33.7922C5.32639 33.1501 5 32.3782 5 31.4764V8.52361C5 7.62189 5.32639 6.84996 5.97917 6.20783C6.63194 5.5657 7.41667 5.24463 8.33333 5.24463H25C25.9167 5.24463 26.7014 5.5657 27.3542 6.20783C28.0069 6.84996 28.3333 7.62189 28.3333 8.52361V15.0816H25Z"
                        fill="white" />
                </g>
            </svg>
            <span>LogOut</span>
            </Link>
        </nav>
    </aside>
</template>

<style>
.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
    max-height: 0;
    opacity: 0;
}

.slide-enter-to,
.slide-leave-from {
    max-height: 500px;
    opacity: 1;
}
</style>