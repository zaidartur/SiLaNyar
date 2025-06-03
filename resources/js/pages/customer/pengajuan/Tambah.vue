<script setup lang="ts">
import { watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

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
    id: number
    nama: string
}

const { props } = usePage();

const instansiList = props.instansi as Instansi[];
const jenisCairan = props.jenis_cairan as JenisCairan[];
const kategori = props.kategori as Kategori[];
const semuaParameter = props.parameter as Parameter[];

const form = useForm({
    id_instansi: '',
    id_jenis_cairan: null,
    volume_sampel: null,
    metode_pengambilan: '',
    lokasi: '',
    waktu_pengambilan: null,
    id_kategori: null,
    parameter: [] as number[],
    keterangan: ''
});

// Isi otomatis lokasi jika "diantar"
watch(() => form.metode_pengambilan, (val) => {
    if (val === 'diantar') {
        form.lokasi = 'Jl. Lawu No.204, Tegalasri, Bejen, Kec. Karanganyar, Kabupaten Karanganyar, Jawa Tengah 57716 (DLH Kabupaten Karanganyar)';
    } else {
        form.lokasi = '';
    }
    form.parameter = [];
    form.id_kategori = null;
});

function parameterIsInKategori(id: number): boolean {
    const kat = kategori.find(k => k.id === form.id_kategori);
    if (!kat) return true; 

    const allowedParamIds = kat.subkategori.length > 0
        ? kat.subkategori.flatMap(s => s.parameter.map(p => p.id))
        : kat.parameter.map(p => p.id);

    return allowedParamIds.includes(id);
}

watch(() => form.id_kategori, (kategoriId) => {
    const kat = kategori.find(k => k.id === kategoriId);
    if (!kat) return;

    const allowedParamIds = kat.subkategori.length > 0
        ? kat.subkategori.flatMap(s => s.parameter.map(p => p.id))
        : kat.parameter.map(p => p.id);

    form.parameter = [...new Set(allowedParamIds)];
});


function submit() {
    form.post(route('customer.pengajuan.store'), {
        onError: (errors) => {
            console.error(errors);
        }
    });
}

</script>

<template>
    <div class="p-6 space-y-4">
        <h1 class="text-2xl font-bold">Form Pengajuan Uji Laboratorium</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Instansi -->
            <div>
                <label>Instansi</label>
                <select v-model="form.id_instansi" class="w-full border rounded">
                    <option value="">Pilih Instansi</option>
                    <option v-for="ins in instansiList" :key="ins.id" :value="ins.id">
                        {{ ins.nama }}
                    </option>
                </select>
            </div>
            <!-- Jenis Cairan -->
            <div>
                <label>Jenis Cairan</label>
                <select v-model="form.id_jenis_cairan" class="w-full border rounded">
                    <option value="">Pilih Jenis Cairan</option>
                    <option v-for="jenis in jenisCairan" :key="jenis.id" :value="jenis.id">{{ jenis.nama }}</option>
                </select>
            </div>

            <!-- Volume -->
            <div>
                <label>Volume Sampel (ml)</label>
                <input type="number" v-model.number="form.volume_sampel" class="w-full border rounded" />
            </div>

            <!-- Metode Pengambilan -->
            <div>
                <label>Metode Pengambilan</label>
                <select v-model="form.metode_pengambilan" class="w-full border rounded">
                    <option value="diantar">Diantar</option>
                    <option value="diambil">Diambil</option>
                </select>
            </div>

            <!-- Lokasi -->
            <div v-if="form.metode_pengambilan === 'diambil'">
                <label>Lokasi Pengambilan</label>
                <input type="text" v-model="form.lokasi" class="w-full border rounded" />
            </div>

            <!-- Waktu Pengambilan -->
            <div v-if="form.metode_pengambilan === 'diantar'">
                <label>Waktu Pengambilan</label>
                <input type="date" v-model="form.waktu_pengambilan" class="w-full border rounded" />
            </div>

            <!-- Kategori -->
            <div>
                <label>Kategori</label>
                <select v-model="form.id_kategori" class="w-full border rounded">
                    <option value="">Pilih Kategori</option>
                    <option v-for="kat in kategori" :key="kat.id" :value="kat.id">{{ kat.nama }}</option>
                </select>
            </div>

            <div>
                <label>Pilih Parameter</label>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div v-for="param in semuaParameter" :key="param.id" class="flex items-center">
                        <input type="checkbox" :value="param.id" v-model="form.parameter"
                            :disabled="form.id_kategori && !parameterIsInKategori(param.id)" />
                        <span class="ml-2"
                            :class="{ 'text-gray-400': form.id_kategori && !parameterIsInKategori(param.id) }">
                            {{ param.nama_parameter }}
                            <span v-if="form.id_kategori && !parameterIsInKategori(param.id)">
                                (tidak termasuk kategori)
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label>Keterangan (Opsional)</label>
                <textarea v-model="form.keterangan" class="w-full border rounded" rows="3"></textarea>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</template>

<style scoped>
label {
    font-weight: 600;
    display: block;
    margin-bottom: 0.5rem;
}
</style>
