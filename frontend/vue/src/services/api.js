import axios from 'axios'
import { useAuthStore } from '@/store/auth'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE || 'http://localhost/api',
  headers: { Accept: 'application/json' },
})

api.interceptors.request.use(config => {
  const { token } = useAuthStore()
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

api.interceptors.response.use(
  res => res,
  async err => {
    const auth = useAuthStore()
    if (err.response?.status === 401 && auth.token) {
      try {
        await auth.refresh()           // chama /auth/refresh
        return api(err.config)         // refaz a requisição
      } catch { auth.logout() }
    }
    return Promise.reject(err)
  },
)

export default api
