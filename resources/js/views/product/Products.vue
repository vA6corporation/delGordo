<template>
  <div class="row">
    <div class="col">
      <div class="form-group">
        <form @submit.prevent="searchProducts" class="search-input">
          <input type="text" v-model="key" class="form-control" placeholder="BUSCADOR" required>
        </form>
      </div>
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title mb-0">Productos</h3>
            <div class="btn-toolbar">
              <router-link class="btn btn-info" to="/products/create">
                <feather type="plus"/>
                Nuevo
              </router-link>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table">
            <caption>
              <page-navigation v-model="page" :pages="pages" :count="count" :items="products.length" @confirm="fetchData"/>
            </caption>
            <thead>
              <th>Nombre</th>
              <th>Categoria</th>
              <th>Sub Categoria</th>
              <th>P. de Venta</th>
              <th>Activo</th>
              <th>Opciones</th>
            </thead>
            <tbody>
              <tr v-for="item in products" :key='item.id'>
                <td>{{ item.name }}</td>
                <td>{{ item.category.name }}</td>
                <td>{{ item.sub_category.name }}</td>
                <td>S/ {{ item.sale_price.toFixed(2) }}</td>
                <td>
                  <toggle-button :value="!disableds.find(e => e.product_id == item.id)" @change="disableProduct(item, $event.target.value)"></toggle-button>
                </td>
                <td>
                  <router-link :to="{ path: `/products/${item.id}/edit` }" class="mr-2">
                    <feather type="edit"/>
                  </router-link>
                  <a href="#" @click.prevent="deleteProduct(item.id)">
                    <feather type="trash-2"/>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      disableds: [],
      products: [],
      page: 1,
      pages: null,
      count: null,
      key: null,
    }
  },
  methods: {
    deleteProduct(id) {
      var ok = confirm('Esta seguro de eliminar?...');
      if (ok) {
        axios.delete(`products/${id}`).then(res => {
          console.log(res);
          this.fetchData();
        });
      }
    },
    searchProducts() {
      axios.get(`products/${this.key}/search`).then(res => {
        console.log(res);
        this.products = res.data.products;
      }).catch(err => {
        console.log(err.response);
        this.$snotify.error(err.response.data);
      });
      this.key = '';
    },
    disableProduct(product) {
      let disabled = this.disableds.find(e => e.product_id == product.id);
      if (disabled) {
        axios.delete(`disableds/${disabled.id}`).then(res => {
          console.log(res);
          this.fetchData();
        });
      } else {
        disabled = { product_id: product.id };
        axios.post('disableds', { disabled }).then(res => {
          console.log(res);
          this.fetchData();
        });
      }
    },
    fetchData() {
      axios.get('disableds').then(res => {
        console.log(res);
        this.disableds = res.data.disableds;
      });
      var params = { page: this.page };
      axios.get('products', { params }).then(res => {
        console.log(res);
        this.products = res.data.products;
        this.pages = res.data.pages;
        this.count = res.data.count;
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>