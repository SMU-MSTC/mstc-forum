import Vue from 'vue'
import App from './App'
import router from './router'
import jquery from 'jquery'
import md5 from 'js-md5'
import 'bootstrap'
import 'popper.js'
import 'bootstrap/dist/css/bootstrap.css'

global.jquery = jquery
global.$ = jquery
global.api = 'http://test.vanka.site/api'
global.md5 = md5

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
