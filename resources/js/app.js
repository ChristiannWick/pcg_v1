require('./bootstrap');
import Vue from 'vue'
import Vuetify from 'vuetify'
import Router from './router'
import store from './store'
import App from './template/App';
import Sample from './template/Sample';
Vue.use(Vuetify)

new Vue({
    el: '#app',
    store,
    router:Router,
    vuetify: new Vuetify(),
    components : {
        App,
        Sample
    }
    // render: h=>h(App)
});