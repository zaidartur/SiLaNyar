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
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect width="38" height="38" fill="url(#pattern0_1746_183)" />
                    <defs>
                        <pattern id="pattern0_1746_183" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_1746_183" transform="scale(0.01)" />
                        </pattern>
                        <image id="image0_1746_183" width="100" height="100" preserveAspectRatio="none"
                            xlink:href="data:image/png;base64,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" />
                    </defs>
                </svg>
                <span>Permission</span>
            </a>

            <!-- Menu Role -->
            <a v-if="can('kelola role')" href="/superadmin/role"
                class="flex items-center gap-3 py-3 px-3 hover:bg-green-700 rounded">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect width="38" height="38" fill="url(#pattern0_1746_186)" />
                    <defs>
                        <pattern id="pattern0_1746_186" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_1746_186" transform="scale(0.01)" />
                        </pattern>
                        <image id="image0_1746_186" width="100" height="100" preserveAspectRatio="none"
                            xlink:href="data:image/png;base64,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" />
                    </defs>
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
                        <Link v-if="can('kelola jenis cairan')" href="/pegawai/jenis-cairan"
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