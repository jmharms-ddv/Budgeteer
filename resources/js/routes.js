/*
|-------------------------------------------------------------------------------
| routes.js
|-------------------------------------------------------------------------------
| Contains all of the routes for the application
*/

/*
    Imports Vue and VueRouter to extend with the routes.
*/
import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store.js';

/*
    Extends Vue to use Vue Router
*/
Vue.use(VueRouter)

/*
    This will cehck to see if the user is authenticated or not.
*/
function requireAuth(to, from, next) {
  /*
  Determines where we should send the user.
  */
  function proceed() {
    /*
      If the user has been loaded determine where we should
      send the user.
    */
    if(store.getters.getUserLoadStatus() == 2) {
      /*
        If the user is not empty, that means there's a user
        authenticated we allow them to continue. Otherwise, we
        send the user back to the home page.
      */
      if(store.getters.getUser != '') {
        next();
      } else {
        next('/');
      }
    }
  }

  if(store.getters.getUserLoadStatus() != 2) {
    store.dispatch('loadUser');
    store.watch(store.getters.getUserLoadStatus, function() {
      if(store.getters.getUserLoadStatus() == 2) {
        proceed();
      }
    });
  } else {
    proceed();
  }
}

/*
    Makes a new VueRouter that we will use to run all of the routes
    for the app.
*/
export default new VueRouter({
    routes: [
        {
          path: '/home',
          name: 'home',
          component: Vue.component('Home', require('./pages/Home.vue').default),
          beforeEnter: requireAuth
        },
        {
          path: '/',
          name: 'vistor',
          component: Vue.component('Visitor', require('./pages/Vistor.vue').default)
        },
        {
          path: '/incomes',
          name: 'incomes',
          component: Vue.component('Incomes', require('./pages/incomes/Incomes.vue').default),
          beforeEnter: requireAuth
        },
        {
          path: '/incomes/new',
          name: 'newincomes',
          component: Vue.component('NewIncome', require('./pages/incomes/NewIncome.vue').default),
          beforeEnter: requireAuth
        },
        {
          path: '/incomes/:id',
          name: 'income',
          component: Vue.component('Income', require('./pages/incomes/Income.vue').default),
          beforeEnter: requireAuth
        },
        {
          path: '/bills',
          name: 'bills',
          component: Vue.component('Bills', require('./pages/bills/Bills.vue').default),
          beforeEnter: requireAuth
        },
        {
          path: '/bills/new',
          name: 'newbill',
          component: Vue.component('NewBill', require('./pages/bills/NewBill.vue').default),
          beforeEnter: requireAuth
        },
        {
          path: '/bills/:id',
          name: 'bill',
          component: Vue.component('Bill', require('./pages/bills/Bill.vue').default),
          beforeEnter: requireAuth
        },
        {
          path: '/paychecks',
          name: 'paychecks',
          component: Vue.component('Paychecks', require('./pages/paychecks/Paychecks.vue').default),
          beforeEnter: requireAuth
        },
        {
          path: '/paychecks/new',
          name: 'newpaycheck',
          component: Vue.component('NewPaycheck', require('./pages/paychecks/NewPaycheck.vue').default),
          beforeEnter: requireAuth
        },
        {
          path: '/paychecks/:id',
          name: 'paycheck',
          component: Vue.component('Paycheck', require('./pages/paychecks/Paycheck.vue').default),
          beforeEnter: requireAuth
        }
    ]
});
