<template>
<div class="modal fade" id="posModal" tabindex="-1" role="dialog">
  <form @submit.prevent="submit" class="modal-dialog modal-lg" autocomplete="off">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Asistente de pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row form-group">
          <label for="" class="col-form-label col-md-3">
            Distrito de envio
          </label>
          <div class="col">
            <select v-model="delivery" class="custom-select" required>
              <option :value="null">SELECCIONE DISTRITO DE ENVIO...</option>
              <option v-for="item in deliveries" :value="item" :key="item.id">{{ item.name }} / Precio: S/ {{ item.price.toFixed(2) }}</option>
            </select>
          </div>
        </div>
        <!-- <div class="row form-group">
          <label for="" class="col-form-label col-md-3">
            Estado de pago
          </label>
          <div class="col">
            <select v-model="sale.payment_id" class="custom-select">
              <option :value="null">PENDIENTE</option>
              <option value="1">PAGADO</option>
            </select>
          </div>
        </div>
        <div class="row form-group" v-if="!sale.payment_id">
          <label for="" class="col-form-label col-md-3">
            Adjuntar link de pago
          </label>
          <div class="col d-flex align-items-center">
            <toggle-button v-model='sale.processPayment'></toggle-button>
          </div>
        </div>
        <div class="row form-group" v-if="!sale.payment_id">
          <label for="" class="col-form-label col-md-3">
            Contraentrega
          </label>
          <div class="col">
            <select v-model="sale.deliver_date" class="custom-select" required>
              <option :value="null">SELECCIONE SI ES CONTRAENTREGA...</option>
              <option value="true">SI ES CONTRA ENTREGA</option>
              <option value="false">NO ES CONTRA ENTREGA</option>
            </select>
          </div>
        </div>
        <div class="row form-group" v-if="sale.payment_id">
          <label for="" class="col-form-label col-md-3">
            Metodo de pago
          </label>
          <div class="col">
            <select v-model="sale.payment_method_id" class="custom-select" required>
              <option :value="null">SELECCIONE UN METODO DE PAGO...</option>
              <option v-for="item in paymentMethods" :value="item.name" :key="item.id">{{ item.name }}</option>
            </select>
          </div>
        </div> -->
        <div class="row form-group">
          <label for="" class="col-form-label col-md-3">
            DNI del cliente
          </label>
          <div class="col">
            <input type="text" v-model="customer.document" maxlength="8" class="form-control" placeholder="DNI del cliente" @input="findCustomer" required>
          </div>
        </div>
        <div class="row form-group">
          <label for="" class="col-form-label col-md-3">
            Nombres del cliente
          </label>
          <div class="col">
            <input type="text" v-model="customer.name" class="form-control" placeholder="Nombres del cliente" required>
          </div>
        </div>
        <div class="row form-group">
          <label for="" class="col-form-label col-md-3">
            Celular del cliente
          </label>
          <div class="col">
            <input type="text" v-model="customer.mobile" maxlength="9" class="form-control" placeholder="Mobile del cliente" required>
          </div>
        </div>
        <div class="row form-group">
          <label for="" class="col-form-label col-md-3">
            Email del cliente
          </label>
          <div class="col">
            <input type="text" v-model="customer.email" class="form-control" placeholder="Email del cliente" required>
          </div>
        </div>
        <div class="row form-group">
          <label for="" class="col-form-label col-md-3">
            Direccion del cliente
          </label>
          <div class="col">
            <input type="text" v-model="customer.address" class="form-control" placeholder="Direccion del cliente" required>
          </div>
        </div>
        <div class="row form-group">
          <label for="" class="col-form-label col-md-3">
            Referencia de la direccion
          </label>
          <div class="col">
            <input type="text" v-model="customer.reference" class="form-control" placeholder="Referencia de la direccion" required>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <h4>Total: S/ {{ (totalProducts + (delivery ? delivery.price : 0)).toFixed(2) }}</h4>
        <div class="btn-toolbar">
          <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>
  </form>
</div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      paymentMethods: [],
      currentDate: new Date,
      sale: {
        processPayment: false,
        deliver_date: null,
        payment_id: null,
        payment_method_id: null,
      },
      delivery: null,
      deliveries: [],
      customer: {},
    }
  },
  computed: {
    ...mapGetters({
      totalProducts: 'sale/totalProducts',
    }),
  },
  methods: {
    findCustomer() {
      if (this.customer.document.length == 8) {
        var params = {
          dni: this.customer.document,
        };
        axios.get('customers/byDni', { params }).then(res => {
          console.log(res);
          this.customer = res.data.customer;
        }).catch(err => {
          console.log(err.response);
        });
      }
    },
    submit() {
      this.$loading(true)
      // if (this.sale.payment_id) {
      // }
      // this.sale.deliver_date = 'true';
      this.sale.processPayment = false;
      if (this.customer._id) {
        axios.put('customers', { customer: this.customer }).then(res => {
          console.log(res);
          var customer = res.data.customer;
          var sale = Object.assign({}, this.sale);
          sale.delivery_id = this.delivery.id;
          sale.delivery_price = this.delivery.price;
          sale.customer_id = customer.id;
          sale.email = customer.email;
          this.$emit('confirm', sale);
          this.customer = {};
          this.sale = {
            processPayment: false,
            deliver_date: null,
            payment_id: null,
            payment_method_id: null,
          };
        });
      } else {
        axios.post('customers', { customer: this.customer }).then(res => {
          console.log(res);
          var customer = res.data.customer;
          var sale = Object.assign({}, this.sale);
          sale.delivery_id = this.delivery.id;
          sale.delivery_price = this.delivery.price;
          sale.customer_id = customer.id;
          sale.email = customer.email;
          this.$emit('confirm', sale);
          this.customer = {};
          this.sale = {
            processPayment: false,
            deliver_date: null,
            payment_id: null,
            payment_method_id: null,
          };
        });
      }
      $('.modal').modal('hide');
    },
    fetchData() {
      axios.get('deliveries').then(res => {
        console.log(res);
        this.deliveries = res.data.deliveries;
      });
      axios.get('paymentMethods').then(res => {
        console.log(res);
        this.paymentMethods = res.data.paymentMethods;
      });
    }
  }
}
</script>

<style>

</style>