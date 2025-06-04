<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
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
    tanggal_mulai: props.pengujian.tanggal_mulai,
    tanggal_selesai: props.pengujian.tanggal_selesai,
    jam_mulai: props.pengujian.jam_mulai,
    jam_selesai: props.pengujian.jam_selesai,
})

const selectedPengajuan = computed(() =>
    props.form_pengajuan.find(f => f.id === form.id_form_pengajuan)
)

const idKategori = computed(() => selectedPengajuan.value?.kategori.id ?? null)

watch(() => form.id_form_pengajuan, () => {
    form.id_kategori = idKategori.value
})

const submit = () => {
    form.put(`/pegawai/pengujian/${props.pengujian.id}/edit`)
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <!-- Form Pengajuan -->
        <div>
            <Label for="id_form_pengajuan">Pilih Form Pengajuan</Label>
            <select v-model="form.id_form_pengajuan" id="id_form_pengajuan" class="w-full border rounded p-2 mt-1">
                <option value="" disabled>Pilih Pengajuan</option>
                <option v-for="item in form_pengajuan" :key="item.id" :value="item.id">
                    {{ item.kode_pengajuan }} - {{ item.instansi.nama }}
                </option>
            </select>
            <p v-if="form.errors.id_form_pengajuan" class="text-sm text-red-500 mt-1">{{ form.errors.id_form_pengajuan }}</p>
        </div>

        <!-- Teknisi -->
        <div>
            <Label for="id_user">Pilih Teknisi</Label>
            <select v-model="form.id_user" id="id_user" class="w-full border rounded p-2 mt-1">
                <option value="" disabled>Pilih Teknisi</option>
                <option v-for="u in user" :key="u.id" :value="u.id">
                    {{ u.nama }}
                </option>
            </select>
            <p v-if="form.errors.id_user" class="text-sm text-red-500 mt-1">{{ form.errors.id_user }}</p>
        </div>

        <!-- Tanggal Mulai & Selesai -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <Label for="tanggal_mulai">Tanggal Mulai</Label>
                <Input type="date" id="tanggal_mulai" v-model="form.tanggal_mulai" />
                <p v-if="form.errors.tanggal_mulai" class="text-sm text-red-500 mt-1">{{ form.errors.tanggal_mulai }}</p>
            </div>
            <div>
                <Label for="tanggal_selesai">Tanggal Selesai</Label>
                <Input type="date" id="tanggal_selesai" v-model="form.tanggal_selesai" />
                <p v-if="form.errors.tanggal_selesai" class="text-sm text-red-500 mt-1">{{ form.errors.tanggal_selesai }}</p>
            </div>
        </div>

        <!-- Jam Mulai & Selesai -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <Label for="jam_mulai">Jam Mulai</Label>
                <Input type="time" id="jam_mulai" v-model="form.jam_mulai" />
                <p v-if="form.errors.jam_mulai" class="text-sm text-red-500 mt-1">{{ form.errors.jam_mulai }}</p>
            </div>
            <div>
                <Label for="jam_selesai">Jam Selesai</Label>
                <Input type="time" id="jam_selesai" v-model="form.jam_selesai" />
                <p v-if="form.errors.jam_selesai" class="text-sm text-red-500 mt-1">{{ form.errors.jam_selesai }}</p>
            </div>
        </div>

        <!-- Submit -->
        <Button type="submit" :disabled="form.processing">
            Update Jadwal Pengujian
        </Button>
    </form>
</template>
