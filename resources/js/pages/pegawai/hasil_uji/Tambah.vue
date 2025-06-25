<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm, Head } from '@inertiajs/vue3';
import { watch, computed } from 'vue';

interface Parameter {
    id: number;
    nama: string;
    satuan: string;
    baku_mutu: string | null;
}

interface Pengujian {
    id: number;
    kode_pengujian: string;
    status: string;
    form_pengajuan: {
        kode_pengajuan: string;
        instansi: {
            nama: string;
        };
    };
}

const props = defineProps<{
    pengujianList: Pengujian[];
    pilihPengujian: Pengujian | null;
    parameter: Parameter[];
}>();

const form = useForm({
    id_pengujian: props.pilihPengujian?.id ?? null,
    hasil: props.parameter.map((param) => ({
        id_parameter: param.id,
        nilai: '',
        keterangan: '',
    })),
});

const pengujianSelesai = computed(() =>
    props.pengujianList.filter(item => item.status === 'selesai')
);

// const selectedPengujian = computed(() =>
//     props.pengujianList.find(p => p.id === form.id_pengujian)
// )

watch(
    () => form.id_pengujian,
    (newVal) => {
        if (newVal !== props.pilihPengujian?.id) {
            window.location.href = `?id_pengujian=${newVal}`; // reload halaman untuk load parameter baru
        }
    },
);

const submit = () => {
    form.post('/pegawai/hasiluji/store', {
        onSuccess: () => {
            window.location.href = '/pegawai/hasiluji';
        },
        onError: (errors) => {
            console.log('Validation errors:', errors);
        },
    });
};
</script>

<template>
    <Head title="Tambah Hasil Uji" />
    <div class="h-screen w-full bg-white lg:grid lg:grid-cols-3">
        <!-- Left Side - Logo Section -->
        <div class="hidden h-screen flex-col bg-customDarkGreen lg:col-span-1 lg:flex lg:items-center lg:justify-center">
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
                    <h1 class="text-3xl font-bold">Input Hasil Uji</h1>
                </div>

                <!-- Pilih Pengujian -->
                <div class="grid gap-2">
                    <Label for="id_pengujian">Pilih Pengujian</Label>
                    <select v-model="form.id_pengujian" id="id_pengujian" class="mt-1 w-full rounded border p-2">
                        <option :value="null" disabled>Pilih Pengujian</option>
                        <option v-for="item in pengujianSelesai" :key="item.id" :value="item.id">
                            {{ item.kode_pengujian }} - {{ item.form_pengajuan.instansi.nama }}
                        </option>
                    </select>
                    <span v-if="form.errors.id_pengujian" class="text-sm text-red-600">
                        {{ form.errors.id_pengujian }}
                    </span>
                </div>

                <!-- Parameter Hasil Uji -->
                <div v-if="parameter.length" class="grid gap-4">
                    <Label>Parameter dan Hasil Uji</Label>
                    <div v-for="(param, index) in parameter" :key="param.id" class="mb-2 flex flex-col gap-2 rounded border p-4">
                        <div class="flex flex-col gap-1">
                            <label :for="`nilai-${index}`" class="font-semibold"> {{ param.nama }} ({{ param.satuan }}) </label>
                            <Input :id="`nilai-${index}`" v-model="form.hasil[index].nilai" placeholder="Masukkan nilai" type="text" />
                            <span v-if="(form.errors as any)[`hasil.${index}.nilai`]" class="text-sm text-red-600">
                                {{ (form.errors as any)[`hasil.${index}.nilai`] }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label :for="`keterangan-${index}`" class="font-semibold">Keterangan</label>
                            <Input :id="`keterangan-${index}`" v-model="form.hasil[index].keterangan" placeholder="Opsional" type="text" />
                            <span v-if="(form.errors as any)[`hasil.${index}.keterangan`]" class="text-sm text-red-600">
                                {{ (form.errors as any)[`hasil.${index}.keterangan`] }}
                            </span>
                        </div>
                        <div v-if="param.baku_mutu">
                            <small class="text-gray-500">Baku Mutu: {{ param.baku_mutu }}</small>
                        </div>
                    </div>
                </div>

                <Button
                    type="submit"
                    :disabled="form.processing"
                    class="mb-8 w-full rounded bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700"
                >
                    Simpan Hasil Uji
                </Button>
            </form>
        </div>
    </div>
</template>
