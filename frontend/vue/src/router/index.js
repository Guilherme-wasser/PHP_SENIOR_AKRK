import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const routes = [
  { path: '/login',     component: () => import('@/views/Login.vue') },
  { path: '/',          redirect: '/imports' },

  { path: '/imports',   component: () => import('@/views/Imports/List.vue'),
    meta: { requiresAuth: true } },
  { path: '/imports/new', component: () => import('@/views/Imports/New.vue'),
    meta: { requiresAuth: true, role: 'admin' } },

  { path: '/users',     component: () => import('@/views/Users/List.vue'),
    meta: { requiresAuth: true, role: 'admin' } },
]

const router = createRouter({ history: createWebHistory(), routes })

router.beforeEach((to, _from, next) => {
  const auth = useAuthStore()
  if (to.meta.requiresAuth && !auth.isLogged) return next('/login')
  if (to.meta.role && auth.user?.role !== to.meta.role) return next('/imports')
  next()
})

export default router
