<template>
<div class="form-group">
  <form @submit.prevent="find" class="search-input">
    <input type="text" v-model="key" class="form-control" placeholder="BUSCADOR" required>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <i class="tim-icons icon-simple-remove"></i>
    </button>
  </form>
</div>
</template>

<script>
export default {
  props: ['value'],
  data() {
    return {
      key: null,
    }
  },
  methods: {
    find() {
      this.$emit('input', this.key);
      this.key = null;
      this.$emit('confirm');
    }
  }
}
</script>

<style>

</style>