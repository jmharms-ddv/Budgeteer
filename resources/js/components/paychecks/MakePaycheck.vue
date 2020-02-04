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
            <label for="amount">Amount: </label>
            <div v-if="projected" class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">$</div>
              </div>
              <input type="text" class="form-control" id="amount" placeholder="Amount" v-model="paycheck.amount_project" :class="{ 'is-invalid': $v.paycheck.amount_project.$invalid && !$v.paycheck.amount_project.$pending,
                        'is-valid': !$v.paycheck.amount_project.$invalid && !$v.paycheck.amount_project.$pending }">
            </div>
            <div v-else class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">$</div>
              </div>
              <input type="text" class="form-control" id="amount" placeholder="Amount" v-model="paycheck.amount" :class="{ 'is-invalid': $v.paycheck.amount.$invalid && !$v.paycheck.amount.$pending,
                        'is-valid': !$v.paycheck.amount.$invalid && !$v.paycheck.amount.$pending }">
            </div>
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="projected" v-model="projected" @change="onCheck()">
              <label class="custom-control-label" for="projected">Projected?</label>
            </div>
            <div v-if="projected && !$v.paycheck.amount_project.required" class="invalid-feedback">
              Amount is required
            </div>
            <div v-if="!projected && !$v.paycheck.amount.required" class="invalid-feedback">
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
        projected: false,
        paycheck: {
          income_id: 0,
          amount_project: null,
          amount: null,
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
      EventBus.$on('make-paycheck', arr => {
        this.paycheck.income_id = arr[0];
        this.paycheck.amount_project = null;
        this.paycheck.amount = null;
        this.paycheck.paid_on = arr[1];
        this.showModal = true;
      });
    },
    methods: {
      onSave(paycheck) {
        this.$store.dispatch('addPaycheck', paycheck);
        this.$emit('close');
      },
      onCheck() {
        if(this.projected) {
          this.paycheck.amount_project = this.paycheck.amount;
          this.paycheck.amount = null;
        } else {
          this.paycheck.amount = this.paycheck.amount_project;
          this.paycheck.amount_project = null;
        }
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
