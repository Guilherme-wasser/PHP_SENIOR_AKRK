<template>
  <div class="container my-4">
    <h2 class="mb-3">Usuários</h2>

    <!-- Botão -->
    <button class="btn btn-primary mb-3" @click="showForm = !showForm">
      + Novo Usuário
    </button>

    <!-- Formulário -->
    <div v-if="showForm" class="mb-3">
      <input v-model="form.name" placeholder="Nome" class="form-control mb-2" />
      <input v-model="form.email" placeholder="E-mail" class="form-control mb-2" />
      <input v-model="form.password" type="password" placeholder="Senha" class="form-control mb-2" />
      <input v-model="form.password_confirmation" type="password" placeholder="Confirme a senha" class="form-control mb-2" />
      <select v-model="form.role" class="form-control mb-2">
        <option disabled value="">Selecione um perfil</option>
        <option value="admin">Admin</option>
        <option value="user">Operador</option>
      </select>
      <button @click="saveUser" class="btn btn-success">Salvar</button>
    </div>

    <!-- Tabela -->
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Papel</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="u in store.list" :key="u.id">
          <td>{{ u.id }}</td>
          <td>{{ u.name }}</td>
          <td>{{ u.email }}</td>
          <td>{{ u.role }}</td>
          <td>
            <button @click="deleteUser(u.id)" class="btn btn-sm btn-danger">
              Excluir
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useUserStore } from '@/store/user'

const store = useUserStore()

const showForm = ref(false)
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: '',
})

async function saveUser() {
  if (!form.value.role) {
    alert('Selecione um perfil para o usuário.')
    return
  }
  try {
    await store.create(form.value)
    form.value = {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      role: '',
    }
    showForm.value = false
    await store.fetch()
  } catch (err) {
    console.error('Erro ao salvar usuário:', err)
    alert('Erro ao salvar usuário: verifique os dados ou tente novamente.')
  }
}

async function deleteUser(id) {
  if (confirm('Tem certeza que deseja excluir este usuário?')) {
    await store.delete(id)
    await store.fetch()
  }
}

onMounted(() => {
  store.fetch()
})
</script>
