<template>
  <div class="card mb-2 mt-2" :class="{ 'border-base': isSelected }" style="display: flex;">
    <div class="card-body">
      <h5 class="card-title">{{ monthsStr[month][0] }} {{ year }}</h5>
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            Bills <button type="button" class="btn btn-outline-base btn-sm">+</button>
          </div>
          <hr>
          <collection :items="billsMonth"
                      @open-item="itemSelected"></collection>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            Paychecks <button type="button" class="btn btn-outline-base btn-sm">+</button>
          </div>
          <hr>
          <collection :items="paychecksMonth"
                      @open-item="itemSelected"></collection>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Collection from './Collection.vue';
  import moment from 'moment';
  export default {
    components: {
      Collection
    },
    props: {
      month: {
        type: Number,
        required: true
      },
      year: {
        type: Number,
        required: true
      },
      isSelected: {
        type: Boolean,
        default: false
      },
      incomesshown: {
        type: String,
        required: true
      }
    },

    created() {

    },

    data() {
      return {
        monthsStr: [
          ['January', 31],
          ['Febuary', 28],
          ['March', 31],
          ['April', 30],
          ['May', 31],
          ['June', 30],
          ['July', 31],
          ['August', 31],
          ['September', 30],
          ['October', 31],
          ['November', 30],
          ['December', 31]
        ]
      };
    },

    computed: {
      startDate() {
        return moment([this.year, this.month, 1]).format("YYYY-MM-DD");
      },
      endDate() {
        return moment([this.year, this.month, this.monthsStr[this.month][1]]).format("YYYY-MM-DD");
      },
      /**
        Gets the incomes
        */
      incomes() {
        if(this.incomesshown == "*") return this.$store.getters.getIncomes;
        return this.$store.getters.getIncomes.filter((income) => {
          return income.id == this.incomesshown;
        });
      },
      /**
        Gets the paychecks for the incomes shown
        */
      paychecks() {
        let paycheckArr = [];
        for(let i in this.incomes) {
          for(let j in this.incomes[i].paychecks) {
            paycheckArr.push(this.incomes[i].paychecks[j]);
          }
        }
        return paycheckArr;
      },
      /**
        Gets the paychecks that fall within "this" month (see startDate and endDate)
        */
      paychecksMonth() {
        return this.paychecks.filter((paycheck) => {
          return moment(paycheck.paid_on).isBetween(this.startDate, this.endDate, 'month', "[]");
        });
      },
      /**
        Gets the incomes load status
        */
      incomesLoadStatus() {
        return this.$store.getters.getIncomesLoadStatus;
      },
      /**
        Gets the bills
        */
      bills() {
        return this.$store.getters.getBills;
      },
      /**
        Gets the bills that fall within "this" month (see startDate and endDate)
        */
      billsMonth() {
        return this.bills.filter((bill) => {
          return moment(this.startDate).isSameOrBefore(bill.end_at) && moment(this.endDate).isSameOrAfter(bill.start_at);
        });
      }
    },

    methods: {
      itemSelected(id, event) {

      }
    }
  }
</script>
