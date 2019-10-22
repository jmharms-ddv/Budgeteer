<template>
  <div id="income">
    <h3></h3>
  </div>
</template>

<script>
  export default {
    created() {
      this.$store.dispatch('loadIncome', {
        id: this.$route.params.id
      });
    }
  }
</script>
