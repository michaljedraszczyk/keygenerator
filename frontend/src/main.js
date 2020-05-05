import Vue from 'vue'
import Buefy from 'buefy'
import './assets/scss/styles.scss'
import '@fortawesome/fontawesome-free'
import App from './App.vue'
import { extend } from "vee-validate"
import { required, regex, length } from 'vee-validate/dist/rules'

extend('required', {
  ...required,
  message: 'To pole jest wymagane.'
});

extend('regex', {
  ...regex,
  message: 'Przyjmowane są tylko maile z domen com.pl.'
});

extend('regexUuid', {
  ...regex,
  message: 'Przyjmowane są tylko maile z domen com.pl.'
});

extend('length', {
  ...length,
  message: 'Wybierz dokładnie 10 znaków.'
});

Vue.use(Buefy)

new Vue({
  el: '#app',
  render: h => h(App)
})
