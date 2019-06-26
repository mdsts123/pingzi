import Vue from 'vue'
import { Button,Table,Modal} from 'iview'

Vue.component('Button', Button)
Vue.component('Table', Table)
Vue.component('Modal', Modal)
Vue.prototype.$Modal = Modal

import 'iview/dist/styles/iview.css'
