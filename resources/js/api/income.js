/*
    Imports the Budgeteer API URL from the config.
*/
import { BUDGETEER_CONFIG } from '../config.js';

export default {
  /*
      GET     /api/income
      @param options {object}
        can contain with [array]
      @return Promise
  */
  getIncomes: function(options = {}) {
    let optionsStr = '?';
    if(options.hasOwnProperty('with') && options.with.length != 0) {
      optionsStr += (optionsStr == '?' ? 'with=' + options.with[0] : '&with=' + options.with[0]);
      for(let i in options.with) {
        if(i == 0) continue;
        optionsStr += ':' + options.with[i];
      }
    }
    return axios.get(BUDGETEER_CONFIG.API_URL + '/income' + (optionsStr == '?' ? '' : optionsStr));
  },

  /*
      GET     /api/income/{id}
      @param id int
  */
  getIncome: function(id) {
    return axios.get(BUDGETEER_CONFIG.API_URL + '/income/' + id);
  },

  /*
      POST     /api/income
  */
  postIncome: function(data) {
    return axios.post(BUDGETEER_CONFIG.API_URL + '/income', {
      name: data.name
    });
  }
}
