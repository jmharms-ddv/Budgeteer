/*
    Imports the Budgeteer API URL from the config.
*/
import { BUDGETEER_CONFIG } from '../config.js';

export default {
  /*
      GET /api/paycheck
      @param options {object}
        can contain with [array]
      @return Promise
  */
  getPaychecks: function(options) {
    let optionsStr = '';
    if(options) {
      optionsStr = '?';
      if(options.hasOwnProperty('with') && options.with.length != 0) {
        optionsStr += (optionsStr == '?' ? 'with=' + options.with[0] : '&with=' + options.with[0]);
        for(let i in options.with) {
          if(i == 0) continue;
          optionsStr += ':' + options.with[i];
        }
      } else {
        optionsStr = '';
      }
    }
    return axios.get(BUDGETEER_CONFIG.API_URL + '/paycheck' + optionsStr);
  },
  /*
    GET   /api/paycheck/{id}
    @param id int
  */
  getPaycheck: function(id) {
    return axios.get(BUDGETEER_CONFIG.API_URL + '/paycheck/' + id);
  },
  /*
    POST   /api/paycheck
  */
  postPaycheck: function(data) {
    return axios.post(BUDGETEER_CONFIG.API_URL + '/paycheck', {
      income_id: data.income_id,
      amount: data.amount,
      amount_project: data.amount_project,
      paid_on: data.paid_on
    });
  }
}
