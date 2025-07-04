<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <RouterLink to="/" class="navbar-brand">CNAB Generator</RouterLink>
  
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
  
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <RouterLink to="/imports" class="nav-link">Processamentos</RouterLink>
            </li>
            <li v-if="auth.user?.role === 'admin'" class="nav-item">
              <RouterLink to="/imports/new" class="nav-link">Nova Importação</RouterLink>
            </li>
            <li v-if="auth.user?.role === 'admin'" class="nav-item">
              <RouterLink to="/users" class="nav-link">Usuários</RouterLink>
            </li>
          </ul>
  
          <!-- Área de usuário e logout -->
          <div v-if="auth.isLogged" class="d-flex align-items-center">
            <span class="me-3 text-muted small">
              {{ auth.user?.name }} ({{ auth.user?.role }})
            </span>
            <button class="btn btn-outline-danger btn-sm" @click="onLogout">
              <i class="bi bi-box-arrow-right"></i> Sair
            </button>
          </div>
        </div>
      </div>
    </nav>
  </template>
  
  <script setup>
  import { useAuthStore } from '@/store/auth'
  import { useRouter } from 'vue-router'
  
  const auth = useAuthStore()
  const router = useRouter()
  
  function onLogout() {
    auth.logout()
    router.push('/login')
  }
  </script>
  