<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const form = useForm({
    id_pengajuan: '',
    id_form_pengajuan: '',
    nama_pelanggan: '',
    kategori: '',
    jenis_cairan: '',
    nama_teknisi: '',
    tanggal_pengujian: '',
    tanggal_mulai: '',
    tanggal_selesai: '',
    jam_mulai: '',
    jam_selesai: '',
    status: ''
})

const submit = () => {
    form.post('/pegawai/pengujian/store')
}
</script>


<template>
    <div class="w-full h-screen lg:grid lg:grid-cols-2">
        <div class="flex items-center justify-center h-full overflow-y-auto">
            <div class="max-h-screen overflow-y-auto p-4 w-full">
                <form @submit.prevent="submit" class="mx-auto grid w-full max-w-[800px] gap-6">
                    <div class="grid gap-2 text-center">
                        <h1 class="text-3xl font-bold">Form Pengujian</h1>
                    </div>
                    <div class="grid gap-4 w-full max-w-[800px]">
                        <div class="flex items-center gap-4">
                            <Label for="id_pengajuan" class="w-40">ID Pengajuan</Label>
                            <Input id="id_pengajuan" v-model="form.id_pengajuan" type="text" class="w-full" required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="id_form_pengajuan" class="w-40">ID Form Pengajuan</Label>
                            <Input id="id_form_pengajuan" v-model="form.id_form_pengajuan" type="text" class="w-full"
                                required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="nama_pelanggan" class="w-40">Nama Pelanggan</Label>
                            <Input id="nama_pelanggan" v-model="form.nama_pelanggan" type="text" class="w-full"
                                required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="kategori" class="w-40">Kategori</Label>
                            <Input id="kategori" v-model="form.kategori" type="text" class="w-full" required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="jenis_cairan" class="w-40">Jenis Cairan</Label>
                            <Input id="jenis_cairan" v-model="form.jenis_cairan" type="text" class="w-full" required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="nama_teknisi" class="w-40">Nama Teknisi</Label>
                            <Input id="nama_teknisi" v-model="form.nama_teknisi" type="text" class="w-full" required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="tanggal_pengujian" class="w-40">Tanggal Pengujian</Label>
                            <Input id="tanggal_pengujian" v-model="form.tanggal_pengujian" type="date" class="w-full"
                                required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="tanggal_mulai" class="w-40">Tanggal Mulai</Label>
                            <Input id="tanggal_mulai" v-model="form.tanggal_mulai" type="date" class="w-full"
                                required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="tanggal_selesai" class="w-40">Tanggal Selesai</Label>
                            <Input id="tanggal_selesai" v-model="form.tanggal_selesai" type="date" class="w-full"
                                required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="jam_mulai" class="w-40">Jam Mulai</Label>
                            <Input id="jam_mulai" v-model="form.jam_mulai" type="time" class="w-full" required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="jam_selesai" class="w-40">Jam Selesai</Label>
                            <Input id="jam_selesai" v-model="form.jam_selesai" type="time" class="w-full" required />
                        </div>

                        <div class="flex items-center gap-4">
                            <Label for="status" class="w-40">Status</Label>
                            <Input id="status" v-model="form.status" type="text" placeholder="Contoh: Selesai"
                                class="w-full" required />
                        </div>

                        <div class="flex justify-center">
                            <Button type="submit" class="bg-customDarkGreen hover:bg-green-600 w-40" :disabled="form.processing">
                                Tambah
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="hidden bg-green-800 lg:flex lg:items-center lg:justify-center flex-col h-full">
            <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="w-auto h-48 object-contain mx-auto" />
            <div class="text-center text-white mt-6">
                <h2 class="text-2xl font-bold mb-2 border-b border-white pb-2">SiLanYar</h2>
                <p class="text-sm">Sistem Laboratoruim Karanganyar</p>
            </div>
        </div>
    </div>
</template>

