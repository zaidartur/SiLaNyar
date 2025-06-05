<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { computed, watch } from 'vue'

interface User {
    id: number
    nama: string
}

interface Instansi {
    id: number
    nama: string
    user: User
}

interface Kategori {
    id: number
    nama: string
}

interface Pengajuan {
    id: number
    kode_pengajuan: string
    instansi: Instansi
    kategori: Kategori
}

interface Pengujian {
    id: number
    id_form_pengajuan: number
    id_user: number
    id_kategori: number
    tanggal_mulai: string
    tanggal_selesai: string
    jam_mulai: string
    jam_selesai: string
}

const props = defineProps<{
    pengujian: Pengujian
    form_pengajuan: Pengajuan[]
    user: User[]
}>()

const form = useForm({
    id_form_pengajuan: props.pengujian.id_form_pengajuan,
    id_user: props.pengujian.id_user,
    id_kategori: props.pengujian.id_kategori as number | null,
    tanggal_mulai: formatDate(props.pengujian.tanggal_mulai),
    tanggal_selesai: formatDate(props.pengujian.tanggal_selesai),
    jam_mulai: props.pengujian.jam_mulai,
    jam_selesai: props.pengujian.jam_selesai,
    status: props.pengujian.status,
})

const selectedPengajuan = computed(() =>
    props.form_pengajuan.find(f => f.id === form.id_form_pengajuan)
)

const idKategori = computed(() => selectedPengajuan.value?.kategori.id ?? null)

watch(() => form.id_form_pengajuan, () => {
    form.id_kategori = idKategori.value
})

function formatDate(dateStr: string) {
    if (!dateStr) return ''
    const d = new Date(dateStr)
    return d.toISOString().split('T')[0]
}
const submit = () => {
    form.put(`/pegawai/pengujian/${props.pengujian.id}/edit`, {
        only: ['status']
    })
}
</script>

<template>
    <div class="h-screen w-full bg-white lg:grid lg:grid-cols-3">
        <!-- Left Side - Logo Section -->
        <div
            class="hidden h-screen flex-col bg-customDarkGreen lg:col-span-1 lg:flex lg:items-center lg:justify-center">
            <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="mx-auto h-48 w-auto object-contain" />
            <div class="mt-6 text-center text-white">
                <h2 class="mb-2 border-b border-white pb-2 text-2xl font-bold">SiLanYar</h2>
                <p class="text-sm">Sistem Laboratoruim Karanganyar</p>
            </div>
        </div>

        <!-- Right Side - Form Section -->
        <div class="flex h-screen items-start justify-center overflow-y-auto bg-white lg:col-span-2">
            <form @submit.prevent="submit" class="mx-auto grid w-full max-w-xl gap-6 p-6 md:p-12">
                <div class="grid gap-2 text-center">
                    <h1 class="text-3xl font-bold">Edit Jadwal Pengujian</h1>
                </div>

                <div class="grid gap-4">
                    <!-- Form Pengajuan -->
                    <div class="grid gap-2">
                        <Label for="id_form_pengajuan">Pilih Form Pengajuan</Label>
                        <select v-model="form.id_form_pengajuan" id="id_form_pengajuan"
                            class="w-full rounded border px-3 py-2 mt-1">
                            <option :value="null" disabled>Pilih Pengajuan</option>
                            <option v-for="item in form_pengajuan" :key="item.id" :value="item.id">
                                {{ item.kode_pengajuan }} - {{ item.instansi.nama }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_form_pengajuan" class="text-sm text-red-600">
                            {{ form.errors.id_form_pengajuan }}
                        </span>
                    </div>

                    <!-- Teknisi -->
                    <div class="grid gap-2">
                        <Label for="id_user">Pilih Teknisi</Label>
                        <select v-model="form.id_user" id="id_user" class="w-full rounded border px-3 py-2 mt-1">
                            <option :value="null" disabled>Pilih Teknisi</option>
                            <option v-for="u in user" :key="u.id" :value="u.id">
                                {{ u.nama }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_user" class="text-sm text-red-600">
                            {{ form.errors.id_user }}
                        </span>
                    </div>

                    <!-- Tanggal Mulai & Selesai -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="tanggal_mulai">Tanggal Mulai</Label>
                            <Input type="date" id="tanggal_mulai" v-model="form.tanggal_mulai" />
                            <span v-if="form.errors.tanggal_mulai" class="text-sm text-red-600">
                                {{ form.errors.tanggal_mulai }}
                            </span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="tanggal_selesai">Tanggal Selesai</Label>
                            <Input type="date" id="tanggal_selesai" v-model="form.tanggal_selesai" />
                            <span v-if="form.errors.tanggal_selesai" class="text-sm text-red-600">
                                {{ form.errors.tanggal_selesai }}
                            </span>
                        </div>
                    </div>

                    <!-- Jam Mulai & Selesai -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="jam_mulai">Jam Mulai</Label>
                            <Input type="time" id="jam_mulai" v-model="form.jam_mulai" />
                            <span v-if="form.errors.jam_mulai" class="text-sm text-red-600">
                                {{ form.errors.jam_mulai }}
                            </span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="jam_selesai">Jam Selesai</Label>
                            <Input type="time" id="jam_selesai" v-model="form.jam_selesai" />
                            <span v-if="form.errors.jam_selesai" class="text-sm text-red-600">
                                {{ form.errors.jam_selesai }}
                            </span>
                        </div>
                    </div>
                    <div class="grid gap-2">
                        <Label for="status">Status</Label>
                        <select id="status" v-model="form.status" class="w-full rounded border px-3 py-2 mt-1">
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <span v-if="form.errors.status" class="text-sm text-red-600">
                            {{ form.errors.status }}
                        </span>
                    </div>
                </div>

                <button type="submit" :disabled="form.processing"
                    class="mb-8 w-full rounded bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700">
                    Update Jadwal Pengujian
                </button>
            </form>
        </div>
    </div>
</template>
