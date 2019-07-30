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
    <b-modal v-model="showModal" ref="modify-group-modal" id="modify-group-modal" title="Modify Group" centered no-close-on-backdrop>
      <form @submit.prevent="onSave(groupEdit)">
        <input type="hidden" v-model="groupEdit.id">
        <input type="hidden" v-model="groupEdit.super_id">
        <input type="hidden" v-model="groupEdit.organization_id">
        <div class="form-group">
          <label for="name">Name: </label>
          <input class="form-control" id="name" type="text" placeholder="Group Name" v-model="groupEdit.name">
        </div>
      </form>
      <template slot="modal-footer">
        <b-button size="sm" variant="secondary" @click="onCancel()">
          Cancel
        </b-button>
        <b-button size="sm" variant="primary" @click="onSave(groupEdit)">
          Save
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
  import Entity from './../Entity.js';
  import Alert from './../Alert.js';
  import {cloneDeep } from 'lodash';
  export default {
    props: {
      urlSuffix: String,
      super: Object,
      group: Object,
      show: {
        type: Boolean,
        required: true
      }
    },
    mixins: [Entity, Alert],
    data() {
      return {
        endpointUrl: "api/group",
        groupEdit: {}
      };
    },

    created() {

    },

    mounted() {
      this.$root.$on('bv::modal::hide', (bvModalEvt, modalId) => {
        this.$emit('close');
      });
      this.$root.$on('bv::modal::show', (bvModalEvt, modalId) => {
        this.groupEdit = cloneDeep(this.group);
      });
    },

    methods: {

      onCancel() {
        this.$root.$emit('bv::hide::modal', 'modify-group-modal');
      },

      onSave(group) {
        this.storeEntity(group, this.endpointUrl, "PUT").then(res => {
          this.groupEdit = res.data;
          this.$emit('save', this.groupEdit);
          this.onCancel();
          this.showAlert('success', "Group edited created successfully!");
        }).catch(err => {
          console.log(err);
          this.showAlert('danger', err);
        });
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
