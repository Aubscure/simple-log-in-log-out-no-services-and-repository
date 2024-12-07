// main.js

import { createRouter, createWebHistory } from 'vue-router'
import Index from '../pages/index.vue'
import Login from '../pages/login.vue'
import Register from '../pages/register.vue'
import Dashboard from '../pages/dashboard.vue'

const routes = [
  { path: '/', component: Index },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('_token');
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!token) {
      next('/login');
    } else {
      next();
    }
  } else if (to.path === '/login' || to.path === '/register') {
    if (token) {
      next('/dashboard');
    } else {
      next();
    }
  } else {
    next();
  }
})

export default router;
