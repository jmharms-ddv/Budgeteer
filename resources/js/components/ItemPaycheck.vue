<template>
  <a @click="onPairEnd()">
      <slot name="default" :value="value">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h5>{{ value.paid_on }}</h5>
            <h5>${{ value.amount == null ? value.amount_project : value.amount }}</h5>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <button type="button" class="btn btn-outline-base btn-sm">Bills</button>
            <h5>${{ leftOver }}</h5>
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
      EventBus.$on('bill-pair-start', obj => this.$emit('paycheck-highlight', true));
      EventBus.$on('paycheck-pair-end', obj => this.$emit('paycheck-highlight', false));
    },

    data() {
      return {

      };
    },

    methods: {
      onPairEnd() {
        if(this.highlight) EventBus.$emit('paycheck-pair-end', this.value);
      },
      onPairStart() {
        EventBus.$emit('paycheck-pair-start', this.value);
      }
    },

    computed: {
      leftOver() {
        let total = this.value.amount == null ? this.value.amount_project : this.value.amount;
        for(let i in this.value.bills) {
          total -= this.value.bills[i].pivot_amount == null ? this.value.bills[i].pivot_amount_project : this.value.bills[i].pivot_amount;
        }
        return total;
      }
    }
  }
</script>
