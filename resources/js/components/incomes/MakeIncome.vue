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
      <form @submit.prevent="onSave(name)">
        <div class="form-group">
          <label for="name">Name: </label>
          <input class="form-control" id="name" type="text" placeholder="Income Name" v-model="name">
        </div>
      </form>
      <template slot="modal-footer">
        <b-button size="sm" variant="sub1" @click="$emit('close')">
          Cancel
        </b-button>
        <b-button size="sm" variant="base" @click="onSave(name)">
          Save
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
  import { BModal, BAlert, BButton } from 'bootstrap-vue';
  import Alert from '../../api/alert.js';
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
        name: ''
      };
    },

    mounted() {
      this.$root.$on('bv::modal::hide', (bvModalEvt, modalId) => {
        if(modalId == 'make-income-modal') {
          this.$emit('close');
        }
      });
      this.$root.$on('bv::modal::show', (bvModalEvt, modalId) => {
        if(modalId == 'make-income-modal') {

        }
      });
    },

    methods: {
      onSave(name) {
        this.$emit('save', name);
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
