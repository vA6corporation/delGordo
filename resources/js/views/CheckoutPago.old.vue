<template>
<form @submit.prevent="submit"  id="pay" class="row top-one">
  <div class="col my-4 otro">
    <div class="container">
      <h2>DATOS DE PAGO</h2>
      <hr>
      <!--campos dinámicos del producto seleccionado-->
      <input id="id_product" value="345673" type="hidden"/>
      <input id="title_product" value="Producto demo01" type="hidden"/>
      <input id="description_product" value="Compra tu producto x" type="hidden"/>
      <input id="transaction_amount" value="100" type="hidden"/>
      <input id="quantity" value="150" type="hidden"/>
      <!--campos dinámicos del producto seleccionado-->

      <div class="row form-group">
        <!--campos dinamicos de MP-->
        <input class="form-control" id="payment_method_id"  type="hidden"/>
      </div>

      <div class="row form-group">
        <!--se llena de forma dinamica-->
        <!--campos dinamicos-->
        <input class="form-control" id="token" type="hidden"> 
      </div>
      
      <div class="row form-group">
        <label>Correo Electronico</label>
        <input class="form-control" id="email" value="" type="email"/>
      </div>
      
      <div class="row form-group">
        <label>Nombre del titular</label>
        <input class="form-control" data-checkout="cardholderName" id="cardholderName" type="text"/>
      </div>

      <div class="row form-group">
        <label >No. de tarjeta</label>
        <input class="form-control" id="cardNumber" data-checkout="cardNumber" v-on:keyup="guessPaymentMethod" v-on:change="guessPaymentMethod" type="number" />
      </div>
      
      <div class="row form-group">
        <label>Mes</label>
        <select  data-checkout="cardExpirationMonth" class="form-control" id="cardExpirationMonth" >
          <option  selected value="">Seleccionar Mes</option>
          <option v-for="(month, index) in months" :value="month.value" :key="index">{{month.text}}</option>
        </select>
      </div>
    
      <div class="row form-group">
        <label>Año</label>
        <select data-checkout="cardExpirationYear" class="form-control" id="cardExpirationYear" >
          <option  selected value="">Seleccionar Año</option>
          <option v-for="(year, index) in years" :value="year.value" :key="index">{{year.text}}</option>
        </select>
      </div>

      <div class="row form-group">
        <label>Código de seguridad</label>
        <input data-checkout="securityCode" class="form-control" id="securityCode"  type="number"/>
      </div>

      <div class="row form-group">
        <label stacked>Parcialidades</label>
        <select class="form-control" id="monthly_installments">
          <option selected value="">Tipo de pago</option>
        </select>
      </div>

      <div class="row form-group">
        <label>Tipo de documento</label>
        <select class="form-control" data-checkout="docType" id="docType"></select>
      </div>

      <div class="row form-group">
        <label>Número de documento</label>
        <input class="form-control" data-checkout="docNumber" type="text" id="docNumber"/>
      </div>
        
      <div class="form-group">
        <button type="submit" class="btn btn-info float-right">
          Pagar 
        </button>
      </div>
    </div>
  </div>
</form>
</template>

<script>
export default {
  mounted() {
    this.loadMercadoPago();
  },
  data() {
    return {
      checkout: {},
      years : [
        {'value': 2020, 'text': 2020},
        {'value': 2021, 'text': 2021},
        {'value': 2022, 'text': 2022},
        {'value': 2023, 'text': 2023},
        {'value': 2024, 'text': 2024},
        {'value': 2025, 'text': 2025}
      ],
      months: [
        {'value': '01', 'text': '01'},
        {'value': '02', 'text': '02'},
        {'value': '03', 'text': '03'},
        {'value': '04', 'text': '04'},
        {'value': '05', 'text': '05'},
        {'value': '06', 'text': '06'},
        {'value': '07', 'text': '07'},
        {'value': '08', 'text': '08'},
        {'value': '09', 'text': '09'},
        {'value': '10', 'text': '10'},
        {'value': '11', 'text': '11'},
        {'value': '12', 'text': '12'},
      ]
    }
  },
  methods: {
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
        this.getInstallments();
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
          document.getElementById('monthly_installments').options.length = 0;
          response[0].payer_costs.forEach(installment => {
              let opt = document.createElement('option');
              opt.text = installment.recommended_message;
              opt.value = installment.installments;
              document.getElementById('monthly_installments').appendChild(opt);
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
            msg += response.cause[data].code +"-"+ response.cause[data].description;
          }
          alert(msg);
      } else {
        document.getElementById("token").value = response.id;
        // console.log(document.querySelector('#pay'));
        axios.post('checkout', {
          'payment_method_id': document.getElementById('payment_method_id').value,
          'id_product': document.getElementById('id_product').value,
          'title_product': document.getElementById('title_product').value,
          'description_product': document.getElementById('description_product').value,
          'quantity': document.getElementById('quantity').value,
          'transaction_amount': document.getElementById('transaction_amount').value,
          'token': document.getElementById("token").value,
          'monthly_installments': document.getElementById('monthly_installments').value,
          'email': document.getElementById('email').value,
        }).then(res => {
          console.log(res.data);
          if(res.data.msg[2]){
            alert(res.data.msg[0]);
            // window.location.reload(0)
          } else {
            alert(res.data.msg[0]);
            window.location.reload(0)
          }
        }).catch(err => {
          console.log(err.response);
        });
      }
    }
  }
}
</script>

<style scoped>
  form {
    color: white;
    font-size: 1.2rem;
  }
</style>