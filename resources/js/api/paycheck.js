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
  getPaychecks: function(options = {}) {
    let optionsStr = '?';
    if(options.hasOwnProperty('with') && options.with.length != 0) {
      optionsStr += (optionsStr == '?' ? 'with=' + options.with[0] : '&with=' + options.with[0]);
      for(let i in options.with) {
        if(i == 0) continue;
        optionsStr += ':' + options.with[i];
      }
    }
    return axios.get(BUDGETEER_CONFIG.API_URL + '/paycheck' + (optionsStr == '?' ? '' : optionsStr));
  },
  /*
    GET   /api/paycheck/{id}
    @param id int
    @return Promise
  */
  getPaycheck: function(id) {
    return axios.get(BUDGETEER_CONFIG.API_URL + '/paycheck/' + id);
  },
  /*
    POST   /api/paycheck
    @param data object
    @return Promise
  */
  postPaycheck: function(data) {
    return axios.post(BUDGETEER_CONFIG.API_URL + '/paycheck', {
      income_id: data.income_id,
      amount: data.amount,
      amount_project: data.amount_project,
      paid_on: data.paid_on
    });
  },
  /*
    POST   /api/billpaycheck
    @param data object
    @return Promise
  */
  postBillPaycheck: function(data) {
    return axios.post(BUDGETEER_CONFIG.API_URL + '/billpaycheck', data);
  },
  /*
    PUT   /api/billpaycheck
    @param data object
    @return Promise
  */
  putBillPaycheck: function(data) {
    return axios.put(BUDGETEER_CONFIG.API_URL + '/billpaycheck', data);
  },
  /*
    DELETE   /api/billpaycheck/{billId}/{paycheckId}
    @param data object
    @return Promise
  */
  deleteBillPaycheck: function(data) {
    return axios.delete(BUDGETEER_CONFIG.API_URL + '/billpaycheck/' + data.bill_id + '/' + data.paycheck_id);
  }
}
