<template>
  <div class="card"
       id="item"
       :class="{ 'border-base': !bill_highlight && !paycheck_highlight,
                 'border-success': (type === 'bills' && bill_highlight) || (type === 'paychecks' && paycheck_highlight) }">
    <item-bill v-if="type === 'bills'"
               :value="value"
               :month="month"
               :highlight="bill_highlight"
               :open="open"
               :remove="remove"
               :edit="edit"
               @bill-highlight="onBillHighlight"></item-bill>
     <item-paycheck v-if="type === 'paychecks'"
                    :highlight="paycheck_highlight"
                    :value="value"
                    :open="open"
                    :remove="remove"
                    :edit="edit"
                    @paycheck-highlight="onPaycheckHighlight"></item-paycheck>
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
  import ItemBill from './ItemBill.vue';
  import ItemPaycheck from './ItemPaycheck.vue';
  export default {
    components: {
      'item-bill': ItemBill,
      'item-paycheck': ItemPaycheck
    },

    props: {
      value: {
        type: Object,
        required: true
      },
      type: {
        type: String,
        required: true
      },
      month: {
        type: Array,
        required: false
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

    data() {
      return {
        bill_highlight: false,
        paycheck_highlight: false
      };
    },

    methods: {
      onBillHighlight(value, event) {
        this.bill_highlight = value;
      },
      onPaycheckHighlight(value, event) {
        this.paycheck_highlight = value;
      }
    }
  }
</script>
