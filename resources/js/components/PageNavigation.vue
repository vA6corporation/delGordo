<template>
<nav>
  <ul class="pagination justify-content-center mb-0 mt-3">
    <li class="page-item" :class="{ disabled: value == 1 }">
      <a class="page-link" href="#" tabindex="-1" @click.prevent="go(value - 1)">Previous</a>
    </li>
    <template v-if="(value - 4) > 0">
      <li class="page-item">
        <a class="page-link" href="#" @click.prevent="go(1)">1</a>
      </li>
    </template>
    <template v-for="index in pages">
      <li class="page-item" :key="index" :class="{ active: index == value }" v-if="index < (Number(value) + 4) && index > (Number(value) - 4)">
        <a class="page-link" href="#" @click.prevent="go(index)">{{ index }}</a>
      </li>
    </template>
    <template v-if="!((value + 4) > pages)">
      <li class="page-item">
        <a class="page-link" href="#" @click.prevent="go(pages)">{{ pages }}</a>
      </li>
    </template>
    <!-- <li class="page-item">
      <a class="page-link" href="#">2</a>
    </li> -->
    <!-- <li class="page-item">
      <a class="page-link" href="#">3</a>
    </li> -->
    <li class="page-item" :class="{ disabled: pages == value }">
      <a class="page-link" href="#" @click.prevent="go(value + 1)">Next</a>
    </li>
  </ul>
</nav>
</template>

<script>
export default {
  props: ['pages', 'count', 'items', 'value'], //value = page
  methods: {
    go(page) {
      this.$emit('input', page);
      this.$emit('confirm');
    }
  }
}
</script>