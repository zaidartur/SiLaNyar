<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const form = useForm({
    nama_parameter: '',
    satuan: '',
    baku_mutu: '',
    harga: ''
})

const submit = () => {
    form.post('/pegawai/parameter/store')
}
</script>

<template>
    <div class="w-full lg:grid lg:min-h-[600px] lg:grid-cols-2 xl:min-h-[800px]">
        <div class="flex items-center justify-center py-12">
            <form @submit.prevent="submit" class="mx-auto grid w-[350px] gap-6">
                <div class="grid gap-2 text-center">
                    <h1 class="text-3xl font-bold">Form Parameter</h1>
                </div>
                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="nama_parameter">Nama Parameter</Label>
                        <Input id="nama_parameter" v-model="form.nama_parameter" type="text"
                            placeholder="Contoh: pH Air" required />
                        <span v-if="form.errors.nama_parameter" class="text-sm text-red-600">
                            {{ form.errors.nama_parameter }}
                        </span>
                    </div>

                    <div class="grid gap-2">
                        <Label for="satuan">Satuan</Label>
                        <Input id="satuan" v-model="form.satuan" type="text" placeholder="Contoh: mg/L" required />
                        <span v-if="form.errors.satuan" class="text-sm text-red-600">
                            {{ form.errors.satuan }}
                        </span>
                    </div>

                    <div class="grid gap-2">
                        <Label for="baku_mutu">Baku Mutu</Label>
                        <Input id="baku_mutu" v-model="form.baku_mutu" type="number" step="0.01" placeholder="Contoh: 6.5 - 8.5"
                            required />
                        <span v-if="form.errors.baku_mutu" class="text-sm text-red-600">
                            {{ form.errors.baku_mutu }}
                        </span>
                    </div>

                    <div class="grid gap-2">
                        <Label for="harga">Harga</Label>
                        <Input id="harga" v-model="form.harga" type="number" placeholder="Contoh: 100000" min="0"
                            required />
                        <span v-if="form.errors.harga" class="text-sm text-red-600">
                            {{ form.errors.harga }}
                        </span>
                    </div>

                    <Button type="submit" class="w-full" :disabled="form.processing">
                        Tambah
                    </Button>
                </div>
            </form>
        </div>
        <div class="hidden bg-muted lg:block">
            <img src="/placeholder.svg" alt="Image" width="1920" height="1080"
                class="h-full w-full object-cover dark:brightness-[0.2] dark:grayscale" />
        </div>
    </div>
</template>
