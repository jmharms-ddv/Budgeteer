<template>
  <div id="make-paycheck">
    <b-alert :show="message.countDown"
             dismissible
             :variant="message.type"
             fade
             @dismissed="message.countDown=0"
             @dismiss-count-down="countDownChanged">
      {{message.message}}
    </b-alert>
    <b-modal v-model="showModal" ref="make-paycheck-modal" id="make-paycheck-modal" title="Make Paycheck" centered no-close-on-backdrop>
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
            <div v-if="!$v.paycheck.amount_project.numeric" class="invalid-feedback">
              Amount projected must be a valid number
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
            <div v-if="!$v.paycheck.amount_project.numeric" class="invalid-feedback">
              Amount must be a valid number
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
  import { required, minValue, numeric } from 'vuelidate/lib/validators';
  import Alert from '../../api/alert.js';
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
      },
      income: {
        type: Number,
        required: true
      },
      paidon: {
        type: String
      }
    },
    mixins: [Alert],
    data() {
      return {
        paycheck: {
          income_id: 0,
          amount_project: 0,
          amount: 0,
          paid_on: null
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
          numeric
        },
        amount: {
          numeric
        },
        paid_on: {
          required
        }
      }
    },

    mounted() {
      this.$root.$on('bv::modal::hide', (bvModalEvt, modalId) => {
        if(modalId == 'make-paycheck-modal') {
          this.$emit('close');
        }
      });
      this.$root.$on('bv::modal::show', (bvModalEvt, modalId) => {
        if(modalId == 'make-paycheck-modal') {
          this.paycheck = {
            income_id: this.income,
            amount_project: 0,
            amount: 0,
            paid_on: this.paidon
          };
        }
      });
    },

    methods: {
      onSave(paycheck) {
        this.$store.dispatch('addPaycheck', paycheck);
        this.$emit('save', paycheck);
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
