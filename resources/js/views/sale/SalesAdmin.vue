<template>
  <div class="row">
    <payment-sale-modal :sale="sale" @confirm="payedSale"></payment-sale-modal>
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
              <button type="button" @click="downloadExcel" class="btn btn-info mr-2">
                <feather type="download"/>
                Desc Excel 1
              </button>
              <button type="button" @click="downloadExcel2" class="btn btn-info mr-2">
                <feather type="download"/>
                Desc Excel 2
              </button>
              <button type="button" @click="clearFilters" class="btn btn-info">
                <feather type="x"/>
                Limpiar Filtros
              </button>
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
              <th>Distrito</th>
              <th>T. de Venta</th>
              <th>F. de Entrega</th>
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
                <td v-if="item.delivery">{{ item.delivery.name }}</td>
                <td v-else>Sin delivery</td>
                <td>S/ {{ (item.items.map(e => e.sale_price * e.weight).reduce((a, b) => a + b, 0) + item.delivery_price).toFixed(2) }}</td>
                <td>{{ formatDate(item.delivery_date) }}</td>
                <td v-if="item.payment_id">Pagado</td>
                <td v-else>Pendiente</td>
                <td v-if="item.delivered_date">Entregado</td>
                <td v-else-if="item.dispatched_date">Despachado</td>
                <td v-else-if="item.deliver_date">Pendiente</td>
                <td v-else>S/C entrega</td>
                <td v-if="item.deleted_at">Si</td>
                <td v-else>No</td>
                <td>
                  <div class="btn-toolbar">
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <feather type="more-vertical"/>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <router-link :to="{ path: `/sales/${item.id}/details` }" class="dropdown-item">Detalles</router-link>
                      <router-link :to="{ path: `/sales/${item.id}/edit` }" class="dropdown-item">Editar</router-link>
                      <a href="#" class="dropdown-item" data-toggle="modal" data-target="#paymentSaleModal" @click.prevent="sale = item">Marcar Pago</a>
                      <a href="#" class="dropdown-item" @click.prevent="deliverSale(item)">Enviar a despacho</a>
                      <!-- <a href="#" class="dropdown-item" @click.prevent="deliveredSale(item)">Marcar Entrega</a> -->
                      <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteModal" @click.prevent="sale = item">Anular</a>                    </div>
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
import PaymentSaleModal from '@/components/PaymentSaleModal'
import { excelUtils } from '@/mixins'

export default {
  components: {
    DeleteModal,
    FindInput,
    PaymentSaleModal,
  },
  mixins: [excelUtils],
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
    clearFilters() {
      this.deleted = null;
      this.payed = null;
      this.delivered = null;
      this.fetchData();
    },
    async downloadExcel() {
      var wscols = [20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20];
      var params = { 
        sd: this.sd,
        ed: this.ed,
      };
      try {
        var inventories = await axios.get('inventories', { params }).then(res => res.data.inventories);
      } catch (err) {
        console.log(err.response);
      }
      console.log(inventories);
      var body = [];
      body.push([
        'ORDEN',
        'FECHA Y HORA',
        'COD. DE VENTA',
        'DNI CLIENTE',
        'CLIENTE',
        'DISTRITO',
        'CATEGORIA',
        'PRODUCTO',
        'CODIGO DE EMPAQUE',
        'PESO DE EMPAQUE',
        'PRECIO DE EMPAQUE',
        'CANAL DE VENTA',
        // 'FECHA Y HORA DESPACHO',
      ]);
      inventories
      .sort((a, b) => {
        if (a.sale.id > b.sale.id) {
          return 1;
        }
        if (a.sale.id < b.sale.id) {
          return -1;
        }
        return 0;
      })
      .forEach((item, index) => {
        var customer = item.sale.customer;
        var delivery = item.sale.delivery;
        var category = item.product.category;
        var subCategory = item.product.sub_category;
        console.log(customer);
        body.push([
          index + 1,
          `${this.formatDate(item.sale.created_at)} ${this.formatTime(item.sale.created_at)}`,
          this.formatCode(item.sale.id),
          customer.document,
          customer.name,
          delivery ? delivery.name : null,
          item.product.category.name,
          item.product.name,
          item.codigo,
          item.weight,
          item.sale_price * item.weight,
          item.sale.channel,
          // item.dispatched_date ? `${this.formatDate(item.dispatched_date)} ${this.formatTime(item.dispatched_date)}` : 'Sin despacho',
        ]);
      });
      var name = `Ventas_desde_${this.formatDate(this.sd)}_hasta_${this.formatDate(this.ed)}`;
      this.getExcel(body, name, [], wscols);
    },
    async downloadExcel2() {
      var wscols = [20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20];
      var body = [];
      body.push([
        'ORDEN',
        'FECHA Y HORA',
        // 'HORA',
        'COD. DE VENTA',
        'DNI CLIENTE',
        'CLIENTE',
        'DISTRITO',
        'PRECIO DELIVERY',
        'PRECIO VENTA',
        'PRECIO TOTAL',
        'CANAL DE VENTA',
        'AUTOR DE VENTA',
        'ENCARGADO DE DELIVERY',
        'FECHA HORA DESPACHO',
      ]);
      let params = { 
        // page: this.page,
        sd: this.sd,
        ed: this.ed,
        payed: this.payed,
        deleted: this.deleted,
        delivered: this.delivered,
      };
      let sales = await axios.get('sales/all', { params }).then(res => res.data.sales);
      sales.forEach((item, index) => {
        body.push([
          index + 1,
          `${this.formatDate(item.created_at)} / ${this.formatTime(item.created_at)}`,
          this.formatCode(item.id),
          item.customer.document,
          item.customer.name,
          item.delivery.name,
          item.items.map(e => e.sale_price * e.weight).reduce((a, b) => a + b, 0),
          item.delivery_price,
          item.items.map(e => e.sale_price * e.weight).reduce((a, b) => a + b, 0) + item.delivery_price,
          item.channel,
          item.user ? item.user.name : 'TIENDA VIRTUAL',
          item.deliveryman ? item.deliveryman.name : 'SIN DEFINIR',
          item.dispatched_date ? `${this.formatDate(item.dispatched_date)} ${this.formatTime(item.dispatched_date)}` : 'Sin despacho',
        ]);
      });
      var name = `Ventas_desde_${this.formatDate(this.sd)}_hasta_${this.formatDate(this.ed)}`;
      this.getExcel(body, name, [], wscols);
    },
    payedSale(sale) {
      axios.put(`sales/pay`, { sale }).then(res => {
        console.log(res);
        this.fetchData();
      }).catch(err => {
        console.log(err.response);
      });
    },
    deliverSale(sale) {
      sale.deliver_date = new Date();
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