<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    nama_lengkap: '',
    email: '',
    no_telp: '',
    jabatan: '',
    kelamin: '',
    password: '',
    password_confirmation: '',
    role: 'admin' // Menambahkan role admin
})

const loading = ref(false)

const handleSubmit = () => {
    loading.value = true
    form.post(route('admin.register'), {
        onFinish: () => loading.value = false
    })
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-md">
            <div>
                <h2 class="text-center text-3xl font-extrabold text-gray-900">Registrasi Admin</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Panel Khusus Administrator
                </p>
            </div>

            <!-- Form fields sama seperti sebelumnya, hanya berbeda route -->
            <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
                <!-- ...form fields sama seperti sebelumnya... -->
            </form>
        </div>
    </div>
</template>