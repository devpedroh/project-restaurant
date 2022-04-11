import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import moment from 'moment';
import axios from 'axios'

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueSweetalert2);

Vue.config.productionTip = false
Vue.prototype.$user = JSON.parse(localStorage.getItem('userdata'))
Vue.prototype.$http = axios
Vue.prototype.$url = 'http://localhost:8001/api/'
Vue.prototype.$urlNotify = 'http://localhost:8001/api/send-notification'

moment.locale('pt');
moment.updateLocale('pt', {
  months : [
    "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho",
    "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
  ]
});
Vue.prototype.moment = moment

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
