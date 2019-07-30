/*
|-------------------------------------------------------------------------------
| VUEX modules/incomes.js
|-------------------------------------------------------------------------------
| The Vuex data store for the incomes
*/

import IncomeAPI from '../api/income.js';

export const incomes = {
  state: {
    incomes: [],
    incomesLoadStatus: 0,

    income: {},
    incomeLoadStatus: 0
  },

  actions: {
    loadIncomes({ commit }) {
      commit('setCafesLoadStatus', 1);

      IncomeAPI.getIncomes()
        .then(res => {
          commit('setIncomes', res.data);
          commit('setIncomesLoadStatus', 2);
        })
        .catch(err => {
          commit('setIncomes', []);
          commit('setIncomesLoadStatus', 3);
        });
    },
    loadIncome({ commit }, data) {
      commit('setCafeLoadStatus', 1);

      IncomeAPI.getIncome(data.id)
        .then(res => {
          commit('setIncome', res.data);
          commit('setIncomeLoadStatus', 2);
        })
        .catch(err => {
          commit('setIncome', {});
          commit('setIncomeLoadStatus', 3);
        });

    }
  },

  mutations: {
    setIncomesLoadStatus(state, status) {
      state.incomesLoadStatus = status;
    },

    setIncomes(state, incomes) {
      state.incomes = incomes;
    },

    setIncomeLoadStatus(state, status) {
      state.incomeLoadStatus = status;
    },

    setIncome(state, income) {
      state.income = income;
    }
  },

  getters: {
    getIncomesLoadStatus(state) {
      return state.incomesLoadStatus;
    },

    getIncomes(state) {
      return state.incomes;
    },

    getIncomeLoadStatus(state) {
      return state.incomeLoadStatus;
    },

    getIncome(state){
      return state.income;
    }
  }
}
