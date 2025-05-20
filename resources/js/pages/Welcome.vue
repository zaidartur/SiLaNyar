<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Clock, Mail, Phone } from 'lucide-vue-next';
import { ref } from 'vue';

const showLoginModal = ref(false);

// Add function to toggle modal
const toggleLoginModal = () => {
    showLoginModal.value = !showLoginModal.value;
}

const syaratPenerimaan = ref([
    'Volume / jumlah sample minimal 2,5 liter',
    'Volume / jumlah sample lemak 1 liter dengan berbotol kaca gelap dan bermulut lebar',
    'Volume sample vecal coli dan total coli minimal 100 ml dengan botol kaca gelap steril',
    'Kondisi wadah / kemasan sampel harus bersih dan tidak terkontaminasi',
    'Waktu pengambilan sampel serta lama penyimpanan harus jelas',
    'Memberikan informasi jika sampel diawetkan meliputi waktu pengawetan dan bahan pengawet yang digunakan',
]);

const sampleDitolak = ref([
    'Volume atau jumlah sampel kurang dari persyaratan',
    'Sampel sudah terlalu lama disolasi',
    'Sampel mengalami kerusakan di perjalanan saat pengiriman sampel',
    'Kemasan sampel sudah rusak atau tidak sesuai sehingga mempengaruhi isi sampel',
]);

defineProps({
    src: String,
    alt: String,
});

const workflowSteps = ref([
    {
        number: 1,
        title: 'Registrasi Sampel',
        description: 'Pendaftaran sampel dengan data lengkap lokasi, waktu pengambilan, dan parameter uji.',
    },
    {
        number: 2,
        title: 'Verifikasi Sample',
        description: 'Pemeriksaan kesesuaian sampel dengan kriteria pengujian.',
    },
    {
        number: 3,
        title: 'Distribusi Sample',
        description: 'Pembagian sampel ke bagian laboratorium yang sesuai.',
    },
    {
        number: 4,
        title: 'Menganalisis Sample',
        description: 'Pengujian sampel menggunakan metode dan peralatan yang tepat.',
    },
    {
        number: 5,
        title: 'Pelaporan',
        description: 'Penyusunan hasil analisis dalam bentuk laporan.',
    },
]);
</script>

<template>

    <Head title="Welcome" />

    <AppLayout>
        <!-- Remove padding and make container full width -->
        <div class="flex min-h-screen w-full flex-col">
            <!-- Navigation Bar -->
            <nav class="fixed top-0 z-50 w-full bg-white dark:bg-black backdrop-blur-md shadow-sm">
                <div class="flex h-20 items-center justify-between">
                    <div
                        class="flex aspect-square size-16 items-center justify-center rounded-md ml-4 text-sidebar-primary-foreground">
                        <AppLogoIcon class="size-5 fill-current text-white" />
                    </div>
                    <div class="ml-3 grid flex-1 text-left">
                        <span class="text-2xl mb-1.5 font-bold leading-none text-customGreen">SiLanYar</span>
                        <span class="text-sm leading-none text-black dark:text-white">Sistem Informasi Laboratorium
                            Karanganyar</span>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-4 mr-4">
                        <a href="sso/login"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Login
                        </a>
                        <a href="https://sakti.karanganyarkab.go.id/register"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Registrasi
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <section class=" relative h-screen w-full bg-cover bg-center"
                style="background-image: url('/storage/assetslandingpage/hero.png')">
                <div class="absolute inset-0 flex items-center justify-center bg-black/40 text-white">
                    <div class="text-center">
                        <h2 class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl">Sistem
                            Laboratorium
                            Lingkungan Terpadu</h2>
                        <p class="mx-auto mt-6 max-w-2xl text-lg leading-8">
                            Manajemen dan monitoring laboratorium lingkungan yang komprehensif untuk
                            memantau kualitas
                            lingkungan di Kabupaten
                            Karanganyar.
                        </p>
                        <div class="mt-10">
                            <button @click="toggleLoginModal"
                                class="rounded-lg bg-orange-400 px-6 py-3 text-lg font-semibold text-white transition-colors hover:bg-orange-500">
                                Pelajari Sistem Lab
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <Dialog :open="showLoginModal" @update:open="showLoginModal = false">
                <DialogContent
                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-gradient-to-br from-lime-500 to-green-900 rounded-lg shadow-xl">
                    <DialogHeader>
                        <DialogTitle class="text-center text-2xl font-bold text-gray-300">Login Diperlukan
                        </DialogTitle>
                        <button @click="toggleLoginModal"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                        </button>
                    </DialogHeader>

                    <div class="flex flex-col items-center space-y-6 p-4">
                        <div class="text-center">
                            <p class="text-xl font-bold text-gray-300 mb-2">
                                Anda harus login terlebih dahulu
                            </p>
                            <p class="text-sm font-semibold text-gray-300 mb-2">
                                Untuk mengakses fitur sistem laboratorium, silakan login dengan akun SAKTI Karanganyar
                            </p>
                            <p class="text-sm italic text-gray-300">
                                Note:Jika belum memiliki akun, silakan klik registrasi menuju portal SAKTI
                                Karanganyar
                            </p>
                        </div>

                        <div class="flex gap-4">
                            <a href="sso/login"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Login
                            </a>
                            <a href="https://sakti.karanganyarkab.go.id/register"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Registrasi
                            </a>
                        </div>
                    </div>
                </DialogContent>
            </Dialog>

            <!-- Main Content -->
            <section id="informasi" class="flex flex-col w-full bg-white dark:bg-black p-12">
                <h1 class="self-center text-4xl font-bold text-green-700 dark:text-green-500 max-md:max-w-full mb-12">
                    Jadwal Pelayanan dan Syarat Penerimaan Sample
                </h1>

                <!-- Container pakai grid -->
                <div class="mt-8 grid w-full grid-cols-2 gap-12 px-8 max-md:grid-cols-1">
                    <!-- Syarat Penerimaan Sample -->
                    <div class="space-y-8">
                        <h2 class="text-4xl font-bold text-green-700 dark:text-green-600 mb-6">
                            Syarat Penerimaan Sample
                        </h2>
                        <h3 class="mt-6 text-xl text-black dark:text-white ">
                            Syarat Kelengkapan dan Kelayakan Sample :
                        </h3>
                        <div class="mt-8">
                            <ul class="space-y-6">
                                <li v-for="(text, index) in syaratPenerimaan" :key="index"
                                    class="flex items-start gap-3">
                                    <div
                                        class="mt-1 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-green-600">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xl font-medium text-black dark:text-white">
                                        {{ text }}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <!-- Sample Ditolak -->
                        <div class="mt-16 mb-8">
                            <h2 class="text-4xl mb-8 font-bold text-green-700 dark:text-green-600">
                                Sample dapat ditolak apabila
                            </h2>
                            <div class="mt-6">
                                <ul class="space-y-6">
                                    <li v-for="(text, index) in sampleDitolak" :key="index"
                                        class="flex items-start gap-3">
                                        <div
                                            class="mt-1 flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-red-500">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xl font-medium text-black dark:text-white">
                                            {{ text }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Sample -->
                    <div class="flex flex-col space-y-8">
                        <!-- BOD Sample Table -->
                        <div class="mb-12">
                            <h2
                                class="text-4xl font-extrabold text-center text-customDarkGreen dark:text-green-600 mb-5">
                                Sample dengan parameter BOD
                            </h2>

                            <div class="overflow-hidden rounded-lg border border-green-700">
                                <table class="w-full border-collapse">
                                    <thead class="bg-customDarkGreen text-white">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-3xl font-bold text-center border-r border-customDarkGreen ">
                                                Hari</th>
                                            <th class="px-6 py-3 text-3xl font-bold text-center">Waktu
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-black divide-y divide-green-700 ">
                                        <tr>
                                            <td
                                                class="px-6 py-4 text-3xl font-bold text-center text-black dark:text-white border-r border-customDarkGreen">
                                                Rabu-Kamis</td>
                                            <td
                                                class="px-6 py-4 text-3xl font-bold text-center text-black dark:text-white">
                                                08.00 - 11.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Non-BOD Sample Table -->
                            <div class="mt-8">
                                <h2
                                    class="text-4xl font-extrabold text-center text-customDarkGreen dark:text-green-600 mt-8 mb-5">
                                    Sample Tanpa parameter BOD
                                </h2>

                                <div class="overflow-hidden rounded-lg border border-green-700">
                                    <table class="w-full border-collapse">
                                        <thead class="bg-customDarkGreen text-white">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-3xl font-bold text-center border-r border-customDarkGreen ">
                                                    Hari
                                                </th>
                                                <th class="px-6 py-3 text-3xl font-bold text-center">
                                                    Waktu
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-black divide-y divide-green-700 ">
                                            <tr>
                                                <td
                                                    class="px-6 py-4 text-3xl font-bold text-center text-black dark:text-white border-r border-customDarkGreen dark:border-green-500">
                                                    Rabu-Kamis
                                                </td>
                                                <td
                                                    class="px-6 py-4 text-3xl font-bold text-center text-black dark:text-white">
                                                    08.00 - 11.00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    class="px-6 py-4 text-3xl font-bold text-center text-black dark:text-white border-r border-customDarkGreen dark:border-green-500">
                                                    Jumat
                                                </td>
                                                <td
                                                    class="px-6 py-4 text-3xl font-bold text-center text-black dark:text-white">
                                                    08.00 - 10.00
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Note -->
                <footer
                    class="text-1xl mt-24 px-8 font-normal text-green-700 dark:text-green-500 max-md:mt-10 max-md:max-w-full">
                    <p class="text-center italic">
                        Note: Apabila ada hal-hal yang meragukan, petugas penerima sampel dapat
                        menolak<br />
                        setelah berkonsultasi dengan pengendali teknis
                    </p>
                </footer>
            </section>

            <!-- Section Alur Pelayanan -->
            <section>
                <div
                    class="flex flex-col items-center justify-center rounded-md border border-blue-300 bg-green-100 p-6">
                    <h1 class="mb-8 text-center text-3xl font-semibold text-green-700">
                        Diagram Alur Pelayanan Laboratorium Penguji Dinas Lingkungan Hidup<br />
                        Kabupaten karanganyar
                    </h1>

                    <div class="mx-auto mb-8 w-full max-w-4xl">
                        <img src="/storage/assetslandingpage/alurdiagram.png" alt="Diagram Alur Pelayanan Laboratorium"
                            class="h-auto w-full rounded-lg object-contain shadow-lg" />
                    </div>

                    <div class="mt-6 text-sm italic text-green-700">
                        <p>Note: Pendaftaran Dilakukan 1 Hari Sebelum Penyerahan Sample</p>
                    </div>
                </div>
            </section>

            <!-- Section Alur Kerja -->
            <section class="bg-green-50 dark:bg-black p-4 md:p-8 rounded-0">
                <h1
                    class="text-2xl md:text-4xl text-customDarkGreen dark:text-green-600 font-bold text-center mb-4 md:mb-6">
                    Alur Kerja Laboratorium
                </h1>
                <p class="text-center text-base md:text-lg mb-8 md:mb-16 text-black dark:text-gray-300">
                    SiLanyar mengotomatisasi dan mengintegrasikan seluruh alur kerja laboratorium
                    lingkungan
                </p>

                <!-- Workflow diagram -->
                <div class="mb-8 md:mb-12">
                    <!-- Desktop workflow -->
                    <div class="relative hidden md:block">
                        <!-- Connecting line -->
                        <div class="absolute h-0.5 bg-green-700 dark:bg-green-500 w-[80%] left-[10%] top-8 z-0">
                        </div>

                        <!-- Steps with circles -->
                        <div class="relative z-10 flex justify-between">
                            <div v-for="step in workflowSteps" :key="step.number" class="flex flex-col items-center">
                                <div
                                    class=":bg-green-600 mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-700 text-xl font-bold text-white lg:h-16 lg:w-16 lg:text-2xl">
                                    {{ step.number }}
                                </div>
                                <h3
                                    class=":text-green-500 mb-2 text-center text-sm font-bold text-green-700 lg:text-lg">
                                    {{ step.title }}
                                </h3>
                                <p class="text-xs lg:text-sm text-center max-w-xs text-black dark:text-gray-300">
                                    {{ step.description }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile workflow -->
                    <div class="space-y-0 md:hidden">
                        <!-- Changed space-y-6 to space-y-0 -->
                        <div v-for="(step, index) in workflowSteps" :key="step.number" class="relative flex">
                            <!-- Added relative positioning -->
                            <div class="mr-4 flex flex-col items-center">
                                <!-- Circle with number -->
                                <div
                                    class="z-10 flex h-12 w-12 items-center justify-center rounded-full bg-green-700 text-xl font-bold text-white dark:bg-green-600">
                                    <!-- Added z-10 -->
                                    {{ step.number }}
                                </div>
                                <!-- Vertical line -->
                                <div v-if="index !== workflowSteps.length - 1"
                                    class="mt-0 h-24 w-0.5 bg-green-700 dark:bg-green-500">
                                    <!-- Changed h-12 to h-24 and mt-2 to mt-0 -->
                                </div>
                            </div>
                            <!-- Content -->
                            <div class="pb-8">
                                <!-- Added padding bottom -->
                                <h3 class="mb-1 text-lg font-bold text-green-700 dark:text-green-500">
                                    {{ step.title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ step.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Us -->
            <section id="about-us" class="bg-green-100 py-16">
                <div class="mx-auto max-w-4xl p-4">
                    <!-- Header -->
                    <h1 class="mb-8 text-center text-4xl font-bold text-green-700">About Us</h1>

                    <h2 class="mb-4 text-2xl font-bold text-green-700">Dinas Lingkungan Hidup Kabupaten
                        Karanganyar</h2>

                    <div class="space-y-4 font-normal text-gray-800">
                        <p>
                            Dinas Lingkungan Hidup Kabupaten Karanganyar adalah instansi pemerintah yang
                            bertugas
                            mengelola dan menjaga kelestarian
                            lingkungan hidup di wilayah Kabupaten Karanganyar. Kami berkomitmen untuk
                            menciptakan
                            lingkungan yang bersih, sehat, dan
                            berkelanjutan bagi seluruh masyarakat.
                        </p>

                        <p>
                            Sejak didirikan pada tahun 2008, kami telah melaksanakan berbagai program
                            pengelolaan
                            lingkungan, termasuk pengelolaan
                            sampah terpadu, penghijauan, konservasi sumber daya air, pemantauan kualitas
                            udara, serta
                            pendidikan dan kesadaran
                            lingkungan untuk masyarakat.
                        </p>

                        <p>
                            Visi kami adalah mewujudkan Kabupaten Karanganyar yang hijau, bersih, dan
                            lestari melalui
                            pengelolaan lingkungan yang
                            berkelanjutan dan partisipatif. Kami mengajak seluruh lapisan masyarakat
                            untuk berperan
                            aktif dalam menjaga kelestarian
                            lingkungan hidup demi masa depan yang lebih baik.
                        </p>
                    </div>
                </div>
            </section>

            <footer class="bg-green-700 px-6 pb-8 pt-16 text-white md:px-20">
                <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 md:grid-cols-3">
                    <!-- Company Info -->
                    <section class="flex flex-col">
                        <header class="mb-6 flex items-center gap-4">
                            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/6d88c42ac234a0d92c5665f917a3f3dc9452ba52?placeholderIfAbsent=true&apiKey=6e80c266b96f4327b2d8569c33619dcf"
                                alt="Dinas Lingkungan Hidup Logo" class="h-20 w-20 object-contain" />
                            <h2 class="text-xl font-bold">Dinas Lingkungan Hidup Kota Karanganyar</h2>
                        </header>

                        <address class="text-sm not-italic leading-relaxed text-zinc-300">
                            Jl. Lawu No.204, Tegalasri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah
                            57716
                        </address>

                        <div class="mt-6 space-y-4 text-sm text-zinc-300">
                            <div class="mb-4">
                                <h3 class="flex items-center gap-2 font-semibold text-white">
                                    <Clock class="h-5 w-5" />
                                    Jam Pelayanan:
                                </h3>
                                <p>Sen – Kam : 08:00 – 16:30 WIB</p>
                                <p>Jum : 08:00 – 16:30 WIB</p>
                            </div>

                            <div class="space-y-3">
                                <p class="flex items-center gap-2">
                                    <span class="flex items-center gap-2 font-semibold text-white">
                                        <Phone class="h-5 w-5" />
                                        Telpon:
                                    </span>
                                    <a href="https://www.google.com/search?q=No+telp+dlh+karanganyar"
                                        class="underline hover:text-zinc-100" target="_blank">(0271) 495149</a>
                                </p>
                                <p class="flex items-center gap-2">
                                    <span class="flex items-center gap-2 font-semibold text-white">
                                        <Mail class="h-5 w-5" />
                                        Email:
                                    </span>
                                    <a href="#" class="underline hover:text-zinc-100"
                                        title="Kirim email ke DLH Karanganyar"> dlh@karanganyarkab.go.id </a>
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Map -->
                    <section class="flex flex-col">
                        <h2 class="mb-6 text-xl font-semibold">Temukan Kami</h2>
                        <div class="w-full overflow-hidden rounded-lg">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.1601683843385!2d110.95587037570697!3d-7.599453192437656!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a188319bc5cb5%3A0x5e3a9c5fea004c28!2sDinas%20Lingkungan%20Hidup!5e0!3m2!1sid!2sid!4v1681636149407!5m2!1sid!2sid"
                                class="h-64 w-full border-0" allowfullscreen loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </section>

                    <!-- Contact Form -->
                    <section class="flex flex-col">
                        <h2 class="mb-6 text-xl font-semibold">Hubungi Kami</h2>

                        <form @click.prevent="toggleLoginModal" class="flex flex-col gap-2">
                            <input type="text" placeholder="Nama"
                                class="rounded border border-gray-300 p-3 text-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-300" />

                            <input type="email" placeholder="Email"
                                class="rounded border border-gray-300 p-3 text-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-300" />

                            <textarea placeholder="Pesan" rows="3"
                                class="rounded border border-gray-300 p-3 text-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-300"></textarea>

                            <button type="submit"
                                class="w-32 rounded bg-gray-500 px-4 py-2 font-semibold text-white hover:bg-gray-600">Kirim</button>
                        </form>
                    </section>
                </div>

                <div class="mt-10 text-center text-xs text-zinc-300">&copy; 2025 Dinas Lingkungan Hidup Karanganyar. All
                    rights reserved.</div>
            </footer>
        </div>
    </AppLayout>
</template>