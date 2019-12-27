<style>

</style>

<template>
  <div id="home" class="container-fluid">
    <select class="custom-select custom-select-lg mb-3" v-model="incomesSelected">
      <option value="*" selected>All Incomes</option>
      <option v-for="income in incomes" :key="income.id" :value="income.id">
        {{ income.name }}
      </option>
    </select>
    <calendar :total-months="3"
              :incomes="incomesSelected">

    </calendar>
  </div>
</template>

<script>
  import Calendar from '../components/Calendar.vue';
  import moment from 'moment';
  export default {
    components: {
      Calendar
    },

    data() {
      return {
        incomesSelected: "*"
      };
    },

    created() {
      this.$store.dispatch('loadUser');
      this.$store.dispatch('loadBills');
      this.$store.dispatch('loadIncomes', {
        with: ['paychecks']
      });
    },

    computed: {
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
      },
      /**
        Gets the incomes
        */
      incomes() {
        return this.$store.getters.getIncomes;
      },
      /**
        Gets the incomes load status
        */
      incomesLoadStatus() {
        return this.$store.getters.getIncomesLoadStatus;
      },
      /**
        Gets currently selected incomes
        */
      incomesSelect() {
        return this.incomes.filter((income) => {
          if(this.incomesSelected == "*") return true;
          return income.id == this.incomesSelected;
        });
      }
    }
  }
</script>
