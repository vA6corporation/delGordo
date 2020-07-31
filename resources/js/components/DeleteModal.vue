<template>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form @submit.prevent="submit" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Motivo para anular</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select v-model="sale.deleted_id" class="custom-select" required>
          <option :value="null">MOTIVO DE BAJA</option>
          <option value="1">Opcion 1</option>
          <option value="2">Opcion 2</option>
          <option value="3">Opcion 3</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Anular</button>
      </div>
    </form>
  </div>
</div>
</template>

<script>
export default {
  props: ['sale'],
  methods: {
    submit() {
      $('.modal').modal('hide');
      axios.post('sales/deleteSale', { sale: this.sale }).then(res => {
        console.log(res);
        this.$emit('confirm');
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>