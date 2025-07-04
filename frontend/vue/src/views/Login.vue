<template>
  <div class="container my-5">
    <form @submit.prevent="onLogin" class="w-100" style="max-width: 400px; margin: auto;">
      <h2 class="mb-3 text-center">CNAB Generator</h2>
      <div class="form-floating mb-2">
        <input v-model="email" type="email" class="form-control" id="email" placeholder="E-mail" />
        <label for="email">E-mail</label>
      </div>
      <div class="form-floating mb-3">
        <input v-model="password" type="password" class="form-control" id="pwd" placeholder="Senha" />
        <label for="pwd">Senha</label>
      </div>
      <button class="btn btn-primary w-100" :disabled="loading">
        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
        Entrar
      </button>
      <p v-if="error" class="text-danger small mt-2">{{ error }}</p>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref(null)
const router = useRouter()
const auth = useAuthStore()

async function onLogin() {
  loading.value = true
  error.value = null
  try {
    await auth.login(email.value, password.value)
    router.push('/imports')
  } catch (e) {
    error.value = 'Credenciais inválidas'
  } finally {
    loading.value = false
  }
}
</script>
