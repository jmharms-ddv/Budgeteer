/*
    Imports the Budgeteer API URL from the config.
*/
import { BUDGETEER_CONFIG } from '../config.js';

export default {
  /*
      GET     /api/bill
      @param options {object}
        can contain {with [array] | filter_date [array]}
      @return Promise
  */
  getBills: function(options = {}) {
    let optionsStr = '?';
    if(options.hasOwnProperty('with') && options.with.length != 0) {
      optionsStr += (optionsStr == '?' ? 'with=' + options.with[0] : '&with=' + options.with[0]);
      for(let i in options.with) {
        if(i == 0) continue;
        optionsStr += ':' + options.with[i];
      }
    }
    if(options.hasOwnProperty('filter_date') && options.filter_date.length != 0) {
      optionsStr += (optionsStr == '?' ? 'filter_date=' + options.filter_date[0] : '&filter_date=' + options.filter_date[0]);
      for(let i in options.filter_date) {
        if(i == 0) continue;
        optionsStr += ':' + options.filter_date[i];
      }
    }
    return axios.get(BUDGETEER_CONFIG.API_URL + '/bill' + (optionsStr == '?' ? '' : optionsStr));
  },
  /*
    GET     /api/bill/{id}
    @param id int
    @return Promise
  */
  getBill: function(id) {
    return axios.get(BUDGETEER_CONFIG.API_URL + '/bill/' + id);
  },
  /*
    POST     /api/bill
    @param data object
    @return Promise
  */
  postBill: function(data) {
    return axios.post(BUDGETEER_CONFIG.API_URL + '/bill', data);
  },
  /*
    PUT     /api/bill
    @param data object
    @return Promise
  */
  putBill: function(data) {
    return axios.put(BUDGETEER_CONFIG.API_URL + '/bill', data);
  },
  /*
    DELETE  /api/bill
    @param id int
    @return Promise
  */
  deleteBill: function(id) {
    return axios.delete(BUDGETEER_CONFIG.API_URL + '/bill/' + id);
  }
}
