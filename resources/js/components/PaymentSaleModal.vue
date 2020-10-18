<template>
<div class="modal fade" id="paymentSaleModal" tabindex="-1">
  <div class="modal-dialog">
    <form @submit.prevent="submit" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pagar venta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <select v-model="localSale.payment_method_id" class="custom-select">
              <option :value="null">SELECCIONE UN METODO DE PAGO...</option>
              <option v-for="item in paymentMethods" :value="item.name" :key="item.id">{{ item.name }}</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <feather type="x"/>
          Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
          <feather type="save"/>
          Guardar
        </button>
      </div>
    </form>
  </div>
</div>
</template>

<script>
export default {
  props: ['sale'],
  mounted() {
    this.fetchData();
    $('.modal').on('shown.bs.modal', () => {
      this.localSale = Object.assign({}, this.sale);
    });
  },
  data() {
    return {
      localSale: {},
      paymentMethods: [],
    }
  },
  methods: {
    submit() {
      $('.modal').modal('hide');
      this.$emit('confirm', this.localSale);
    },
    fetchData() {
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