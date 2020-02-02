<template>
  <div id="incomes" class="container-fluid">
    <make-income :show="showMake"
                  @open="showMake = true"
                  @close="showMake = false"></make-income>
    <modify-income :show="showModify"
                  @open="showModify = true"
                  @close="showModify = false"></modify-income>
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
  import ModifyIncome from '../../components/incomes/ModifyIncome.vue';
  import { EventBus } from '../../event-bus.js';
  export default {
    components: {
      Collection,
      'make-income': MakeIncome,
      'modify-income': ModifyIncome
    },
    data() {
      return {
        showMake: false,
        showModify: false
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
