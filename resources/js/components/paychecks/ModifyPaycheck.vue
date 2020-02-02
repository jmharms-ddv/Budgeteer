<template>
  <div id="modify-paycheck">
    <b-alert :show="message.countDown"
             dismissible
             :variant="message.type"
             fade
             @dismissed="message.countDown=0"
             @dismiss-count-down="countDownChanged">
      {{message.message}}
    </b-alert>
    <b-modal v-model="showModal" ref="modify-paycheck-modal" id="modify-paycheck-modal" title="Edit Paycheck" centered no-close-on-backdrop>
      <form @submit.prevent="onSave(paycheck)">
        <div class="form-group">
          <label for="income_id">Income: </label>
          <select class="custom-select"
                  :class="{ 'is-invalid': $v.paycheck.income_id.$invalid && !$v.paycheck.income_id.$pending,
                            'is-valid': !$v.paycheck.income_id.$invalid && !$v.paycheck.income_id.$pending }"
                  v-model.number="paycheck.income_id"
                  id="income_id">
            <option :value="0">Please Select a Source of Income</option>
            <option v-for="income in incomes" :key="income.id" :value="income.id">
              {{ income.name }}
            </option>
          </select>
        </div>
        <div class="row">
          <div class="col form-group">
            <label for="amount_project">Amount Projected: </label>
            <input class="form-control"
                    :class="{ 'is-invalid': $v.paycheck.amount_project.$invalid && !$v.paycheck.amount_project.$pending,
                              'is-valid': !$v.paycheck.amount_project.$invalid && !$v.paycheck.amount_project.$pending }"
                    id="amount_project"
                    type="text"
                    placeholder="Amount Projected"
                    v-model="paycheck.amount_project">
            <div v-if="!$v.paycheck.amount_project.required" class="invalid-feedback">
              Amount is required
            </div>
          </div>
          <div class="col form-group">
            <label for="amount">Amount: </label>
            <input class="form-control"
                    :class="{ 'is-invalid': $v.paycheck.amount.$invalid && !$v.paycheck.amount.$pending,
                              'is-valid': !$v.paycheck.amount.$invalid && !$v.paycheck.amount.$pending }"
                    id="amount"
                    type="text"
                    placeholder="Amount"
                    v-model="paycheck.amount">
            <div v-if="!$v.paycheck.amount.required" class="invalid-feedback">
              Amount is required
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="paid_on">Paid On: </label>
          <input class="form-control"
                  :class="{ 'is-invalid': $v.paycheck.paid_on.$invalid && !$v.paycheck.paid_on.$pending,
                            'is-valid': !$v.paycheck.paid_on.$invalid && !$v.paycheck.paid_on.$pending }"
                  id="paid_on"
                  type="date"
                  placeholder="mm/dd/yyyy"
                  v-model.date="paycheck.paid_on">
          <div v-if="!$v.paycheck.paid_on.required" class="invalid-feedback">
            A valid date is required
          </div>
        </div>
      </form>
      <template slot="modal-footer">
        <b-button size="sm" variant="sub1" @click="$emit('close')">
          Cancel
        </b-button>
        <b-button size="sm" variant="base" @click="onSave(paycheck)">
          Save
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
  import { BModal, BAlert, BButton } from 'bootstrap-vue';
  import { required, requiredIf, minValue, numeric } from 'vuelidate/lib/validators';
  import Alert from '../../api/alert.js';
  import { EventBus } from '../../event-bus.js';
  export default {
    components: {
      'b-modal': BModal,
      'b-alert': BAlert,
      'b-button': BButton
    },
    props: {
      user: {
        type: Object
      },
      show: {
        type: Boolean,
        required: true
      }
    },
    mixins: [Alert],
    data() {
      return {
        paycheck: {
          id: 0,
          income_id: 0,
          amount_project: 0,
          amount: 0,
          paid_on: ""
        }
      };
    },
    validations: {
      paycheck: {
        income_id: {
          required,
          minValue: minValue(1)
        },
        amount_project: {
          required: requiredIf(function() {
            return !this.paycheck.amount;
          })
        },
        amount: {
          required: requiredIf(function() {
            return !this.paycheck.amount_project;
          })
        },
        paid_on: {
          required
        }
      }
    },
    created() {
      EventBus.$on('modify-paycheck', obj => {
        this.paycheck.id = obj.id;
        this.paycheck.income_id = obj.income_id;
        this.paycheck.amount_project = obj.amount_project;
        this.paycheck.amount = obj.amount;
        this.paycheck.paid_on = obj.paid_on;
        this.showModal = true;
      });
    },
    methods: {
      onSave(paycheck) {
        this.$store.dispatch('editPaycheck', paycheck);
        this.$emit('close');
      }
    },
    computed: {
      showModal: {
        get() {
          return this.show;
        },
        set(value) {
          if(value) {
            this.$emit('open');
          } else {
            this.$emit('close');
          }
        }
      },
      /**
        Gets the incomes
        */
      incomes() {
        return this.$store.getters.getIncomes;
      },
      /**
        Gets the incomes load status
        */
      incomesLoadStatus() {
        return this.$store.getters.getIncomesLoadStatus;
      }
    }
  };
</script>
