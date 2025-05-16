<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useForm } from '@inertiajs/vue3'

const step = ref(1)
const selectedJenisAkun = ref('')

const form = useForm({
    nama: '',
    email: '',
    kontak_pribadi: '',
    jenis_user: '',
    password: '',
    password_confirmation: '',
    alamat_pribadi: '',
    nama_instansi: '',
    tipe_instansi: '',
    kontak_instansi: '',
    alamat_instansi: '',
})

const submit = () => {
    form.jenis_user = selectedJenisAkun.value === 'pribadi' ? 'perorangan' : 'instansi'
    form.post(route('customer.register'), {
        onSuccess: () => {
            form.reset()
            step.value = 1
        },
    })
}
</script>

<template>
    <div class="w-full lg:grid lg:min-h-[600px] lg:grid-cols-2 xl:min-h-[800px]">
        <!-- Logo Section -->
        <div class="hidden bg-green-800 lg:flex lg:items-center lg:justify-center flex-col">
            <img src="/storage/assetsadmin/logodlh.png" alt="Logo DLH" class="w-auto h-48 object-contain mx-auto" />
            <div class="text-center text-white mt-6">
                <h2 class="text-2xl font-bold mb-2 border-b border-white pb-2">SiLanYar</h2>
                <p class="text-sm">Sistem Laboratoruim Karanganyar</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="flex items-center justify-center py-12">
            <div class="mx-auto grid w-[350px] gap-6">
                <!-- LANGKAH 1: Form Awal -->
                <form v-if="step === 1" class="grid gap-6" @submit.prevent="() => { if (selectedJenisAkun) step = 2 }">
                    <div class="grid gap-2 text-center">
                        <h1 class="text-3xl font-bold">Buat Akun</h1>
                    </div>
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="nama">Nama *</Label>
                            <Input id="nama" v-model="form.nama" type="text" placeholder="Masukan Nama Lengkap Anda"
                                required />
                            <div v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="email">Email *</Label>
                            <Input id="email" v-model="form.email" type="email" placeholder="Masukan Email Anda"
                                required />
                            <div v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="kontak_pribadi">No.Telp *</Label>
                            <Input id="kontak_pribadi" v-model="form.kontak_pribadi" type="tel"
                                placeholder="Masukan No Telp Anda" required />
                            <div v-if="form.errors.kontak_pribadi" class="text-red-500 text-sm">{{
                                form.errors.kontak_pribadi }}</div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="jenis_user">Jenis Akun *</Label>
                            <select v-model="selectedJenisAkun" id="jenis_user"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                <option disabled value="">Pilih Jenis Akun</option>
                                <option value="pribadi">Pribadi</option>
                                <option value="instansi">Instansi</option>
                            </select>
                        </div>
                        <div class="grid gap-2">
                            <Label for="password">Password *</Label>
                            <Input id="password" v-model="form.password" type="password"
                                placeholder="Masukan kata sandi Anda" required />
                            <div v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="password_confirmation">Konfirmasi Password *</Label>
                            <Input id="password_confirmation" v-model="form.password_confirmation" type="password"
                                placeholder="Masukkan kembali kata sandi Anda" required />
                        </div>
                        <Button type="submit"
                            class="w-full bg-green-700 hover:bg-green-800 text-white rounded-md px-4 py-2 text-sm font-medium">
                            Lanjut
                        </Button>
                    </div>
                </form>

                <!-- LANGKAH 2: Form Tambahan -->
                <form v-else-if="step === 2" @submit.prevent="submit" class="grid gap-6">
                    <div class="grid gap-2 text-center">
                        <h1 class="text-2xl font-semibold">{{ selectedJenisAkun === 'pribadi' ? 'Biodata Pribadi' :
                            'Detail Institusi' }}</h1>
                    </div>

                    <!-- Form Pribadi -->
                    <div v-if="selectedJenisAkun === 'pribadi'" class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="alamat_pribadi">Alamat</Label>
                            <textarea id="alamat_pribadi" v-model="form.alamat_pribadi"
                                placeholder="Masukkan alamat lengkap Anda" required
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            </textarea>
                            <div v-if="form.errors.alamat_pribadi" class="text-red-500 text-sm">{{
                                form.errors.alamat_pribadi }}</div>
                        </div>
                    </div>

                    <!-- Form Instansi -->
                    <div v-else-if="selectedJenisAkun === 'instansi'" class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="nama_instansi">Nama Institusi</Label>
                            <Input id="nama_instansi" v-model="form.nama_instansi" type="text"
                                placeholder="Masukkan nama Instansi Anda" required />
                            <div v-if="form.errors.nama_instansi" class="text-red-500 text-sm">{{
                                form.errors.nama_instansi }}</div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="kontak_instansi">Kontak Instansi</Label>
                            <Input id="kontak_instansi" v-model="form.kontak_instansi" type="text"
                                placeholder="Masukkan nomor Instansi Anda" required />
                            <div v-if="form.errors.kontak_instansi" class="text-red-500 text-sm">{{
                                form.errors.kontak_instansi }}</div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="tipe_instansi">Tipe Institusi</Label>
                            <select id="tipe_instansi" v-model="form.tipe_instansi"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                required>
                                <option disabled value="">Pilih Tipe Institusi</option>
                                <option value="pemerintahan">Pemerintah</option>
                                <option value="swasta">Swasta</option>
                            </select>
                            <div v-if="form.errors.tipe_instansi" class="text-red-500 text-sm">{{
                                form.errors.tipe_instansi }}</div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="alamat_instansi">Alamat Instansi</Label>
                            <textarea id="alamat_instansi" v-model="form.alamat_instansi"
                                placeholder="Masukkan alamat lengkap Instansi Anda" required
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            </textarea>
                            <div v-if="form.errors.alamat_instansi" class="text-red-500 text-sm">{{
                                form.errors.alamat_instansi }}</div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <Button type="submit" :disabled="form.processing"
                        class="w-full bg-green-700 hover:bg-green-800 text-white rounded-md px-4 py-2 text-sm font-medium">
                        {{ form.processing ? 'Mendaftar...' : 'Daftar' }}
                    </Button>

                    <!-- Tombol Kembali -->
                    <Button @click="step = 1" variant="outline" type="button"
                        class="w-full border-green-700 text-green-700 hover:bg-green-800 mt-2">
                        Kembali
                    </Button>
                </form>
            </div>
        </div>
    </div>
</template>