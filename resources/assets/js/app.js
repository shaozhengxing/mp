
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import store from './store'
import Router from 'vue-router'
import routers from './routers'

Vue.use(Router)

const router = new Router({routes: routers, mode: 'history'})

const app = new Vue({
    router,
    el: '#app',
    store,
    template: `<div>
                    <router-view></router-view>
               </div>`
});
