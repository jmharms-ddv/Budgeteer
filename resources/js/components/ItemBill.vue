<template>
  <div class="card-body">
    <template v-if="(paycheck.pivot_amount || paycheck.pivot_amount_project) && (paycheck.pivot_paid_on == null)">
      <div class="d-flex justify-content-between">
        <h5 class="card-title">
          {{ value.name }}
        </h5>
        <h5 class="card-title">
          ${{ (paycheck.pivot_amount == null ? paycheck.pivot_amount_project : paycheck.pivot_amount) }}
        </h5>
        <h5 class="card-title text-primary">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52.917 52.917" height="26" width="26"><g fill="none" stroke="#007BFF"><path d="M52.13 26.458A25.672 25.672 0 0126.458 52.13 25.672 25.672 0 01.786 26.458 25.672 25.672 0 0126.458.786 25.672 25.672 0 0152.13 26.458z" stroke-width="1.573"/><path d="M26.309.744h.299v22.325h-.299z" stroke-width="1.487"/></g></svg>
        </h5>
      </div>
      <div class="d-flex justify-content-between">
        <small class="text-muted">Scheduled on {{ paycheck.paid_on }}</small>
      </div>
    </template>
    <template v-else-if="(paycheck.pivot_amount || paycheck.pivot_amount_project) && (paycheck.pivot_paid_on != null)">
      <div class="d-flex justify-content-between">
        <h5 class="card-title">
          {{ value.name }}
        </h5>
        <h5 class="card-title">
          ${{ (paycheck.pivot_amount == null ? paycheck.pivot_amount_project : paycheck.pivot_amount) }}
        </h5>
        <h5 class="card-title text-success">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52.917 52.917" height="26" width="26"><g fill="none" stroke="#28A745"><path d="M52.13 26.458A25.672 25.672 0 0126.458 52.13 25.672 25.672 0 01.786 26.458 25.672 25.672 0 0126.458.786 25.672 25.672 0 0152.13 26.458z" stroke-width="1.573"/><path stroke-width="1.605" d="M25.712 28.197L47.817 6.092l.674.674-22.105 22.105z"/><path stroke-width="1.605" d="M15.761 18.246l.682-.681 9.951 9.95-.681.682z"/></g></svg>
        </h5>
      </div>
      <div class="d-flex justify-content-between">
        <small class="text-muted">Paid on {{ paycheck.pivot_paid_on }}</small>
      </div>
    </template>
    <template v-else>
      <div class="d-flex justify-content-between">
        <h5 class="card-title">
          {{ value.name }}
        </h5>
        <h5 class="card-title">
          ${{ value.amount }}
        </h5>
        <h5 class="card-title text-base">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52.917 52.917" height="26" width="26"><path d="M52.13 26.458A25.672 25.672 0 0126.458 52.13 25.672 25.672 0 01.786 26.458 25.672 25.672 0 0126.458.786 25.672 25.672 0 0152.13 26.458z" fill="none" stroke="#1D4880" stroke-width="1.573"/></svg>
        </h5>
      </div>
      <div class="d-flex justify-content-between">
        <small class="text-muted">Due on {{ value.day_due_on }}</small>
      </div>
    </template>
    <div v-if="highlight && !receivingPair" class="d-flex justify-content-between mt-2">
      <button class="btn btn-outline-sub1 btn-sm" @click="$emit('edit')">Edit</button>
      <button v-if="paycheck.hasOwnProperty('id')"
              type="button"
              class="btn btn-outline-base btn-sm"
              @click="onPairUpdate()">Update</button>
      <button v-else
              type="button"
              class="btn btn-outline-base btn-sm"
              @click="onPair()">Pair</button>
      <button class="btn btn-outline-sub2 btn-sm" @click="$emit('delete')">Delete</button>
    </div>
    <div v-if="receivingPair" class="text-center mt-2">
      <button type="button"
              class="btn btn-outline-base btn-sm"
              @click="onPair()">Pair</button>
    </div>
  </div>
</template>

<style>
  .elips {
    transform: rotate(-90deg);
  }
  .elips:after {
    content: '\2807';
    font-size: 15px;
  }
</style>

<script>
  import { EventBus } from '../event-bus.js';

  export default {
    props: {
      value: {
        type: Object,
        required: true
      },
      highlight: {
        type: Boolean,
        required: true
      },
      month: {
        type: Array,
        required: true
      },
      open: {
        type: Boolean,
        default: false
      },
      remove: {
        type: Boolean,
        default: false
      },
      edit: {
        type: Boolean,
        default: false
      }
    },

    created() {
      EventBus.$on('paycheck-pair-start', obj => {
        this.$emit('bill-stay-highlighted', true);
      });
      EventBus.$on('bill-pair-end', arr => {
        //this.$emit('bill-highlight', false);
      });
      EventBus.$on('bill-pair-start', arr => {
        //if(arr[0].id != this.value.id) this.pairMode[0] = false;
      });
      EventBus.$on('paycheck-pair-end', obj => {
        if(this.receivingPair) {
          this.receivingPair = false;
          this.$emit('bill-stay-highlighted', false);
        }
      });
    },

    data() {
      return {
        receivingPair: false
      };
    },

    methods: {
      onPairUpdate() {
        EventBus.$emit('pair-update', [this.value, this.paycheck]);
      },

      onPair() {
        if(!this.receivingPair) {
          // case: the bill is selected first
          this.receivingPair = true;
          this.$emit('bill-stay-highlighted', true);
          EventBus.$emit('bill-pair-start', [this.value, this.month]);
        } else {
          EventBus.$emit('bill-pair-end', [this.value, this.month]);
        }
      }
    },

    computed: {
      thisMonth() {
        let monthStr = "" + this.month[1] + "-" + (this.month[0] > 9 ? this.month[0] : "0" + this.month[0]);
        return monthStr;
      },

      paycheck() {
        for(let i in this.value.paychecks) {
          if(this.month[1] + "-" + (this.month[0] > 9 ? this.month[0] : "0" + this.month[0]) == this.value.paychecks[i].pivot_due_on.substr(0, 7)) return this.value.paychecks[i];
        }
        return {};
      }
    }
  }
</script>
