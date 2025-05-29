<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { ref } from 'vue'

const form = useForm({
    nama_parameter: '',
    satuan: '',
    harga: ''
})

const displayValue = ref('')

const formatCurrency = (value) => {
    if (!value) return ''
    const num = parseInt(value.toString().replace(/[^\d]/g, ''), 10)
    if (isNaN(num)) {
        return ''
    }
    // Update both display and form value
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

// Initialize display value if form has initial value
if (form.harga) {
    displayValue.value = formatCurrency(form.harga)
}

const submit = () => {
    form.post('/pegawai/parameter/store', {
        onSuccess: () => {
            emit('close')
        }
    })
}

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
        @click.self="closeModal">
        <div class="w-full max-w-4xl mx-auto overflow-hidden rounded-2xl shadow-lg lg:grid lg:min-h-[600px] lg:grid-cols-3 bg-white">
            <div class="hidden bg-customDarkGreen lg:col-span-1 lg:flex lg:items-center lg:justify-center flex-col">
                <img src="/assets/assetsadmin/logodlh.png" alt="Logo DLH" class="w-auto h-48 object-contain mx-auto" />
                <div class="text-center text-white mt-6">
                    <h2 class="text-2xl font-bold mb-2 border-b border-white pb-2">SiLanYar</h2>
                    <p class="text-sm">Sistem Laboratoruim Karanganyar</p>
                </div>
            </div>
            <div class="flex items-center justify-center p-12 lg:col-span-2 bg-white">
                <form @submit.prevent="submit" class="mx-auto grid w-full mx-w-sm gap-6">
                    <div class="grid gap-2 text-center">
                        <h1 class="text-3xl font-bold">Tambah Parameter</h1>
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
                            <Label for="harga">Harga</Label>
                            <Input id="harga" v-model="displayValue" @input="handleInput"
                                @blur="formatOnBlur" type="text" placeholder="Contoh: 100.000" min="0" required />
                            <span v-if="form.errors.harga" class="text-sm text-red-600">
                                {{ form.errors.harga }}
                            </span>
                        </div>

                        <div class="flex justify-end mt-4">
                            <Button type="submit" class="bg-customDarkGreen hover:bg-green-600 w-32"
                                :disabled="form.processing">
                                Tambah
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
