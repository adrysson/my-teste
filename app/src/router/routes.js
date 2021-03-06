
const routes = [
  {
    path: '/',
    component: () => import('layouts/MyLayout.vue'),
    children: [
      { path: '', component: () => import('pages/Home.vue') },
      { path: 'criarconta', component: () => import('pages/Cadastro.vue') },
      { path: 'entrar', component: () => import('pages/Login.vue') },
      { path: 'perfil', component: () => import('pages/Perfil.vue') },
      { path: 'ativacao/:hash', component: () => import('pages/Ativacao.vue') }
    ]
  }
]

// Always leave this as last one
if (process.env.MODE !== 'ssr') {
  routes.push({
    path: '*',
    component: () => import('pages/Error404.vue')
  })
}

export default routes
