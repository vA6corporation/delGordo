<template>
  <div class="row">
    <delete-modal :sale="sale" @confirm="fetchData"/>
    <div class="col">
      <find-input v-model="key" @confirm="findSales"/>
      <div class="form-row">
        <div class="col form-group">
          <div class="input-group">
            <datepicker v-model="sd" append="F. Incial" @change="fetchData"/>
          </div>
        </div>
        <div class="col form-group">
          <div class="input-group">
            <datepicker v-model="ed" append="F. Final" @change="fetchData"/>
          </div>
        </div>
      </div>
      <div id="collapse" class="collapse">
        <div class="form-group">
          <select v-model="payed" class="custom-select" @change="fetchData">
            <option :value="null">PAGADOS Y PENDIENTES DE PAGO</option>
            <option :value="true">SOLO PAGADOS</option>
            <option :value="false">SOLO PENDIENTES DE PAGO</option>
          </select>
        </div>
        <div class="form-group">
          <select v-model="delivered" class="custom-select" @change="fetchData">
            <option :value="null">ENTREGADOS Y PENDIENTES DE ENTREGA</option>
            <option :value="true">SOLO ENTREGADOS</option>
            <option :value="false">SOLO PENDIENTES DE ENTREGA</option>
          </select>
        </div>
        <div class="form-group">
          <select v-model="deleted" class="custom-select" @change="fetchData">
            <option :value="null">ANULADOS Y NO ANULADOS</option>
            <option :value="true">SOLO ANULADOS</option>
            <option :value="false">SOLO NO ANULADOS</option>
          </select>
        </div>
      </div>
      <div class="text-center form-group">
        <a href="#" data-toggle="collapse" data-target="#collapse">Filtros adicionales</a>
      </div>
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title mb-0">Ventas</h3>
            <div class="btn-toolbar">
              <!-- <router-link class="btn btn-info" to="/sales/create">
                <feather type="plus"/>
                Nuevo
              </router-link> -->
            </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table">
            <caption>
              <page-navigation v-model="page" :pages="pages" :count="count" :items="sales.length" @confirm="fetchData"/>
            </caption>
            <thead>
              <th>F/H de venta</th>
              <th>Cliente</th>
              <th>Codigo</th>
              <!-- <th>Sub Categoria</th> -->
              <th>T. de Venta</th>
              <th>Pagado</th>
              <th>Entrega</th>
              <th>Anulado</th>
              <th>Opciones</th>
            </thead>
            <tbody>
              <tr v-for="item in sales" :key='item.id'>
                <td>{{ formatDate(item.created_at) }} / {{ formatTime(item.created_at) }}</td>
                <td>{{ item.customer.name }}</td>
                <td>{{ formatCode(item.id) }}</td>
                <td>S/ {{ (item.items.map(e => e.sale_price * e.weight).reduce((a, b) => a + b, 0) + item.delivery_price).toFixed(2) }}</td>
                <td v-if="item.payment_id">Pagado</td>
                <td v-else>Pendiente</td>
                <td v-if="item.delivered_date">Entregado</td>
                <td v-else>Pendiente</td>
                <td v-if="item.deleted_at">Si</td>
                <td v-else>No</td>
                <td>
                  <div class="btn-toolbar">
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <feather type="more-vertical"/>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <router-link :to="{ path: `/sales/${item.id}/details` }" class="dropdown-item">Detalles</router-link>
                      <a href="#" class="dropdown-item" @click.prevent="payedSale(item)">Marcar Pago</a>
                      <a href="#" class="dropdown-item" @click.prevent="deliveredSale(item)">Marcar Entrega</a>
                      <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteModal" @click.prevent="sale = item">Anular</a>
                      <!-- <router-link :to="{ path: `/inventories/${item.id}/details` }" class="dropdown-item">Ver Paketes</router-link> -->
                      <!-- <button class="dropdown-item" type="button">Another action</button> -->
                      <!-- <button class="dropdown-item" type="button">Retirar</button> -->
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DeleteModal from '@/components/DeleteModal'
import FindInput from '@/components/FindInput'

export default {
  components: {
    DeleteModal,
    FindInput,
  },
  mounted() {
    this.sd.setHours(0, 0, 0, 0);
    this.ed.setHours(0, 0, 0, 0);
    this.fetchData();
  },
  data() {
    return {
      key: null,
      sale: {},
      sales: [],
      page: 1,
      pages: null,
      count: null,
      sd: new Date(),
      ed: new Date(),
      deleted: null,
      payed: null,
      delivered: null,
    }
  },
  methods: {
    payedSale(sale) {
      sale.payment_id = '1';
      axios.put(`sales/${sale.id}`, { sale }).then(res => {
        console.log(res);
        this.fetchData();
      }).catch(err => {
        console.log(err.response);
      });
    },
    deliveredSale(sale) {
      axios.get(`sales/${sale.id}/delivery`).then(res => {
        console.log(res);
        this.fetchData();
      }).catch(err => {
        console.log(err.response);
      });
    },
    findSales() {
      var params = { 
        key: this.key,
      };
      axios.get('sales/find', { params }).then(res => {
        console.log(res.data);
        this.sales = res.data.sales;
      }).catch(err => {
        console.log(err.response);
      });
    },
    fetchData() {
      var params = { 
        page: this.page,
        sd: this.sd,
        ed: this.ed,
        payed: this.payed,
        deleted: this.deleted,
        delivered: this.delivered,
      };
      axios.get('sales', { params }).then(res => {
        console.log(res);
        this.sales = res.data.sales;
        this.pages = res.data.pages;
        this.count = res.data.count;
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>