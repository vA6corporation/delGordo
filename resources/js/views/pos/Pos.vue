<template>
<div class="row">
  <pos-modal @confirm="submit"/>
  <div class="col">
    <div class="form-group">
      <form @submit.prevent="searchProducts" class="search-input">
        <input type="text" v-model="key" class="form-control" placeholder="BUSCADOR" required>
      </form>
    </div>
    <div class="form-row">
      <div class="col">
        <div class="card">
          <div class="card-header py-2">
            <div class="d-flex justify-content-between">
              <h3 class="card-title mb-0">Punto de Venta</h3>
              <div class="btn-toolbar">
                <button type="button" data-toggle="modal" data-target="#posModal" class="btn btn-info" to="/deliveries/create">
                  <feather type="save"/>
                  Guardar
                </button>
              </div>
            </div>
          </div>
          <ul class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action" v-for="(item, productIndex) in products" :key="item.id">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <span class="lead">{{ item.name }} {{ item.sub_category.name }}</span>
                  <br>
                  <span>{{ item.category.name }}</span>
                  <br>
                </div>
                <div class="align-items-center">
                  <span class="mr-2">{{ item.counter.toFixed(3) }} Kg - Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(3) }}Kg - Total: S/ {{ (checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) * item.sale_price).toFixed(2) }}</span>
                  <br>
                  <div class="btn-group float-right">
                    <button type="button" class="btn btn-secondary" data-toggle="collapse" :data-target="`#inventoryCollapse${productIndex}`">
                      <feather type="chevron-down"/>
                    </button>
                    <button type="button" @click="removeProduct(item)" class="btn btn-secondary">
                      <feather type="trash-2"/>
                    </button>
                    <button type="button" @click="minusProduct(item)" class="btn btn-info">
                      <feather type="minus"/>
                    </button>
                    <button type="button" @click="plusProduct(item)" class="btn btn-info">
                      <feather type="plus"/>
                    </button>
                  </div>
                </div>
              </div>
              <div class="collapse pt-3" :id="`inventoryCollapse${productIndex}`">
                <select v-model="orderBy" class="custom-select">
                  <option value="date">ORDENAR MAS ANTIGUO</option>
                  <option value="peso">ORDENAR MAS PESO</option>
                </select>
                <table class="table w-100">
                  <thead>
                    <th>Codigo</th>
                    <th>Peso</th>
                    <th>F. Ingreso</th>
                    <th>Incluido</th>
                  </thead>
                  <tbody>
                    <tr v-for="inventory in orderProducts(item.inventory)" :key="inventory.id">
                      <td>{{ inventory.codigo }}</td>
                      <td>{{ inventory.weight.toFixed(3) }}</td>
                      <td>{{ formatDate(inventory.created_at) }}</td>
                      <td>
                        <toggle-button :value='item.picked.includes(e => e == e.id == inventory.id)' @change="pick(item, inventory)"></toggle-button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </a>
          </ul>
        </div>
      </div>
      <div class="col-md-4" v-if="productsPane.length">
        <div class="card">
          <div class="card-header py-2">
            <div class="d-flex justify-content-between">
              <h3 class="card-title mb-0">Resultados</h3>
              <div class="btn-toolbar">
              </div>
            </div>
          </div>
          <ul class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action" @click.prevent="addProduct(item)" v-for="item in productsPane" :key="item.id">
              <span class="lead">{{ item.name }} {{ item.sub_category.name }}</span>
              <br>
              <span>{{ item.category.name }}</span>
            </a>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import PosModal from '@/components/PosModal'
import { mapGetters, mapActions } from 'vuex'

export default {
  components: {
    PosModal,
  },
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      orderBy: 'date',
      key: '',
      deliveries: [],
      productsPane: [],
    }
  },
  computed: {
    ...mapGetters({
      products: 'sale/products',
    }),
  },
  methods: {
    ...mapActions({
      addProduct: 'sale/addProduct',
      plusProduct: 'sale/plusProduct',
      minusProduct: 'sale/minusProduct',
      removeProduct: 'sale/removeProduct',
      removeAllProducts: 'sale/removeAllProducts',
    }),
    orderProducts(products) {
      let items = JSON.parse(JSON.stringify(products));
      switch (this.orderBy) {
        case 'date':
          return items.sort((a, b) => {
            if (a.created_at > b.created_at) {
              return 1;
            }
            if (a.created_at < b.created_at) {
              return -1;
            }
            return 0;
          });
        case 'peso':
          return items.sort((a, b) => {
            if (a.weight < b.weight) {
              return 1;
            }
            if (a.weight > b.weight) {
              return -1;
            }
            return 0;
          }); 
      }
    },
    pick(product, inventory) {
      let index = product.picked.findIndex(e => e.id == inventory.id);
      console.log(index);
      if (index >= 0) {
        product.picked.splice(index, 1);
      } else {
        product.picked.push(inventory);
      }
    },
    submit({ sale, customer }) {
      var inventories = [];
      this.products.forEach(item => {
        inventories.push(...this.checkInventory(item));
      });
      if (inventories.length) {
        sale.channel = 'PUNTO DE VENTA';
        axios.post('sales', { sale, inventories, email: sale.email, processPayment: sale.processPayment }).then(res => {
          console.log(res);
          this.$loading(false);
          this.$snotify.success('Venta registrada correctamente');
          this.productsPane = [];
          this.removeAllProducts();
          let sale = res.data.sale;
          console.log(customer);
          console.log(sale);
          location.replace(`https://wa.me/51${customer.mobile}?text=Hola,%20le%20envio%20los%20detalles%20de%20su%20compra%20en%20DelGordo%20-%20Carnes%20Premium:%20delgordo.com.pe/${sale.id}/checkoutDetails`);
        }).catch(error => {
          this.productsPane = [];
          console.log(error.response);
        });
      } else {
        this.$loading(false);
        this.$snotify.error('Es necesario al menos un producto');
      }
    },
    searchProducts() {
      axios.get(`products/${this.key}/search`).then(res => {
        console.log(res);
        this.productsPane = res.data.products;
      }).catch(err => {
        console.log(err.response);
        this.$snotify.error(err.response.data);
      });
      this.key = '';
    },
    fetchData() {
      this.productsPane = [];
      // this.products = [];
      this.removeAllProducts();
      axios.get('deliveries').then(res => {
        console.log(res.data);
        this.deliveries = res.data.deliveries;
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style scoped>
.list-group-item-action:hover, .list-group-item-action:focus {
  background-color: unset;
  color: white;
}

.search-input input {
  padding-right: 2rem;
  padding-left: 2rem;
  height: 4rem;
  border: none;
  font-size: 17px;
  font-weight: 100;
}
</style>