<template>
  <div id="incomes" class="container-fluid">
    <make-income :show="showMakeForm"
                  @open="showMakeForm = true"
                  @close="showMakeForm = false"></make-income>
    <div class="d-flex justify-content-between">
      <h3>Sources of Income</h3>
      <button type="button" class="btn btn-outline-base" @click="makeIncome()">+</button>
    </div>
    <hr>
    <collection :items="incomes"
                type="incomes"
                :size="6"></collection>
  </div>
</template>

<script>
  import Collection from '../../components/Collection.vue';
  import MakeIncome from '../../components/incomes/MakeIncome.vue';
  import { EventBus } from '../../event-bus.js';
  export default {
    components: {
      Collection,
      'make-income': MakeIncome
    },
    data() {
      return {
        showMakeForm: false
      }
    },
    created() {
      this.$store.dispatch('loadIncomes', {
        with: ['paychecks']
      });
    },
    methods: {
      makeIncome() {
        EventBus.$emit('make-income');
      }
    },

    computed: {
      incomes() {
        return this.$store.getters.getIncomes;
      }
    }
  }
</script>
