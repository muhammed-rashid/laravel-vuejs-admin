require('./bootstrap');

import {createApp} from 'vue'
import router from './routes/router'
import VueCookie from 'vue3-cookies'
import store from './store/index.js'
import App from './App.vue';
const app = createApp(App)

app.use(VueCookie)
app.use(store)
app.use(router)

app.mount('#app')