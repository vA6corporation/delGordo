<template>
<div class="modal fade" id="checkoutModal" role="dialog" style="margin-top:10vh">
  <div class="modal-dialog">
    <form @submit.prevent="submit" class="modal-content" method="post" id="pay" name="pay">
      <div class="modal-header">
        <img src="/assets/images/logo.png" style="height:8vh" alt="">
        <div>
          <span class="lead">Total a pagar: S/ {{ (totalProducts).toFixed(2) }}</span>
          <br>
          <span class="lead">Delivery: S/ {{ (sale.delivery_price || 0).toFixed(2) }}</span>
        </div>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="col-7">
            <label for="card_name">Nombre</label>
            <input id="cardholderName" data-checkout="cardholderName" type="text" class="form-control" placeholder="Del Gordo" required>
          </div>
          <div class="col-5">
            <label for="cardNumber">Número</label>
            <input id="cardNumber" data-checkout="cardNumber" @input="guessPaymentMethod" type="text" class="form-control" placeholder="4557 8808 1234 5678" required>
          </div>
        </div>
        <br>
        <div class="form-row">
          <div class="col-4">
            <label for="card_month">Mes</label>
            <select id="cardExpirationMonth" data-checkout="cardExpirationMonth" class="form-control" required>
              <option v-for="(item, index) in months"  :value="item" :key="index">{{ item }}</option>
            </select>
          </div>
          <div class="col-4">
            <label for="card_year">Año</label>
            <select id="cardExpirationYear" data-checkout="cardExpirationYear" class="form-control" required>
              <option v-for="(item, index) in years"  :value="item" :key="index">{{ item }}</option>
            </select>
          </div>
          <div class="col-4">
            <label for="card_year">CVV</label>
            <input id="securityCode" data-checkout="securityCode" type="text" class="form-control" placeholder="123" required>
          </div>
        </div>
        <br>
        <div class="form-row">
          <div class="col-6">
            <label for="card_doctype">Tipo DOC</label>
            <select id="docType" data-checkout="docType" class="form-control" required disabled>
              <option selected>DNI</option>
              <option>RUC</option>
            </select>
          </div>
          <div class="col-6">
            <label for="card_docnumber">Número DOC</label>
            <input id="docNumber" data-checkout="docNumber" type="text" class="form-control" placeholder="01234567" required>
          </div>
        </div>
        <!-- <input type="hidden" name="transactionAmount" id="transactionAmount" value="100" />
        <input type="hidden" name="paymentMethodId" id="paymentMethodId" />
        <input type="hidden" name="description" id="description" /> -->
      </div>
      <div class="modal-footer">  
        <button type="submit" class="btn btn-block" style="background-color: rgb(245, 166, 35)!important;  width: 100%;">
          Pagar
        </button>
      </div>
    </form>
  </div>
</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  mounted() {
    this.loadMercadoPago();
  },
  data() {
    return {
      token: process.env.VUE_APP_TOKEN || 'TEST-dd114825-b697-4ce1-9f03-e5fce322b40f',
      transaction_amount: null,
      payment_method_id: null,
      years: [
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
      products: 'sale/products',
      totalProducts: 'sale/totalProducts',
    }),
  },
  methods: {
    ...mapActions({
      addProduct: 'sale/addProduct',
      removeAllProducts: 'sale/removeAllProducts',
      setSale: 'sale/setSale',
    }),
    submit() {
      $('.modal').modal('hide');
      this.$loading(true);
      let $form = document.querySelector('#pay');
      Mercadopago.createToken($form, this.sdkResponseHandler.bind(this));
    },
    loadMercadoPago() {
      // console.log(this.token);
      Mercadopago.getIdentificationTypes();
      Mercadopago.setPublishableKey(this.token);
    },
    guessPaymentMethod() {
      let cardnumber = document.getElementById("cardNumber").value;
      if (cardnumber.length >= 6) {
        let bin = cardnumber.substring(0,6);
        Mercadopago.getPaymentMethod({ "bin": bin }, this.setPaymentMethod.bind(this));
      }
    },
    setPaymentMethod(status, response) {
      // console.log(this);
      if (status == 200) {
        let paymentMethodId = response[0].id;
        // let element = document.getElementById('payment_method_id');
        // element.value = paymentMethodId;
        this.payment_method_id = paymentMethodId;
      } else {
        alert(`payment method info error: ${response}`);
      }
    }, 
    getInstallments() {
      Mercadopago.getInstallments({
        payment_method_id: this.payment_method_id,
        amount: 1,
        // "payment_method_id" : document.getElementById('payment_method_id').value,
        // "amount": parseFloat(document.getElementById('transaction_amount').value)
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
        // document.getElementById("token").value = response.id;
        var inventories = [];
        this.products.forEach(item => {
          inventories.push(...this.checkInventory(item));
        });
        if (inventories.length) {
          var sale = this.sale;
          sale.channel = 'TIENDA VIRTUAL';
          sale.deliver_date = 'true';
          axios.post('sales/shop', { 
            inventories, 
            sale,
            // 'payment_method_id': document.getElementById('payment_method_id').value,
            // 'transaction_amount': document.getElementById('transaction_amount').value,
            // 'token': document.getElementById("token").value,
            payment_method_id: this.payment_method_id,
            transaction_amount: this.totalProducts + this.sale.delivery_price,
            token: response.id,
            email: sale.email,
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
            this.$snotify.success('Compra reazalizada correctamente');
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

<style>
.creditCardForm {
    max-width: 700px;
    background-color: #fff;
    margin: 100px auto;
    overflow: hidden;
    padding: 25px;
    color: #4c4e56;
}
.creditCardForm label {
    width: 100%;
    margin-bottom: 10px;
}
.creditCardForm .heading h1 {
    text-align: center;
    font-family: 'Open Sans', sans-serif;
    color: #4c4e56;
}
.creditCardForm .payment {
    float: left;
    font-size: 18px;
    padding: 10px 25px;
    margin-top: 20px;
    position: relative;
}
.creditCardForm .payment .form-group {
    float: left;
    margin-bottom: 15px;
}
.creditCardForm .payment .form-control {
    line-height: 40px;
    height: auto;
    padding: 0 16px;
}
.creditCardForm .owner {
    width: 63%;
    margin-right: 10px;
}
.creditCardForm .CVV {
    width: 35%;
}
.creditCardForm #card-number-field {
    width: 100%;
}
.creditCardForm #expiration-date {
    width: 49%;
}
.creditCardForm #credit_cards {
    width: 50%;
    margin-top: 25px;
    text-align: right;
}
.creditCardForm #pay-now {
    width: 100%;
    margin-top: 25px;
}
.creditCardForm .payment .btn {
    width: 100%;
    margin-top: 3px;
    font-size: 24px;
    background-color: #2ec4a5;
    color: white;
}
.creditCardForm .payment select {
    padding: 10px;
    margin-right: 15px;
}
.transparent {
    opacity: 0.2;
}
@media(max-width: 650px) {
    .creditCardForm .owner,
    .creditCardForm .CVV,
    .creditCardForm #expiration-date,
    .creditCardForm #credit_cards {
        width: 100%;
    }
    .creditCardForm #credit_cards {
        text-align: left;
    }
}
</style>