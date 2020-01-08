<template>
  <div class="card"
       :class="{'border-base': !paycheck_highlight && !bill_highlight,
                'border-success': (paycheck_highlight && type == 'paychecks') || (bill_highlight && type == 'bills')}">
    <a @click="onPairEnd()">
      <slot name="default" :value="value">
        <div v-if="type === 'bills'">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h5 class="card-title">
                {{ value.name }}
              </h5>
              <h5 v-if="value.pivot_amount || value.pivot_amount_project" class="card-title">
                ${{ (value.pivot_amount == null ? value.pivot_amount_project : value.pivot_amount) }}
              </h5>
              <h5 v-else class="card-title">
                ${{ value.amount }}
              </h5>
            </div>
            <div class="d-flex justify-content-between">
              <p class="card-text">
                
              </p>
              <p class="card-text">

              </p>
            </div>
          </div>
        </div>
        <div v-else-if="type === 'paychecks'">
          <div class="card-body">
            <div class="row">
              <div class="col">
                Amount:
              </div>
              <div class="col">
                {{ value.amount }}
              </div>
            </div>
            <div class="row">
              <div class="col">
                Projected:
              </div>
              <div class="col">
                {{ value.amount_project }}
              </div>
            </div>
            <div class="row">
              <div class="col">
                Left Over:
              </div>
              <div class="col">

              </div>
            </div>
          </div>
        </div>
        <div v-else>
          <div class="card-body">
            Other
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <router-link v-if="edit" class="btn btn-outline-sub1 btn-sm" @click="$emit('edit')">Edit</router-link>
            <router-link v-if="remove" class="btn btn-outline-sub2 btn-sm" @click="$emit('delete')">Delete</router-link>
            <button type="button"
                    class="elips btn btn-outline-base btn-sm"
                    :disabled="(type == 'bills' && bill_highlight) || (type == 'paychecks' && paycheck_highlight)"
                    @click="onPairStart()"></button>
          </div>
        </div>
      </slot>
    </a>
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
      type: {
        type: String,
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
        paycheck_highlight: false,
        bill_highlight: false
      };
    },

    methods: {

    }
  }
</script>
