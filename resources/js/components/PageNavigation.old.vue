<template lang="pug">
  nav.d-flex.justify-content-between
    div
      | Mostrando 
      b {{ (value * 20) - 19 }} - {{ (value * 20) - (20 - items) }} 
      | de {{ count }} Items
    ul.pagination
      template(v-if='(Number(value) - 4) > 0')
        li.page-item
          a.page-link(href='#' @click.prevent='go(1)') 1
      template(v-for='index in pages')
        li.page-item(:class='{ active: index == value }' v-if='index < (Number(value) + 4) && index > (Number(value) - 4)')
          a.page-link(href='#' @click.prevent='go(index)') {{ index }}
      template(v-if='!((Number(value) + 4) > pages)')
        li.page-item
          a.page-link(href='#' @click.prevent='go(pages)') {{ pages }}
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