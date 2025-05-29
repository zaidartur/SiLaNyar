<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { ref } from 'vue'

interface Parameter {
    id: number
    kode_parameter: string
    nama_parameter: string
    satuan: string
    harga: number
}

const props = defineProps<{
    parameter: Parameter[]
}>()

const displayValue = ref('')

const form = useForm({
    nama: '',
    harga: '',
    parameter: (props.parameter || []).map(param => ({
        id: param.id,
        checked: false,
        baku_mutu: ''
    }))
})

const formatCurrency = (value) => {
    if (!value) return ''
    const num = parseInt(value.toString().replace(/[^\d]/g, ''), 10)
    if (isNaN(num)) return ''
    
    form.harga = num.toString()
    return 'Rp ' + num.toLocaleString('id-ID')
}

const handleInput = (e) => {
    const formatted = formatCurrency(e.target.value)
    displayValue.value = formatted
}

const formatOnBlur = () => {
    displayValue.value = formatCurrency(form.harga)
}

const submit = () => {
    const filterParam = form.parameter.filter(p => p.checked)

    if (filterParam.length === 0) {
        alert('Pilih minimal satu parameter!')
        return
    }

    form.parameter = filterParam
    form.post('/pegawai/kategori/store', {
        onSuccess: () => emit('close')
    })
}

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" @click.self="closeModal">
        <div class="w-full max-w-4xl mx-auto overflow-hidden rounded-2xl shadow-lg lg:grid lg:min-h-[600px] lg:grid-cols-3 bg-white">
            <!-- Left Side - Logo Section -->
            <div class="hidden bg-customDarkGreen lg:col-span-1 lg:flex lg:items-center lg:justify-center flex-col">
                <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="w-auto h-48 object-contain mx-auto" />
                <div class="text-center text-white mt-6">
                    <h2 class="text-2xl font-bold mb-2 border-b border-white pb-2">SiLanYar</h2>
                    <p class="text-sm">Sistem Laboratoruim Karanganyar</p>
                </div>
            </div>

            <!-- Right Side - Form Section -->
            <div class="flex items-center justify-center p-12 lg:col-span-2 bg-white">
                <form @submit.prevent="submit" class="mx-auto grid w-full mx-w-sm gap-6">
                    <div class="grid gap-2 text-center">
                        <h1 class="text-3xl font-bold">Tambah Kategori</h1>
                    </div>

                    <div class="grid gap-4">
                        <!-- Nama Kategori -->
                        <div class="grid gap-2">
                            <Label for="nama">Nama Kategori</Label>
                            <Input id="nama" v-model="form.nama" type="text" placeholder="Masukkan nama kategori" required />
                            <span v-if="form.errors.nama" class="text-sm text-red-600">
                                {{ form.errors.nama }}
                            </span>
                        </div>

                        <!-- Harga -->
                        <div class="grid gap-2">
                            <Label for="harga">Harga</Label>
                            <Input id="harga" v-model="displayValue" @input="handleInput" @blur="formatOnBlur"
                                type="text" placeholder="Contoh: 100.000" required />
                            <span v-if="form.errors.harga" class="text-sm text-red-600">
                                {{ form.errors.harga }}
                            </span>
                        </div>

                        <!-- Parameter List -->
                        <div class="grid gap-2">
                            <Label>Parameter dan Baku Mutu</Label>
                            <div class="space-y-2 max-h-60 overflow-y-auto p-4 border rounded">
                                <div v-for="(param, index) in form.parameter" :key="param.id" 
                                    class="flex items-center gap-4 p-2 hover:bg-gray-50">
                                    <input type="checkbox" v-model="param.checked" :id="'param-' + param.id"
                                        class="rounded border-gray-300" />
                                    <Label :for="'param-' + param.id" class="flex-1">
                                        {{ props.parameter[index].nama_parameter }}
                                    </Label>
                                    <Input v-model="param.baku_mutu" type="text" 
                                        :disabled="!param.checked"
                                        placeholder="Baku Mutu"
                                        class="w-48" />
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
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