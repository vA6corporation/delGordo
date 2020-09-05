<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Nuevo Producto</h3>
        </div>
        <div class="card-body">
          <div class="row form-group">
            <div class="col">
              <label for="">Nombre</label>
              <input type="text" v-model="product.name" class="form-control" placeholder="Nombre" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Categoria</label>
              <select class="custom-select text-uppercase" v-model="product.category_id" required>
                <option :value="null" disabled selected>SELECCIONE UNA CATEGORIA</option>
                <option v-for="item in categories" :value="item.id" :key="item.id">{{ item.name }}</option>
              </select>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Sub Categoria</label>
              <select class="custom-select text-uppercase" v-model="product.sub_category_id" required>
                <option :value="null" disabled selected>SELECCIONE UNA SUB CATEGORIA</option>
                <option v-for="item in subCategories" :key="item.id" :value="item.id">{{ item.name }}</option>
              </select>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Unidad</label>
              <select class="custom-select text-uppercase" v-model="product.unit_code" required>
                <!-- <option :value="null" disabled selected>SELECCIONE UNA SUB CATEGORIA</option> -->
                <option v-for="(item, index) in unitCodes" :key="index" :value="item.unitCode">{{ item.name }}</option>
              </select>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Precio de Venta (Incluir IGV)</label>
              <input type="number" v-model.number="product.sale_price" step="any" min="0" class="form-control" placeholder="Precio de Venta (Incluir IGV)" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Descripcion</label>
              <textarea rows="2" v-model="product.description" class="form-control" placeholder="Descripcion (Opcional)"></textarea>
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
    <div class="col">
      <div class="card">
        <input id="inputFile" @change="setImage($event.target.files[0])" type="file" hidden>
        <div class="card-header">
          <h3>Imagen del Producto</h3>
        </div>
        <label for="inputFile" class="card-body text-white text-center border" style="cursor:pointer">
          <feather class="feather-xxl" type="image" v-if='!src'/>
          <img id="priviewImage" class="img-fluid" :src="src" alt="imagen" v-else>
        </label>
      </div>
    </div>
  </form>
</template>

<script>
import Compressor from 'compressorjs'

export default {
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      categories: [],
      subCategories: [],
      srcTmp: null,
      file: null,
      product: {
        unit_code: 'KGM',
        category_id: null,
        sub_category_id: null,
      },
    }
  },
  computed: {
    src() {
      if (this.product.image_url) {
        return `/api/products/${this.product.image_url}`;
      } else if (this.srcTmp) {
        return this.srcTmp;
      } else {
        return null;
      }
    }
  },
  methods: {
    setImage(file) {
      this.file = file;
      // console.log(file);
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        // console.log(reader.result);
        this.srcTmp = reader.result;
      };
    },
    fetchData() {
      axios.get('categories').then(res => {
        console.log(res);
        this.categories = res.data.categories;
      });
      axios.get('subCategories').then(res => {
        console.log(res);
        this.subCategories = res.data.subCategories;
      });
    },
    async submit() {
      if (!this.file) {
        return this.$snotify.error('Es necesario una imagen');
      }
      var image = await new Promise((resolve, reject) => {
        new Compressor(this.file, {
          quality: 0.2,
          success: async (result) => {
            const formData = new FormData();
            resolve(result);
            // formData.append('file', result, result.name);
            // await this.$http.post('uploadsPhotos', formData).then(res => {
            //   var file = res.body.file;
            //   this.pictures.push(file)
            // });
          }
        });
      });
      var formData = new FormData();
      formData.append('image', image);
      var config = {
          headers: {
              'content-type': 'multipart/form-data'
          }
      }
      var path = await axios.post('products/image', formData, config).then(res => res.data);
      this.product.image_url = path;
      axios.post('products', { product: this.product }).then(res => {
        console.log(res.data);
        this.$snotify.success('Producto registrado correctamente');
        this.$router.replace('/products')
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>