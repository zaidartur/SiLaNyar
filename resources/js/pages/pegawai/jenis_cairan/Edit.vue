<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    jenisCairan: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    nama: props.jenisCairan.nama,
    deskripsi: props.jenisCairan.deskripsi,
});

const submit = () => {
    form.put(route('pegawai.jenis-cairan.update', props.jenisCairan.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Jenis Cairan" />

    <div class="mx-auto max-w-2xl p-4">
        <div class="rounded-lg bg-white p-6 shadow">
            <h1 class="mb-6 text-2xl font-bold">Edit Jenis Cairan</h1>

            <form @submit.prevent="submit">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input
                            v-model="form.nama"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea
                            v-model="form.deskripsi"
                            rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        ></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a :href="route('pegawai.jenis-cairan.index')" class="rounded-lg bg-gray-200 px-4 py-2 text-gray-800 hover:bg-gray-300">
                            Batal
                        </a>
                        <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700" :disabled="form.processing">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
