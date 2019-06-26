import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store/index.js'
import './registerServiceWorker'
import './plugins/iview.js'

import './ui.js'
import './mock/index.js'
import './login.js'

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
