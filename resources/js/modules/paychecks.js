/*
|-------------------------------------------------------------------------------
| VUEX modules/paychecks.js
|-------------------------------------------------------------------------------
| The Vuex data store for the paychecks
*/

import PaycheckAPI from '../api/paycheck.js';

export const paychecks = {
  state: {
    paychecks: [],
    paychecksLoadStatus: 0,

    paycheck: {},
    paycheckLoadStatus: 0,

    addPaycheckStatus: 0
  },

  actions: {
    loadPaychecks({ commit }, data) {
      commit('setPaychecksLoadStatus', 1);
      let options = data.with ? data.with : [];
      PaycheckAPI.getPaychecks({ with: options })
        .then(res => {
          commit('setPaychecks', res.data.data);
          commit('setPaychecksLoadStatus', 2);
        })
        .catch(err => {
          commit('setPaychecks', []);
          commit('setPaychecksLoadStatus', 3);
        });
    },
    loadPaycheck({ commit }, data) {
      commit('setPaycheckLoadStatus', 1);
      PaycheckAPI.getPaycheck(data.id)
        .then(res => {
          commit('setPaycheck', res.data.data);
          commit('setPaycheckLoadStatus', 2);
        })
        .catch(err => {
          commit('setPaycheck', {});
          commit('setPaycheckLoadStatus', 3);
        });
    },
    addPaycheck({ commit, state, dispatch }, data) {
      commit('setAddPaycheckStatus', 1);
      PaycheckAPI.postPaycheck(data)
        .then(res => {
          commit('setAddPaycheckStatus', 2);
          dispatch('loadPaychecks');
        })
        .catch(err => {
          commit('setAddPaycheckStatus', 3);
        });
    }
  },

  mutations: {
    setPaychecksLoadStatus(state, status) {
      state.paychecksLoadStatus = statue;
    },
    setPaychecks(state, paychecks) {
      state.paychecks = paychecks;
    },
    setPaycheckLoadStatus(state, status) {
      state.paycheckLoadStatus = status;
    },
    setPaycheck(state, paycheck) {
      state.paycheck = paycheck;
    },
    setAddPaycheckStatus(state, status) {
      state.addPaycheckStatus = status;
    }
  },

  getters: {
    getPaychecksLoadStatus(state) {
      return state.paychecksLoadStatus;
    },
    getPaychecks(state) {
      return state.paychecks;
    },
    getPaycheckLoadStatus(state) {
      return state.paycheckLoadStatus;
    },
    getPaycheck(state) {
      return state.paycheck;
    },
    getAddPaycheckState(state) {
      return state.addPaycheckStatus;
    }
  }
}
