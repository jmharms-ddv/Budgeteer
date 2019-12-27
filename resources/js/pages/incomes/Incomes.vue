<template>
  <div id="incomes" class="container-fluid">
    <h3>Sources of Income</h3>
    <hr>
    <make-income :show="showMakeForm"
                  @save="saveNewIncome"
                  @open="showMakeForm = true"
                  @close="showMakeForm = false"></make-income>
    <collection :items="incomes"
                url="income"
                from="incomes"
                open
                add
                @add-item="showMakeForm = true"
                size="6"></collection>
  </div>
</template>

<script>
  import Collection from '../../components/Collection.vue';
  import MakeIncome from '../../components/incomes/MakeIncome.vue';
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
      this.$store.dispatch('loadUser');
      this.$store.dispatch('loadIncomes', {
        with: ['paychecks']
      });
    },

    methods: {
      saveNewIncome(name, event) {
        this.$store.dispatch('addIncome', {
          name: name
        });
        this.showMakeForm = false;
      }
    },

    computed: {
      incomes() {
        return this.$store.getters.getIncomes;
      }
    }
  }
</script>
