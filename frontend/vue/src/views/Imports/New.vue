<template>
  <div class="container">
    <h2>Nova Importação</h2>

    <form @submit.prevent="onSubmit">
      <div class="mb-3">
        <label>Fundo</label>
        <select v-model="fund" class="form-select">
          <option disabled value="">Selecione...</option>
          <option v-for="f in funds" :key="f.id" :value="f.id">{{ f.name }}</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Sequência</label>
        <input v-model="sequence" type="text" class="form-control" maxlength="4" />
      </div>

      <div class="mb-3">
        <label>Arquivo Excel</label>
        <input type="file" class="form-control" @change="onFileChange" />
      </div>

      <button class="btn btn-primary">Enviar</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import { useImportStore } from '@/store/imports'

const store = useImportStore()
const fund = ref('')
const funds = ref([])
const sequence = ref('')
const file = ref(null)

onMounted(async () => {
  // Busca lista de fundos fictícios
  const { data } = await api.get('/funds') // certifique-se de ter essa rota
  funds.value = data
})

function onFileChange(e) {
  file.value = e.target.files[0]
}

async function onSubmit() {
  await store.create({
    fund_id: fund.value,
    sequence: sequence.value,
    file: file.value
  })
  alert('Importação enviada para fila!')
}
</script>
