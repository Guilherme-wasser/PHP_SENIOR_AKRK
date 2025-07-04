<template>
  <div class="container my-4">
    <h2>Processamentos</h2>

    <!-- FILTROS -->
    <form class="row mb-3" @submit.prevent="applyFilters">
      <div class="col-md-3">
        <input v-model="filters.sequence" type="text" class="form-control" placeholder="Sequência">
      </div>
      <div class="col-md-3">
        <select v-model="filters.status" class="form-control">
          <option value="">Todos</option>
          <option value="pending">Pendente</option>
          <option value="processing">Processando</option>
          <option value="done">Finalizado</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <button type="button" class="btn btn-secondary ms-2" @click="clearFilters">Limpar</button>
      </div>
    </form>

    <!-- TABELA -->
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Fundo</th>
          <th>Sequência</th>
          <th>Status</th>
          <th>Solicitante</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="imp in store.list" :key="imp.id">
          <td>{{ imp.id }}</td>
          <td>{{ imp.fund?.name }}</td>
          <td>{{ imp.sequence }}</td>
          <td>{{ imp.status }}</td>
          <td>{{ imp.user?.name }}</td>
          <td>
            <a
              :href="`${apiBase}/imports/${imp.id}/download/excel?token=${auth.token}`"
              class="btn btn-sm btn-outline-primary me-1"
              target="_blank">
              <i class="bi bi-file-earmark-spreadsheet"></i>
            </a>
            <a
              :href="`${apiBase}/imports/${imp.id}/download/cnab?token=${auth.token}`"
              class="btn btn-sm btn-outline-success"
              target="_blank">
              <i class="bi bi-download"></i>
            </a>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- PAGINAÇÃO -->
    <nav v-if="store.pagination.last_page > 1">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: store.pagination.current_page === 1 }">
          <a class="page-link" href="#" @click.prevent="goToPage(store.pagination.current_page - 1)">Anterior</a>
        </li>
        <li 
          v-for="page in store.pagination.last_page" 
          :key="page" 
          class="page-item"
          :class="{ active: page === store.pagination.current_page }">
          <a class="page-link" href="#" @click.prevent="goToPage(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ disabled: store.pagination.current_page === store.pagination.last_page }">
          <a class="page-link" href="#" @click.prevent="goToPage(store.pagination.current_page + 1)">Próximo</a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import { useImportStore } from '@/store/imports'
import { useAuthStore } from '@/store/auth'

const apiBase = import.meta.env.VITE_API_BASE
const store = useImportStore()
const auth = useAuthStore()

// Estado reativo dos filtros
const filters = reactive({
  sequence: '',
  status: ''
})

const applyFilters = () => {
  store.fetch({ ...filters, page: 1 })
}

const clearFilters = () => {
  filters.sequence = ''
  filters.status = ''
  store.fetch()
}

const goToPage = (page) => {
  store.pagination.current_page = page
  store.fetch({ ...filters, page })
}

onMounted(() => {
  store.fetch()
})
</script>
