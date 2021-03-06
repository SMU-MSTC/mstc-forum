import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('./views/Home')
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('./views/About')
    },
    {
      path: '/board/:board_id',
      name: 'board',
      component: () => import('./views/Board')
    },
    {
      path: '/update/:board_id',
      name: 'update',
      component: () => import('./views/Update')
    },
    {
      path: '/create',
      name: 'create',
      component: () => import('./views/Create')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('./views/Register')

    },
    {
      path: '/login',
      name: 'login',
      component: () => import('./views/Login')
    },
    {
      path: '/logout',
      name: 'logout',
      component: () => import('./views/Logout')
    },
    {
      path: '/user/:user_id',
      name: 'user',
      component: () => import('./views/User')
    },
    {
      path: '/post/:board_id',
      name: 'post',
      component: () => import('./views/Post')
    },
    {
      path: '/read/:thread_id',
      name: 'read',
      component: () => import('./views/Read')
    },
    {
        path: '/favorites',
        name: 'favorites',
        component: () => import('./views/Favorites')
    },
    {
      path: '/message',
      name: 'message',
      component: () => import('./views/Message')
    },
    {
      path: '/send/:user_id',
      name: 'send',
      component: () => import('./views/Send')
    },
    {
      path: '/search/:search',
      name: 'search',
      component: () => import('./views/Search')
    },
    {
      path: '/404',
      name: 'not-found',
      component: () => import('./views/NotFound')
    },
    {
      path: '*',
      redirect: '/404'
    }
  ]
})
