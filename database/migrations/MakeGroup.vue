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
    <b-modal v-model="showModal" ref="make-group-modal" id="make-group-modal" title="Make Group" centered no-close-on-backdrop>
      <form @submit.prevent="onSave(group)">
        <input type="hidden" v-model="group.super_id">
        <input type="hidden" v-model="group.organization_id">
        <div class="form-group">
          <label for="name">Name: </label>
          <input class="form-control" id="name" type="text" placeholder="Group Name" v-model="group.name">
        </div>
      </form>
      <template slot="modal-footer">
        <b-button size="sm" variant="secondary" @click="onCancel()">
          Cancel
        </b-button>
        <b-button size="sm" variant="primary" @click="onSave(group)">
          Save
        </b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
  import Entity from './../Entity.js';
  import Alert from './../Alert.js';
  export default {
    props: {
      urlSuffix: String,
      super: Object,
      show: {
        type: Boolean,
        required: true
      }
    },
    mixins: [Entity, Alert],
    data() {
      return {
        endpointUrl: "api/group",
        group: {}
      };
    },

    created() {
      this.fetchEntity(`${this.endpointUrl}/blank`).then(res => {
        this.group = res.data;
        this.group.super_id = this.super.id;
        this.group.organization_id = this.super.organization_id;
      }).catch(err => {
        console.log(err);
        this.showAlert('danger', err);
      });
    },

    mounted() {
      this.$root.$on('bv::modal::hide', (bvModalEvt, modalId) => {
        this.onCancel();
        this.$emit('close');
      });
      this.$root.$on('bv::modal::show', (bvModalEvt, modalId) => {
        this.getBlankGroup().then(groupBlank => {
          this.group = groupBlank;
        });
      });
    },

    methods: {
      async getBlankGroup() {
        return await this.fetchEntity(`${this.endpointUrl}/blank`).then(res => {
          let group = res.data;
          group.super_id = this.super.id;
          group.organization_id = this.super.organization_id;
          return group;
        }).catch(err => {
          console.log(err);
          throw err;
        });
      },

      onCancel() {
        this.getBlankGroup().then(groupBlank => {
          this.group = groupBlank;
          this.$root.$emit('bv::hide::modal', 'make-group-modal');
        }).catch(err => {
          this.showAlert('danger', err);
        });
      },

      onSave(group) {
        this.storeEntity(group, this.endpointUrl).then(res => {
          this.group = res.data;
          this.$emit('save', this.group);
          this.onCancel();
          this.showAlert('success', "New Group created successfully!");
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
