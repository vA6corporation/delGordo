<template>
<div class="custom-control custom-switch">
  <input class="custom-control-input" type="checkbox" :disabled="disabled" v-model="state" :id="_uid" @change="change" />
  <label class="custom-control-label" :for="_uid"></label>
</div>
</template>

<script>
export default {
  props: ['value', 'disabled'],
  mounted() {
    this.state = this.value;
  },
  data() {
    return {
      state: true,
    }
  },
  methods: {
    change(event) {
      this.$emit('input', this.state);
      this.$emit('change', event);
    }
  },
  watch: {
    value() {
      this.state = this.value;
    }
  }
}
</script>

<style scoped>
.custom-switch {
  padding-left: 3rem;
}
.custom-switch .custom-control-label::after {
  top: 0.12rem;
  left: calc(-2.5rem + 1px);
  cursor: pointer;
}
.custom-switch .custom-control-label::before {
  top: -0.01rem;
  left: -2.6rem;
  height: 1.3rem;
  width: 2.8rem;
  pointer-events: all;
  border-radius: 1rem;
  cursor: pointer;
}
.custom-control-label::after {
  width: 1rem;
  height: 1rem;
}
.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
  transform: translateX(1.5rem);
  cursor: pointer;
}

</style>
