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

/*
    Extends Vue to use Vue Router
*/
Vue.use( VueRouter )


/*
    Makes a new VueRouter that we will use to run all of the routes
    for the app.
*/
export default new VueRouter({
    routes: [
        {
            path: '/',
            name: 'home',
            component: Vue.component( 'Home', require( './pages/Home.vue' ) )
        },
        {
            path: '/incomes',
            name: 'incomes',
            component: Vue.component( 'Incomes', require( './pages/incomes/Incomes.vue' ) )
        },
        {
          path: '/incomes/new',
          name: 'newincomes',
          component: Vue.component( 'NewIncome', require( './pages/incomes/NewIncome.vue' ) )
        },
        {
          path: '/incomes/:id',
          name: 'income',
          component: Vue.component( 'Income', require( './pages/incomes/Income.vue' ) )
        },
        {
            path: '/bills',
            name: 'bills',
            component: Vue.component( 'Bills', require( './pages/bills/Bills.vue' ) )
        },
        {
          path: '/bills/new',
          name: 'newbill',
          component: Vue.component( 'NewBill', require( './pages/bills/NewBill.vue' ) )
        },
        {
          path: '/bills/:id',
          name: 'bill',
          component: Vue.component( 'Bill', require( './pages/bills/Bill.vue' ) )
        },
        {
            path: '/paychecks',
            name: 'paychecks',
            component: Vue.component( 'Paychecks', require( './pages/paychecks/Paychecks.vue' ) )
        },
        {
          path: '/paychecks/new',
          name: 'newpaycheck',
          component: Vue.component( 'NewPaycheck', require( './pages/paychecks/NewPaycheck.vue' ) )
        },
        {
          path: '/paychecks/:id',
          name: 'paycheck',
          component: Vue.component( 'Paycheck', require( './pages/paychecks/Paycheck.vue' ) )
        }
    ]
});
