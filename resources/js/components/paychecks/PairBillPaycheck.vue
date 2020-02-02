<template>
  <div id="pair-paycheck">
    <b-alert :show="message.countDown"
             dismissible
             :variant="message.type"
             fade
             @dismissed="message.countDown=0"
             @dismiss-count-down="countDownChanged">
      {{message.message}}
    </b-alert>
    <b-alert dismissible
            fade
            v-model="showPairAlert"
            variant="success"
            @dismissed="onDismissAlert()">
      Select a {{ type == "paycheck" ? "bill" : "paycheck" }} to pair with this {{ type }}
    </b-alert>
    <b-modal v-model="showModal"
             ref="pair-paycheck-modal"
             id="pair-paycheck-modal"
             centered
             no-close-on-backdrop
             @hidden="onHideModal()">
      <template slot="modal-header">
        <h5>{{ (isUpdate ? 'Update' : 'Pair') }} Bill-Paycheck</h5>
        <button type="button" class="close" aria-label="Close" @click="onClose()">
          <span aria-hidden="true">&times;</span>
        </button>
      </template>
      <form @submit.prevent="onSave()" v-if="paycheck != null && bill != null">
        <div class="row">
          <div class="col">
            <h5 v-if="amount">${{ paycheckLeft }} - ${{ amount }}</h5>
            <h5 v-else>${{ paycheckLeft }}</h5>
            <p class="text-muted">
              Paycheck left
            </p>
          </div>
          <div class="col form-group">
            <label class="sr-only" for="amount">Amount</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">$</div>
              </div>
              <input type="text" class="form-control" id="amount" placeholder="Amount" v-model="amount">
            </div>
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="projected" v-model="projected">
              <label class="custom-control-label" for="projected">Projected?</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col form-group">
            <label for="due_on">Due on: </label>
            <input type="date" class="form-control" id="due_on" v-model="pair.due_on">
          </div>
          <div class="col form-group">
            <label for="paid_on">Paid on: </label>
            <input type="date" class="form-control" id="paid_on" v-model="pair.paid_on">
          </div>
        </div>
      </form>
      <template slot="modal-footer">
        <b-button v-if="isUpdate" size="sm" variant="danger" @click="onDeletePair()">
          Delete
        </b-button>
        <b-button size="sm" variant="sub1" @click="onClose()">
          Cancel
        </b-button>
        <b-button size="sm" variant="base" @click="onSave()">
          Save
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
  import { BAlert, BButton, BModal } from 'bootstrap-vue';
  import Alert from '../../api/alert.js';
  import { EventBus } from '../../event-bus.js';

  export default {
    components: {
      'b-alert': BAlert,
      'b-button': BButton,
      'b-modal': BModal
    },

    mixins: [Alert],

    created() {
      EventBus.$on('paycheck-pair-start', obj => {
        this.paycheck = obj;
        this.pair.paycheck_id = obj.id;
        this.type = "paycheck";
        this.showPairAlert = true;
      });
      EventBus.$on('bill-pair-start', arr => {
        this.type = "bill";
        this.bill = arr[0];
        this.month = arr[1];
        this.pair.bill_id = this.bill.id;
        this.showPairAlert = true;
      });
      EventBus.$on('paycheck-pair-end', obj => {
        if(obj != null) {
          this.paycheck = obj;
          this.pair.paycheck_id = obj.id;
          this.amount = "" + this.bill.amount;
          this.pair.due_on = "" + this.month[1] + "-" +
                            (this.month[0] > 9 ? this.month[0] : "0" + this.month[0]) + "-" +
                            (this.bill.day_due_on > 9 ? this.bill.day_due_on : "0" + this.bill.day_due_on);
          this.showPairAlert = false;
        }
      });
      EventBus.$on('bill-pair-end', arr => {
        if(arr != null) {
          this.bill = arr[0];
          this.month = arr[1];
          this.pair.bill_id = this.bill.id;
          this.amount = "" + this.bill.amount;
          this.pair.due_on = "" + this.month[1] + "-" +
                            (this.month[0] > 9 ? this.month[0] : "0" + this.month[0]) + "-"+
                            (this.bill.day_due_on > 9 ? this.bill.day_due_on : "0" + this.bill.day_due_on);
          this.showPairAlert = false;
        }
      });
      EventBus.$on('pair-update', arr => {
        this.isUpdate = true;
        this.bill = arr[0];
        this.paycheck = arr[1];
        this.pair.bill_id = this.bill.id;
        this.pair.paycheck_id = this.paycheck.id;
        this.pair.paid_on = this[arr[2]].pivot_paid_on;
        this.pair.due_on = this[arr[2]].pivot_due_on;
        if(this[arr[2]].pivot_amount == null) {
          this.projected = true;
          this.amount = this[arr[2]].pivot_amount_projected;
        } else {
          this.projected = false;
          this.amount = this[arr[2]].pivot_amount;
        }
        this.showModal = true;
      });
    },

    data() {
      return {
        isUpdate: false,
        showPairAlert: false,
        showModal: false,
        type: "",
        bill: null,
        paycheck: null,
        projected: false,
        amount: "",
        month: [],
        pair: {
          bill_id: null,
          paycheck_id: null,
          amount: null,
          amount_project: null,
          due_on: "",
          paid_on: ""
        }
      };
    },

    methods: {
      hasNoRelationship(bill, paycheck) {
        for(let i in bill.paychecks) {
          if(bill.paychecks[i].id == paycheck.id) {
            return false;
          }
        }
        return true;
      },

      onDismissAlert() {
        if(this.paycheck == null) {
          this.onHideModal();
          EventBus.$emit('paycheck-pair-end', null);
        } else if (this.bill == null) {
          this.onHideModal();
          EventBus.$emit('bill-pair-end', null);
        } else if(this.hasNoRelationship(this.bill, this.paycheck)) {
          this.showModal = true;
        } else {
          this.showAlert('warning', "The bill and paycheck selected are already paired.");
          this.onHideModal();
        }
      },

      onSave() {
        if(this.projected) {
          this.pair.amount_project = parseFloat(this.amount);
        } else {
          this.pair.amount = parseFloat(this.amount);
        }
        if(this.isUpdate) {
          this.$store.dispatch('updateBillPaycheck', this.pair);
        } else {
          this.$store.dispatch('pairBillPaycheck', this.pair);
        }
        this.showModal = false;
        this.update = false;
      },

      onDeletePair() {
        this.$store.dispatch('deleteBillPaycheck', {
          bill_id: this.pair.bill_id,
          paycheck_id: this.pair.paycheck_id
        });
        this.showModal = false;
        this.update = false;
      },

      onHideModal() {
        this.bill = null;
        this.paycheck = null;
        this.pair.bill_id = null;
        this.pair.paycheck_id = null;
        this.pair.amount = null;
        this.pair.amount_project = null;
        this.pair.due_on = "";
        this.pair.paid_on = "";
      },

      onClose() {
        this.showModal = false;
      }
    },

    computed: {
      paycheckLeft() {
        if(this.paycheck == null) return 0;
        let total = (this.paycheck.amount == null ? this.paycheck.amount_project : this.paycheck.amount);
        for(let i in this.paycheck.bills) {
          total -= (this.paycheck.bills[i].pivot_amount ? this.paycheck.bills[i].pivot_amount == null : this.paycheck.bills[i].pivot_amount_project);
        }
        return total;
      }
    }
  }
</script>
