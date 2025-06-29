<script setup lang="ts">
/* eslint-disable */
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    nama: '',
    email: '',
    no_telepon: '',
    jabatan: '',
    jenis_kelamin: '',
    password: '',
    password_confirmation: '',
    id_role: ''
})

const loading = ref(false)

const handleSubmit = () => {
    loading.value = true
    form.post(route('pegawai.registrasi'), {
        onFinish: () => loading.value = false
    })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
            <div>
                <h2 class="text-center text-3xl font-extrabold text-gray-900">Registrasi Pegawai</h2>
            </div>

            <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
                <div class="space-y-4">
                    <div>
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" v-model="form.nama" required />
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" v-model="form.email" required />
                    </div>

                    <div>
                        <label for="no_telepon">No Telepon</label>
                        <input type="tel" id="no_telepon" v-model="form.no_telepon" required />
                    </div>

                    <div>
                        <label for="jabatan">Jabatan</label>
                        <input type="text" id="jabatan" v-model="form.jabatan" required />
                    </div>

                    <div>
                        <label>Jenis Kelamin</label>
                        <select v-model="form.jenis_kelamin" required>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="password">Password</label>
                        <input type="password" id="password" v-model="form.password" required />
                    </div>

                    <div>
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" v-model="form.password_confirmation"
                            required />
                    </div>

                    <div>
                        <label for="id_role">Role</label>
                        <input type="text" id="id_role" v-model="form.id_role" required />
                    </div>
                </div>

                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md" :disabled="loading">
                    {{ loading ? 'Loading...' : 'Register' }}
                </button>
            </form>
        </div>
    </div>
</template>