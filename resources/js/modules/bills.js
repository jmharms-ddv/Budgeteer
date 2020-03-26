/*
|-------------------------------------------------------------------------------
| VUEX modules/bills.js
|-------------------------------------------------------------------------------
| The Vuex data store for the bills
*/

import BillAPI from '../api/bill.js';

export const bills = {
  state: {
    bills: [],
    billsLoadStatus: 0,
    bill: {},
    billLoadStatus: 0,
    addBillStatus: 0,
    editBillStatus: 0,
    deleteBillStatus: 0
  },
  actions: {
    loadBills({ commit }, data) {
      commit('setBillsLoadStatus', 1);
      BillAPI.getBills(data)
        .then(res => {
          commit('setBills', res.data.data);
          commit('setBillsLoadStatus', 2);
        })
        .catch(err => {
          commit('setBills', []);
          commit('setBillsLoadStatus', 3);
        });
    },
    loadBill({ commit }, data) {
      commit('setBillLoadStatus', 1);
      BillAPI.getBill(data.id)
        .then(res => {
          commit('setBill', res.data.data);
          commit('setBillLoadStatus', 2);
        })
        .catch(err => {
          commit('setBill', {});
          commit('setBillLoadStatus', 3);
        });

    },
    addBill({ commit, state, dispatch }, data) {
      commit('setAddBillStatus', 1);
      BillAPI.postBill(data)
        .then(res => {
          commit('setAddBillStatus', 2);
          dispatch('loadBills', {
            with: ['paychecks']
          });
        })
        .catch(err => {
          commit('setAddBillStatus', 3);
        });
    },
    editBill({ commit, state, dispatch }, data) {
      commit('setEditBillStatus', 1);
      BillAPI.putBill(data)
        .then(res => {
          commit('setEditBillStatus', 2);
          dispatch('loadBills', {
            with: ['paychecks']
          });
        })
        .catch(err => {
          commit('setEditBillStatus', 3);
        });
    },
    deleteBill({ commit, state, dispatch }, id) {
      commit('setDeleteBillStatus', 1);
      BillAPI.deleteBill(id)
        .then(res => {
          commit('setDeleteBillStatus', 2);
          dispatch('loadBills', {
            with: ['paychecks']
          });
          dispatch('loadIncomes', {
            with: ['paychecks.bills']
          });
        })
        .catch(err => {
          commit('setDeleteBillStatus', 3);
        });
    }
  },
  mutations: {
    setBillsLoadStatus(state, status) {
      state.billsLoadStatus = status;
    },
    setBills(state, bills) {
      state.bills = bills;
    },
    setBillLoadStatus(state, status) {
      state.billLoadStatus = status;
    },
    setBill(state, bill) {
      state.bill = bill;
    },
    setAddBillStatus(state, status) {
      state.addBillStatus = status;
    },
    setEditBillStatus(state, status) {
      state.editBillStatus = status;
    },
    setDeleteBillStatus(state, status) {
      state.deleteBillStatus = status;
    }
  },
  getters: {
    getBillsLoadStatus(state) {
      return state.billsLoadStatus;
    },
    getBills(state) {
      return state.bills;
    },
    getBillLoadStatus(state) {
      return state.billLoadStatus;
    },
    getBill(state){
      return state.bill;
    },
    getAddBillStatus(state) {
      return state.addBillStatus;
    },
    getEditBillStatus(state) {
      return state.editBillStatus;
    },
    getDeleteBillStatus(state) {
      return state.deleteBillStatus;
    }
  }
}
