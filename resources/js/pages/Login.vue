<template>
  <div>
    <h1>Login to SSO</h1>
    <!-- Tombol yang mengarah ke halaman SSO untuk login -->
    <button @click="redirectToSSO">Login with SSO</button>
  </div>
</template>

<script lang="ts">
/* eslint-disable */
export default {
  methods: {
    redirectToSSO() {
      window.location.href = '/login/sakti';
    }
  }
};
</script>
