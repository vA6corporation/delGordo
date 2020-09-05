<template>
<ul id="categories" class="rounded p-2">
  <a href="#" class="d-flex justify-content-between text-reset p-2" v-for="item in categories" :key="item.id" @click="$emit('input', item.id)">
    <h3 class="mb-0 text-capitalize">{{ item.name }}</h3>
    <feather type="chevron-right"/>
  </a>
  <a href="#" class="d-flex justify-content-between text-reset p-2" @click="$emit('input', null)">
    <h3 class="mb-0">T. las categorias</h3>
    <feather type="chevron-right"/>
  </a>
  <a href="#" v-if='!categories.length' class="text-reset">
    <h3 class="mb-0 text-muted">Ninguna categoria definida</h3>
  </a>
</ul>
</template>

<script>
export default {
  props: ['value'],
  mounted() {
    window.onscroll = function() {
      myFunction()
    };
    
    var header = document.getElementById("categories");
    var sticky = header.offsetTop;

    function myFunction() {
      if (window.pageYOffset > 100) {
        header.classList.add("sticky");
      } else {
        header.classList.remove("sticky");
      }
    }
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
h3 {
  font-size: 1.3rem;
}

.sticky {
  position: fixed;
  top: 7rem;
  /* width: 100%; */
}

#categories {
  /* z-index: 100;
  width: 18rem;
  position:absolute;
  left:3vw;
  top: 25vh; */
  /* font-size: 0.2rem !important; */
  border: 1px solid black;
  background-color: white;
}
</style>