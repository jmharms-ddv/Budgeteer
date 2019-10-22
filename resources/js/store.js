/*
    Imports Vue and Vuex
*/
import Vue from 'vue'
import Vuex from 'vuex'

/*
    Initializes Vuex on Vue.
*/
Vue.use( Vuex )

/*
    Imports all of the modules used in the application to build the data store.
*/
import { incomes } from './modules/incomes.js'
import { users } from './modules/users.js'

/*
  Exports our data store.
*/
export default new Vuex.Store({
    modules: {
      incomes,
      users
    }
});
