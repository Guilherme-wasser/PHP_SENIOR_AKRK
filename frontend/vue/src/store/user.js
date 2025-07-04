import { defineStore } from 'pinia'
import api from '@/services/api'

export const useUserStore = defineStore('users', {
  state: () => ({
    list: [],
  }),

  actions: {
    // ✅ Carrega todos os usuários
    async fetch() {
      try {
        const { data } = await api.get('/users')
        this.list = data
      } catch (err) {
        console.error('Erro ao carregar usuários:', err)
      }
    },

    // ✅ Cadastra novo usuário
    async create(payload) {
      return api.post('/users', payload)
    },
    async delete(id) {
      return api.delete(`/users/${id}`)
    },
  }
})
