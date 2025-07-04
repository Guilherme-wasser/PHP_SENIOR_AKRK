import axios from 'axios'
import { useAuthStore } from '@/store/auth'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE || 'http://localhost/api',
  headers: { Accept: 'application/json' },
})

api.interceptors.request.use(config => {
  const auth = useAuthStore()   // Pega a store inteira
  if (auth.token) {
    config.headers.Authorization = `Bearer ${auth.token}`
  }
  return config
})

api.interceptors.response.use(
  res => res,
  async err => {
    const auth = useAuthStore()
    if (err.response?.status === 401 && auth.token) {
      try {
        await auth.refresh()
        return api(err.config)
      } catch {
        auth.logout()
      }
    }
    return Promise.reject(err)
  },
)

export default api
