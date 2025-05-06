<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Add data for the new content
const bulletIcon = "https://cdn.builder.io/api/v1/image/assets/TEMP/fff689dc8350fb828f397d0c5ecf83aeccfb0cc6?placeholderIfAbsent=true&apiKey=6e80c266b96f4327b2d8569c33619dcf";
const rejectedBulletIcon = "https://cdn.builder.io/api/v1/image/assets/TEMP/dbead78d1e3d5e23a94657a6b75448b0533e7b59?placeholderIfAbsent=true&apiKey=6e80c266b96f4327b2d8569c33619dcf";

const syaratPenerimaan = ref([
    "Volume / jumlah sample minimal 2,5 liter",
    "Volume / jumlah sample lemak 1 liter dengan berbotol kaca gelap dan bermulut lebar",
    "Volume sample vecal coli dan total coli minimal 100 ml dengan botol kaca gelap steril",
    "Kondisi wadah / kemasan sampel harus bersih dan tidak terkontaminasi",
    "Waktu pengambilan sampel serta lama penyimpanan harus jelas",
    "Memberikan informasi jika sampel diawetkan meliputi waktu pengawetan dan bahan pengawet yang digunakan"
]);

const sampleDitolak = ref([
    "Volume atau jumlah sampel kurang dari persyaratan",
    "Sampel sudah terlalu lama disolasi",
    "Sampel mengalami kerusakan di perjalanan saat pengiriman sampel",
    "Kemasan sampel sudah rusak atau tidak sesuai sehingga mempengaruhi isi sampel"
]);

defineProps({
    src: String,
    alt: String,
});

const workflowSteps = ref([
    {
        number: 1,
        title: 'Registrasi Sampel',
        description: 'Pendaftaran sampel dengan data lengkap lokasi, waktu pengambilan, dan parameter uji.'
    },
    {
        number: 2,
        title: 'Verifikasi Sample',
        description: 'Pemeriksaan kesesuaian sampel dengan kriteria pengujian.'
    },
    {
        number: 3,
        title: 'Distribusi Sample',
        description: 'Pembagian sampel ke bagian laboratorium yang sesuai.'
    },
    {
        number: 4,
        title: 'Menganalisis Sample',
        description: 'Pengujian sampel menggunakan metode dan peralatan yang tepat.'
    },
    {
        number: 5,
        title: 'Pelaporan',
        description: 'Penyusunan hasil analisis dalam bentuk laporan.'
    }
]);

// Add to script setup section
const activeTab = ref('Berita Terkini');

const changeTab = (tabName: string) => {
    activeTab.value = tabName;
};
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Remove padding and make container full width -->
        <div class="flex min-h-screen w-full flex-col">
            <!-- Hero Section -->
            <section class="relative h-screen w-full bg-cover bg-center"
                style="background-image: url('/storage/assetslandingpage/hero.png')">
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white">
                    <div class="text-center">
                        <h2 class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl">
                            Sistem Laboratorium Lingkungan Terpadu
                        </h2>
                        <p class="mx-auto mt-6 max-w-2xl text-lg leading-8">
                            Manajemen dan monitoring laboratorium lingkungan yang komprehensif untuk memantau
                            kualitas lingkungan di Kabupaten Karanganyar.
                        </p>
                        <div class="mt-10">
                            <button
                                class="rounded-lg bg-orange-400 px-6 py-3 text-lg font-semibold text-white transition-colors hover:bg-orange-500">
                                Pelajari Sistem Lab
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main Content -->
            <section id="informasi" class="flex flex-col w-full bg-white dark:bg-black p-12">
                <h1 class="self-center text-4xl font-bold text-green-700 dark:text-green-500 max-md:max-w-full mb-12">
                    Jadwal Pelayanan dan Syarat Penerimaan Sample
                </h1>

                <!-- Container pakai grid -->
                <div class="grid grid-cols-2 gap-12 mt-8 px-8 max-md:grid-cols-1 w-full">
                    <!-- Syarat Penerimaan Sample -->
                    <div class="space-y-8">
                        <h2 class="text-4xl font-bold text-green-700 dark:text-green-500 mb-6">
                            Syarat Penerimaan Sample
                        </h2>
                        <h3 class="mt-6 text-xl text-black dark:text-white">
                            Syarat Kelengkapan dan Kelayakan Sample :
                        </h3>
                        <div class="mt-8">
                            <ul class="space-y-6">
                                <li v-for="(text, index) in syaratPenerimaan" :key="index"
                                    class="flex items-start gap-3">
                                    <div
                                        class="flex-shrink-0 h-6 w-6 rounded-full bg-green-600 flex items-center justify-center mt-1">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xl font-medium text-black dark:text-gray-200">
                                        {{ text }}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <!-- Sample Ditolak -->
                        <div class="mt-16 mb-8">
                            <h2 class="text-4xl mb-8 font-bold text-green-700 dark:text-green-500">
                                Sample dapat ditolak apabila
                            </h2>
                            <div class="mt-6">
                                <ul class="space-y-6">
                                    <li v-for="(text, index) in sampleDitolak" :key="index"
                                        class="flex items-start gap-3">
                                        <div
                                            class="flex-shrink-0 h-6 w-6 rounded-full bg-red-500 flex items-center justify-center mt-1">
                                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <span class="text-xl font-medium text-black dark:text-gray-200">
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
                            <h2 class="text-4xl font-extrabold text-center text-green-700 dark:text-green-500 mb-5">
                                Sample dengan parameter BOD
                            </h2>

                            <div class="border border-green-700 dark:border-green-500 rounded-lg overflow-hidden">
                                <table class="w-full border-collapse">
                                    <thead class="bg-green-700 dark:bg-green-500 text-white">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-3xl font-bold text-center border-r border-white dark:border-green-500">
                                                Hari</th>
                                            <th class="px-6 py-3 text-3xl font-bold text-center">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-green-700 dark:divide-green-600">
                                        <tr>
                                            <td
                                                class="px-6 py-4 text-3xl font-bold text-center text-black dark:text-white border-r border-green-700 dark:border-green-500">
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
                                    class="text-4xl font-extrabold text-center text-green-700 dark:text-green-500 mt-8 mb-5">
                                    Sample Tanpa parameter BOD
                                </h2>

                                <div class="border border-green-700 dark:border-green-500 rounded-lg overflow-hidden">
                                    <table class="w-full border-collapse">
                                        <thead class="bg-green-700 dark:bg-green-500 text-white">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-3xl font-bold text-center border-r border-white dark:border-green-500">
                                                    Hari
                                                </th>
                                                <th class="px-6 py-3 text-3xl font-bold text-center">
                                                    Waktu
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white dark:bg-gray-800 divide-y divide-green-700 dark:divide-green-500">
                                            <tr>
                                                <td
                                                    class="px-6 py-4 text-3xl font-bold text-center text-black dark:text-white border-r border-green-700 dark:border-green-500">
                                                    Rabu-Kamis
                                                </td>
                                                <td
                                                    class="px-6 py-4 text-3xl font-bold text-center text-black dark:text-white">
                                                    08.00 - 11.00
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    class="px-6 py-4 text-3xl font-bold text-center text-black dark:text-white border-r border-green-700 dark:border-green-500">
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
                    class="mt-24 px-8 text-1xl font-normal text-green-700 dark:text-green-500 max-md:mt-10 max-md:max-w-full">
                    <p class="italic text-center">
                        Note: Apabila ada hal-hal yang meragukan, petugas penerima sampel dapat menolak<br />
                        setelah berkonsultasi dengan pengendali teknis
                    </p>
                </footer>
            </section>

            <!-- Section Alur Pelayanan -->
            <section>
                <div
                    class="flex flex-col justify-center items-center bg-green-100 p-6 rounded-md border border-blue-300">
                    <h1 class="text-3xl font-semibold text-green-700 text-center mb-8">
                        Diagram Alur Pelayanan Laboratorium Penguji Dinas Lingkungan Hidup<br>
                        Kabupaten karanganyar
                    </h1>

                    <div class="w-full max-w-4xl mx-auto mb-8">
                        <img src="/storage/assetslandingpage/alurdiagram.png" alt="Diagram Alur Pelayanan Laboratorium"
                            class="w-full h-auto object-contain rounded-lg shadow-lg" />
                    </div>

                    <div class="mt-6 text-green-700 text-sm italic">
                        <p>Note: Pendaftaran Dilakukan 1 Hari Sebelum Penyerahan Sample</p>
                    </div>
                </div>
            </section>

            <!-- Section Alur Kerja -->
            <section class="bg-green-50 dark:bg-black p-4 md:p-8 rounded-0">
                <h1 class="text-2xl md:text-4xl text-green-700 dark:text-green-500 font-bold text-center mb-4 md:mb-6">
                    Alur Kerja Laboratorium
                </h1>
                <p class="text-center text-base md:text-lg mb-8 md:mb-16 text-gray-600 dark:text-gray-300">
                    SiLanyar mengotomatisasi dan mengintegrasikan seluruh alur kerja laboratorium lingkungan
                </p>

                <!-- Workflow diagram -->
                <div class="mb-8 md:mb-12">
                    <!-- Desktop workflow -->
                    <div class="hidden md:block relative">
                        <!-- Connecting line -->
                        <div class="absolute h-0.5 bg-green-700 dark:bg-green-500 w-[80%] left-[10%] top-8 z-0"></div>

                        <!-- Steps with circles -->
                        <div class="flex justify-between relative z-10">
                            <div v-for="step in workflowSteps" :key="step.number" class="flex flex-col items-center">
                                <div
                                    class="bg-green-700 dark:bg-green-600 text-white rounded-full w-12 h-12 lg:w-16 lg:h-16 flex items-center justify-center text-xl lg:text-2xl font-bold mb-4">
                                    {{ step.number }}
                                </div>
                                <h3
                                    class="text-green-700 dark:text-green-500 font-bold text-sm lg:text-lg mb-2 text-center">
                                    {{ step.title }}
                                </h3>
                                <p class="text-xs lg:text-sm text-center max-w-xs text-gray-600 dark:text-gray-300">
                                    {{ step.description }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile workflow -->
                    <div class="md:hidden space-y-0"> <!-- Changed space-y-6 to space-y-0 -->
                        <div v-for="(step, index) in workflowSteps" :key="step.number" class="flex relative">
                            <!-- Added relative positioning -->
                            <div class="flex flex-col items-center mr-4">
                                <!-- Circle with number -->
                                <div
                                    class="bg-green-700 dark:bg-green-600 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold z-10">
                                    <!-- Added z-10 -->
                                    {{ step.number }}
                                </div>
                                <!-- Vertical line -->
                                <div v-if="index !== workflowSteps.length - 1"
                                    class="w-0.5 bg-green-700 dark:bg-green-500 h-24 mt-0">
                                    <!-- Changed h-12 to h-24 and mt-2 to mt-0 -->
                                </div>
                            </div>
                            <!-- Content -->
                            <div class="pb-8"> <!-- Added padding bottom -->
                                <h3 class="text-green-700 dark:text-green-500 font-bold text-lg mb-1">
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
                <div class="max-w-4xl mx-auto p-4">
                    <!-- Header -->
                    <h1 class="text-4xl text-center font-bold text-green-700 mb-8">About Us</h1>

                    <h2 class="text-2xl font-bold text-green-700 mb-4">Dinas Lingkungan Hidup Kabupaten Karanganyar</h2>

                    <div class="space-y-4 font-normal text-gray-800">
                        <p>
                            Dinas Lingkungan Hidup Kabupaten Karanganyar adalah instansi pemerintah yang bertugas
                            mengelola dan menjaga kelestarian lingkungan hidup di wilayah Kabupaten Karanganyar. Kami
                            berkomitmen untuk menciptakan lingkungan yang bersih, sehat, dan berkelanjutan bagi seluruh
                            masyarakat.
                        </p>

                        <p>
                            Sejak didirikan pada tahun 2008, kami telah melaksanakan berbagai program pengelolaan
                            lingkungan, termasuk pengelolaan sampah terpadu, penghijauan, konservasi sumber daya air,
                            pemantauan kualitas udara, serta pendidikan dan kesadaran lingkungan untuk masyarakat.
                        </p>

                        <p>
                            Visi kami adalah mewujudkan Kabupaten Karanganyar yang hijau, bersih, dan lestari melalui
                            pengelolaan lingkungan yang berkelanjutan dan partisipatif. Kami mengajak seluruh lapisan
                            masyarakat untuk berperan aktif dalam menjaga kelestarian lingkungan hidup demi masa depan
                            yang lebih baik.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
