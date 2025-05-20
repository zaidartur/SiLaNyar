<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
import { toTypedSchema } from '@vee-validate/zod'
import { Check, Circle, Dot } from 'lucide-vue-next'
import { h, ref } from 'vue'
import * as z from 'zod'
import { Button } from '@/components/ui/button'
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import { Stepper, StepperDescription, StepperItem, StepperSeparator, StepperTitle, StepperTrigger } from '@/components/ui/stepper'
import { toast } from '@/components/ui/toast'
import { Checkbox } from '@/components/ui/checkbox'
import { Head } from '@inertiajs/vue3'

const formSchema = [
    z.object({
        jenisCairan: z.string().min(1, "Pilih jenis cairan"),
        volume: z.string().min(1, "Volume harus diisi"),
        instansi: z.string().min(1, "Pilih instansi"),
        metodePengambilan: z.string().min(1, "Pilih metode pengambilan"),
        tanggalPengantaran: z.string().optional().refine((val) => {
            if (values.metodePengambilan === 'diantar') {
                return !!val
            }
            return true
        }, "Tanggal pengantaran harus diisi"),
        lokasiPengambilan: z.string().optional().refine((val) => {
            if (values.metodePengambilan === 'dijemput') {
                return !!val
            }
            return true
        }, "Lokasi pengambilan harus diisi"),
    }),
    z.object({
        // Step 2 validation schema
        password: z.string().min(2).max(50),
        confirmPassword: z.string(),
    }).refine(
        (values) => {
            return values.password === values.confirmPassword
        },
        {
            message: 'Passwords must match!',
            path: ['confirmPassword'],
        },
    ),
    z.object({
        // Step 3 validation schema
        favoriteDrink: z.union([z.literal('coffee'), z.literal('tea'), z.literal('soda')]),
    }),
    z.object({
        // Step 4 validation schema
        favoriteDrink: z.union([z.literal('coffee'), z.literal('tea'), z.literal('soda')]),
    }),
]

const stepIndex = ref(1)
const steps = [
    {
        step: 1,
        title: 'Detail Sample',
    },
    {
        step: 2,
        title: 'Parameter Pengujian',
    },
    {
        step: 3,
        title: 'Periksa dan Serahkan',
    },
    {
        step: 4,
        title: 'Pembayaran',
    },
]

function onSubmit(values: any) {
    toast({
        title: 'You submitted the following values:',
        description: h('pre', { class: 'mt-2 w-[340px] rounded-md bg-slate-950 p-4' }, h('code', { class: 'text-white' }, JSON.stringify(values, null, 2))),
    })
}
</script>

<template>
    <Head title="Pengajuan" />
    <CustomerLayout>
        <Form v-slot="{ meta, values, validate }" as="" keep-values
            :validation-schema="toTypedSchema(formSchema[stepIndex - 1])">
            <Stepper v-slot="{ isNextDisabled, isPrevDisabled, nextStep, prevStep }" v-model="stepIndex"
                class="block w-full">
                <form @submit="(e) => {
                e.preventDefault()
                validate()

                if (stepIndex === steps.length && meta.valid) {
                    onSubmit(values)
                }
            }">
                    <div class="flex w-full flex-start gap-2">
                        <StepperItem v-for="step in steps" :key="step.step" v-slot="{ state }"
                            class="relative flex w-full flex-col items-center justify-center" :step="step.step">
                            <StepperSeparator v-if="step.step !== steps[steps.length - 1].step" :class="[
                                'absolute left-[calc(50%+20px)] right-[calc(-50%+10px)] top-5 block h-0.5 shrink-0 rounded-full',
                                state === 'completed' ? 'bg-customDarkGreen' : 'bg-gray-200',
                                state === 'active' ? 'bg-gray-200' : '',
                            ]" />

                            <StepperTrigger as-child>
                                <Button :class="[
                                    'z-10 rounded-full shrink-0',
                                    state === 'completed' ? 'bg-customDarkGreen text-white' : '',
                                    state === 'active' ? 'bg-customDarkGreen text-white ring-2 ring-customDarkGreen ring-offset-2' : '',
                                    state === 'inactive' ? 'bg-gray-200 text-gray-400' : ''
                                ]" size="icon" :disabled="state !== 'completed' && !meta.valid">
                                    <Check v-if="state === 'completed'" class="size-5" />
                                    <Circle v-if="state === 'active'" />
                                    <Dot v-if="state === 'inactive'" />
                                </Button>
                            </StepperTrigger>

                            <div class="mt-5 flex flex-col items-center text-center">
                                <StepperTitle :class="[state === 'active' && 'text-primary']"
                                    class="text-sm font-semibold transition lg:text-base">
                                    {{ step.title }}
                                </StepperTitle>
                                <StepperDescription :class="[state === 'active' && 'text-primary']"
                                    class="sr-only text-xs text-muted-foreground transition md:not-sr-only lg:text-sm">
                                    {{ step.description }}
                                </StepperDescription>
                            </div>
                        </StepperItem>
                    </div>

                    <div class="flex flex-col gap-4 mt-4">
                        <template v-if="stepIndex === 1">
                            <div class="space-y-6">
                                <!-- Informasi Dasar Sample -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-green-700 flex items-center gap-2">
                                        <svg width="33" height="30" viewBox="0 0 33 30" fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <rect width="33" height="30" fill="url(#pattern0_1392_980)" />
                                            <defs>
                                                <pattern id="pattern0_1392_980" patternContentUnits="objectBoundingBox"
                                                    width="1" height="1">
                                                    <use xlink:href="#image0_1392_980"
                                                        transform="matrix(0.00909091 0 0 0.01 0.0454545 0)" />
                                                </pattern>
                                                <image id="image0_1392_980" width="100" height="100"
                                                    preserveAspectRatio="none"
                                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE0UlEQVR4nO2dXWhcRRiGj1dSUO8Keq13hbM2W7ZkIYlNmuRkz3f2zF5sS7G9shYRf6r1BwuJbWkikuJugmiKWoPWv607YxCSemFF2ysNRUEsFfW24GUFqVU7MqcpxM0mu9vdOd/snO+FF/YiOXvmfebnm9mLcRwSiUQikUgkEolEIpFICVCwEGwBwU7a6GAh2OJ0k7xF704Q4fcgmLTRPmc/FivFTU63CDibxQ4NdEMR4YzTDfJFOAqc3cAODHSbsxsBZ+CYLK9S3AwivPK/nlQN5UNv7bTCfjWsARP+nqsU73WMlHTu8EX4eW1Pys70S3e8xwpnZ/vrjZSzqu2OacqJ8Mnalx2cH0EP0e2wB98dWbuecPaEY5JUGeiL8M/VLzlWCeSDR7ehB+h22KpN3idB7Si5FiwErmNqieuLUGams+jhuZqceTUbtdHIUrheids/N4QemqvZA3ND5pXC9Urc0Y98mZpIowfmanbq5bQc+cA3pxSuW+LyUKantqOH5cbk9OR2Q0rh9UrcWXtKXLdJZ2cG8EthqBaeqn2JoXlPuhP4AbkIVuV9bR5qG4BY4ubl1mP2lbhuC6WwKvNrS2G/WkhpB+KL8Ijuc6KBGKq0gZOD2s+7VFZWANnxjv4d/uCptdMMAVknhOH3x7QDGT49RkCa7oGcaT122Xp0W/QdNEJamBZ6S33agPSW+7TDsGoNAbXj/9DXU0ZP9ESnCQTEkFHSW67zmwaNkOZCyH2al+njmY7B6DmekbkzeQLS7tSVOtL+Aq+eoZ4V1+iwbg2BVfY+9mV6MtPWyIgbhtVAQB3PnMlHa0pqvIWj/YmbFZWa+jDe2WogsGLV07PlvmgvsR4ItYdRfxNXNZVoIHDLnEW77R2nhqNfKJXV55HTuVg2fQRE4AdMQAR+qARE4AdJQAwIDwgIfmBAQPBDAgKCHwwQkO7wnqXd8vDyAW3PT9bGULTn3Yu75NLVKfmdLMljPzxOQPBhTMplWY6sCwqNENE6DJ1QCIhovGZ88ccra2CshjJ+8TECgjFNLdfx19em5SNfPkxAbIQBNGWxNmHs7XhHoDVE1FszpjaE8c1femBYByQQBfnmb8/L6UtPa5umzl+flge+2qetDdYACVZg3AquVSgmwLAGSCAK8o1fX1gTYLNQTIFhBZBgHRjNQjEJhhVAXvz20Q3DjKD8dLDu/+5Z2iXPXm28gO8/p2cBt3aEvP7Lcw2hnLh00OiRYQ0QuA0opsKwBgi0AGXm50MNp6nzMU9TVgKBFZcvP9sQiokjw1og0AYUbBjWAoHbgGICDKuBgGCy1CSUC9dPGAHDeiDQBBSTYCQCCGwAxTQYiQECgsnXLj9jPIxEAYFVUEyFkTggIFi0W99/zkwYiQQChpuACHwIBETgB09ABH7YBETgB0xABH6oBETgB0lADAgPCAh+YEBA8EMCAoIfDBAQ/DAgQUcnh7EbCl1in7OXtAMBEe7Dbih0iXOc7dUOZLRauM8X4T/YjQXTzdnfsd0j4nP2NnqDhdn2OZtz4lL+s/zdNt91C+3DuFisFO9y4pS36N3jc/ZeIq5ZFU2CEOG/IMJ51WEdLAULwQPRpZKclbCv1QYsc1ZSl0t6nN2PBoJEIpFIJBKJRCKRSCSS03n9B+yy/yvpNyiBAAAAAElFTkSuQmCC" />
                                            </defs>
                                        </svg>
                                        Informasi Dasar Sample
                                    </h3>

                                    <FormField v-slot="{ componentField }" name="jenisCairan">
                                        <FormItem>
                                            <FormLabel>Jenis Cairan</FormLabel>
                                            <div class="grid grid-cols-3 gap-4">
                                                <div v-for="(jenis, index) in ['Universal', 'Lemak', 'Vecal coli']"
                                                    :key="index" :class="[
                                                        'border rounded-lg p-4 cursor-pointer transition-colors',
                                                        componentField.modelValue === jenis.toLowerCase() ? 'border-customDarkGreen bg-customLightGreen' : 'hover:border-customDarkGreen'
                                                    ]" @click="componentField.onChange(jenis.toLowerCase())">
                                                    <div class="flex flex-col items-center gap-2">
                                                        <svg v-if="jenis === 'Universal'" width="59" height="56"
                                                            viewBox="0 0 59 56" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <ellipse cx="29.5" cy="28" rx="29.5" ry="28"
                                                                fill="#F5F5F5" />
                                                            <rect x="9" y="12" width="42" height="33"
                                                                fill="url(#pattern0_1433_1546)" fill-opacity="0.78" />
                                                            <defs>
                                                                <pattern id="pattern0_1433_1546"
                                                                    patternContentUnits="objectBoundingBox" width="1"
                                                                    height="1">
                                                                    <use xlink:href="#image0_1433_1546"
                                                                        transform="matrix(0.00785714 0 0 0.01 0.107143 0)" />
                                                                </pattern>
                                                                <image id="image0_1433_1546" width="100" height="100"
                                                                    preserveAspectRatio="none"
                                                                    xlink:href="data:image/png;base64,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" />
                                                            </defs>
                                                        </svg>
                                                        <svg v-if="jenis === 'Lemak'" width="59" height="56"
                                                            viewBox="0 0 59 56" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <ellipse cx="29.5" cy="28" rx="29.5" ry="28"
                                                                fill="#F5F5F5" />
                                                            <rect x="5" y="7" width="50" height="42"
                                                                fill="url(#pattern0_1433_1547)" />
                                                            <defs>
                                                                <pattern id="pattern0_1433_1547"
                                                                    patternContentUnits="objectBoundingBox" width="1"
                                                                    height="1">
                                                                    <use xlink:href="#image0_1433_1547"
                                                                        transform="matrix(0.0084 0 0 0.01 0.08 0)" />
                                                                </pattern>
                                                                <image id="image0_1433_1547" width="100" height="100"
                                                                    preserveAspectRatio="none"
                                                                    xlink:href="data:image/png;base64,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" />
                                                            </defs>
                                                        </svg>
                                                        <svg v-if="jenis === 'Vecal coli'" width="59" height="56"
                                                            viewBox="0 0 59 56" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <ellipse cx="29.5" cy="28" rx="29.5" ry="28"
                                                                fill="#F5F5F5" />
                                                            <rect x="3" y="9" width="54" height="37"
                                                                fill="url(#pattern0_1433_1548)" />
                                                            <defs>
                                                                <pattern id="pattern0_1433_1548"
                                                                    patternContentUnits="objectBoundingBox" width="1"
                                                                    height="1">
                                                                    <use xlink:href="#image0_1433_1548"
                                                                        transform="matrix(0.00685185 0 0 0.01 0.157407 0)" />
                                                                </pattern>
                                                                <image id="image0_1433_1548" width="100" height="100"
                                                                    preserveAspectRatio="none"
                                                                    xlink:href="data:image/png;base64,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" />
                                                            </defs>
                                                        </svg>
                                                        <span class="text-sm">{{ jenis }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>
                                </div>

                                <!-- Informasi Volume dan Pengambilan -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-700 flex items-center gap-2">
                                        <svg width="35" height="26" viewBox="0 0 35 26" fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <rect width="35" height="26" fill="url(#pattern0_1393_1239)" />
                                            <defs>
                                                <pattern id="pattern0_1393_1239" patternContentUnits="objectBoundingBox"
                                                    width="1" height="1">
                                                    <use xlink:href="#image0_1393_1239"
                                                        transform="matrix(0.00742857 0 0 0.01 0.128571 0)" />
                                                </pattern>
                                                <image id="image0_1393_1239" width="100" height="100"
                                                    preserveAspectRatio="none"
                                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB8klEQVR4nO2dsU0DARAEv9G9LkxMSOAGEJRABS9BLXRi9MFLhJbxaweYkT4De9i9JQHEsoiIiIj8Zeb14XL00/4afxVjISz2Qk5vT5fzx/Pdnu31XMgN7KFtIa6f73d7ttezkBuwEBjjQlhYCIxxISwsBMa4EBYWAmNcCAsLgTEuhIWFwBgXwsJCYIwLYWEhMIa0EH98uVgIjSEu5D//PHmIhSBkSqAyQMmUQGWAkimBygAlUwKVAUqmBCoDlEwJVAYomRKoDFAyJVAZoGRKoDJAyZRAZYCSKYHKACVTApUBSqYEKgOUTAlUBiiZEqgMUDIlUBmgZEqgMkDJlEBlgJIpgcoAJVMClQFKpgQqA5RMCVQGbZm8nB73jzvq2d6DnAFKJhbCLeR8x1/U+/7+LuTGQtY7HsT2WMgPF7JaCOtb1mohFkI4SpRMXIiF0I4SJRMXYiG0o0TJxIVYCO0oUTJxIRZCO0qUTFyIhdCOEiUTF2IhtKNEycSFWAjtKFEycSEWQjtKlExciIXQjhIlExdiIbSjRMnEhVgI7ShRMnEhFkI7SpRMXIiF0I4SJRMXYiG0o0TJxIVYCO0oUTJxIRZCO0qUTFyIhdCOEiUTF8L6hy4B/Z064p/a7J9w5HNtIXPQc20hrQxQMrEQERERWX45X3mFX7Fsubh8AAAAAElFTkSuQmCC" />
                                            </defs>
                                        </svg>
                                        Informasi Volume dan Pengambilan
                                    </h3>

                                    <!-- Volume/Berat Sample -->
                                    <FormField v-slot="{ componentField }" name="volume">
                                        <FormItem>
                                            <FormLabel>Volume/Berat Sample</FormLabel>
                                            <FormControl>
                                                <div class="relative">
                                                    <Input type="text" v-bind="componentField" placeholder="Min : 2,5 L"
                                                        class="pl-10" />
                                                    <!-- Icon/gambar placeholder -->
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <rect width="21" height="21"
                                                                fill="url(#pattern0_1393_1242)" />
                                                            <defs>
                                                                <pattern id="pattern0_1393_1242"
                                                                    patternContentUnits="objectBoundingBox" width="1"
                                                                    height="1">
                                                                    <use xlink:href="#image0_1393_1242"
                                                                        transform="scale(0.01)" />
                                                                </pattern>
                                                                <image id="image0_1393_1242" width="100" height="100"
                                                                    preserveAspectRatio="none"
                                                                    xlink:href="data:image/png;base64,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" />
                                                            </defs>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>

                                    <!-- Instansi -->
                                    <FormField v-slot="{ componentField }" name="instansi">
                                        <FormItem>
                                            <FormLabel>Instansi</FormLabel>
                                            <Select v-bind="componentField">
                                                <FormControl>
                                                    <SelectTrigger>
                                                        <SelectValue placeholder="Pilih instansi" />
                                                    </SelectTrigger>
                                                </FormControl>
                                                <SelectContent>
                                                    <SelectGroup>
                                                        <SelectItem value="perguruan-tinggi">
                                                            Perguruan Tinggi
                                                        </SelectItem>
                                                        <SelectItem value="industri">
                                                            Industri
                                                        </SelectItem>
                                                        <SelectItem value="umum">
                                                            Umum
                                                        </SelectItem>
                                                        <SelectItem value="internal">
                                                            Internal
                                                        </SelectItem>
                                                    </SelectGroup>
                                                </SelectContent>
                                            </Select>
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>

                                    <!-- Metode Pengambilan -->
                                    <FormField v-slot="{ componentField }" name="metodePengambilan">
                                        <FormItem>
                                            <FormLabel>Metode pengambilan</FormLabel>
                                            <div class="grid grid-cols-2 gap-4">
                                                <Button type="button" variant="outline" :class="[
                                                    componentField.modelValue === 'diantar'
                                                        ? 'bg-customLightGreen border-customDarkGreen'
                                                        : 'hover:border-customLightGreen'
                                                ]" @click="componentField.onChange('diantar')">
                                                    Diantar
                                                </Button>
                                                <Button type="button" variant="outline" :class="[
                                                    componentField.modelValue === 'dijemput'
                                                        ? 'bg-customLightGreen border-customDarkGreen'
                                                        : 'hover:border-customDarkGreen'
                                                ]" @click="componentField.onChange('dijemput')">
                                                    Dijemput
                                                </Button>
                                            </div>
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>

                                    <!-- Tanggal Pengantaran (muncul jika memilih diantar) -->
                                    <FormField v-if="values.metodePengambilan === 'diantar'" v-slot="{ componentField }"
                                        name="tanggalPengantaran">
                                        <FormItem>
                                            <FormLabel>Tanggal Pengantaran</FormLabel>
                                            <FormControl>
                                                <Input type="date" v-bind="componentField"
                                                    :min="new Date().toISOString().split('T')[0]" />
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>

                                    <!-- Lokasi Pengambilan (muncul jika memilih dijemput) -->
                                    <FormField v-if="values.metodePengambilan === 'dijemput'"
                                        v-slot="{ componentField }" name="lokasiPengambilan">
                                        <FormItem>
                                            <FormLabel>Lokasi Pengambilan Sample</FormLabel>
                                            <FormControl>
                                                <Input type="text" v-bind="componentField"
                                                    placeholder="Masukan alamat lengkap pengambilan sample" />
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>
                                </div>
                            </div>
                        </template>

                        <template v-if="stepIndex === 2">
                            <div class="space-y-6">
                                <!-- Pilih Kategori Pengujian -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2">
                                        <CheckCircle class="size-5 text-customDarkGreen" />
                                        <svg width="33" height="30" viewBox="0 0 33 30" fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <rect width="33" height="30" fill="url(#pattern0_1392_980)" />
                                            <defs>
                                                <pattern id="pattern0_1392_980" patternContentUnits="objectBoundingBox"
                                                    width="1" height="1">
                                                    <use xlink:href="#image0_1392_980"
                                                        transform="matrix(0.00909091 0 0 0.01 0.0454545 0)" />
                                                </pattern>
                                                <image id="image0_1392_980" width="100" height="100"
                                                    preserveAspectRatio="none"
                                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE0UlEQVR4nO2dXWhcRRiGj1dSUO8Keq13hbM2W7ZkIYlNmuRkz3f2zF5sS7G9shYRf6r1BwuJbWkikuJugmiKWoPWv607YxCSemFF2ysNRUEsFfW24GUFqVU7MqcpxM0mu9vdOd/snO+FF/YiOXvmfebnm9mLcRwSiUQikUgkEolEIpFICVCwEGwBwU7a6GAh2OJ0k7xF704Q4fcgmLTRPmc/FivFTU63CDibxQ4NdEMR4YzTDfJFOAqc3cAODHSbsxsBZ+CYLK9S3AwivPK/nlQN5UNv7bTCfjWsARP+nqsU73WMlHTu8EX4eW1Pys70S3e8xwpnZ/vrjZSzqu2OacqJ8Mnalx2cH0EP0e2wB98dWbuecPaEY5JUGeiL8M/VLzlWCeSDR7ehB+h22KpN3idB7Si5FiwErmNqieuLUGams+jhuZqceTUbtdHIUrheids/N4QemqvZA3ND5pXC9Urc0Y98mZpIowfmanbq5bQc+cA3pxSuW+LyUKantqOH5cbk9OR2Q0rh9UrcWXtKXLdJZ2cG8EthqBaeqn2JoXlPuhP4AbkIVuV9bR5qG4BY4ubl1mP2lbhuC6WwKvNrS2G/WkhpB+KL8Ijuc6KBGKq0gZOD2s+7VFZWANnxjv4d/uCptdMMAVknhOH3x7QDGT49RkCa7oGcaT122Xp0W/QdNEJamBZ6S33agPSW+7TDsGoNAbXj/9DXU0ZP9ESnCQTEkFHSW67zmwaNkOZCyH2al+njmY7B6DmekbkzeQLS7tSVOtL+Aq+eoZ4V1+iwbg2BVfY+9mV6MtPWyIgbhtVAQB3PnMlHa0pqvIWj/YmbFZWa+jDe2WogsGLV07PlvmgvsR4ItYdRfxNXNZVoIHDLnEW77R2nhqNfKJXV55HTuVg2fQRE4AdMQAR+qARE4AdJQAwIDwgIfmBAQPBDAgKCHwwQkO7wnqXd8vDyAW3PT9bGULTn3Yu75NLVKfmdLMljPzxOQPBhTMplWY6sCwqNENE6DJ1QCIhovGZ88ccra2CshjJ+8TECgjFNLdfx19em5SNfPkxAbIQBNGWxNmHs7XhHoDVE1FszpjaE8c1femBYByQQBfnmb8/L6UtPa5umzl+flge+2qetDdYACVZg3AquVSgmwLAGSCAK8o1fX1gTYLNQTIFhBZBgHRjNQjEJhhVAXvz20Q3DjKD8dLDu/+5Z2iXPXm28gO8/p2cBt3aEvP7Lcw2hnLh00OiRYQ0QuA0opsKwBgi0AGXm50MNp6nzMU9TVgKBFZcvP9sQiokjw1og0AYUbBjWAoHbgGICDKuBgGCy1CSUC9dPGAHDeiDQBBSTYCQCCGwAxTQYiQECgsnXLj9jPIxEAYFVUEyFkTggIFi0W99/zkwYiQQChpuACHwIBETgB09ABH7YBETgB0xABH6oBETgB0lADAgPCAh+YEBA8EMCAoIfDBAQ/DAgQUcnh7EbCl1in7OXtAMBEe7Dbih0iXOc7dUOZLRauM8X4T/YjQXTzdnfsd0j4nP2NnqDhdn2OZtz4lL+s/zdNt91C+3DuFisFO9y4pS36N3jc/ZeIq5ZFU2CEOG/IMJ51WEdLAULwQPRpZKclbCv1QYsc1ZSl0t6nN2PBoJEIpFIJBKJRCKRSCSS03n9B+yy/yvpNyiBAAAAAElFTkSuQmCC" />
                                            </defs>
                                        </svg>
                                        <h3 class="text-lg font-medium text-customDarkGreen">Pilih Kategori Pengujian
                                        </h3>
                                    </div>
                                    <Select>
                                        <SelectTrigger>
                                            <SelectValue placeholder="Semua Kategori" />
                                        </SelectTrigger>
                                    </Select>
                                </div>

                                <!-- Parameter Fisika -->
                                <div class="space-y-4">
                                    <h3 class="font-medium text-green-700">Parameter Fisika</h3>
                                    <div class="grid grid-cols-3 gap-4">
                                        <FormField v-slot="{ componentField }" name="parameterFisika.suhu">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">Suhu</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterFisika.ph">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">Ph</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterFisika.dhl">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">DHL</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterFisika.tss">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">TSS</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterFisika.tds">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">TDS</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterFisika.kekeruhan">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">Kekeruhan</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterFisika.salinitas">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">Salinitas</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterFisika.warna">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">Warna</FormLabel>
                                            </FormItem>
                                        </FormField>
                                    </div>
                                </div>

                                <!-- Parameter Kimia -->
                                <div class="space-y-4">
                                    <h3 class="font-medium text-green-700">Parameter Kimia</h3>
                                    <div class="grid grid-cols-3 gap-4">
                                        <FormField v-slot="{ componentField }" name="parameterKimia.cod">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">COD</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterKimia.bod">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">BOD</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterKimia.phosphate">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">Phosphate</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterKimia.nitrat">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">Nitrat</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterKimia.nitrit">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">Nitrit</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterKimia.mbas">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">MBAS Detergent</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterKimia.minyakLemak">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">Minyak Lemak</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterKimia.fenol">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">Fenol</FormLabel>
                                            </FormItem>
                                        </FormField>

                                        <FormField v-slot="{ componentField }" name="parameterKimia.nh3n">
                                            <FormItem class="flex items-center space-x-3 space-y-0">
                                                <FormControl>
                                                    <Checkbox v-bind="componentField" />
                                                </FormControl>
                                                <FormLabel class="font-normal">NH3-N</FormLabel>
                                            </FormItem>
                                        </FormField>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template v-if="stepIndex === 3">
                            <FormField v-slot="{ componentField }" name="favoriteDrink">
                                <FormItem>
                                    <FormLabel>Drink</FormLabel>

                                    <Select v-bind="componentField">
                                        <FormControl>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a drink" />
                                            </SelectTrigger>
                                        </FormControl>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem value="coffee">
                                                    Coffe
                                                </SelectItem>
                                                <SelectItem value="tea">
                                                    Tea
                                                </SelectItem>
                                                <SelectItem value="soda">
                                                    Soda
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                        </template>
                        <template v-if="stepIndex === 4">
                            <FormField v-slot="{ componentField }" name="favoriteDrink">
                                <FormItem>
                                    <FormLabel>Drink</FormLabel>

                                    <Select v-bind="componentField">
                                        <FormControl>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a drink" />
                                            </SelectTrigger>
                                        </FormControl>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem value="coffee">
                                                    Coffe
                                                </SelectItem>
                                                <SelectItem value="tea">
                                                    Tea
                                                </SelectItem>
                                                <SelectItem value="soda">
                                                    Soda
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                        </template>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <Button :disabled="isPrevDisabled" variant="outline" size="sm" @click="prevStep()">
                            Back
                        </Button>
                        <div class="flex items-center gap-3">
                            <Button v-if="stepIndex !== 4" :type="meta.valid ? 'button' : 'submit'"
                                :disabled="isNextDisabled" size="sm" @click="meta.valid && nextStep()">
                                Next
                            </Button>
                            <Button v-if="stepIndex === 4" size="sm" type="submit">
                                Submit
                            </Button>
                        </div>
                    </div>
                </form>
            </Stepper>
        </Form>
    </CustomerLayout>
</template>