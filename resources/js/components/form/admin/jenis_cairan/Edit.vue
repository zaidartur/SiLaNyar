<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const props = defineProps<{
    jenis_cairan: {
        id: number,
        nama: string,
        batas_minimum: number,
        batas_maksimum: '',
    }
}>()

const emit = defineEmits(['close'])

const form = useForm({
    nama: props.jenis_cairan?.nama ?? '',
    batas_minimum: props.jenis_cairan?.batas_minimum ?? '',
    batas_maksimum: props.jenis_cairan?.batas_maksimum ?? ''
})

const submit = () => {
    form.put(`/pegawai/jenis-cairan/${props.jenis_cairan.id}/edit`, {
        onSuccess: () => emit('close')
    })
}

const closeModal = () => emit('close')
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
        @click.self="closeModal">
        <div
            class="w-full max-w-4xl mx-auto overflow-hidden rounded-2xl shadow-lg lg:grid lg:min-h-[500px] lg:grid-cols-3 bg-white">
            <!-- Sidebar -->
            <div class="hidden bg-customDarkGreen lg:col-span-1 lg:flex lg:items-center lg:justify-center flex-col">
                <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="w-auto h-48 object-contain mx-auto" />
                <div class="text-center text-white mt-6">
                    <h2 class="text-2xl font-bold mb-2 border-b border-white pb-2">SiLanYar</h2>
                    <p class="text-sm">Sistem Laboratoruim Karanganyar</p>
                </div>
            </div>
            <!-- Form -->
            <div class="flex items-center justify-center p-12 lg:col-span-2 bg-white">
                <form @submit.prevent="submit" class="mx-auto grid w-full max-w-sm gap-6">
                    <div class="grid gap-2 text-center">
                        <h1 class="text-3xl font-bold">Edit Jenis Cairan</h1>
                    </div>
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="nama">Nama Jenis Cairan</Label>
                            <Input id="nama" v-model="form.nama" type="text" required />
                            <span v-if="form.errors.nama" class="text-sm text-red-600">
                                {{ form.errors.nama }}
                            </span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="batas_minimum">Batas Minimum</Label>
                            <Input id="batas_minimum" v-model="form.batas_minimum" type="number" min="0" required />
                            <span v-if="form.errors.batas_minimum" class="text-sm text-red-600">
                                {{ form.errors.batas_minimum }}
                            </span>
                        </div>
                        <div class="grid gap-2">
                            <Label for="batas_maksimum">Batas Maksimum</Label>
                            <Input id="batas_maksimum" v-model="form.batas_maksimum" type="number" min="0" />
                            <span v-if="form.errors.batas_maksimum" class="text-sm text-red-600">
                                {{ form.errors.batas_maksimum }}
                            </span>
                        </div>
                        <div class="flex justify-end mt-4">
                            <Button type="submit" class="bg-customDarkGreen hover:bg-green-600 w-32"
                                :disabled="form.processing">
                                Simpan
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>