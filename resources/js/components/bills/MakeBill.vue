<template>
  <div id="make-bill">
    <b-alert :show="message.countDown"
             dismissible
             :variant="message.type"
             fade
             @dismissed="message.countDown=0"
             @dismiss-count-down="countDownChanged">
      {{message.message}}
    </b-alert>
    <b-modal v-model="showModal" ref="make-bill-modal" id="make-bill-modal" title="Make Bill" centered no-close-on-backdrop>
      <form @submit.prevent="onSave(bill)">
        <div class="form-group">
          <label for="name">Name: </label>
          <input class="form-control"
                  :class="{ 'is-invalid': $v.bill.name.$invalid && !$v.bill.name.$pending,
                            'is-valid': !$v.bill.name.$invalid && !$v.bill.name.$pending }"
                  id="name"
                  type="text"
                  placeholder="Name"
                  v-model="bill.name">
          <div v-if="!$v.bill.name.required" class="invalid-feedback">
            Name is required
          </div>
        </div>
        <div class="row">
          <div class="col form-group">
            <label class="sr-only" for="amount">Amount</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text">$</div>
              </div>
              <input class="form-control"
                      :class="{ 'is-invalid': $v.bill.amount.$invalid && !$v.bill.amount.$pending,
                                'is-valid': !$v.bill.amount.$invalid && !$v.bill.amount.$pending }"
                      id="amount"
                      type="text"
                      placeholder="Amount"
                      v-model="bill.amount">
            </div>
            <div v-if="!$v.bill.amount.required" class="invalid-feedback">
              Amount is required
            </div>
          </div>
          <div class="col form-group">
            <label for="day_due_on">Day Due: </label>
            <input class="form-control"
                    :class="{ 'is-invalid': $v.bill.day_due_on.$invalid && !$v.bill.day_due_on.$pending,
                              'is-valid': !$v.bill.day_due_on.$invalid && !$v.bill.day_due_on.$pending }"
                    id="day_due_on"
                    type="number"
                    placeholder="Day Due"
                    v-model.number="bill.day_due_on">
            <div v-if="!$v.bill.day_due_on.integer || !$v.bill.day_due_on.minValue || !$v.bill.day_due_on.maxValue" class="invalid-feedback">
              Amount must be a valid integer day (1-31)
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col form-group">
            <label for="start_on">Start On Date: </label>
            <input class="form-control"
                    :class="{ 'is-invalid': $v.bill.start_on.$invalid && !$v.bill.start_on.$pending,
                              'is-valid': !$v.bill.start_on.$invalid && !$v.bill.start_on.$pending }"
                    id="start_on"
                    type="date"
                    placeholder="mm/dd/yyyy"
                    v-model="bill.start_on">
            <div v-if="!$v.bill.start_on.required" class="invalid-feedback">
              Start On Date is required
            </div>
          </div>
          <div class="col form-group">
            <label for="end_on">End On Date: </label>
            <input class="form-control"
                    :class="{ 'is-invalid': $v.bill.end_on.$invalid && !$v.bill.end_on.$pending,
                              'is-valid': !$v.bill.end_on.$invalid && !$v.bill.end_on.$pending }"
                    id="end_on"
                    type="date"
                    placeholder="mm/dd/yyyy"
                    v-model="bill.end_on">
            <div v-if="!$v.bill.end_on.required" class="invalid-feedback">
              End On Date is required
            </div>
          </div>
        </div>
      </form>
      <template slot="modal-footer">
        <b-button size="sm" variant="sub1" @click="$emit('close')">
          Cancel
        </b-button>
        <b-button size="sm" variant="base" @click="onSave(bill)">
          Save
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
  import { BModal, BAlert, BButton } from 'bootstrap-vue';
  import { required, minValue, maxValue, integer } from 'vuelidate/lib/validators';
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
      starton: {
        type: String
      }
    },
    mixins: [Alert],
    data() {
      return {
        bill: {
          name: "",
          amount: null,
          day_due_on: null,
          start_at: "",
          end_at: ""
        }
      };
    },

    validations: {
      bill: {
        name: {
          required
        },
        amount: {
          required
        },
        day_due_on: {
          integer,
          minValue: minValue(1),
          maxValue: maxValue(31)
        },
        start_on: {
          required
        },
        end_on: {
          required
        }
      }
    },

    mounted() {
      this.$root.$on('bv::modal::hide', (bvModalEvt, modalId) => {
        if(modalId == 'make-bill-modal') {
          this.$emit('close');
        }
      });
      this.$root.$on('bv::modal::show', (bvModalEvt, modalId) => {
        if(modalId == 'make-bill-modal') {
          this.bill = {
            name: "",
            amount: null,
            day_due_on: null,
            start_on: this.starton,
            end_on: ""
          };
        }
      });
    },

    methods: {
      onSave(bill) {
        this.$store.dispatch('addBill', bill);
        this.$emit('save', bill);
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
