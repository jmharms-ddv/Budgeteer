/*
    Imports the Budgeteer API URL from the config.
*/
import { BUDGETEER_CONFIG } from '../config.js';

export default {
  /*
      GET     /api/income
  */
  getIncomes: function(){
    return axios.get( BUDGETEER_CONFIG.API_URL + '/income' );
  },

  /*
      GET     /api/income/{id}
  */
  getIncome: function(id){
    return axios.get( BUDGETEER_CONFIG.API_URL + '/income' + id);
  },

  /*
      POST     /api/income
  */
  postIncome: function(income) {
    return axios.post(BUDGETEER_CONFIG.API_URL + '/income', income);
  }
}
