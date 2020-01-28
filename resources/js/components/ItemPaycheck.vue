<template>
  <div class="card-body">
    <div class="d-flex justify-content-between">
      <h5>{{ value.paid_on }}</h5>
      <h5>${{ value.amount == null ? value.amount_project : value.amount }}</h5>
    </div>
    <div class="d-flex justify-content-between mb-2">
      <div class="btn-group dropright">
        <button type="button" class="btn btn-outline-base btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Bills
        </button>
        <div class="dropdown-menu">
          <a v-for="bill in value.bills" :key="bill.id" class="dropdown-item" @click.prevent="EventBus.$emit('bill-selected', [bill.id, value.id])">{{ bill.name }} Due on: {{ bill.pivot_due_on }}</a>
        </div>
      </div>
      <h5>${{ leftOver }}</h5>
    </div>
    <div v-if="highlight && !receivingPair" class="d-flex justify-content-between mt-2">
      <button class="btn btn-outline-sub1 btn-sm" @click="$emit('edit')">Edit</button>
      <button type="button"
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
      EventBus.$on('bill-pair-start', arr => {
        this.receivingPair = true;
        this.$emit('paycheck-stay-highlighted', true);
      });
      EventBus.$on('paycheck-pair-end', obj => {
        this.receivingPair = false;
        this.$emit('paycheck-stay-highlighted', false);
      });
      EventBus.$on('bill-pair-end', arr => {
        if(this.receivingPair) {
          this.receivingPair = false;
          this.$emit('paycheck-stay-highlighted', false);
        }
      })
    },

    data() {
      return {
        receivingPair: false
      };
    },

    methods: {
      onPair() {
        if(!this.receivingPair) {
          // case: the paycheck is selected first
          this.receivingPair = true;
          this.$emit('paycheck-stay-highlighted', true);
          EventBus.$emit('paycheck-pair-start', this.value);
        } else {
          // case: the paycheck is selected last
          EventBus.$emit('paycheck-pair-end', this.value);
        }
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
