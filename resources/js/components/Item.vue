<template>
  <div class="card border-base">
    <div class="card-body">
      <h5 class="card-title text-center">
        <slot name="default" :value="value">
          {{ value.name }}
        </slot>
      </h5>
      <div class="d-flex justify-content-between">
        <router-link v-if="open" :to="{ name: url, params: { id: value.id } }" class="btn btn-outline-accent1 btn-sm">Open</router-link>
        <router-link v-if="edit" class="btn btn-outline-sub1 btn-sm" @click="editItem()">Edit</router-link>
        <router-link v-if="remove" class="btn btn-outline-sub2 btn-sm" @click="deleteItem()">Delete</router-link>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      value: {
        type: Object,
        required: true
      },
      url: {
        type: String,
        required: true
      },
      from: {
        type: String
      },
      open: {
        type: Boolean,
        default: false
      },
      remove: {
        type: Boolean,
        default: false
      },
      edit: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {

      };
    },

    methods: {
      deleteItem() {
        this.$emit('delete');
      },
      editItem() {
        this.$emit('edit');
      }
    }
  }
</script>
