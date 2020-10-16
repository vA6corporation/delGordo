<template>
<div class="input-group">
  <input class="form-control" :value="formatDateInput" :disabled="disabled" :readonly="!disabled" :placeholder="placeholder" :required="required" @input="$emit('input', $event.target.value)" @click="start" @blur="leaveFocus" />
  <div class="input-group-append" v-if="append">
    <div class="input-group-text">{{ append }}</div>
  </div>
  <div class="position-absolute w-100 border bg-white text-center" style="top:38px;z-index:100" v-if="isFocus" @mouseover="isLeave = false" @mouseleave="isLeave = true">
    <div class="border" v-if="viewMonth">
      <div class="text-center text-capitalize p-2">
        <div class="input-group">
          <div class="input-group-prepend">
            <button class="btn btn-info" type="button" @click="year--">
              <feather type="chevron-left"/>
            </button>
          </div>
          <input class="form-control text-center" v-model="tmpYear" />
          <div class="input-group-append">
            <button class="btn btn-info" type="button" @click="year++">
              <feather type="chevron-right"/>
            </button>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-4 unselectable p-1" v-for="(item, index) in months" :key="index" @click="pickMonth(index)">{{ item }}</div>
        </div>
      </div>
    </div>
    <div class="border" v-else>
      <div class="text-center text-capitalize py-2 unselectable" @mousedown.stop="viewMonth = true">{{ months[tmpMonth] }} {{ tmpYear }}</div>
      <div class="d-flex">
        <template v-for="day in daysInMonth">
          <template v-if="day < 11">
            <div class="w-100 p-1 unselectable" :key="day" @click="pickDate(day)">{{ day }}</div>
          </template>
        </template>
      </div>
      <div class="d-flex">
        <template v-for="day in daysInMonth">
          <template v-if="day < 21 && day > 10">
            <div class="w-100 p-1 unselectable" :key="day" @click="pickDate(day)">{{ day }}</div>
          </template>
        </template>
      </div>
      <div class="d-flex">
        <template v-for="day in daysInMonth">
          <template v-if="day < 31 && day > 20">
            <div class="w-100 p-1 unselectable" :key="day" @click="pickDate(day)">{{ day }}</div>
          </template>
        </template>
        <template v-if="daysInMonth &lt; 29">
          <template v-for="n in 2">
            <div class="w-100 p-1" :key="n"></div>
          </template>
        </template>
      </div>
      <div class="d-flex" v-if="daysInMonth &gt; 30">
        <div class="w-100 p-1 unselectable" @click="pickDate(31)">31</div>
        <template v-for="n in 9">
          <div class="w-100 p-1 unselectable" :key="n"></div>
        </template>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  mounted() {
    $(window).on('click', e => {
      if (this.isLeave) {
        this.isFocus = false;
      }
    });
    $(document).on('keydown', e => {
      if(e.which == 27) {
        this.leaveFocusForce();
      }
    });
  },
  props: { 
    value: {
      type: null,
    }, 
    placeholder: String, 
    disabled: { 
      type: Boolean, 
      default: false, 
    }, 
    required: Boolean, 
    append: String, 
  },
  data() {
    return {
      viewMonth: false,
      isFocus: null,
      isLeave: true,
      pickedDate: null,
      months: [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre',
      ],
      tmpMonth: new Date().getMonth(),
      tmpYear: new Date().getFullYear(),
    }
  },
  computed: {
    year: {
      get() {
        return new Date(this.value || new Date()).getFullYear()
      },
      set(value) {
        this.tmpYear = value;
      }
    },
    month: {
      get() {
        return new Date(this.value || new Date()).getMonth()
      },
      set(value) {
        this.tmpMonth = value;
      }
    },
    day() {
      return new Date(this.value || new Date()).getDate()
    },
    formatDateInput() {
      if (this.pickedDate) {
        return `${this.months[this.month]} ${this.pickedDate}, ${this.year}`;
      } else if(this.value) {
        var date = new Date(this.value);
        return `${this.months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
      }
      return null;
    },
    daysInMonth() {
      return new Date(this.tmpYear, this.tmpMonth + 1, 0).getDate();
    },
  },
  methods: {
    start() {
      this.viewMonth = false;
      setTimeout(() => {
        this.isFocus = true;
      }, 100);
    },
    pickDate(day) {
      var date = new Date(this.value);
      // console.log(date);
      date.setFullYear(this.tmpYear);
      date.setMonth(this.tmpMonth);
      date.setDate(day);
      this.$emit('input', new Date(date));
      this.$emit('change', new Date(date));
      this.pickedDate = day;
      this.isFocus = false;
      this.isLeave = true;
    },
    pickMonth(month) {
      this.month = month;
      this.viewMonth = false;
    },
    leaveFocus() {
      setTimeout(() => {
        if (this.isLeave) {
          this.isFocus = false;
          this.viewMonth = false;
        }
      }, 100);
    },
    leaveFocusForce() {
      this.isFocus = false;
      this.viewMonth = false;
    }
  },
  watch: {
    value() {
      if (!this.value) {
        this.pickedDate = null;
      } else {
        this.pickedDate = new Date(this.value).getDate();
      }
    }
  }
}
</script>

<style lang="css" scoped>
.unselectable:hover { 
  background-color: #6cb2eb;
  cursor: pointer;
}

.form-control[readonly] {
    background-color: #ffffff;
    opacity: 1;
}
</style>
