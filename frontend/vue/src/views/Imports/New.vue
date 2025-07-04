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
  try {
    const { data } = await api.get('/funds')
    funds.value = data
  } catch (err) {
    console.error('Erro ao carregar fundos:', err)
    alert('Erro ao carregar lista de fundos. Verifique seu login ou servidor.')
  }
})

function onFileChange(e) {
  file.value = e.target.files[0]
}

async function onSubmit() {
  if (!fund.value) {
    alert('Selecione um fundo.')
    return
  }
  if (!sequence.value) {
    alert('Digite a sequência.')
    return
  }
  if (!file.value) {
    alert('Selecione o arquivo Excel.')
    return
  }

  try {
    await store.create({
      fund_id: fund.value,
      sequence: sequence.value,
      file: file.value
    })
    alert('Importação enviada para fila!')

    // Limpa campos
    fund.value = ''
    sequence.value = ''
    file.value = null
  } catch (err) {
    console.error('Erro ao enviar importação:', err)
    alert('Erro ao enviar importação. Verifique os dados e tente novamente.')
  }
}
</script>
