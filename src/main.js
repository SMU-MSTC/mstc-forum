import Vue from 'vue'
import App from './App'
import router from './router'
import jquery from 'jquery'
import md5 from 'js-md5'
import 'bootstrap'
import 'popper.js'
import 'bootstrap/dist/css/bootstrap.css'

function extract(form) {
  const formData = new FormData()
  for (const key in form) {
    if (form.hasOwnProperty(key))
      formData.append(key, form[key])
  }
  return formData
}

function post(form) {
  return {
    method: 'POST',
    body: extract(form),
  }
}

global.jquery = jquery
global.api = 'http://forum.localhost/api'
global.md5 = md5

Vue.prototype.post = post
Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
