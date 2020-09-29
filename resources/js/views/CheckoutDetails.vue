<template>
<div>
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
        <h2 class="text-center mb-4">Detalles de su compra</h2>
        <ul class="list-group list-group-flush">
          <table class='table'>
            <thead>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Precio U.</th>
              <th>Sub Total</th>
            </thead>
            <tbody>
              <tr v-for='item in products' :key="item.id">
                <td>{{ item.product.name }}</td>
                <td>{{ item.totalWeight.toFixed(3) }} {{ item.product.short_unit }}</td>
                <td>S/ {{ item.sale_price.toFixed(2) }}</td>  
                <td>S/ {{ (item.totalWeight * item.sale_price).toFixed(2) }}</td>
              </tr>
            </tbody>
          </table>
          <li class="list-group-item text-center lead">
            Costo de envio: S/ {{ sale.delivery_price }}
          </li>
          <li class="list-group-item">
            <h2 class="text-center font-weight-bold mb-0">Total: S/ {{ (items.map(e => e.weight * e.sale_price).reduce((a, b) => a + b, 0) + sale.delivery_price).toFixed(2) }}</h2>
          </li>
        </ul>
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
        this.items = sale.items;
        let groupItems = res.data.items;
        this.setSale(sale);
        for (const key in groupItems) {
          if (groupItems.hasOwnProperty(key)) {
            const element = groupItems[key];
            let product = element[0];
            product.totalWeight = element.map(e => e.weight).reduce((a, b) => a + b, 0);
            this.products.push(product);
          }
        }
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
      ],
      products: [],
      items: [],
      groupitems: [],
    }
  },
  computed: {
    ...mapGetters({
      sale: 'sale/getSale',
      totalProducts: 'sale/totalProducts',
      // products: 'sale/products',
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
          axios.post('sales', { inventories, sale }).then(res => {
            console.log(res);
            axios.get('shoppings/removeAll').catch(err => {
              console.log(err.response);
            });
            let sale = res.data.sale;
            this.removeAllProducts();
            axios.post('checkout', {
              'payment_method_id': document.getElementById('payment_method_id').value,
              'transaction_amount': document.getElementById('transaction_amount').value,
              'token': document.getElementById("token").value,
              'email': document.getElementById('email').value,
              sale,
            }).then(res => {
              this.$loading(false);
              console.log(res.data);
              this.$router.replace(`/${sale.id}/checkout`);
              location.reload();
              this.$snotify.success('Compra reazalizada correctamente');
            }).catch(err => {
              this.$loading(false);
              console.log(err);
              var res = err.response;
              console.log(err.response);
              if (res.data.msg[2]) {
                alert(res.data.msg[0]);
              } else {
                alert(res.data.msg[0]);
                window.location.reload(0)
              }
            });
          }).catch(err => {
            console.log(err.response);
          });
        } else {
          this.$snotify.error('Debe haber almenos un producto disponible');
          $('.modal').modal('hide');
        }
      }
    }
  }
}
</script>

<style scoped>
  .container {
    max-width: 70rem;
  }
  form {
    /* color: white; */
    font-size: 1.5rem;
  }
</style>