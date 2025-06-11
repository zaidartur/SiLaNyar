<script setup lang="ts">
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface JenisCairan {
    id: number;
    nama: string;
    batas_minimum: number;
    batas_maksimum: number;
}
interface Parameter {
    id: number;
    nama_parameter: string;
}
interface SubKategori {
    id: number;
    nama: string;
    parameter: Parameter[];
}
interface Kategori {
    id: number;
    nama: string;
    parameter: Parameter[];
    subkategori: SubKategori[];
}
interface Instansi {
    id: number;
    nama: string;
}

const { props } = usePage();
const instansiList = props.instansi as Instansi[];
const jenisCairan = props.jenis_cairan as JenisCairan[];
const kategori = props.kategori as Kategori[];
const semuaParameter = props.parameter as Parameter[];

const step = ref(1);
const pengajuanBerhasil = ref(false);

const form = useForm({
    id_instansi: '',
    id_jenis_cairan: null,
    volume_sampel: null,
    metode_pengambilan: '',
    lokasi: '',
    waktu_pengambilan: null,
    id_kategori: null,
    parameter: [] as number[],
    keterangan: '',
});

// Otomatis lokasi jika "diantar"
watch(
    () => form.metode_pengambilan,
    (val) => {
        if (val === 'diantar') {
            form.lokasi = 'Jl. Lawu No.204, Tegalasri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57716 (DLH Kabupaten Karanganyar)';
        } else {
            form.lokasi = '';
        }
        form.parameter = [];
        form.id_kategori = null;
    },
);

function parameterIsInKategori(id: number): boolean {
    const kat = kategori.find((k) => k.id === form.id_kategori);
    if (!kat) return true;
    const allowedParamIds =
        kat.subkategori.length > 0 ? kat.subkategori.flatMap((s) => s.parameter.map((p) => p.id)) : kat.parameter.map((p) => p.id);
    return allowedParamIds.includes(id);
}

watch(
    () => form.id_kategori,
    (kategoriId) => {
        const kat = kategori.find((k) => k.id === kategoriId);
        if (!kat) return;
        const allowedParamIds =
            kat.subkategori.length > 0 ? kat.subkategori.flatMap((s) => s.parameter.map((p) => p.id)) : kat.parameter.map((p) => p.id);
        form.parameter = [...new Set(allowedParamIds)];
    },
);

function nextStep() {
    if (step.value < 4) step.value += 1;
}
function prevStep() {
    if (step.value > 1) step.value -= 1;
}
function submit() {
    form.post(route('customer.pengajuan.store'), {
        onSuccess: () => {
            pengajuanBerhasil.value = true;
            step.value = 3;
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
}
function getNamaJenisCairan() {
    return jenisCairan.find((j) => j.id === form.id_jenis_cairan)?.nama || '-';
}
function getNamaInstansi() {
    return instansiList.find((i) => i.id == form.id_instansi)?.nama || '-';
}
function getNamaKategori() {
    return kategori.find((k) => k.id === form.id_kategori)?.nama || '-';
}
// function getNamaParameter() {
//     return semuaParameter.filter(p => form.parameter.includes(p.id)).map(p => p.nama_parameter)
// }
</script>

<template>

    <Head title="Pengajuan Sampel" />
    <CustomerLayout>
        <!-- Stepper 4 langkah -->
        <div>
            <h1 class="mb-2 text-2xl font-bold text-customDarkGreen">Pengajuan Sampel</h1>
            <p class="mb-6 text-gray-600 border-b-2 border-green-700 inline-block w-fit">Ikuti langkah-langkah berikut
                untuk mengajukan sampel.</p>
        </div>
        <div class="flex p-6 mb-5">
            <ol class="mb-8 flex w-full items-center justify-center">
                <!-- Step 1 -->
                <li class="flex w-full items-center after:inline-block after:h-1 after:w-full after:border-4 after:border-b after:content-['']"
                    :class="[
                        step > 1 ? 'text-customDarkGreen after:border-customLightGreen' : 'text-gray-400 after:border-gray-100'
                    ]">
                    <div :class="[
                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-full',
                        step === 1 ? 'bg-customDarkGreen text-white' : step > 1 ? 'bg-customDarkGreen text-white' : 'bg-gray-100 text-gray-400',
                    ]">
                        <span class="text-2xl font-bold">1</span>
                    </div>
                    <span class="mt-2 text-xs font-semibold text-center">Detail Sample</span>
                </li>
                <!-- Step 2 -->
                <li class="flex w-full items-center after:inline-block after:h-1 after:w-full after:border-4 after:border-b after:content-['']"
                    :class="[
                        step > 2 ? 'text-customDarkGreen after:border-customLightGreen' : 'text-gray-400 after:border-gray-100'
                    ]">
                    <div :class="[
                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-full',
                        step === 2 ? 'bg-customDarkGreen text-white' : step > 2 ? 'bg-customDarkGreen text-white' : 'bg-gray-100 text-gray-400',
                    ]">
                        <span class="text-2xl font-bold">2</span>
                    </div>
                    <span class="mt-2 text-xs font-semibold text-center">Parameter Pengujian</span>
                </li>
                <!-- Step 3 -->
                <li class="flex w-full items-center after:inline-block after:h-1 after:w-full after:border-4 after:border-b after:content-['']"
                    :class="[
                        step > 3 ? 'text-customDarkGreen after:border-customLightGreen' : 'text-gray-400 after:border-gray-100'
                    ]">
                    <div :class="[
                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-full',
                        step === 3 ? 'bg-customDarkGreen text-white' : step > 3 ? 'bg-blue-100 text-customDarkGreen' : 'bg-gray-100 text-gray-400',
                    ]">
                        <span class="text-2xl font-bold">3</span>
                    </div>
                    <span class="mt-2 text-xs font-semibold text-center">Periksa & Serahkan</span>
                </li>
            </ol>
        </div>

        <!-- Step 1: Jenis Cairan, Volume, Instansi, Metode Pengambilan -->
        <form v-if="step === 1" @submit.prevent="nextStep" class="space-y-4">
            <div class="rounded-lg border border-gray-300 bg-gray-100 p-6 shadow-sm">
                <h3 class="mb-2 flex items-center gap-2 text-lg font-bold text-customDarkGreen">
                    <svg width="33" height="30" viewBox="0 0 33 30" fill="none" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect width="33" height="30" fill="url(#pattern0_1392_980)" />
                        <defs>
                            <pattern id="pattern0_1392_980" patternContentUnits="objectBoundingBox" width="1"
                                height="1">
                                <use xlink:href="#image0_1392_980"
                                    transform="matrix(0.00909091 0 0 0.01 0.0454545 0)" />
                            </pattern>
                            <image id="image0_1392_980" width="100" height="100" preserveAspectRatio="none"
                                xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE0UlEQVR4nO2dXWhcRRiGj1dSUO8Keq13hbM2W7ZkIYlNmuRkz3f2zF5sS7G9shYRf6r1BwuJbWkikuJugmiKWoPWv607YxCSemFF2ysNRUEsFfW24GUFqVU7MqcpxM0mu9vdOd/snO+FF/YiOXvmfebnm9mLcRwSiUQikUgkEolEIpFICVCwEGwBwU7a6GAh2OJ0k7xF704Q4fcgmLTRPmc/FivFTU63CDibxQ4NdEMR4YzTDfJFOAqc3cAODHSbsxsBZ+CYLK9S3AwivPK/nlQN5UNv7bTCfjWsARP+nqsU73WMlHTu8EX4eW1Pys70S3e8xwpnZ/vrjZSzqu2OacqJ8Mnalx2cH0EP0e2wB98dWbuecPaEY5JUGeiL8M/VLzlWCeSDR7ehB+h22KpN3idB7Si5FiwErmNqieuLUGams+jhuZqceTUbtdHIUrheids/N4QemqvZA3ND5pXC9Urc0Y98mZpIowfmanbq5bQc+cA3pxSuW+LyUKantqOH5cbk9OR2Q0rh9UrcWXtKXLdJZ2cG8EthqBaeqn2JoXlPuhP4AbkIVuV9bR5qG4BY4ubl1mP2lbhuC6WwKvNrS2G/WkhpB+KL8Ijuc6KBGKq0gZOD2s+7VFZWANnxjv4d/uCptdMMAVknhOH3x7QDGT49RkCa7oGcaT122Xp0W/QdNEJamBZ6S33agPSW+7TDsGoNAbXj/9DXU0ZP9ESnCQTEkFHSW67zmwaNkOZCyH2al+njmY7B6DmekbkzeQLS7tSVOtL+Aq+eoZ4V1+iwbg2BVfY+9mV6MtPWyIgbhtVAQB3PnMlHa0pqvIWj/YmbFZWa+jDe2WogsGLV07PlvmgvsR4ItYdRfxNXNZVoIHDLnEW77R2nhqNfKJXV55HTuVg2fQRE4AdMQAR+qARE4AdJQAwIDwgIfmBAQPBDAgKCHwwQkO7wnqXd8vDyAW3PT9bGULTn3Yu75NLVKfmdLMljPzxOQPBhTMplWY6sCwqNENE6DJ1QCIhovGZ88ccra2CshjJ+8TECgjFNLdfx19em5SNfPkxAbIQBNGWxNmHs7XhHoDVE1FszpjaE8c1femBYByQQBfnmb8/L6UtPa5umzl+flge+2qetDdYACVZg3AquVSgmwLAGSCAK8o1fX1gTYLNQTIFhBZBgHRjNQjEJhhVAXvz20Q3DjKD8dLDu/+5Z2iXPXm28gO8/p2cBt3aEvP7Lcw2hnLh00OiRYQ0QuA0opsKwBgi0AGXm50MNp6nzMU9TVgKBFZcvP9sQiokjw1og0AYUbBjWAoHbgGICDKuBgGCy1CSUC9dPGAHDeiDQBBSTYCQCCGwAxTQYiQECgsnXLj9jPIxEAYFVUEyFkTggIFi0W99/zkwYiQQChpuACHwIBETgB09ABH7YBETgB0xABH6oBETgB0lADAgPCAh+YEBA8EMCAoIfDBAQ/DAgQUcnh7EbCl1in7OXtAMBEe7Dbih0iXOc7dUOZLRauM8X4T/YjQXTzdnfsd0j4nP2NnqDhdn2OZtz4lL+s/zdNt91C+3DuFisFO9y4pS36N3jc/ZeIq5ZFU2CEOG/IMJ51WEdLAULwQPRpZKclbCv1QYsc1ZSl0t6nN2PBoJEIpFIJBKJRCKRSCSS03n9B+yy/yvpNyiBAAAAAElFTkSuQmCC" />
                        </defs>
                    </svg>
                    Informasi Dasar Sample
                </h3>
                <label class="mb-2 block font-semibold text-gray-600">Jenis Cairan</label>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    <div v-for="jenis in jenisCairan" :key="jenis.id" @click="form.id_jenis_cairan = jenis.id" :class="[
                        'cursor-pointer rounded-2xl border p-4 transition-all',
                        form.id_jenis_cairan === jenis.id
                            ? 'border-customDarkGreen bg-customDarkGreen text-white'
                            : 'border-gray-300 bg-white text-customDarkGreen hover:bg-customLightGreen',
                    ]">
                        <div
                            :class="['mb-1 text-2xl font-bold', form.id_jenis_cairan === jenis.id ? 'text-white' : 'text-customDarkGreen']">
                            {{ jenis.nama }}
                        </div>
                        <div :class="form.id_jenis_cairan === jenis.id ? 'text-white' : 'text-gray-500'"
                            class="text-xs">
                            Batas Minimum: {{ jenis.batas_minimum }}
                        </div>
                        <div :class="form.id_jenis_cairan === jenis.id ? 'text-white' : 'text-gray-500'"
                            class="text-xs">
                            Batas Maksimum: {{ jenis.batas_maksimum ?? '-'}}
                        </div>
                        <div v-if="form.id_jenis_cairan === jenis.id" class="mt-2 text-xs font-semibold text-blue-400">
                            Dipilih</div>
                    </div>
                </div>
                <!-- Hidden select for validation fallback -->
                <select v-model="form.id_jenis_cairan" class="hidden" required>
                    <option value="">Pilih Jenis Cairan</option>
                    <option v-for="jenis in jenisCairan" :key="jenis.id" :value="jenis.id">{{ jenis.nama }}</option>
                </select>
            </div>
            <div class="rounded-lg border border-gray-300 bg-gray-100 p-6 shadow-sm">
                <div class="grid grid-cols-1 gap-4">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-customDarkGreen">
                        <svg width="35" height="26" viewBox="0 0 35 26" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="35" height="26" fill="url(#pattern0_1393_1239)" />
                            <defs>
                                <pattern id="pattern0_1393_1239" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_1393_1239"
                                        transform="matrix(0.00742857 0 0 0.01 0.128571 0)" />
                                </pattern>
                                <image id="image0_1393_1239" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAB8klEQVR4nO2dsU0DARAEv9G9LkxMSOAGEJRABS9BLXRi9MFLhJbxaweYkT4De9i9JQHEsoiIiIj8Zeb14XL00/4afxVjISz2Qk5vT5fzx/Pdnu31XMgN7KFtIa6f73d7ttezkBuwEBjjQlhYCIxxISwsBMa4EBYWAmNcCAsLgTEuhIWFwBgXwsJCYIwLYWEhMIa0EH98uVgIjSEu5D//PHmIhSBkSqAyQMmUQGWAkimBygAlUwKVAUqmBCoDlEwJVAYomRKoDFAyJVAZoGRKoDJAyZRAZYCSKYHKACVTApUBSqYEKgOUTAlUBiiZEqgMUDIlUBmgZEqgMkDJlEBlgJIpgcoAJVMClQFKpgQqA5RMCVQGbZm8nB73jzvq2d6DnAFKJhbCLeR8x1/U+/7+LuTGQtY7HsT2WMgPF7JaCOtb1mohFkI4SpRMXIiF0I4SJRMXYiG0o0TJxIVYCO0oUTJxIRZCO0qUTFyIhdCOEiUTF2IhtKNEycSFWAjtKFEycSEWQjtKlExciIXQjhIlExdiIbSjRMnEhVgI7ShRMnEhFkI7SpRMXIiF0I4SJRMXYiG0o0TJxIVYCO0oUTJxIRZCO0qUTFyIhdCOEiUTF8L6hy4B/Z064p/a7J9w5HNtIXPQc20hrQxQMrEQERERWX45X3mFX7Fsubh8AAAAAElFTkSuQmCC" />
                            </defs>
                        </svg>
                        Informasi Volume dan Pengambilan
                    </h3>
                    <!-- Volume -->
                    <div>
                        <label class="mb-1 block font-semibold">Volume/Berat Sampel</label>
                        <div class="relative">
                            <input type="text" inputmode="numeric" v-model.number="form.volume_sampel"
                                class="w-full rounded border px-3 py-2" required />
                        </div>
                    </div>
                    <!-- Instansi -->
                    <div>
                        <label class="mb-1 block font-semibold">Instansi</label>
                        <select v-model="form.id_instansi" class="w-full rounded border px-3 py-2" required>
                            <option value="">Pilih Instansi</option>
                            <option v-for="ins in instansiList" :key="ins.id" :value="ins.id">{{ ins.nama }}</option>
                        </select>
                    </div>
                    <!-- Metode Pengambilan -->
                    <div>
                        <label class="mb-1 block font-semibold">Metode Pengambilan</label>
                        <div class="flex gap-2">
                            <button type="button" :class="[
                                'flex-1 rounded border px-3 py-2 font-semibold transition',
                                form.metode_pengambilan === 'diantar'
                                    ? 'border-customdarkbg-customDarkGreen bg-customDarkGreen text-white'
                                    : 'border-gray-300 bg-white text-customDarkGreen hover:bg-customLightGreen',
                            ]" @click="form.metode_pengambilan = 'diantar'">
                                Diantar
                            </button>
                            <button type="button" :class="[
                                'flex-1 rounded border px-3 py-2 font-semibold transition',
                                form.metode_pengambilan === 'diambil'
                                    ? 'border-customDarkGreen bg-customDarkGreen text-white'
                                    : 'border-gray-300 bg-white text-customDarkGreen hover:bg-customLightGreen',
                            ]" @click="form.metode_pengambilan = 'diambil'">
                                Diambil
                            </button>
                        </div>
                    </div>
                    <!-- Lokasi Pengambilan (jika diambil) -->
                    <div v-if="form.metode_pengambilan === 'diambil'" class="md:col-span-2 lg:col-span-1">
                        <label class="mb-1 block font-semibold">Lokasi Pengambilan</label>
                        <input type="text" v-model="form.lokasi" class="w-full rounded border px-3 py-2" required />
                    </div>

                    <div v-if="form.metode_pengambilan === 'diantar'"
                        class="mt-2 rounded-lg border border-orange-300 bg-yellow-50 p-3 text-orange-700">
                        <div class="font-semibold">Perhatian untuk Pengantaran Sampel</div>
                        <div>
                            Karena Anda memilih metode <b>"Diantar"</b>, sampel harus menggunakan wadah sejenis gelas
                            untuk
                            memastikan akurasi hasil uji dan mencegah kontaminasi.
                        </div>
                    </div>

                    <div v-if="form.metode_pengambilan === 'diantar'" class="rounded-lg border border-gray-300 bg-white p-3">
                        <div class="font-semibold mb-1">Persyaratan Wadah</div>
                        <ul class="list-disc pl-5 text-sm text-gray-800">
                            <li>Wadah harus dari bahan kaca/gelas</li>
                            <li>Bersih dan steril (jika memungkinkan)</li>
                            <li>Tutup rapat untuk mencegah kontaminasi</li>
                            <li>Tidak retak atau rusak</li>
                            <li>Kapasitas minimal sesuai volume sampel</li>
                        </ul>
                    </div>

                    <!-- Waktu Pengambilan (jika diantar) -->
                    <div v-if="form.metode_pengambilan === 'diantar'" class="md:col-span-2 lg:col-span-1">
                        <label class="mb-1 block font-semibold">Waktu Pengambilan</label>
                        <input type="date" v-model="form.waktu_pengambilan" class="w-full rounded border px-3 py-2"
                            required />
                    </div>
                </div>
            </div>
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Lanjut</button>
        </form>

        <!-- Step 2: Kategori & Parameter -->
        <form v-else-if="step === 2" @submit.prevent="nextStep" class="space-y-6">
            <div class="space-y-4 rounded-lg border border-gray-300 bg-gray-100 p-6 shadow-sm">
                <!-- Kategori -->
                <div>
                    <label class="mb-1 flex items-center gap-2 text-xl font-semibold text-customDarkGreen">
                        <!-- SVG Icon -->
                        <svg width="33" height="30" viewBox="0 0 33 30" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="33" height="30" fill="url(#pattern0_1392_980)" />
                            <defs>
                                <pattern id="pattern0_1392_980" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_1392_980"
                                        transform="matrix(0.00909091 0 0 0.01 0.0454545 0)" />
                                </pattern>
                                <image id="image0_1392_980" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE0UlEQVR4nO2dXWhcRRiGj1dSUO8Keq13hbM2W7ZkIYlNmuRkz3f2zF5sS7G9shYRf6r1BwuJbWkikuJugmiKWoPWv607YxCSemFF2ysNRUEsFfW24GUFqVU7MqcpxM0mu9vdOd/snO+FF/YiOXvmfebnm9mLcRwSiUQikUgkEolEIpFICVCwEGwBwU7a6GAh2OJ0k7xF704Q4fcgmLTRPmc/FivFTU63CDibxQ4NdEMR4YzTDfJFOAqc3cAODHSbsxsBZ+CYLK9S3AwivPK/nlQN5UNv7bTCfjWsARP+nqsU73WMlHTu8EX4eW1Pys70S3e8xwpnZ/vrjZSzqu2OacqJ8Mnalx2cH0EP0e2wB98dWbuecPaEY5JUGeiL8M/VLzlWCeSDR7ehB+h22KpN3idB7Si5FiwErmNqieuLUGams+jhuZqceTUbtdHIUrheids/N4QemqvZA3ND5pXC9Urc0Y98mZpIowfmanbq5bQc+cA3pxSuW+LyUKantqOH5cbk9OR2Q0rh9UrcWXtKXLdJZ2cG8EthqBaeqn2JoXlPuhP4AbkIVuV9bR5qG4BY4ubl1mP2lbhuC6WwKvNrS2G/WkhpB+KL8Ijuc6KBGKq0gZOD2s+7VFZWANnxjv4d/uCptdMMAVknhOH3x7QDGT49RkCa7oGcaT122Xp0W/QdNEJamBZ6S33agPSW+7TDsGoNAbXj/9DXU0ZP9ESnCQTEkFHSW67zmwaNkOZCyH2al+njmY7B6DmekbkzeQLS7tSVOtL+Aq+eoZ4V1+iwbg2BVfY+9mV6MtPWyIgbhtVAQB3PnMlHa0pqvIWj/YmbFZWa+jDe2WogsGLV07PlvmgvsR4ItYdRfxNXNZVoIHDLnEW77R2nhqNfKJXV55HTuVg2fQRE4AdMQAR+qARE4AdJQAwIDwgIfmBAQPBDAgKCHwwQkO7wnqXd8vDyAW3PT9bGULTn3Yu75NLVKfmdLMljPzxOQPBhTMplWY6sCwqNENE6DJ1QCIhovGZ88ccra2CshjJ+8TECgjFNLdfx19em5SNfPkxAbIQBNGWxNmHs7XhHoDVE1FszpjaE8c1femBYByQQBfnmb8/L6UtPa5umzl+flge+2qetDdYACVZg3AquVSgmwLAGSCAK8o1fX1gTYLNQTIFhBZBgHRjNQjEJhhVAXvz20Q3DjKD8dLDu/+5Z2iXPXm28gO8/p2cBt3aEvP7Lcw2hnLh00OiRYQ0QuA0opsKwBgi0AGXm50MNp6nzMU9TVgKBFZcvP9sQiokjw1og0AYUbBjWAoHbgGICDKuBgGCy1CSUC9dPGAHDeiDQBBSTYCQCCGwAxTQYiQECgsnXLj9jPIxEAYFVUEyFkTggIFi0W99/zkwYiQQChpuACHwIBETgB09ABH7YBETgB0xABH6oBETgB0lADAgPCAh+YEBA8EMCAoIfDBAQ/DAgQUcnh7EbCl1in7OXtAMBEe7Dbih0iXOc7dUOZLRauM8X4T/YjQXTzdnfsd0j4nP2NnqDhdn2OZtz4lL+s/zdNt91C+3DuFisFO9y4pS36N3jc/ZeIq5ZFU2CEOG/IMJ51WEdLAULwQPRpZKclbCv1QYsc1ZSl0t6nN2PBoJEIpFIJBKJRCKRSCSS03n9B+yy/yvpNyiBAAAAAElFTkSuQmCC" />
                            </defs>
                        </svg>
                        Pilih Kategori Pengujian
                    </label>
                    <select v-model="form.id_kategori" class="w-full rounded border px-3 py-2" required>
                        <option value="">Pilih Kategori</option>
                        <option v-for="kat in kategori" :key="kat.id" :value="kat.id">{{ kat.nama }}</option>
                    </select>
                </div>
                <!-- Parameter -->
                <div>
                    <label class="mb-1 block text-xl font-semibold text-customDarkGreen">Pilih Parameter</label>
                    <div class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
                        <div v-for="param in semuaParameter" :key="param.id"
                            class="flex items-center rounded p-2 transition hover:bg-blue-50">
                            <input type="checkbox" :value="param.id" v-model="form.parameter"
                                :disabled="form.id_kategori && !parameterIsInKategori(param.id)" class="mr-2" />
                            <span :class="{
                                'text-gray-400': form.id_kategori && !parameterIsInKategori(param.id),
                                }">
                                {{ param.nama_parameter }}
                                <span v-if="form.id_kategori && !parameterIsInKategori(param.id)"> (tidak termasuk
                                    kategori) </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Ringkasan Pengujian dan Harga -->
            <div>
                <div
                    class="flex items-center gap-2 rounded-t-lg bg-green-700 px-4 py-2 text-base font-semibold text-white">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.5 20C8.32843 20 9 19.3284 9 18.5C9 17.6716 8.32843 17 7.5 17C6.67157 17 6 17.6716 6 18.5C6 19.3284 6.67157 20 7.5 20Z"
                            fill="white" />
                        <path
                            d="M16.5 20C17.3284 20 18 19.3284 18 18.5C18 17.6716 17.3284 17 16.5 17C15.6716 17 15 17.6716 15 18.5C15 19.3284 15.6716 20 16.5 20Z"
                            fill="white" />
                        <path d="M3 5H5L5.6 8M5.6 8L7 15H17L19 8H5.6Z" fill="white" />
                        <path d="M3 5H5L5.6 8M5.6 8L7 15H17L19 8H5.6Z" stroke="white" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Ringkasan Pengujian
                </div>
                <table
                    class="w-full border-separate border-spacing-0 overflow-hidden rounded-b-lg border border-green-700">
                    <thead>
                        <tr>
                            <th
                                class="w-1/3 border-b border-green-700 bg-green-100 px-4 py-3 text-center font-semibold">
                                Nama Parameter</th>
                            <th
                                class="w-1/3 border-b border-green-700 bg-green-100 px-4 py-3 text-center font-semibold">
                                Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="param in semuaParameter.filter((p) => form.parameter.includes(p.id))" :key="param.id"
                            class="transition hover:bg-green-50">
                            <td class="border-green-2000 border-b px-4 py-3 text-left">
                                {{ param.nama_parameter }}
                            </td>
                            <td class="border-b border-green-200 px-4 py-3 text-right">Rp {{
                                param.harga?.toLocaleString('id-ID') || '0' }}</td>
                        </tr>
                        <tr v-if="form.parameter.length === 0">
                            <td colspan="2" class="border-b border-green-200 py-4 text-center text-gray-400">Belum ada
                                parameter dipilih</td>
                        </tr>
                    </tbody>
                </table>
                <div
                    class="mt-2 flex flex-col rounded-lg border border-gray-300 bg-gray-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                    <span class="text-sm text-gray-600">Jumlah Parameter:<br /><b>{{ form.parameter.length }}
                            item</b></span>
                    <span class="mt-2 text-lg font-semibold text-gray-700 sm:mt-0">
                        Biaya:<br />
                        <span class="text-2xl font-bold text-green-700">
                            Rp
                            {{
                            semuaParameter
                            .filter((p) => form.parameter.includes(p.id))
                            .reduce((sum, p) => sum + (p.harga || 0), 0)
                            .toLocaleString('id-ID')
                            }}
                        </span>
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="button" @click="prevStep" class="rounded bg-gray-300 px-4 py-2">Kembali</button>
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Lanjut</button>
            </div>
        </form>

        <!-- Step 3: Periksa & Serahkan -->
        <form v-else-if="step === 3" @submit.prevent="submit" class="space-y-4">
            <div class="pt-4">
                <h2 class="mb-4 flex items-center gap-2 text-lg font-bold text-customDarkGreen">
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#059669" />
                    </svg>
                    Periksa Data Pengajuan
                </h2>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <table class="mb-4 w-full">
                        <tbody>
                            <tr>
                                <td class="w-1/3 py-2 pr-4 font-semibold text-gray-600">Jenis Cairan</td>
                                <td class="py-2 text-gray-800">{{ getNamaJenisCairan() }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 pr-4 font-semibold text-gray-600">Volume/Berat Sampel</td>
                                <td class="py-2 text-gray-800">{{ form.volume_sampel }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 pr-4 font-semibold text-gray-600">Instansi</td>
                                <td class="py-2 text-gray-800">{{ getNamaInstansi() }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 pr-4 font-semibold text-gray-600">Metode Pengambilan</td>
                                <td class="py-2 text-gray-800">{{ form.metode_pengambilan }}</td>
                            </tr>
                            <tr v-if="form.metode_pengambilan === 'diambil'">
                                <td class="py-2 pr-4 font-semibold text-gray-600">Lokasi Pengambilan</td>
                                <td class="py-2 text-gray-800">{{ form.lokasi }}</td>
                            </tr>
                            <tr v-if="form.metode_pengambilan === 'diantar'">
                                <td class="py-2 pr-4 font-semibold text-gray-600">Waktu Pengambilan</td>
                                <td class="py-2 text-gray-800">{{ form.waktu_pengambilan }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 pr-4 font-semibold text-gray-600">Kategori</td>
                                <td class="py-2 text-gray-800">{{ getNamaKategori() }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 pr-4 align-top font-semibold text-gray-600">Parameter</td>
                                <td class="py-2 text-gray-800">
                                    <ul class="ml-5 list-disc">
                                        <li v-for="param in semuaParameter.filter((p) => form.parameter.includes(p.id))"
                                            :key="param.id">
                                            {{ param.nama_parameter }}
                                            <span class="ml-2 text-gray-500">Rp {{ param.harga?.toLocaleString('id-ID')
                                                || '0' }}</span>
                                        </li>
                                    </ul>
                                    <div v-if="form.parameter.length === 0" class="text-gray-400">Belum ada parameter
                                        dipilih</div>
                                </td>
                            </tr>
                            <tr v-if="form.keterangan">
                                <td class="py-2 pr-4 font-semibold text-gray-600">Keterangan</td>
                                <td class="py-2 text-gray-800">{{ form.keterangan }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div
                        class="flex flex-col rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <span class="text-sm text-gray-600">Jumlah Parameter: <b>{{ form.parameter.length }}
                                item</b></span>
                        <span class="mt-2 text-lg font-semibold text-gray-700 sm:mt-0">
                            Total Biaya:
                            <span class="ml-2 text-2xl font-bold text-green-700">
                                Rp
                                {{
                                semuaParameter
                                .filter((p) => form.parameter.includes(p.id))
                                .reduce((sum, p) => sum + (p.harga || 0), 0)
                                .toLocaleString('id-ID')
                                }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="button" @click="prevStep" class="rounded bg-gray-300 px-4 py-2">Kembali</button>
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Kirim Pengajuan</button>
            </div>
        </form>
    </CustomerLayout>
</template>
