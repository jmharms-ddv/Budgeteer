<style>

</style>

<template>
  <div id="home">
    Manage your sources of income <router-link to="/incomes" class="btn btn-base">Here</router-link>
  </div>
</template>

<script>
  export default {
    created() {
      this.$store.dispatch('loadUser');
      this.$store.dispatch('loadIncomes');
    },

    computed: {
      /**
        Gets the incomes
        */
      incomes(){
        return this.$store.getters.getIncomes;
      },
      /**
        Gets the incomes load status
        */
      incomesLoadStatus(){
        return this.$store.getters.getIncomesLoadStatus;
      },
      /**
        Gets the user
        */
      user() {
        return this.$store.getters.getUser;
      },
      /**
        Gets the user load status
        */
      userLoadStatus() {
        return this.$store.getters.getUserLoadStatus();
      }
    }
  }
</script>
