<template>
  <div>
    <div class="card-deck mb-4" v-for="row in rowsFull">
      <template v-for="col in deckSize">
        <item :value="items[(row - 1)*deckSize + col - 1]"
              :url="url"
              :from="from"
              :open="canOpen((row - 1)*deckSize + col - 1)"
              :remove="remove"
              :edit="edit"
              @delete="deleteItem((row - 1)*deckSize + col - 1)"
              @edit="editItem((row - 1)*deckSize + col - 1)">
          <template v-if="content" #default="slotProps">
            {{ slotProps.value[content] }}
          </template>
        </item>
      </template>
    </div>
    <div class="row">
      <div v-if="colsInPartialRow > 0" :class="'col-sm-' + (12/deckSize)*colsInPartialRow">
        <div class="card-deck mb-4">
          <template v-for="col in colsInPartialRow">
            <item :value="items[rowsFull*deckSize + col - 1]"
                  :url="url"
                  :from="from"
                  :open="canOpen(rowsFull*deckSize + col - 1)"
                  :remove="remove"
                  :edit="edit"
                  @delete="deleteItem(rowsFull*deckSize + col - 1)"
                  @edit="editItem(rowsFull*deckSize + col - 1)">
              <template v-if="content" #default="slotProps">
                {{ slotProps.value[content] }}
              </template>
            </item>
          </template>
        </div>
      </div>
      <div v-if="add" :class="'col-sm-' + 12/deckSize">
        <div class="card border-base text-center">
          <a class="card-body blank-body" @click="addItem()">
            <h1 class="card-title">+</h1>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
  .blank-body:hover {
    background-color: #1D4880;
    color: white;
  }
  .blank-body .card-title {
    color: #1D4880;
  }
  .blank-body:hover .card-title {
    color: white;
  }
</style>

<script>
  import Item from './Item.vue'
  export default {
    components: {
      Item
    },
    props: {
      items: {
        type: Array,
        required: true
      },
      url: {
        type: String,
        required: true
      },
      itemname: {
        type: String,
        default: function() {
          return this.url.charAt(0).toUpperCase() + this.url.slice(1);
        }
      },
      from: {
        type: String
      },
      add: {
        type: Boolean,
        default: false
      },
      open: {
        type: Boolean,
        default: false
      },
      allowOpen: {
        type: Array,
        default: function() {
          return [];
        }
      },
      allowOpenIf: {
        type: Array,
        default: function() {
          return [];
        }
      },
      allowEdit: {
        type: Array,
        default: function() {
          return [];
        }
      },
      allowRemove: {
        type: Array,
        default: function() {
          return [];
        }
      },
      remove: {
        type: Boolean,
        default: false
      },
      edit: {
        type: Boolean,
        default: false
      },
      content: {
        type: String
      }
    },

    data() {
      return {
        deckSize: 3
      };
    },

    methods: {
      getItemIndex(row, col) {
        return (row - 1)*this.deckSize + col - 1;
      },
      addItem() {
        this.$emit('add-item');
      },
      deleteItem(index) {
        this.$emit('delete-item', index);
      },
      editItem(index) {
        this.$emit('edit-item', index);
      },
      canOpen(index) {
        for(let i in this.allowOpen) {
          if(this.allowOpen[i].id == this.items[index].id) {
            return true;
          }
        }
        return this.open;
      },
      canEdit(index) {
        for(let i in this.allowEdit) {
          if(this.allowEdit[i].id == this.items[index].id) {
            return true;
          }
        }
        return this.edit;
      },
      canRemove(index) {
        for(let i in this.allowRemove) {
          if(this.allowRemove[i].id == this.items[index].id) {
            return true;
          }
        }
        return this.remove;
      },
      capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
      }
    },

    computed: {
      rowsFull() {
        return Math.floor(this.items.length / this.deckSize);
      },

      colsInPartialRow() {
        return (this.items.length % this.deckSize);
      }
    }
  }
</script>
