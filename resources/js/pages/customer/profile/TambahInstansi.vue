<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    nama: '',
    tipe: '',
    alamat: '',
    no_telepon: '',
    email: '',
});

const submit = () => {
    form.post(route('customer.profile.instansi'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <div class="fixed inset-0 h-full w-full overflow-y-auto bg-gray-600 bg-opacity-50" id="my-modal">
        <div class="relative top-20 mx-auto w-96 rounded-md border bg-white p-5 shadow-lg">
            <div class="mt-3">
                <h3 class="mb-4 text-lg font-semibold">Tambah Instansi</h3>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Instansi</label>
                        <input
                            v-model="form.nama"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe Instansi</label>
                        <select
                            v-model="form.tipe"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        >
                            <option value="">Pilih Tipe</option>
                            <option value="swasta">Swasta</option>
                            <option value="pemerintahan">Pemerintahan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea
                            v-model="form.alamat"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">No Telepon</label>
                        <input
                            v-model="form.no_telepon"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        />
                    </div>

                    <div class="mt-4 flex justify-end gap-3">
                        <button type="button" class="rounded-lg bg-gray-200 px-4 py-2 text-gray-800 hover:bg-gray-300" @click="$emit('close')">
                            Batal
                        </button>
                        <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700" :disabled="form.processing">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
