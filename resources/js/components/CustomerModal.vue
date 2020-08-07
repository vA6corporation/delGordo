<template>
<div class="modal fade" id="shoppingModal" role="dialog">
  <div class="modal-dialog">
    <form @submit.prevent="submit" class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Del Gordo</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="email">Por favor, ingrese su DNI, teléfono y dirección de entrega</label>
          <select class="custom-select" v-model="delivery" required>
            <option :value="null">SELECCIONE EL DISTRITO DE ENVIO...</option>
            <option v-for="item in deliveries" :value="item" :key="item.id">{{ `${item.name} envio: S/ ${item.price.toFixed(2)}` }}</option>
          </select>
        </div>
        <div class="form-group">
          <input type="text" v-model="customer.document" class="form-control" placeholder="DNI: 77035606" minlength="8" maxlength="8" required>
        </div>
        <div class="form-group">
          <input type="text" v-model="customer.name" class="form-control" placeholder="Nombres: mi nombre" required>
        </div>
        <div class="form-group">
          <input type="text" v-model="customer.mobile" class="form-control" placeholder="Tel: 934094501" minlength="9" maxlength="9" required>
        </div>
        <div class="form-group">
          <input type="text" v-model="customer.address" class="form-control" placeholder="Direc: Cerro Prieto 269" required>
        </div>
        <div class="form-group">
          <input type="text" v-model="customer.reference" class="form-control" placeholder="Referencia de la direccion" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info">
          Finalizar
        </button>
      </div>
    </form>
  </div>
</div>
</template>

<script>
export default {
  mounted() {
    this.fecthData();
  },
  data() {
    return {
      delivery: null,
      deliveries: [],
      customer: {},
    }
  },
  methods: {
    fecthData() {
      axios.get('deliveries').then(res => {
        console.log(res);
        this.deliveries = res.data.deliveries;
      });
    },
    submit() {
      axios.post('customers', { customer: this.customer }).then(res => {
        console.log(res);
        var customer = res.data.customer;
        var sale = {};
        sale.customer_id = customer.id,
        sale.delivery_id = this.delivery.id;
        sale.delivery_price = this.delivery.price;
        this.$emit('confirm', sale);
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>