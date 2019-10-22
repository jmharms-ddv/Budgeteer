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
    incomeLoadStatus: 0,

    addIncomeStatus: 0
  },

  actions: {
    loadIncomes({ commit }) {
      commit('setIncomesLoadStatus', 1);

      IncomeAPI.getIncomes()
        .then(res => {
          commit('setIncomes', res.data.data);
          commit('setIncomesLoadStatus', 2);
        })
        .catch(err => {
          commit('setIncomes', []);
          commit('setIncomesLoadStatus', 3);
        });
    },
    loadIncome({ commit }, data) {
      commit('setIncomeLoadStatus', 1);

      IncomeAPI.getIncome(data.id)
        .then(res => {
          commit('setIncome', res.data.data);
          commit('setIncomeLoadStatus', 2);
        })
        .catch(err => {
          commit('setIncome', {});
          commit('setIncomeLoadStatus', 3);
        });

    },
    addIncome({ commit, state, dispatch }, data) {
      commit('setAddIncomeStatus', 1);

      IncomeAPI.postIncome(data.name)
        .then(res => {
          commit('setAddIncomeStatus', 2);
          dispatch('loadIncomes');
        })
        .catch(err => {
          commit('setAddIncomeStatus', 3);
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
    },

    setAddIncomeStatus(state, status) {
      state.addIncomeStatus = status;
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
    },

    getAddIncomeStatus(state) {
      return state.addIncomeStatus;
    }
  }
}
