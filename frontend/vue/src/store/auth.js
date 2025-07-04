import { defineStore } from 'pinia'
import api from '@/services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token'),
    user:  JSON.parse(localStorage.getItem('user') || 'null'),
    exp:   Number(localStorage.getItem('exp') || 0),
  }),
  getters: {
    isLogged: s => !!s.token && Date.now()/1000 < s.exp,
  },
  actions: {
    async login(email, password) {
      const { data } = await api.post('/auth/login', { email, password })
      this.$patch({ token: data.access_token, user: data.user, exp: data.expires_in + Date.now()/1000 })
      localStorage.setItem('token', this.token)
      localStorage.setItem('user', JSON.stringify(this.user))
      localStorage.setItem('exp',  this.exp)
    },
    logout() {
      this.$reset()
      localStorage.clear()
    },
    async refresh() {
      const { data } = await api.post('/auth/refresh')
      this.token = data.access_token
      localStorage.setItem('token', this.token)
    },
  },
})
