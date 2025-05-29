<script setup lang="ts">
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const isSidebarOpen = ref(true)
const toggles = ref({
    daftar: false,
    kategori: false
})

const page = usePage()
const permissions = page.props.auth.permissions as string[]

const can = (permission: string): boolean => {
    return permissions.includes(permission)
}

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
}

const toggle = (menu: 'daftar' | 'kategori') => {
    toggles.value[menu] = !toggles.value[menu]
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
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.25 20.5835H23.75V33.2502H14.25V20.5835Z" fill="url(#paint0_linear_1673_67)" />
                    <path
                        d="M21.2958 7.1773C20.6534 6.63583 19.8402 6.33887 19 6.33887C18.1598 6.33887 17.3466 6.63583 16.7042 7.1773L6.01667 16.1944C5.61998 16.5287 5.30109 16.9456 5.08231 17.416C4.86352 17.8864 4.75011 18.3989 4.75 18.9177V30.484C4.75 32.0135 5.99133 33.2548 7.52083 33.2548H15.0417V24.1459C15.0417 23.0692 15.8998 22.1952 16.9686 22.1667H21.0314C21.5472 22.1803 22.0373 22.3949 22.3972 22.7646C22.7572 23.1343 22.9585 23.6299 22.9583 24.1459V33.2548H30.4792C31.214 33.2548 31.9188 32.9629 32.4384 32.4432C32.9581 31.9236 33.25 31.2188 33.25 30.484V18.9177C33.2499 18.3989 33.1365 17.8864 32.9177 17.416C32.6989 16.9456 32.38 16.5287 31.9833 16.1944L21.2958 7.1773Z"
                        fill="url(#paint1_linear_1673_67)" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M20.2731 3.64016C19.9194 3.33462 19.4675 3.1665 19.0001 3.1665C18.5327 3.1665 18.0809 3.33462 17.7271 3.64016L3.81119 15.721C3.62351 15.8821 3.47009 16.0792 3.36003 16.3008C3.24996 16.5223 3.18548 16.7636 3.17042 17.0105C3.15536 17.2574 3.19002 17.5048 3.27235 17.738C3.35467 17.9713 3.48299 18.1856 3.64969 18.3683C3.99194 18.7419 4.46631 18.9675 4.97212 18.997C5.47792 19.0266 5.97534 18.8579 6.35877 18.5267L19.0001 7.55416L31.6414 18.5267C32.4331 19.2154 33.6459 19.1442 34.3489 18.3683C34.516 18.1857 34.6446 17.9713 34.7272 17.738C34.8098 17.5046 34.8447 17.2571 34.8298 17.01C34.8149 16.7629 34.7505 16.5214 34.6404 16.2997C34.5303 16.078 34.3768 15.8806 34.189 15.7194L20.2731 3.64016Z"
                        fill="url(#paint2_linear_1673_67)" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M17.727 3.64016C18.0808 3.33462 18.5326 3.1665 19 3.1665C19.4675 3.1665 19.9193 3.33462 20.273 3.64016L34.189 15.721C34.9806 16.4082 35.0534 17.5941 34.3504 18.3683C34.0082 18.7419 33.5338 18.9675 33.028 18.997C32.5222 19.0266 32.0248 18.8579 31.6414 18.5267L19 7.55416L6.3587 18.5251C5.97535 18.8558 5.47828 19.0242 4.97286 18.9947C4.46743 18.9651 3.99338 18.7399 3.6512 18.3667C3.48416 18.1841 3.35551 17.9697 3.27291 17.7364C3.19031 17.503 3.15543 17.2555 3.17035 17.0084C3.18527 16.7613 3.24967 16.5198 3.35974 16.2981C3.46982 16.0764 3.62331 15.8791 3.81112 15.7178L17.727 3.64016Z"
                        fill="url(#paint3_linear_1673_67)" />
                    <defs>
                        <linearGradient id="paint0_linear_1673_67" x1="19" y1="20.5835" x2="10.6194" y2="34.5564"
                            gradientUnits="userSpaceOnUse">
                            <stop stop-color="#944600" />
                            <stop offset="1" stop-color="#CD8E02" />
                        </linearGradient>
                        <linearGradient id="paint1_linear_1673_67" x1="7.47017" y1="5.02238" x2="34.1493" y2="27.9823"
                            gradientUnits="userSpaceOnUse">
                            <stop stop-color="#FFD394" />
                            <stop offset="1" stop-color="#FFB357" />
                        </linearGradient>
                        <linearGradient id="paint2_linear_1673_67" x1="13.8828" y1="-0.593677" x2="20.8399" y2="18.2163"
                            gradientUnits="userSpaceOnUse">
                            <stop stop-color="#FF921F" />
                            <stop offset="1" stop-color="#EB4824" />
                        </linearGradient>
                        <linearGradient id="paint3_linear_1673_67" x1="13.8827" y1="-0.593677" x2="20.8399" y2="18.2163"
                            gradientUnits="userSpaceOnUse">
                            <stop stop-color="#FF921F" />
                            <stop offset="1" stop-color="#EB4824" />
                        </linearGradient>
                    </defs>
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
                        <svg width="44" height="42" viewBox="0 0 44 42" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="44" height="42" fill="url(#pattern0_1524_3530)" />
                            <defs>
                                <pattern id="pattern0_1524_3530" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_1524_3530"
                                        transform="matrix(0.00954545 0 0 0.01 0.0227273 0)" />
                                </pattern>
                                <image id="image0_1524_3530" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFeUlEQVR4nO2dW0wcZRTHp/Lo5cUHE1NfTHxUY2K9PCiNOwNpjG20rDKz1KTMUE2bJm3qrUkFIY2mKTBDIfAAoSwzUOmyU5poWzUFtVItLdZ4LzVFkQJSSeUi7LbCZ77NomWZ2csM7JydPb/k/wKzs+f7fjs7EzjJYZgMRVGD6xRVr5NV/SdFC85E8yP9WbU/8KjT9WUNNeqJu2RVPyKrwQVF04lR6O9kVW870NR1p9P1uppav363rOnfmYlYFjX4LX2N03W7krKystsULdiTtIz/c5q+1un6XUeN1rll+VeTPq6o+ltym/5kJJq+V1GD15ZL6RScrt91yKr+9RIZWvBKjRpcG3tc5ZFj9ylqcDDmnnLBmapdSo0aXBv7qa9R9Tyz42VN3xB7k69t77o3vVW7GFk9tn7pzVqfiHdfiN5vri8R2KbnMtmK1+vN8fjE9awgVbC8eJgTpKNG2f2u/MM+pfH3RKloaPnz1s2t8gfCiWqoagmEb31NRYP/WjLvRWsyqze6lnKWL8mla2QyAQ9fspnlpcucIJFEeftQY6pPTCR6hZCy6vr7zWp4/UDDA5bOq+mRmpKpnRPEAc4nPc8AZg3LS9XJLcamEE0n+xvUS2aF7G9o/WX1hUTDS5V07Qw0OF58J6WF2BSiaDopr2vuia2jvN7/mZ1zpixEkAjLi6UMJDyFxY+wvDifbiFK5H7SOf9eY/sfNNWtgXm757MoZD6vaOvDDBRYQTqW6iJWSoiywrEiJCpFZyCQ691+B8dLc0ZF5hdtI2VVdURuajWM0hpwXIASm9aAab2llbWRNRkKEcTQxuJi5/+YyfqkJ8w+NZ9+eZ7Eo/OTz50XoC0NrSkePWf7TK+SPF58zGkfjEeQNhkV99zWHWRhYSGBkDMAhZyJWzNdE12b0ZrpXjjtg+EE0WtUXMGru0kiTn1x3nEBSkxO9V5IWDddm/FVInozWsiV4VHHBSgxGbw6mr1CKKfPXXRcghJNd983SdXsaiGUgV+HyfGes6T9RLcjoe898Ntw0vW6XkimUYBCYIFCgIFCgIFCgIFCgIFCgIFCgIFCgIFCgIFCgIFCgIFC0sTw5DAJ/xNOeBwKSQNDfw0Rb0cheePjvSSUQAoKsUHoZoiMz4wnJcPTkh9JIikoxIaM1z56k/CBLWRkasTwmMHrg2Tz+y/9J2MxTf3N2SOk/vINUv592HYO/nwjoYzFDTaSEntlLGbXyT1k9uZs9gh58OQsyemYsZ17uv42fY9DX9Ut22hf58tkdHrMlgwU0mFNyHR4mmz/YOeyDadXSv/VfssyUEiHNSHxpHD+DZZluFLInoth8mJvyHZK+kIJN89MilUZrhSSbqZCU6S4q8RQxs4Pd0UeAFIBhawAY9Nj5Flt0xIZG9tfIBNzEymfC4WsghSrMigoZAUZmRwh0vFXyOTcpOVzoBBgoBBgoBBgoBBgoBBgoBBgoBBgoBBgoBBgoBBgoBBgoBBgoJA0gY1ygBjCRrn0EMJGOTj/wg1hoxw2yjFOg41yUuZ3naSjc3EaG+VgCaFgoxw2ynmd/sbCRjnBBfcQJxjDRjlYQijYKAeQEWyUcx8F2P0OCxQCDBQCDBQCDBQCDBQCDBQCDBQCDBQCDBQCDBQCDNBC7Iw8ykQWwI88Ktz2uPlQsD7iNrp7z5kOBfP4StaBH5tHR82ZjaHLtJTGGZvHCeIsV1R0OwMBOlTR7FOTNeGlIAOFZwrFh6yMXnVLWF6cp+NnGUjQAb1ObwznXPYxAFnDCmIVgM0haQ0vHQQ5vnsROvQ9Mvzd6Y0SVjviJRCPucng9XpzOKH4aTpjneXFw5wgHXVJmuma8n3SU3SNTu8zgiAIgiAIgiAIswL8C14t80cmVfdkAAAAAElFTkSuQmCC" />
                            </defs>
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
                v-if="can('kelola parameter') || can('kelola kategori') || can('kelola subkategori') || can('kelola jenis cairan')">
                <button @click="toggle('kategori')"
                    class="w-full flex items-center justify-between gap-3 py-3 px-3 hover:bg-green-700 rounded">
                    <div class="flex items-center gap-3">
                        <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="38" height="38" fill="url(#pattern0_1673_84)" />
                            <defs>
                                <pattern id="pattern0_1673_84" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_1673_84" transform="scale(0.01)" />
                                </pattern>
                                <image id="image0_1673_84" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC6ElEQVR4nO2dT0tUURiHTwQiCW0CE4wB++M9Qn8WLRIion1kG6GgzxERLXIVtWydzbFF4GS1yyRFsTLvUUjaVNAq6yvoRv3FGIihlDVnfO9MzwPvB3ifZ849w13MOAcAAAAAAAAAAAAAAADbokeuTWXXqyHXp7LrTzy9aN8hGnSnFNxTBbes4FSXKbsVld0VovwuhNweBXdLwa3WLUQgyo5RcPd2JUQgyp9jlN3lXY8ReHxtH6Pi9qrsPpsFCdwpvwYZcudNY4RNUYK79t9f9ApuwDxG4KRsvj8emocInJTN364qW6Q83y+Nd0ozR6U8k6Jnov/p4u2RFU2Wvmms474qrqW+QYZbpdddyI87/AC+Obys0far9Qky0ibNdhMj/uXToOps7OD1tEEet0jviKF/fTTPHFvTywMX0gWZOMTJiDXek1Ol72mCDLe+4NL2aT6QKU6JRtvfE8SnCfKq41ntQcY7vxDEpwky0fmh9iCTpa8E8WmCTJa+1h5kqrRIEJ8myFRpkSAxkUyCeHuBBPH20ghSAFGRIPZyIkHshcRmCDLdtWC+SGySme5aqD1I9BXzRWLTTIUg0TwCQWQvniCyl00Q2QsmiOylEkT2Igkie3kEkb0wghRAkghiL0YEsZehAgyvTmQfgSCyF08Q2csmiOwFE0T2Ugkie5EEkb08gsheGEEKIEkEsRcjgtjLUAGGVyeyj0AQ2YsniOxlE0T2ggkie6kEkb1IgsheHkFkL4wgBZAkgtiLUeMGyf1gARZRc0z2IEGQbMB+Ed8ck2e3aw8y23POfJHYJDPXfTbNDynn/pP5MrHBJ/cfqy5rDrIeJWaXzBeKDTy5X1PuLyaJsSnKXfPFYqNOdidpjI2/q4jZTeV+1X5B3yizotzfqLpLHmQjzHx2QrkfUfRLBVhYBZ0l5f6Joj9etxBbwiycbNN8zxnNdfcpz/qZrH/dRdXJ/Ol9uxYCAAAAAAAAAAAAAADANRY/ADAF4C+d92oyAAAAAElFTkSuQmCC" />
                            </defs>
                        </svg>
                        <span>Kategori</span>
                    </div>
                    <svg class="w-3 fill-white inline ml-3" viewBox="0 0 24 24">
                        <path d="M7 10l5 5 5-5H7z" />
                    </svg>
                </button>

                <Transition name="slide">
                    <div v-if="toggles.kategori" class="pl-8 space-y-1">

                        <Link v-if="can('kelola parameter')" href="/pegawai/parameter"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                        <span>Parameter</span>
                        </Link>
                        <Link v-if="can('kelola kategori')" href="/pegawai/kategori"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                        <span>Detail Kategori</span>
                        </Link>
                        <Link v-if="can('kelola subkategori')" href="/pegawai/subkategori"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                        <span>Sub Kategori</span>
                        </Link>
                        <Link v-if="can('kelola jenis cairan')" href="/pegawai/jenis_cairan"
                            class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                        <span>Jenis Cairan</span>
                        </Link>
                    </div>
                </Transition>
            </div>

            <a v-if="can('kelola instansi')" href="/pegawai/instansi"
                class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect width="38" height="38" fill="url(#pattern0_1673_92)" />
                    <defs>
                        <pattern id="pattern0_1673_92" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_1673_92" transform="scale(0.01)" />
                        </pattern>
                        <image id="image0_1673_92" width="100" height="100" preserveAspectRatio="none"
                            xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAACXElEQVR4nO3cwWoTURTG8TyZPpNkXkCEgvsmIBdBlwXprlwEoXAcLXYZ+gAJbs7SfRa3FFobTA0WMn7n3vv/4GwCDZnvlwkzJ6GzGSGEEELIsBwLM+51IHtngDHGBDk5W5V3X9Zdz8nZKg5IutwU89L1pMsNIBYAAhDXlw+I6wsHxPUlA+L6YgEJUKYBoi/QANGXZoDoizJA9OUYIKxOEquTEmoAcT0CIK4vHhDXlw2I6wsGJECpBoi+SAPk8TLxb3N+82uvqLvHDv3NVPPUa2nysvfQL1bSE8+3e8D/c/712ABZAnLUM6S1SbV/ZLU2CZAiRwDE9cUD4vqyAXF9wYAEKNUA0RdpgOjLM0D0hRkgh0tglzVBWC6Wdu7UWb9PEHZZpZ0zpLVJgBQ5AiCuLx4Q15cNiOsLBiRAqQaIvkgDRF+eAbJfAnfqE4RdVmnnTp1t7wRhdVLaOUNamwRIkSMA4vriAXF92YC4vmBAApRqgOiLNED05Rkg+sIMkMMlsDqZIOyySjt36qzfJwi7rNLOGdLaJECKHAEQ1xcPiOvLBsT1BQMSoFQDJMYMR/7PQlz2OiBN3YcM98dxer4qF1frZ8/rD9e/n2O++LrlDPHjgHy+3pTnJv9YP2Isx+2rxfeXgLgGJBRG7yA5GkbPIDkiRq8gOSpGjyA5MkZvIDk6Rk8guQaMXkByLRg9gOSaMFoHybVhtAySa8RoFSTXitEiyJuPO1vb5bidn357MaspDy/+7aeb8t5+VjvDH18wzRcVYtzl2N+0DQGmuo+p3ajLG8AghBBCyCxobgFjAQlcrdoQqAAAAABJRU5ErkJggg==" />
                    </defs>
                </svg>
                <span>Daftar Pelanggan</span>
            </a>

            <a v-if="can('lihat hasil uji')" href="/pegawai/hasiluji"
                class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                <svg width="33" height="34" viewBox="0 0 33 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M10.9764 13.6616L20.6973 11.5752L26.9305 14.5024L10.1369 31.296C9.02338 32.4097 7.5131 33.0354 5.93828 33.0355C4.36346 33.0356 2.8531 32.4101 1.73946 31.2966C0.625813 30.1831 0.000111376 28.6728 1.48675e-08 27.098C-0.000111347 25.5232 0.625376 24.0128 1.73886 22.8992L2.99405 21.644L4.24924 21.7473V20.3888L8.04568 16.5924L9.29136 16.5568V15.3467L10.9764 13.6616Z"
                        fill="#C3EF3C" />
                    <path
                        d="M22.3124 1.48624C21.9783 1.15208 21.5251 0.964355 21.0525 0.964355C20.5799 0.964355 20.1267 1.15208 19.7926 1.48624C19.4584 1.82039 19.2707 2.27361 19.2707 2.74618C19.2707 3.21874 19.4584 3.67196 19.7926 4.00611L20.2129 4.4253L14.3051 10.3331L14.7599 11.5752L13.1022 11.5361L10.9766 13.6617L26.9306 14.5012L28.6098 12.8221L29.0301 13.2425C29.1938 13.4154 29.3904 13.5538 29.6085 13.6494C29.8265 13.745 30.0615 13.796 30.2996 13.7992C30.5376 13.8025 30.7739 13.758 30.9945 13.6684C31.2151 13.5787 31.4154 13.4458 31.5837 13.2774C31.752 13.109 31.8849 12.9086 31.9744 12.688C32.064 12.4674 32.1083 12.231 32.105 11.993C32.1016 11.7549 32.0506 11.5199 31.9548 11.3019C31.8591 11.084 31.7206 10.8874 31.5476 10.7238L22.3124 1.48624Z"
                        fill="#AEDDFF" />
                    <path
                        d="M14.3347 10.3032L17.0636 13.0321C17.1511 13.1136 17.2212 13.212 17.2699 13.3212C17.3186 13.4305 17.3448 13.5484 17.3469 13.668C17.349 13.7876 17.327 13.9063 17.2822 14.0172C17.2374 14.1281 17.1707 14.2289 17.0862 14.3135C17.0016 14.398 16.9009 14.4647 16.79 14.5095C16.6791 14.5543 16.5603 14.5763 16.4407 14.5742C16.3211 14.5721 16.2032 14.5459 16.0939 14.4972C15.9847 14.4485 15.8863 14.3784 15.8048 14.2908L13.0736 11.5632L14.3347 10.3032ZM9.29731 15.3418L12.025 18.0707C12.1823 18.2395 12.268 18.4628 12.2639 18.6935C12.2598 18.9243 12.1664 19.1444 12.0032 19.3076C11.84 19.4708 11.6199 19.5642 11.3891 19.5683C11.1584 19.5724 10.9351 19.4867 10.7663 19.3294L8.03737 16.6005L9.29731 15.3418ZM6.98762 23.108L4.25875 20.3792L3 21.6379L5.72769 24.368C5.89558 24.5303 6.12052 24.6202 6.35404 24.6182C6.58756 24.6163 6.81098 24.5228 6.97619 24.3577C7.1414 24.1927 7.23517 23.9693 7.23731 23.7358C7.23945 23.5023 7.14978 23.2761 6.98762 23.108Z"
                        fill="#008463" />
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