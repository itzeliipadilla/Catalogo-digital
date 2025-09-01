import { createRouter, createWebHistory } from 'vue-router'
import ClienteView from '../views/ClienteView.vue';
import ProductosView from '../views/ProductosView.vue';
import EditarProductoView from '../views/EditarProductoView.vue';
import CatalogosView from '../views/CatalogosView.vue';
import RegistrarProducto from '../views/RegistrarProductoView.vue';
import UsuariosView from '../views/UsuariosView.vue';
import EditarUsuario from '../views/EditarUsuarioView.vue';
import RegistrarUsuarios from '../views/RegistrarUsuariosView.vue';
import PerfilView from '../views/PerfilView.vue';
import ContrasenaView from '../views/ContrasenaView.vue';
import LoginView from '../views/LoginView.vue';

const routes = [
  {
    path: '/',
    name: 'ClienteView',
    component: ClienteView,
    meta: { hideNav: true }
  },
  {
    path: '/login',
    name: 'LoginView',
    component: LoginView,
    meta: { hideNav: true }
  },
  {
    path: '/catalogos',
    name: 'CatalogosView',
    component: CatalogosView,
    meta: { requiresAuth: true }
  },
  {
    path: '/productos',
    name: 'Productos',
    component: ProductosView,
    meta: { requiresAuth: true }
  },
  {
    path: '/registro-productos',
    name: 'RegistrarProducto',
    component: RegistrarProducto,
    meta: { requiresAuth: true }
  },
  {
    path: '/editar-producto',
    name: 'EditarProducto',
    component: EditarProductoView,
    meta: { requiresAuth: true }
  },
  {
    path: '/usuarios',
    name: 'Usuarios',
    component: UsuariosView,
    meta: { requiresAuth: true }
  },
  {
    path: '/registro-usuarios',
    name: 'RegistrarUsuarios',
    component: RegistrarUsuarios,
    meta: { requiresAuth: true }
  },
  {
    path: '/editar-usuario',
    name: 'EditarUsuario',
    component: EditarUsuario,
    meta: { requiresAuth: true }
  },
  {
    path: '/cambiar-contrasena',
    name: 'ContrasenaView',
    component: ContrasenaView,
    meta: { requiresAuth: true }
  },
  {
    path: '/perfil',
    name: 'Perfil',
    component: PerfilView,
    meta: { requiresAuth: true }
  },
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
  },
  {
    path: '/catalogos',
    name: 'catalogos',
    meta: { requiresAuth: true }
  },
  {
    path: '/productos',
    name: 'productos',
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('token') !== null;
  if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
    next({ name: 'LoginView' });
  } else {
    next();
  }
});

export default router
