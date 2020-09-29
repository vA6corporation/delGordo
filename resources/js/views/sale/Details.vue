<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detalles de Venta</h3>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between">
              <span>Producto</span>
              <span>Codigo</span>
              <span>Peso</span>
          </li>
          <li class="list-group-item d-flex justify-content-between" v-for="(item, index) in sale.items" :key="index">
            {{ item.product.name }}
            <span>{{ item.codigo }}</span>
            <span>{{ item.weight.toFixed(3) }} Kg</span>
          </li>
        </ul>
      </div>
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detalles de Venta</h3>
        </div>
        <table class='table'>
          <thead>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio U.</th>
            <th>Sub Total</th>
          </thead>
          <tbody style="color: white">
            <tr v-for='item in products' :key="item.id">
              <td>{{ item.product.name }}</td>
              <td>{{ item.totalWeight.toFixed(3) }} {{ item.product.short_unit }}</td>
              <td>S/ {{ item.sale_price.toFixed(2) }}</td>  
              <td>S/ {{ (item.totalWeight * item.sale_price).toFixed(2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card">
        <div class="card-header">
          <h3>Datos del envio</h3>
        </div>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between">
            <span>Fecha:</span>
            <span>{{ formatDate(sale.created_at) }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Distrito:</span>
            <span>{{ sale.delivery.name }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Repartidor:</span>
            <span v-if="sale.deliveryman">{{ sale.deliveryman.name }}</span>
            <span v-else>Sin asignar</span>
          </li>
        </ul>
      </div>
    </div>
    <div class="col">
      <div class="form-group">
        <div class="card">
          <div class="card-header">
            <h3>Datos del Cliente</h3>
          </div>
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
              <span>Nombres:</span>
              <span>{{ sale.customer.name }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Direccion:</span>
              <span>{{ sale.customer.address }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Celuar:</span>
              <span>{{ sale.customer.mobile }}</span>
            </li>
          </ul>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h3>Datos de la Venta</h3>
        </div>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between">
            <span>Fecha:</span>
            <span>{{ formatDate(sale.created_at) }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Total en productos:</span>
            <span>S/ {{ (sale.items.map(e => e.sale_price * e.weight).reduce((a, b) => a + b, 0)).toFixed(3) }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Total de envio:</span>
            <span>S/ {{ (sale.delivery_price).toFixed(2) }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Total de venta:</span>
            <span>S/ {{ (sale.items.map(e => e.sale_price * e.weight).reduce((a, b) => a + b, 0) + sale.delivery_price).toFixed(3) }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Usuario:</span>
            <span v-if="sale.user">{{ sale.user.name }}</span>
            <span v-else>Vendido por tienda</span>
          </li>
        </ul>
      </div>
      <div class="card">
        <div class="card-header">
          <h3>Datos del pago</h3>
        </div>
        <ul class="list-group" v-if='sale.payment_id'>
          <li class="list-group-item d-flex justify-content-between">
            <span>Fecha:</span>
            <span>{{ formatDate(sale.payment.created_at) }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Usuario:</span>
            <span v-if="sale.payment.user">{{ sale.payment.user.name }}</span>
            <span v-else>MercadoPago</span>
          </li>
        </ul>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  mounted() {
    this.inventories.push(Object.assign({}, this.inventory));
    this.fetchData();
  },
  data() {
    return {
      sale: {
        delivery_price: 0,
        customer: {},
        items: [],
        delivery: {},
      },
      inventory: {
        weight: null,
        quantity: 1
      },
      inventories: [],
      products: [],
    }
  },
  methods: {
    addInventory() {
      this.inventories.push(Object.assign({}, this.inventory));
    },
    async fetchData() {
      var saleId = this.$route.params.saleId;
      await axios.get(`sales/${saleId}`).then(res => {
        console.log(res);
        let sale = res.data.sale;
        this.sale = res.data.sale;
        let groupItems = res.data.items;
        console.log(groupItems);
        for (const key in groupItems) {
          if (groupItems.hasOwnProperty(key)) {
            const element = groupItems[key];
            let product = element[0];
            product.totalWeight = element.map(e => e.weight).reduce((a, b) => a + b, 0);
            this.products.push(product);
          }
        }
      }).catch(err => {
        console.log(err.response);
      });
    },
    submit() {
      if (!this.inventories.length) {
        return this.$snotify.error('Es necesario al menos 1 ingreso');
      }
      this.inventories.forEach(item => {
        item.sale_id = this.sale.id;
      });
      axios.post('inventories', { inventories: this.inventories }).then(res => {
        console.log(res.data);
        this.$snotify.success('Inventario registrado correctamente');
        this.$router.replace('/inventories')
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>