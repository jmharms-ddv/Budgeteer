<template>
  <a @click="onPairEnd()">
    <slot name="default" :value="value">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <h5 class="card-title">
            {{ value.name }}
          </h5>
          <h5 v-if="(paycheck.pivot_amount || paycheck.pivot_amount_project) && (paycheck.pivot_paid_on == null)" class="card-title text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52.917 52.917" height="26" width="26"><g fill="none" stroke="#007BFF"><path d="M52.13 26.458A25.672 25.672 0 0126.458 52.13 25.672 25.672 0 01.786 26.458 25.672 25.672 0 0126.458.786 25.672 25.672 0 0152.13 26.458z" stroke-width="1.573"/><path d="M26.309.744h.299v22.325h-.299z" stroke-width="1.487"/></g></svg>
            ${{ (paycheck.pivot_amount == null ? paycheck.pivot_amount_project : paycheck.pivot_amount) }}
          </h5>
          <h5 v-else-if="(paycheck.pivot_amount || paycheck.pivot_amount_project) && (paycheck.pivot_paid_on != null)" class="card-title text-success">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52.917 52.917" height="26" width="26"><g fill="none" stroke="#28A745"><path d="M52.13 26.458A25.672 25.672 0 0126.458 52.13 25.672 25.672 0 01.786 26.458 25.672 25.672 0 0126.458.786 25.672 25.672 0 0152.13 26.458z" stroke-width="1.573"/><path stroke-width="1.605" d="M25.712 28.197L47.817 6.092l.674.674-22.105 22.105z"/><path stroke-width="1.605" d="M15.761 18.246l.682-.681 9.951 9.95-.681.682z"/></g></svg>
            ${{ (paycheck.pivot_amount == null ? paycheck.pivot_amount_project : paycheck.pivot_amount) }}
          </h5>
          <h5 v-else class="card-title text-base">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52.917 52.917" height="26" width="26"><path d="M52.13 26.458A25.672 25.672 0 0126.458 52.13 25.672 25.672 0 01.786 26.458 25.672 25.672 0 0126.458.786 25.672 25.672 0 0152.13 26.458z" fill="none" stroke="#1D4880" stroke-width="1.573"/></svg>
            ${{ value.amount }}
          </h5>
        </div>
        <div class="d-flex justify-content-between">
          <p class="card-text">

          </p>
          <p class="card-text">

          </p>
        </div>
        <div class="d-flex justify-content-between">
          <router-link v-if="edit" class="btn btn-outline-sub1 btn-sm" @click="$emit('edit')">Edit</router-link>
          <router-link v-if="remove" class="btn btn-outline-sub2 btn-sm" @click="$emit('delete')">Delete</router-link>
          <button type="button"
                  class="elips btn btn-outline-base btn-sm"
                  :disabled="highlight"
                  @click="onPairStart()"></button>
        </div>
      </div>
    </slot>
  </a>
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
      EventBus.$on('paycheck-pair-start', obj => this.$emit('bill-highlight', true));
      EventBus.$on('bill-pair-end', arr => this.$emit('bill-highlight', false));
    },

    data() {
      return {

      };
    },

    methods: {
      hasSameMonth() {

      },
      onPairEnd() {
        if(this.highlight) EventBus.$emit('bill-pair-end', [this.value, this.month]);
      },
      onPairStart() {
        EventBus.$emit('bill-pair-start', [this.value, this.month]);
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
