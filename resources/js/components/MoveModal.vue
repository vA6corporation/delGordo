<template>
<div class="modal fade" id="moveModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form @submit.prevent="submit" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Mover</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select v-model="officeId" class="custom-select" required>
          <option :value="null">SELECCIONE UNA SUCURSAL</option>
          <option v-for="item in offices" :key="item.id" :value="item.id">{{ item.name }}</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
</template>

<script>
export default {
  props: ['inventory'],
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      officeId: null,
      offices: [],
      office: null,
    }
  },
  methods: {
    fetchData() {
      // axios.get('deletedReasons/all').then(res => {
      //   console.log(res);
      //   this.deletedReasons = res.data.deletedReasons;
      // });
      axios.get('offices').then(res => {
        console.log(res);
        this.offices = res.data.offices;
      });
    },
    submit() {
      $('.modal').modal('hide');
      let inventory = Object.assign({}, this.inventory);
      inventory.office_id = this.officeId;
      axios.put(`inventories/${inventory.id}`, { inventory }).then(res => {
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