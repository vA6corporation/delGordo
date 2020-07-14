<template>
  <form @submit.prevent="submit" class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Nuevo Producto</h3>
          <!-- <div class="d-flex justify-content-between">
            <div class="btn-toolbar">
              <button type="submit" class="btn btn-info">
                <feather type="save"/>
                Guardar
              </button>
            </div>
          </div> -->
        </div>
        <div class="card-body">
          <div class="row form-group">
            <div class="col">
              <label for="">Nombre</label>
              <input type="text" class="form-control" placeholder="Nombre" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Categoria</label>
              <select class="custom-select" required>
                <option value="" disabled selected>SELECCIONE UNA CATEGORIA</option>
                <option value="1">OPCION</option>
              </select>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Sub Categoria</label>
              <select class="custom-select" required>
                <option value="" disabled selected>SELECCIONE UNA SUB CATEGORIA</option>
                <option value="1">OPCION</option>
              </select>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Precio de Venta (Incluir IGV)</label>
              <input type="number" step="any" class="form-control" placeholder="Precio de Venta (Incluir IGV)" required>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button type="submit" class="btn btn-info float-right">
                <feather type="save"/>
                Guardar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  data() {
    return {
      product: {},
    }
  },
  methods: {
    submit() {
      axios.post('products', { product: this.product }).then(res => {
        console.log(res.data);
      });
    }
  }
}
</script>

<style>

</style>