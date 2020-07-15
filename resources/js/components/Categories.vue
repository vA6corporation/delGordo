<template>
<ul id="categories" class="rounded p-2">
  <a href="#" class="d-flex justify-content-between text-reset p-2" v-for="item in categories" :key="item.id">
    <h3 class="mb-0">{{ item.name }}</h3>
    <feather type="chevron-right"/>
  </a>
  <a href="#" v-if='!categories.length' class="text-reset">
    <h3 class="mb-0 text-muted">Ninguna categoria definida</h3>
  </a>
</ul>
</template>

<script>
export default {
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      categories: [],
    }
  },
  methods: {
    fetchData() {
      axios.get('categories').then(res => {
        console.log(res);
        this.categories = res.data.categories;
      });
    }
  }
}
</script>

<style scoped>
#categories {
  z-index: 100;
  width: 18rem;
  position:absolute;
  left:3vw;
  top: 25vh;
  border: 1px solid black;
  /* border-radius: 5px; */
  background-color: white;
}
</style>