<template>
<div v-if="sale.payment_id">
  <div class="container">
    <div class="row">
      <div class="col">
        <router-link to="/store" class="btn btn-dark my-3">
          <feather type="chevron-left"/> 
          Ir a la tienda
        </router-link>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <h1 class="text-center">Compra procesada con exito</h1>
        <h2 class="text-center mb-4">Detalles de su compra</h2>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-for="item in sale.items" :key="item.id">
            <div class="form-row">
              <div class="col-2">
                <img :src="`/api/products/${item.product.image_url}`" class="img-fluid" alt="producto">
              </div>
              <div class="col text-center">
                <h5>
                  {{ item.product.name }} {{ item.weight }} Kg
                </h5>
                <h5 class="text-success">Cantidad: {{ item.weight }} Kg</h5>
                <p>Precio: S/ {{ (item.sale_price).toFixed(2) }}</p>
              </div>
            </div>
          </li>
          <li class="list-group-item text-center lead">
            Costo de envio: S/ {{ sale.delivery_price }}
          </li>
          <li class="list-group-item">
            <h2 class="text-center font-weight-bold mb-0">Total: S/ {{ (totalProducts + sale.delivery_price).toFixed(2) }}</h2>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div v-else>
  <div>
    <div class="container">
      <div class="row">
        <div class="col">
          <router-link to="/store" class="btn btn-dark my-3">
            <feather type="chevron-left"/> 
            Seguir Comprando
          </router-link>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h2 class="text-center mb-4">S/ {{ (totalProducts + sale.delivery_price).toFixed(2) }}</h2>
          <form @submit.prevent="submit" method="post" id="pay" name="pay" >
            <fieldset>
              <div class="row form-group">
                <label for="cardNumber" class="col-md-3 col-form-label">Número de la tarjeta</label>
                <div class="col">
                  <input type="text" class="form-control" placeholder="Numero de tarjeta" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" @input="guessPaymentMethod" autocomplete=off required>
                </div>
              </div>
              <div class="row form-group">
                <label for="cardholderName" class="col-md-3 col-form-label">Nombre y apellido</label>
                <div class="col">
                  <input type="text" class="form-control" placeholder="Nombres" id="cardholderName" data-checkout="cardholderName" required>
                </div>
              </div>
              <div class="row form-group">
                <label for="cardExpirationMonth" class="col-md-3 col-form-label">Mes de vencimiento</label>
                <div class="col">
                  <select id="carExpirationMonth" data-checkout="cardExpirationMonth" class="custom-select">
                    <option v-for="(item, index) in months"  :value="item" :key="index">{{ item }}</option>
                  </select>
                </div>
              </div>
              <div class="row form-group">
                <label for="cardExpirationYear" class="col-md-3 col-form-label">Año de vencimiento</label>
                <div class="col">
                  <select id="cardExpirationYear" data-checkout="cardExpirationYear" class="custom-select">
                    <option v-for="(item, index) in years"  :value="item" :key="index">{{ item }}</option>
                  </select>
                </div>
              </div>
              <div class="row form-group">
                <label for="securityCode" class="col-md-3 col-form-label">Código de seguridad</label>
                <div class="col">
                  <input type="text" class="form-control" placeholder="Codigo de seguridad" id="securityCode" data-checkout="securityCode" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off required>
                </div>
              </div>
              <div class="row form-group">
                <label for="docType" class="col-md-3 col-form-label">Tipo de documento</label>
                <div class="col">
                  <select id="docType" class="custom-select" data-checkout="docType"></select>
                </div>
              </div>
              <div class="row form-group">
                <label for="docNumber" class="col-md-3 col-form-label">Número de documento</label>
                <div class="col">
                  <input type="text" class="form-control" placeholder="Numero de documento" id="docNumber" data-checkout="docNumber" required>
                </div>
              </div>
              <div class="row form-group" hidden>
                <label for="email" class="col-md-3 col-form-label">Email</label>
                <div class="col">
                  <input type="email" class="form-control" id="email" name="email">
                </div>
              </div>
              <input name="transaction_amount" :value="totalProducts + sale.delivery_price" class="form-control" id="transaction_amount" hidden>
              <input name="payment_method_id" id="payment_method_id" hidden>
              <input class="form-control" id="token" hidden>
              <select id="installments" class="form-control" name="installments" hidden>
                <option value="1">1</option>
              </select>
              <div class="row">
                <div class="col">
                  <button type="submit" class="btn btn-info float-right">
                    <feather type="check"/>
                    Pagar
                  </button>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  mounted() {
    this.loadMercadoPago();
    var saleId = this.$route.params.saleId;
    if (saleId) {
      axios.get(`sales/${saleId}`).then(res => {
        console.log(res);
        var sale = res.data.sale;
        this.setSale(sale);
        this.products = sale.items;
        sale.items.forEach(item => {
          console.log(item);
          this.inventories.push(item);
          var product = item.product;
          product.counter = item.counter;
          console.log(product);
          this.addProduct(product);
        });
      });
    } else {
      if (!this.sale.customer_id) {
        this.$router.replace(`/shopping`);
      }
    }
  },
  data() {
    return {
      token: process.env.VUE_APP_TOKEN || 'TEST-dd114825-b697-4ce1-9f03-e5fce322b40f',
      inventories: [],
      checkout: {},
      years : [
        2020,
        2021,
        2022,
        2023,
        2024,
        2025
      ],
      months: [
        '01',
        '02',
        '03',
        '04',
        '05',
        '06',
        '07',
        '08',
        '09',
        '10',
        '11',
        '12',
      ]
    }
  },
  computed: {
    ...mapGetters({
      sale: 'sale/getSale',
      totalProducts: 'sale/totalProducts',
      products: 'sale/products',
    }),
  },
  methods: {
    ...mapActions({
      addProduct: 'sale/addProduct',
      removeAllProducts: 'sale/removeAllProducts',
      setSale: 'sale/setSale',
    }),
    submit() {
      this.$loading(true);
      let $form = document.querySelector('#pay');
      Mercadopago.createToken($form, this.sdkResponseHandler.bind(this));
    },
    loadMercadoPago() {
      Mercadopago.getIdentificationTypes();
      Mercadopago.setPublishableKey(this.token);
    },
    guessPaymentMethod() {
      let cardnumber = document.getElementById("cardNumber").value;
      if (cardnumber.length >= 6) {
      let bin = cardnumber.substring(0,6);
      Mercadopago.getPaymentMethod({
        "bin": bin
      }, this.setPaymentMethod.bind(this));
      }
    },
    setPaymentMethod(status, response) {
      if (status == 200) {
        let paymentMethodId = response[0].id;
        let element = document.getElementById('payment_method_id');
        element.value = paymentMethodId;
      } else {
        alert(`payment method info error: ${response}`);
      }
    }, 
    getInstallments() {
      Mercadopago.getInstallments({
        "payment_method_id" : document.getElementById('payment_method_id').value,
        "amount": parseFloat(document.getElementById('transaction_amount').value)
      }, function (status, response) {
      if (status == 200) {
        document.getElementById('installments').options.length = 0;
        response[0].payer_costs.forEach(installment => {
          let opt = document.createElement('option');
          opt.text = installment.recommended_message;
          opt.value = installment.installments;
          document.getElementById('installments').appendChild(opt);
        });
      } else {
        alert(`installments method info error: ${response}`);
      }
      });
    },
    sdkResponseHandler(status, response) {
      console.log(status)
      console.log(response)
      if (status != 200 && status != 201) {
        let msg = "";
        for (let data in response.cause) {
          msg += response.cause[data].code + "-" + response.cause[data].description;
        }
        alert(msg);
        this.$loading(false);
      } else {
        document.getElementById("token").value = response.id;
        var inventories = [];
        this.products.forEach(item => {
          inventories.push(...this.checkInventory(item));
        });
        if (inventories.length) {
          var sale = this.sale;
          sale.channel = 'TIENDA VIRTUAL';
          sale.deliver_date = 'true';
          axios.post('sales/shop', { 
            inventories, sale,
            'payment_method_id': document.getElementById('payment_method_id').value,
            'transaction_amount': document.getElementById('transaction_amount').value,
            'token': document.getElementById("token").value,
            'email': sale.email,
          }).then(res => {
            console.log(res);
            axios.get('shoppings/removeAll').catch(err => {
              console.log(err.response);
            });
            let sale = res.data.sale;
            this.removeAllProducts();
            this.$loading(false);
            console.log(res.data);
            this.$router.replace(`/${sale.id}/checkoutDetails`);
            // location.reload();
            this.$snotify.success('Compra reazalizada correctamente');
            // axios.post('checkout', {
            //   'payment_method_id': document.getElementById('payment_method_id').value,
            //   'transaction_amount': document.getElementById('transaction_amount').value,
            //   'token': document.getElementById("token").value,
            //   'email': document.getElementById('email').value,
            //   sale,
            // }).then(res => {
            //   this.$loading(false);
            //   console.log(res.data);
            //   this.$router.replace(`/${sale.id}/checkout`);
            //   location.reload();
            //   this.$snotify.success('Compra reazalizada correctamente');
            // }).catch(err => {
            //   this.$loading(false);
            //   console.log(err);
            //   var res = err.response;
            //   console.log(err.response);
            //   if (res.data.msg[2]) {
            //     alert(res.data.msg[0]);
            //   } else {
            //     alert(res.data.msg[0]);
            //     window.location.reload(0)
            //   }
            // });
          }).catch(error => {
            this.$loading(false);
            console.log(error);
            try {
              alert(error.data.message);
            } catch (error) {
              alert('Revise los datos de su tarjeta');
            }
          });
        } else {
          this.$loading(false);
          this.$snotify.error('Debe haber almenos un producto disponible');
          $('.modal').modal('hide');
        }
      }
    }
  }
}
</script>

<style scoped>
  form {
    /* color: white; */
    font-size: 1.2rem;
  }
</style>