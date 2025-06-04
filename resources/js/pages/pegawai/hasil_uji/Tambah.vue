<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

interface Parameter {
    id: number
    nama: string
    satuan: string
    baku_mutu: string | null
}

interface Pengujian {
    id: number
    kode_pengujian: string
    form_pengajuan: {
        kode_pengajuan: string
        instansi: {
            nama: string
        }
    }
}

const props = defineProps<{
    pengujianList: Pengujian[]
    pilihPengujian: Pengujian | null
    parameter: Parameter[]
}>()

const form = useForm({
    id_pengujian: props.pilihPengujian?.id ?? null,
    hasil: props.parameter.map(param => ({
        id_parameter: param.id,
        nilai: '',
        keterangan: '',
    })),
})

const selectedPengujian = computed(() =>
    props.pengujianList.find(p => p.id === form.id_pengujian)
)

watch(() => form.id_pengujian, (newVal) => {
    if (newVal !== props.pilihPengujian?.id) {
        window.location.href = `?id_pengujian=${newVal}` // reload halaman untuk load parameter baru
    }
})

const submit = () => {
    form.post('/pegawai/hasiluji/store', {
        onError: errors => {
            console.log('Validation errors:', errors)
        },
    })
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">

        <!-- Pilih Pengujian -->
        <div>
            <Label for="id_pengujian">Pilih Pengujian</Label>
            <select v-model="form.id_pengujian" id="id_pengujian" class="w-full border rounded p-2 mt-1">
                <option :value="null" disabled>Pilih Pengujian</option>
                <option v-for="item in pengujianList" :key="item.id" :value="item.id">
                    {{ item.kode_pengujian }} - {{ item.form_pengajuan.instansi.nama }}
                </option>
            </select>
            <p v-if="form.errors.id_pengujian" class="text-sm text-red-500 mt-1">{{ form.errors.id_pengujian }}</p>
        </div>

        <!-- Parameter Hasil Uji -->
        <div v-if="parameter.length">
            <h3 class="text-lg font-semibold">Input Hasil Parameter</h3>
            <div v-for="(param, index) in parameter" :key="param.id" class="border rounded p-4 mt-4 space-y-2">
                <div>
                    <Label :for="`nilai-${index}`">{{ param.nama }} ({{ param.satuan }})</Label>
                    <Input :id="`nilai-${index}`" v-model="form.hasil[index].nilai" placeholder="Masukkan nilai"
                        type="text" />
                </div>
                <div>
                    <Label :for="`keterangan-${index}`">Keterangan</Label>
                    <Input :id="`keterangan-${index}`" v-model="form.hasil[index].keterangan" placeholder="Opsional"
                        type="text" />
                </div>
                <div v-if="param.baku_mutu">
                    <small class="text-gray-500">Baku Mutu: {{ param.baku_mutu }}</small>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <Button type="submit" :disabled="form.processing" class="mt-6">
            Simpan Hasil Uji
        </Button>
    </form>
</template>
