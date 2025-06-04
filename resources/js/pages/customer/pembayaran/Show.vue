<script setup lang="ts">
defineProps<{
    pengajuanBerhasil: boolean
}>();
</script>

<template>
    <div class="space-y-4">
        <div v-if="pengajuanBerhasil" class="rounded border border-green-300 bg-green-100 p-4">
            <b>Pengajuan berhasil dikirim!</b>
        </div>
        <div class="rounded border border-blue-200 bg-blue-50 p-4">
            <h2 class="mb-2 font-bold">Instruksi Pembayaran</h2>
            <p>Silakan lakukan pembayaran ke rekening berikut:</p>
            <ul class="mb-2">
                <li><b>Bank:</b> BRI</li>
                <li><b>No. Rekening:</b> 1234-5678-9012-3456</li>
                <li><b>Atas Nama:</b> DLH Karanganyar</li>
            </ul>
            <p>Setelah pembayaran, silakan konfirmasi ke admin.</p>
        </div>
    </div>
</template>
