<template>
  <div class="card-body">
    <div class="d-flex justify-content-between">
      <h5 class="card-title">
        {{ income.name }}
      </h5>
    </div>
    <div v-if="highlight" class="d-flex justify-content-between mt-2">
      <button class="btn btn-outline-sub1 btn-sm" @click="onModify()">Edit</button>
      <button class="btn btn-outline-sub2 btn-sm" @click="onDelete()">Delete</button>
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
  import { EventBus } from '../../event-bus.js';
  import moment from 'moment';
  export default {
    props: {
      income: {
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
    data() {
      return {

      };
    },
    methods: {
      onModify() {
        EventBus.$emit('modify-income', this.income);
      },
      onDelete() {

      }
    },
    computed: {
      thisMonth() {
        let monthStr = "" + this.month[1] + "-" + (this.month[0] > 9 ? this.month[0] : "0" + this.month[0]);
        return monthStr;
      }
    }
  }
</script>
