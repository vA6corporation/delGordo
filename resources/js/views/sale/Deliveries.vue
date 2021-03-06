<template>
  <div class="row">
    <payment-sale-modal :sale="sale" @confirm="deliveredSale"></payment-sale-modal>
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
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title mb-0">Delivery</h3>
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
                <td>{{ item.delivery.name }}</td>
                <td>S/ {{ (item.items.map(e => e.sale_price * e.weight).reduce((a, b) => a + b, 0) + item.delivery_price).toFixed(2) }}</td>
                <td v-if="item.payment_id">Pagado</td>
                <td v-else>Pendiente</td>
                <td v-if="item.delivered_date">Entregado</td>
                <td v-else-if="item.dispatched_date">Pendiente</td>
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
                      <a href="#" class="dropdown-item" @click.prevent="prePayment(item)">Marcar Entrega</a>
                      <a :href="`https://wa.me/51${item.customer.mobile}`" target='_blank' class="dropdown-item">WhatsApp</a>
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
import PaymentSaleModal from '@/components/PaymentSaleModal'
import { excelUtils } from '@/mixins'
import { mapGetters } from 'vuex'

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
  computed: {
    ...mapGetters({
      user: 'user/user',
    }),
  },
  methods: {
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
        'FECHA',
        'HORA',
        'LOTE',
        'DNI CLIENTE',
        'CLIENTE',
        'DISTRITO',
        'CODIGO DE PRODUCTO',
        'PRODUCTO',
        // 'EMPAQUE',
        'PESO',
        'CATEGORIA',
        'SUBCATEGORIA',
        'VALOR',
        'CODIGO DE VENTA',
        'CANAL',
      ]);
      inventories.forEach(item => {
        var customer = item.sale.customer;
        var delivery = item.sale.delivery;
        var category = item.product.category;
        var subCategory = item.product.sub_category;
        console.log(customer);
        body.push([
          this.formatDate(item.created_at),
          this.formatTime(item.created_at),
          item.codigo,
          customer.document,
          customer.name,
          delivery.name,
          this.formatCode(item.product.id),
          item.product.name,
          item.weight,
          // item.product.category,
          category.name,
          subCategory.name,
          item.sale_price * item.weight,
          this.formatCode(item.sale.id),
          item.sale.channel
        ]);
      });
      var name = `Ventas_desde_${this.formatDate(this.sd)}_hasta_${this.formatDate(this.ed)}`;
      this.getExcel(body, name, [], wscols);
    },
    downloadExcel2() {
      var wscols = [20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20];
      var body = [];
      body.push([
        'FECHA',
        'HORA',
        'DNI CLIENTE',
        'CLIENTE',
        'DISTRITO',
        'VALOR DE VENTA',
        'VALOR DE DELIVERY',
        'MONTO PAGADO',
        'CANAL DE VENTA'
      ]);
      this.sales.forEach(item => {
        body.push([
          this.formatDate(item.created_at),
          this.formatTime(item.created_at),
          item.customer.document,
          item.customer.name,
          item.delivery.name,
          item.items.map(e => e.sale_price * e.weight).reduce((a, b) => a + b, 0),
          item.delivery_price,
          item.items.map(e => e.sale_price * e.weight).reduce((a, b) => a + b, 0) + item.delivery_price,
          item.channel,
        ]);
      });
      var name = `Ventas_desde_${this.formatDate(this.sd)}_hasta_${this.formatDate(this.ed)}`;
      this.getExcel(body, name, [], wscols);
    },
    async prePayment(sale) {
      this.sale = sale;
      if (sale.payment_id) {
        var ok = confirm('Esta seguro de marcar la entrega');
        if (ok) {
          await axios.get(`sales/${sale.id}/delivery`);
          this.fetchData();
          this.$snotify.success('La venta a sido entregada correctamente');
        }
      } else {
        $('#paymentSaleModal').modal('show');
      }
    },
    payedSale(sale) {
      axios.put(`sales/pay`, { sale }).then(res => {
        console.log(res);
        this.fetchData();
        this.$snotify.success('La venta a sido entregada correctamente');
      }).catch(err => {
        console.log(err.response);
      });
    },
    async deliveredSale(sale) {
      axios.put(`sales/${sale.id}`, { sale }).then(async res => {
        console.log(res);
        try {
          await axios.get(`sales/${sale.id}/delivery`);
          this.fetchData();
        } catch (error) {
          console.log(error.response);        
        }
        if (sale.payment_method_id) {
          this.payedSale(sale);
        }
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
      axios.get('sales/deliverySales', { params }).then(res => {
        console.log(res);
        setTimeout(() => {
          let sales = res.data.sales;
          console.log(this.user);
          if (this.user.is_admin) {
            sales = sales;
          } else {
            sales = sales.filter(e => e.deliveryman_id == this.user.id);
          }
          this.sales = sales;
          this.pages = res.data.pages;
          this.count = res.data.count;
        }, 1000)
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>