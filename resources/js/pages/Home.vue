<style>

</style>

<template>
  <div id="home">
    <span v-show="incomesLoadStatus == 1">Loading</span>
    <span v-show="incomesLoadStatus == 2">Loaded!</span>
    <span v-show="incomesLoadStatus == 3">Not Loaded!</span>
  </div>
</template>

<script>
  export default {
    created() {
      this.$store.dispatch('loadIncomes');
    },

    computed: {
      /*
       Gets the incomes load status
     */
     incomesLoadStatus(){
       return this.$store.getters.getIncomesLoadStatus;
     },

     /*
       Gets the incomes
     */
     incomes(){
       return this.$store.getters.getIncomes;
     }
    }
  }
</script>
