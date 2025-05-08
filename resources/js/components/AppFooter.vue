<script lang="ts">
import { Clock, Mail, Phone } from 'lucide-vue-next';
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'AppFooter',
    components: {
        Clock,
        Phone,
        Mail,
    },
    methods: {
        submitForm() {
            console.log('Form submitted');
        },
    },
});
</script>

<template>
    <footer class="bg-green-700 px-6 pb-8 pt-16 text-white md:px-20">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 md:grid-cols-3">
            <!-- Company Info -->
            <section class="flex flex-col">
                <header class="mb-6 flex items-center gap-4">
                    <img
                        src="https://cdn.builder.io/api/v1/image/assets/TEMP/6d88c42ac234a0d92c5665f917a3f3dc9452ba52?placeholderIfAbsent=true&apiKey=6e80c266b96f4327b2d8569c33619dcf"
                        alt="Dinas Lingkungan Hidup Logo"
                        class="h-20 w-20 object-contain"
                    />
                    <h2 class="text-xl font-bold">Dinas Lingkungan Hidup Kota Karanganyar</h2>
                </header>

                <address class="text-sm not-italic leading-relaxed text-zinc-300">
                    Jl. Lawu No.204, Tegalasri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57716
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
                            <a href="https://www.google.com/search?q=No+telp+dlh+karanganyar" class="underline hover:text-zinc-100" target="_blank"
                                >(0271) 495149</a
                            >
                        </p>
                        <p class="flex items-center gap-2">
                            <span class="flex items-center gap-2 font-semibold text-white">
                                <Mail class="h-5 w-5" />
                                Email:
                            </span>
                            <a href="#" class="underline hover:text-zinc-100" title="Kirim email ke DLH Karanganyar"> dlh@karanganyarkab.go.id </a>
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
                        class="h-64 w-full border-0"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </section>

            <!-- Contact Form -->
            <section class="flex flex-col">
                <h2 class="mb-6 text-xl font-semibold">Hubungi Kami</h2>

                <form @submit.prevent="submitForm" class="flex flex-col gap-2">
                    <input
                        type="text"
                        placeholder="Nama"
                        class="rounded border border-gray-300 p-3 text-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-300"
                    />

                    <input
                        type="email"
                        placeholder="Email"
                        class="rounded border border-gray-300 p-3 text-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-300"
                    />

                    <textarea
                        placeholder="Pesan"
                        rows="3"
                        class="rounded border border-gray-300 p-3 text-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-300"
                    ></textarea>

                    <button type="submit" class="w-32 rounded bg-gray-500 px-4 py-2 font-semibold text-white hover:bg-gray-600">Kirim</button>
                </form>
            </section>
        </div>

        <div class="mt-10 text-center text-xs text-zinc-300">&copy; 2025 Dinas Lingkungan Hidup Karanganyar. All rights reserved.</div>
    </footer>
</template>
