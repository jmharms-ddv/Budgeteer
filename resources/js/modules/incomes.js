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
    addIncomeStatus: 0,
    addIncomeErr: "",
    editIncomeStatus: 0,
    editIncomeErr: "",
    deleteIncomeStatus: 0
  },
  actions: {
    loadIncomes({ commit }, data) {
      commit('setIncomesLoadStatus', 1);
      IncomeAPI.getIncomes(data)
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
      IncomeAPI.postIncome(data)
        .then(res => {
          commit('setAddIncomeStatus', 2);
          dispatch('loadIncomes', {
            with: ['paychecks']
          });
        })
        .catch(err => {
          commit('setAddIncomeStatus', 3);
        });
    },
    editIncome({ commit, state, dispatch }, data) {
      commit('setEditIncomeStatus', 1);
      IncomeAPI.putIncome(data)
        .then(res => {
          commit('setEditIncomeStatus', 2);
          dispatch('loadIncomes', {
            with: ['paychecks']
          });
        })
        .catch(err => {
          commit('setEditIncomeStatus', 3);
        });
    },
    deleteIncome({ commit, state, dispatch }, id) {
      commit('setDeleteIncomeStatus', 1);
      IncomeAPI.deleteIncome(id)
        .then(res => {
          commit('setDeleteIncomeStatus', 2);
          dispatch('loadIncomes', {
            with: ['paychecks']
          });
        })
        .catch(err => {
          commit('setDeleteIncomeStatus', 3);
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
    },
    setEditIncomeStatus(state, status) {
      state.editIncomeStatus = status;
    },
    setDeleteIncomeStatus(state, status) {
      state.deleteIncomeStatus = status;
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
    },
    getEditIncomeStatus(state) {
      return state.editIncomeStatus;
    },
    getDeleteIncomeStatus(state) {
      return state.deleteIncomeStatus;
    }
  }
}
