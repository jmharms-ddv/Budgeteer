<template>
  <div>
    <b-alert :show="message.countDown"
             dismissible
             :variant="message.type"
             fade
             @dismissed="message.countDown=0"
             @dismiss-count-down="countDownChanged">
      {{message.message}}
    </b-alert>
    <b-modal v-model="showModal" ref="make-income-modal" id="make-income-modal" title="Make Income" centered no-close-on-backdrop>
      <form @submit.prevent="onSave(income)">
        <div class="form-group">
          <label for="name">Name: </label>
          <input class="form-control" id="name" type="text" placeholder="Income Name" v-model="income.name">
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
          name: ''
        }
      };
    },
    created() {
      EventBus.$on('make-income', () => {
        this.income.name = '';
        this.showModal = true;
      });
    },
    methods: {
      onSave(income) {
        this.$store.dispatch('addIncome', income);
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
