<template>
  <div id="modify-income">
    <b-alert :show="message.countDown"
             dismissible
             :variant="message.type"
             fade
             @dismissed="message.countDown=0"
             @dismiss-count-down="countDownChanged">
      {{message.message}}
    </b-alert>
    <b-modal v-model="showModal" ref="modify-income-modal" id="modify-income-modal" title="Edit Income" centered no-close-on-backdrop>
      <form @submit.prevent="onSave(income)">
        <div class="form-group">
          <label for="name">Name: </label>
          <input class="form-control"
                  :class="{ 'is-invalid': $v.income.name.$invalid && !$v.income.name.$pending,
                            'is-valid': !$v.income.name.$invalid && !$v.income.name.$pending }"
                  id="name"
                  type="text"
                  placeholder="Name"
                  v-model="income.name">
          <div v-if="!$v.income.name.required" class="invalid-feedback">
            Name is required
          </div>
        </div>
      </form>
      <template slot="modal-footer">
        <b-button size="sm" variant="sub1" @click="$emit('close')">
          Cancel
        </b-button>
        <b-button size="sm" variant="base" @click="onSave(income)">
          Save
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
  import { BModal, BAlert, BButton } from 'bootstrap-vue';
  import { required, minValue, maxValue, integer } from 'vuelidate/lib/validators';
  import { cloneDeep } from 'lodash';
  import Alert from '../../api/alert.js';
  import { EventBus } from '../../event-bus.js';
  export default {
    components: {
      'b-modal': BModal,
      'b-alert': BAlert,
      'b-button': BButton
    },
    props: {
      user: {
        type: Object
      },
      show: {
        type: Boolean,
        required: true
      }
    },
    mixins: [Alert],
    data() {
      return {
        income: {
          id: 0,
          name: ""
        }
      };
    },
    validations: {
      income: {
        name: {
          required
        }
      }
    },
    created() {
      EventBus.$on('modify-income', obj => {
        this.income.id = obj.id;
        this.income.name = obj.name;
        this.showModal = true;
      });
    },
    methods: {
      onSave(income) {
        this.$store.dispatch('editIncome', income);
        this.$emit('close');
      }
    },
    computed: {
      showModal: {
        get() {
          return this.show;
        },
        set(value) {
          if(value) {
            this.$emit('open');
          } else {
            this.$emit('close');
          }
        }
      }
    }
  };
</script>
