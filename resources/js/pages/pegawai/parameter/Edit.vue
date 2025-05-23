<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';

interface Parameter {
    id: number
    nama_parameter: string
    satuan: string
    harga: number
}

const props = defineProps<{
    parameter: Parameter
}>();

const form = useForm({
    nama_parameter: props.parameter.nama_parameter,
    satuan: props.parameter.satuan,
    harga: props.parameter.harga,
});

const submit = () => {
    form.put(`/pegawai/parameter/${props.parameter.id}/edit`);
};
</script>

<template>
    <div class="w-full lg:grid lg:min-h-[600px] lg:grid-cols-2 xl:min-h-[800px]">
        <div class="flex items-center justify-center py-12">
            <form @submit.prevent="submit" class="mx-auto grid w-[350px] gap-6">
                <div class="grid gap-2 text-center">
                    <h1 class="text-3xl font-bold">Edit Parameter</h1>
                </div>
                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="nama_parameter">Nama Parameter</Label>
                        <Input id="nama_parameter" v-model="form.nama_parameter" type="text" required />
                        <span v-if="form.errors.nama_parameter" class="text-sm text-red-600">
                            {{ form.errors.nama_parameter }}
                        </span>
                    </div>

                    <div class="grid gap-2">
                        <Label for="satuan">Satuan</Label>
                        <Input id="satuan" v-model="form.satuan" type="text" required />
                        <span v-if="form.errors.satuan" class="text-sm text-red-600">
                            {{ form.errors.satuan }}
                        </span>
                    </div>

                    <div class="grid gap-2">
                        <Label for="harga">Harga</Label>
                        <Input id="harga" v-model="form.harga" type="text" inputmode="numeric" min="0" required />
                        <span v-if="form.errors.harga" class="text-sm text-red-600">
                            {{ form.errors.harga }}
                        </span>
                    </div>

                    <Button type="submit" class="w-full" :disabled="form.processing"> Simpan Perubahan </Button>
                </div>
            </form>
        </div>
    </div>
</template>
