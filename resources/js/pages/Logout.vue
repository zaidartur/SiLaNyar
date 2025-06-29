<template>
  <div>
    <h1>Welcome, you are logged in!</h1>
    <!-- Tombol Logout -->
    <button @click="logout">Logout</button>
  </div>
</template>

<script lang="ts">
/* eslint-disable */
export default {
  methods: {
    // Mengarahkan pengguna ke route logout untuk keluar
    logout() {
      window.location.href = '/customer/sso/logout'; // Endpoint logout Laravel
    }
  }
};
</script>
