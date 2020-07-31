<template>
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
      <h2 class="text-center mb-4">S/ {{ totalProducts.toFixed(2) }}</h2>
      <form @submit.prevent="submit" method="post" id="pay" name="pay" >
        <fieldset>
          <!-- <div class="row form-group">
            <label for="description" class="col-md-3 col-form-label">Descripción</label>
            <div class="col">
              <input type="text" class="form-control" name="description" id="description" value="Ítem seleccionado"/>
            </div>                        
          </div> -->
          <!-- <div class="row form-group">
            <label for="transaction_amount" class="col-md-3 col-form-label">Monto a pagar</label>                        
            <div class="col">
              <input name="transaction_amount" class="form-control" id="transaction_amount" value="10"/>
            </div>
          </div> -->
          <div class="row form-group">
            <label for="cardNumber" class="col-md-3 col-form-label">Número de la tarjeta</label>
            <div class="col">
              <input type="text" class="form-control" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" @input="guessPaymentMethod" autocomplete=off required>
            </div>
          </div>
          <div class="row form-group">
            <label for="cardholderName" class="col-md-3 col-form-label">Nombre y apellido</label>
            <div class="col">
              <input type="text" class="form-control" id="cardholderName" data-checkout="cardholderName" required>
            </div>
          </div>
          <div class="row form-group">
            <label for="cardExpirationMonth" class="col-md-3 col-form-label">Mes de vencimiento</label>
            <div class="col">
              <select id="carExpirationMonth" data-checkout="cardExpirationMonth" class="custom-select">
                <option v-for="(item, index) in months"  :value="item" :key="index">{{ item }}</option>
              </select>
              <!-- <input type="text" class="form-control" id="cardExpirationMonth" data-checkout="cardExpirationMonth" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off /> -->
            </div>
          </div>
          <div class="row form-group">
            <label for="cardExpirationYear" class="col-md-3 col-form-label">Año de vencimiento</label>
            <div class="col">
              <select id="cardExpirationYear" data-checkout="cardExpirationYear" class="custom-select">
                <option v-for="(item, index) in years"  :value="item" :key="index">{{ item }}</option>
              </select>
              <!-- <input type="text" class="form-control" id="cardExpirationYear" data-checkout="cardExpirationYear" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off /> -->
            </div>
          </div>
          <div class="row form-group">
            <label for="securityCode" class="col-md-3 col-form-label">Código de seguridad</label>
            <div class="col">
              <input type="text" class="form-control" id="securityCode" data-checkout="securityCode" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off required>
            </div>
          </div>
          <!-- <div class="row form-group">
            <label for="installments" class="col-md-3 col-form-label">Cuotas</label>
            <div class="col">
              <select id="installments" class="form-control" name="installments">
                <option value="1">1</option>
              </select>
            </div>
          </div> -->
          <div class="row form-group">
            <label for="docType" class="col-md-3 col-form-label">Tipo de documento</label>
            <div class="col">
              <select id="docType" class="custom-select" data-checkout="docType"></select>
            </div>
          </div>
          <div class="row form-group">
            <label for="docNumber" class="col-md-3 col-form-label">Número de documento</label>
            <div class="col">
              <input type="text" class="form-control" id="docNumber" data-checkout="docNumber" required>
            </div>
          </div>
          <div class="row form-group">
            <label for="email" class="col-md-3 col-form-label">Email</label>
            <div class="col">
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
          </div>
          <!-- <input type="text" class="form-control" name="description" id="description" value="Ítem seleccionado"/> -->
          <input name="transaction_amount" :value="totalProducts" class="form-control" id="transaction_amount" hidden>
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
              <!-- <input type="submit" class="btn btn-info" value="Pagar"/> -->
            </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  mounted() {
    this.loadMercadoPago();
    var saleId = this.$route.params.saleId;
    axios.get(`sales/${saleId}`).then(res => {
      console.log(res);
      var sale = res.data.sale;
      sale.items.forEach(item => {
        // var product = item.product;
        // product.counter = item.cantidad;
        // product.weight = item.weight;
        console.log(item);
        // this.addProduct(product);
        this.inventories.push(item);
      });
    });
  },
  data() {
    return {
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
      // products: 'sale/products',
      // totalProductsCheckout: 'sale/totalProductsCheckout',
    }),
    totalProducts() {
      return this.inventories.map(e => e.sale_price * e.weight).reduce((a, b) => a + b, 0);
    },
  },
  methods: {
    ...mapActions({
      addProduct: 'sale/addProduct',
    }),
    submit() {
      let $form = document.querySelector('#pay');
      Mercadopago.createToken($form, this.sdkResponseHandler.bind(this));
    },
    loadMercadoPago() {
      Mercadopago.getIdentificationTypes();
      Mercadopago.setPublishableKey("TEST-764ce5ad-be7f-495f-a34a-27bee3b63d28");
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
        // this.getInstallments();
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
      } else {
        document.getElementById("token").value = response.id;
        axios.post('checkout', {
          'payment_method_id': document.getElementById('payment_method_id').value,
          'transaction_amount': document.getElementById('transaction_amount').value,
          'token': document.getElementById("token").value,
          'email': document.getElementById('email').value,
        }).then(res => {
          console.log(res.data);
          this.$router.replace('/store');
          location.reload();
          this.$snotify.success('Compra reazalizada correctamente');
        }).catch(err => {
          console.log(err);
          var res = err.response;
          if (res.data.msg[2]) {
            alert(res.data.msg[0]);
            // window.location.reload(0)
          } else {
            alert(res.data.msg[0]);
            window.location.reload(0)
          }
          console.log(err.response);
        });
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