<template>
<div class="modal fade" id="deliverymanModal" tabindex="-1">
  <div class="modal-dialog">
    <form @submit.prevent="submit" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Repartidor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <select v-model="localSale.deliveryman_id" class="custom-select" required>
              <option :value="null">SELECCIONE UN REPARTIDOR...</option>
              <option v-for="item in dealers" :value="item.id" :key="item.id">{{ item.name }}</option>
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
      this.localSale = Object.assign({
        // deliveryman_id: null,
      }, this.sale);
    });
  },
  data() {
    return {
      localSale: {},
      dealers: [],
    }
  },
  methods: {
    submit() {
      $('.modal').modal('hide');
      axios.put(`sales/${this.sale.id}`, { sale: this.localSale }).then(res => {
        console.log(res);
        this.$snotify.success('Repartidor asignado');
        this.$emit('confirm');
      });
    },
    fetchData() {
      axios.get('users/dealers').then(res => {
        console.log(res);
        let dealers = res.data.dealers;
        dealers = dealers.filter(e => e.credentials && e.credentials.deliveriesSale == true);
        this.dealers = dealers;
      });
    }
  }
}
</script>

<style>

</style>