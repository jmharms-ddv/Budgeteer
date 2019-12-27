<template>
  <div class="card border-base">
    <div class="card-body">
      <h5 class="card-title text-center">
        <slot name="default" :value="value">
          {{ value.name }}
        </slot>
      </h5>
      <div class="d-flex justify-content-between">
        <router-link v-if="open" class="btn btn-outline-accent1 btn-sm" @click="$emit('open')">Open</router-link>
        <router-link v-if="edit" class="btn btn-outline-sub1 btn-sm" @click="$emit('edit')">Edit</router-link>
        <router-link v-if="remove" class="btn btn-outline-sub2 btn-sm" @click="$emit('delete')">Delete</router-link>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      blank: {
        type: Boolean,
        default: false
      },
      value: {
        type: Object,
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

    }
  }
</script>
