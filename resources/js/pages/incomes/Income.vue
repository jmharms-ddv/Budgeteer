<template>
  <div id="income">
    <template v-if="incomeLoadStatus == 1">
      Loading...
    </template>
    <template v-if="incomeLoadStatus == 2">
      <h3>{{ income.name }}</h3>
      <hr>
    </template>
  </div>
</template>

<script>
  export default {
    created() {
      this.$store.dispatch('loadIncome', {
        id: this.$route.params.id
      });
    },

    computed: {
      income() {
        return this.$store.getters.getIncome;
      },
      incomeLoadStatus() {
        return this.$store.getters.getIncomeLoadStatus;
      }
    }
  }
</script>
