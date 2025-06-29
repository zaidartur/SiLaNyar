<script setup lang="ts">
/* eslint-disable */
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface JenisCairan {
    id: number;
    nama: string;
}

interface Parameter {
    id: number;
    nama_parameter: string;
    satuan: string;
    harga: number;
}

interface Kategori {
    id: number;
    nama: string;
    harga: number;
    parameter: Parameter[];
    subkategori: any[];
}

interface Pengajuan {
    id: number;
    status_pengajuan: string;
    metode_pengambilan: string;
    volume_sampel: string;
    lokasi: string;
    jenis_cairan: JenisCairan;
}

const form = useForm({
    metode_pembayaran: '',
    bukti_pembayaran: null as File | null,
});

const metode = ref(form.metode_pembayaran);
const buktiPembayaran = ref<File | null>(null);
const previewUrl = ref<string | null>(null);
const syarat = ref(false);
const error = ref('');
const loading = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);

watch(buktiPembayaran, (file) => {
    if (file && file.type.startsWith('image/')) {
        previewUrl.value = URL.createObjectURL(file);
    } else {
        previewUrl.value = null;
    }
});

function handleFileChange(e: Event) {
    const files = (e.target as HTMLInputElement).files;
    if (files && files.length > 0) {
        buktiPembayaran.value = files[0];
        form.bukti_pembayaran = files[0];
    }
}

function handleDrop(e: DragEvent) {
    e.preventDefault();
    if (e.dataTransfer && e.dataTransfer.files.length > 0) {
        buktiPembayaran.value = e.dataTransfer.files[0];
        form.bukti_pembayaran = e.dataTransfer.files[0];
    }
}

function handleDragOver(e: DragEvent) {
    e.preventDefault();
}

function submitPembayaran() {
    error.value = '';
    if (!syarat.value) {
        error.value = 'Anda harus menyetujui syarat dan ketentuan.';
        return;
    }
    if (metode.value === 'transfer' && !buktiPembayaran.value) {
        error.value = 'Silakan upload bukti transfer.';
        return;
    }
    loading.value = true;
    form.post(route('customer.pembayaran.process', props.pengajuan.id), {
        forceFormData: true,
        onFinish: () => (loading.value = false),
        onError: (err) => {
            error.value = Object.values(err).join(', ');
        },
    });
}

const props = defineProps<{
    pengajuan: Pengajuan;
    pembayaran: { status_pembayaran: string; total_biaya: number; metode_pembayaran: string } | null;
    metodePembayaran: string[];
    detailPembayaran: { kategori: Kategori; parameter: Parameter[] };
    pengajuanBerhasil?: boolean;
}>();
</script>

<template>

    <Head title="Detail Pembayaran" />
    <CustomerLayout>
        <div class="mx-auto max-w-2xl space-y-6 py-6">
            <!-- Notifikasi Pengajuan -->
            <div v-if="pengajuanBerhasil" class="rounded border border-green-300 bg-green-100 p-4">
                <b>Pengajuan berhasil dikirim!</b>
            </div>

            <!-- Ringkasan Pengajuan -->
            <div class="rounded border-l-4 border-green-600 bg-gray-100 p-4 shadow">
                <div class="mb-2 flex items-center">
                    <span class="mr-2 text-green-600">
                        <svg width="30" height="34" viewBox="0 0 30 34" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="30" height="34" fill="url(#pattern0_1709_2397)" />
                            <defs>
                                <pattern id="pattern0_1709_2397" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_1709_2397"
                                        transform="matrix(0.01 0 0 0.00882353 0 0.0588235)" />
                                </pattern>
                                <image id="image0_1709_2397" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE20lEQVR4nO2cTUxcVRzFLwsXWqNl01bdNGLREj6MH2kwMdFQg6XQFpjXNKJNqyZV48IYN9VYNWmiiYuJCxUTh0at0Q1qZqht2lis0FCYR4EKlsK8h8akmGAHaRk+5uuYN0hhgJkOdph7eXNOcjZzuZv/b/73f16Yd4WgKIqiKJXV3rRBeD0VQm/SbOsOd63QjxUJIEcoLd39htDdQaF7kB12nxZd368VSqrTXSq87qj8Inkya6/nY6GkdPc70oujS7EplJTX7VSgOJDQIaNCSRGIYiIQxUQgiolAFBOBZFYY0vbB1HQYDv9SHjf2TPmNOqjk0b49iD5z/5Iea61ecs+wby/cF1/DA82fQBw7AnHyK4i279RKWfA59sPUsOrcVwPsylvaLZVJ944Yz2L9yXoIjwuiqQGi7QeFgBiOzmwDAlPDq/rhGSCWm79RCog/G4E4e96cA3LqqEpAtFHpxTUJhEB62CFgh9z0keVAeKDiuuevRQYr49ZmHTWqeWSt1AyxCjzSLK57/tqVltvi1mY9eeFRAsl6IHX5wAsF9k9Zq6ZDGsuA0xVAzX32BqK0+/4D8tZDc585SwlEKpC6fKBn19xn/bXAiwXsEGlAGssWf24dXWdtemRZ0daaFcvxVG9pZoAY/3PNTkM9FWdsqJs3ZwIx5UNY9UCiRg2Cvz2xLEcGq6QX27ZA7GwngWjSIRCIOVOEa948XPVuTNlTv24hkJX8Nv595pZlJbXxzk0EQiCe7BnqE91FCHQVpOzpvsfZIbKHLDjUGXsFn0M0ZcznEFM+BPsA+boKOFieug+VA68UyfO5KpsDqd8OPF+Wul/emvhXhZmw7f+FSyCZE4G42CHgkcUOAWdIHoc6Z0geUxZjr4SUFTrxEoKfHUjdnx8A3i1M6Oh7hQi/vTmhI0n2Wg5+kI+pDzcmdPTcNns/h4z9+S0uD/+VsocvDwH1uQkd+uIu+N8XCR04siHp/vGGdUn3hzufIhACSZPYIS4eWSEeWewQcKjncqhzhgimLMbeNIspy8WUFWLKYoeAKSuXKYszRDBlMWWlWUxZLqasEFMWOwRMWblMWZwhgimLKSvNYspyMWWFmLLYIWDKymXKWqkZMvn74dhvs1L11T++BI6XJHTkxIMING5K6ODx4qT7p38sTro/0mv3F3ZsaCeBaNIhEIgpv/AEokCxQSDpLdZ072O4pt+L4MUnl1yPDu7ERFcxxtrvxlj7PZjoLkHUN++6WAJJL5CJ7pJ5VzXlx62FL5Xjyi+3LrrSyd+yBuGBG7+GsLyh7vYzZZnxQCwHugpjRbTuc/S33J7wni3/2Tti3ZM2ILqng0BMDaH+rRhtvTOu2P+0rV/UGdZFZ9ZtdQs7JTKwPT1AOtzPEYg5U7CIbwf8rWsSdsNs11gOnN8ctxbqv/ELOx/1HAyJpoZozKeOTseOpzl7hdezN+MwVH8wDF96Gv4FnTLyc04MwMK/DZwvwEhzTspAYGhOoaJUBoJY4RyxG+asuTLZ83DS4ygyUInJC48gMriDQKSDM5cLmh0C6RAIRJNfeAJRoNirHIgpvTimFB8SKgqG49Ps6w5HBGbtFqGiMLRzLUztJ+lFMjNlRxCG9rpQWYDIgW93IYzdtTAdmm09VLsNvup1sutNURRFUSKJ/gUIHyHKfd8UPgAAAABJRU5ErkJggg==" />
                            </defs>
                        </svg>
                    </span>
                    <h2 class="text-lg font-bold text-green-700">Ringkasan Pengajuan</h2>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <div class="text-gray-500">Jenis cairan</div>
                        <div class="font-semibold">{{ pengajuan.jenis_cairan.nama }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Volume/Berat Sample</div>
                        <div class="font-semibold">{{ pengajuan.volume_sampel }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Metode Pengambilan</div>
                        <div class="font-semibold">{{ pengajuan.metode_pengambilan }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Lokasi Pengambilan</div>
                        <div class="font-semibold">{{ pengajuan.lokasi }}</div>
                    </div>
                </div>
            </div>

            <!-- Parameter Pengujian -->
            <div class="rounded border-l-4 border-green-600 bg-gray-100 p-4 shadow">
                <div class="mb-2 flex items-center">
                    <span class="mr-2 text-green-600">
                        <svg width="30" height="34" viewBox="0 0 30 34" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="30" height="34" fill="url(#pattern0_1709_2397)" />
                            <defs>
                                <pattern id="pattern0_1709_2397" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_1709_2397"
                                        transform="matrix(0.01 0 0 0.00882353 0 0.0588235)" />
                                </pattern>
                                <image id="image0_1709_2397" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE20lEQVR4nO2cTUxcVRzFLwsXWqNl01bdNGLREj6MH2kwMdFQg6XQFpjXNKJNqyZV48IYN9VYNWmiiYuJCxUTh0at0Q1qZqht2lis0FCYR4EKlsK8h8akmGAHaRk+5uuYN0hhgJkOdph7eXNOcjZzuZv/b/73f16Yd4WgKIqiKJXV3rRBeD0VQm/SbOsOd63QjxUJIEcoLd39htDdQaF7kB12nxZd368VSqrTXSq87qj8Inkya6/nY6GkdPc70oujS7EplJTX7VSgOJDQIaNCSRGIYiIQxUQgiolAFBOBZFYY0vbB1HQYDv9SHjf2TPmNOqjk0b49iD5z/5Iea61ecs+wby/cF1/DA82fQBw7AnHyK4i279RKWfA59sPUsOrcVwPsylvaLZVJ944Yz2L9yXoIjwuiqQGi7QeFgBiOzmwDAlPDq/rhGSCWm79RCog/G4E4e96cA3LqqEpAtFHpxTUJhEB62CFgh9z0keVAeKDiuuevRQYr49ZmHTWqeWSt1AyxCjzSLK57/tqVltvi1mY9eeFRAsl6IHX5wAsF9k9Zq6ZDGsuA0xVAzX32BqK0+/4D8tZDc585SwlEKpC6fKBn19xn/bXAiwXsEGlAGssWf24dXWdtemRZ0daaFcvxVG9pZoAY/3PNTkM9FWdsqJs3ZwIx5UNY9UCiRg2Cvz2xLEcGq6QX27ZA7GwngWjSIRCIOVOEa948XPVuTNlTv24hkJX8Nv595pZlJbXxzk0EQiCe7BnqE91FCHQVpOzpvsfZIbKHLDjUGXsFn0M0ZcznEFM+BPsA+boKOFieug+VA68UyfO5KpsDqd8OPF+Wul/emvhXhZmw7f+FSyCZE4G42CHgkcUOAWdIHoc6Z0geUxZjr4SUFTrxEoKfHUjdnx8A3i1M6Oh7hQi/vTmhI0n2Wg5+kI+pDzcmdPTcNns/h4z9+S0uD/+VsocvDwH1uQkd+uIu+N8XCR04siHp/vGGdUn3hzufIhACSZPYIS4eWSEeWewQcKjncqhzhgimLMbeNIspy8WUFWLKYoeAKSuXKYszRDBlMWWlWUxZLqasEFMWOwRMWblMWZwhgimLKSvNYspyMWWFmLLYIWDKymXKWqkZMvn74dhvs1L11T++BI6XJHTkxIMING5K6ODx4qT7p38sTro/0mv3F3ZsaCeBaNIhEIgpv/AEokCxQSDpLdZ072O4pt+L4MUnl1yPDu7ERFcxxtrvxlj7PZjoLkHUN++6WAJJL5CJ7pJ5VzXlx62FL5Xjyi+3LrrSyd+yBuGBG7+GsLyh7vYzZZnxQCwHugpjRbTuc/S33J7wni3/2Tti3ZM2ILqng0BMDaH+rRhtvTOu2P+0rV/UGdZFZ9ZtdQs7JTKwPT1AOtzPEYg5U7CIbwf8rWsSdsNs11gOnN8ctxbqv/ELOx/1HAyJpoZozKeOTseOpzl7hdezN+MwVH8wDF96Gv4FnTLyc04MwMK/DZwvwEhzTspAYGhOoaJUBoJY4RyxG+asuTLZ83DS4ygyUInJC48gMriDQKSDM5cLmh0C6RAIRJNfeAJRoNirHIgpvTimFB8SKgqG49Ps6w5HBGbtFqGiMLRzLUztJ+lFMjNlRxCG9rpQWYDIgW93IYzdtTAdmm09VLsNvup1sutNURRFUSKJ/gUIHyHKfd8UPgAAAABJRU5ErkJggg==" />
                            </defs>
                        </svg>
                    </span>
                    <h2 class="text-lg font-bold text-green-700">Parameter Pengujian & Rincian Biaya</h2>
                </div>
                <table class="mb-2 w-full text-sm bg-white shadow rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-green-500">
                            <th class="p-2 text-left">Parameter</th>
                            <th class="p-2 text-right">Harga (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="param in detailPembayaran.parameter" :key="param.id">
                            <td class="p-2">
                                {{ param.nama_parameter }} <span v-if="param.satuan">({{ param.satuan }})</span>
                            </td>
                            <td class="p-2 text-right">{{ param.harga.toLocaleString('id-ID') }}</td>
                        </tr>
                        <tr class="border-t font-bold">
                            <td class="p-2">Total Biaya</td>
                            <td class="p-2 text-right">
                                {{
                                    detailPembayaran.parameter
                                        .reduce((sum, param) => sum + (param.harga || 0), 0)
                                .toLocaleString('id-ID')
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Metode Pembayaran -->
            <div class="rounded border-l-4 border-green-600 bg-gray-100 p-4 shadow">
                <div class="mb-2 flex items-center">
                    <span class="mr-2 text-green-600">
                        <svg width="30" height="34" viewBox="0 0 30 34" fill="none" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="30" height="34" fill="url(#pattern0_1709_2397)" />
                            <defs>
                                <pattern id="pattern0_1709_2397" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_1709_2397"
                                        transform="matrix(0.01 0 0 0.00882353 0 0.0588235)" />
                                </pattern>
                                <image id="image0_1709_2397" width="100" height="100" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAE20lEQVR4nO2cTUxcVRzFLwsXWqNl01bdNGLREj6MH2kwMdFQg6XQFpjXNKJNqyZV48IYN9VYNWmiiYuJCxUTh0at0Q1qZqht2lis0FCYR4EKlsK8h8akmGAHaRk+5uuYN0hhgJkOdph7eXNOcjZzuZv/b/73f16Yd4WgKIqiKJXV3rRBeD0VQm/SbOsOd63QjxUJIEcoLd39htDdQaF7kB12nxZd368VSqrTXSq87qj8Inkya6/nY6GkdPc70oujS7EplJTX7VSgOJDQIaNCSRGIYiIQxUQgiolAFBOBZFYY0vbB1HQYDv9SHjf2TPmNOqjk0b49iD5z/5Iea61ecs+wby/cF1/DA82fQBw7AnHyK4i279RKWfA59sPUsOrcVwPsylvaLZVJ944Yz2L9yXoIjwuiqQGi7QeFgBiOzmwDAlPDq/rhGSCWm79RCog/G4E4e96cA3LqqEpAtFHpxTUJhEB62CFgh9z0keVAeKDiuuevRQYr49ZmHTWqeWSt1AyxCjzSLK57/tqVltvi1mY9eeFRAsl6IHX5wAsF9k9Zq6ZDGsuA0xVAzX32BqK0+/4D8tZDc585SwlEKpC6fKBn19xn/bXAiwXsEGlAGssWf24dXWdtemRZ0daaFcvxVG9pZoAY/3PNTkM9FWdsqJs3ZwIx5UNY9UCiRg2Cvz2xLEcGq6QX27ZA7GwngWjSIRCIOVOEa948XPVuTNlTv24hkJX8Nv595pZlJbXxzk0EQiCe7BnqE91FCHQVpOzpvsfZIbKHLDjUGXsFn0M0ZcznEFM+BPsA+boKOFieug+VA68UyfO5KpsDqd8OPF+Wul/emvhXhZmw7f+FSyCZE4G42CHgkcUOAWdIHoc6Z0geUxZjr4SUFTrxEoKfHUjdnx8A3i1M6Oh7hQi/vTmhI0n2Wg5+kI+pDzcmdPTcNns/h4z9+S0uD/+VsocvDwH1uQkd+uIu+N8XCR04siHp/vGGdUn3hzufIhACSZPYIS4eWSEeWewQcKjncqhzhgimLMbeNIspy8WUFWLKYoeAKSuXKYszRDBlMWWlWUxZLqasEFMWOwRMWblMWZwhgimLKSvNYspyMWWFmLLYIWDKymXKWqkZMvn74dhvs1L11T++BI6XJHTkxIMING5K6ODx4qT7p38sTro/0mv3F3ZsaCeBaNIhEIgpv/AEokCxQSDpLdZ072O4pt+L4MUnl1yPDu7ERFcxxtrvxlj7PZjoLkHUN++6WAJJL5CJ7pJ5VzXlx62FL5Xjyi+3LrrSyd+yBuGBG7+GsLyh7vYzZZnxQCwHugpjRbTuc/S33J7wni3/2Tti3ZM2ILqng0BMDaH+rRhtvTOu2P+0rV/UGdZFZ9ZtdQs7JTKwPT1AOtzPEYg5U7CIbwf8rWsSdsNs11gOnN8ctxbqv/ELOx/1HAyJpoZozKeOTseOpzl7hdezN+MwVH8wDF96Gv4FnTLyc04MwMK/DZwvwEhzTspAYGhOoaJUBoJY4RyxG+asuTLZ83DS4ygyUInJC48gMriDQKSDM5cLmh0C6RAIRJNfeAJRoNirHIgpvTimFB8SKgqG49Ps6w5HBGbtFqGiMLRzLUztJ+lFMjNlRxCG9rpQWYDIgW93IYzdtTAdmm09VLsNvup1sutNURRFUSKJ/gUIHyHKfd8UPgAAAABJRU5ErkJggg==" />
                            </defs>
                        </svg>
                    </span>
                    <h2 class="text-lg font-bold text-green-700">Metode Pembayaran</h2>
                </div>
                <!-- Pilihan metode pembayaran -->
                <div class="mb-4 flex gap-4">
                    <!-- Jika metode pengambilan di antar, tampilkan tunai dan transfer -->
                    <template v-if="pengajuan.metode_pengambilan === 'diantar'">
                        <button type="button" :class="[
                            'flex flex-1 items-center rounded border p-3 transition',
                            metode === 'tunai' ? 'border-blue-400 bg-blue-100 ring-2 ring-blue-300' : 'border-blue-200 bg-blue-50 hover:bg-blue-100',
                        ]" @click="
                            metode = 'tunai';
                            form.metode_pembayaran = 'tunai';
                        ">
                            <svg width="69" height="69" viewBox="0 0 69 69" fill="none"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <rect width="69" height="69" fill="url(#pattern0_1323_4749)" />
                                <defs>
                                    <pattern id="pattern0_1323_4749" patternContentUnits="objectBoundingBox" width="1"
                                        height="1">
                                        <use xlink:href="#image0_1323_4749" transform="scale(0.01)" />
                                    </pattern>
                                    <image id="image0_1323_4749" width="100" height="100" preserveAspectRatio="none"
                                        xlink:href="data:image/png;base64,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" />
                                </defs>
                            </svg>
                            <div>
                                <div class="font-semibold">Tunai</div>
                                <div class="text-xs text-gray-500">Pembayaran ketika pengantaran sample ke DLH</div>
                            </div>
                        </button>
                        <button type="button" :class="[
                            'flex flex-1 items-center rounded border p-3 transition',
                            metode === 'transfer'
                                ? 'border-blue-400 bg-blue-100 ring-2 ring-blue-300'
                                : 'border-blue-200 bg-blue-50 hover:bg-blue-100',
                        ]" @click="
                            metode = 'transfer';
                            form.metode_pembayaran = 'transfer';
                        ">
                            <img src="@/components/assets/customer/BankJateng.png" alt="Bank Jateng"
                                class="mr-3 h-10 w-28 object-contain" />
                            <div>
                                <div class="font-semibold">Transfer Bank Jateng</div>
                                <div class="text-xs text-gray-500">Pembayaran melalui transfer bank ke rekening Bank
                                    Jateng</div>
                            </div>
                        </button>
                    </template>
                    <!-- Jika metode pengambilan di ambil, hanya tampilkan transfer -->
                    <template v-else>
                        <button type="button" :class="[
                            'flex flex-1 items-center rounded border p-3 transition',
                            metode === 'transfer'
                                ? 'border-blue-400 bg-blue-100 ring-2 ring-blue-300'
                                : 'border-blue-200 bg-blue-50 hover:bg-blue-100',
                        ]" @click="
                            metode = 'transfer';
                            form.metode_pembayaran = 'transfer';
                        ">
                            <img src="@/components/assets/customer/BankJateng.png" alt="Bank Jateng"
                                class="mr-3 h-10 w-28 object-contain" />
                            <div>
                                <div class="font-semibold">Transfer Bank Jateng</div>
                                <div class="text-xs text-gray-500">Pembayaran melalui transfer bank ke rekening Bank
                                    Jateng</div>
                            </div>
                        </button>
                    </template>
                </div>
                <!-- Langkah-langkah pembayaran -->
                <div v-if="metode === 'transfer'">
                    <div class="mb-2 rounded border border-green-200 bg-green-50 p-3">
                        <div class="mb-2 font-semibold">Langkah-langkah Pembayaran via Transfer Bank Jateng</div>
                        <ol class="list-inside list-decimal space-y-2 pl-2 text-sm">
                            <li>
                                Salin nomor rekening tujuan: <b>3-001-12345-6</b><br />
                                atas nama <b>DINAS PERTANIAN KAB. KARANGANYAR</b>.
                            </li>
                            <li>Lakukan transfer dari rekening Bank Jateng Anda atau melalui ATM/Mobile Banking/Internet
                                Banking Bank Jateng.</li>
                            <li>
                                Transfer sebesar <b>Rp. {{ pembayaran ? pembayaran.total_biaya.toLocaleString('id-ID') :
                                    0 }}</b>.
                            </li>
                            <li>
                                Pada kolom keterangan/berita transfer, tuliskan kode pengajuan: <b>{{ pengajuan.id
                                    }}</b>
                            </li>
                            <li>Simpan bukti transfer sebagai bukti pembayaran.</li>
                            <li>Upload bukti transfer melalui halaman konfirmasi pembayaran yang akan muncul setelah
                                menekan tombol <b>"Bayar"</b>.</li>
                            <li>Tim SiLaNyar akan memverifikasi pembayaran Anda dalam waktu 1x24 jam kerja.</li>
                        </ol>
                        <div class="mt-3 rounded border border-yellow-300 bg-yellow-100 p-2 text-xs text-yellow-700">
                            <b>Catatan Penting:</b> Pembayaran harus dilakukan dalam waktu 24 jam sejak pengajuan. Jika
                            tidak, pengajuan akan otomatis
                            dibatalkan oleh sistem.
                        </div>
                    </div>
                    <!-- Area Upload Bukti Transfer -->
                    <div class="mb-2 flex flex-col items-center rounded border border-dashed border-green-400 bg-green-50 p-6"
                        @drop="handleDrop" @dragover="handleDragOver" style="cursor: pointer"
                        @click="fileInput?.click()">
                        <template v-if="!buktiPembayaran">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-10 w-10 text-green-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="font-semibold text-green-700">Klik atau seret Bukti Transfer di sini</span>
                            <span class="text-xs text-gray-500">Format file yang didukung: JPG, PNG. Maks. 5 MB</span>
                        </template>
                        <template v-else>
                            <span class="font-semibold text-green-700">File: {{ buktiPembayaran.name }}</span>
                            <img v-if="previewUrl" :src="previewUrl" alt="Preview Bukti Transfer"
                                class="mt-2 max-h-40 rounded shadow" />
                            <span v-if="buktiPembayaran && !previewUrl" class="mt-2 text-xs text-gray-500"> File
                                berhasil dipilih. </span>
                        </template>
                        <input type="file" ref="fileInput" class="hidden" accept="image/jpeg,image/png,application"
                            @change="handleFileChange" />
                    </div>
                </div>
                <!-- Checkbox -->
                <div class="mb-2 flex items-start">
                    <input type="checkbox" id="syarat" class="mr-2 mt-1" v-model="syarat" />
                    <label for="syarat" class="text-xs text-gray-700">
                        Saya menyatakan bahwa semua informasi yang diberikan adalah benar dan akurat. Saya juga
                        menyetujui syarat dan ketentuan yang
                        berlaku untuk pengujian sampel ini.
                    </label>
                </div>
                <!-- Error -->
                <div v-if="error" class="mb-2 text-xs text-red-600">{{ error }}</div>
                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-2">
                    <button class="rounded border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-100"
                        type="button" @click="router.visit(route('customer.dashboard'))">
                        Batal
                    </button>
                    <button class="rounded bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700"
                        :disabled="loading" @click="submitPembayaran" type="button">
                        <span v-if="loading">Memproses...</span>
                        <span v-else>Konfirmasi Pembayaran</span>
                    </button>
                </div>
            </div>
        </div>
    </CustomerLayout>
</template>

<style scoped>
ol.list-decimal {
    list-style-type: decimal;
}

ol.list-decimal > li::marker {
    font-weight: bold;
    color: #1e293b;
    /* opsional: warna angka, bisa diubah */
}
</style>
