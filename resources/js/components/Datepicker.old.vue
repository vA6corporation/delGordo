<template lang="pug">
.input-group
  input.form-control(:value='formatDateInput', :disabled='disabled', :readonly='!disabled' :placeholder='placeholder', 
    :required='required' @input="$emit('input', $event.target.value)" @click='start' @blur='leaveFocus')
  .input-group-append(v-if='append')
    .input-group-text
      | {{ append }}
  .position-absolute.w-100.border.bg-white.text-center(style='top:38px;z-index:100' 
    v-if='isFocus' @mouseover='isLeave = false' @mouseleave='isLeave = true')
    .border(v-if='viewMonth')
      .text-center.text-capitalize.p-2
        .input-group
          .input-group-prepend
            button.btn.btn-info(type='button' @click='year--')
              feather(type='chevron-left')
          input.form-control.text-center(v-model='tmpYear')
          .input-group-append
            button.btn.btn-info(type='button' @click='year++')
              feather(type='chevron-right')
      .container
        .row
          .col-4.unselectable.p-1(v-for='(item, index) in months' @click='pickMonth(index)')
            | {{ item }}
    .border(v-else)
      .text-center.text-capitalize.py-2.unselectable(@mousedown.stop='viewMonth = true')
        | {{ months[tmpMonth] }} {{ tmpYear }}
      .d-flex
        template(v-for='day in daysInMonth')
          template(v-if='day < 11')
            .w-100.p-1.unselectable(@click='pickDate(day)')
              | {{ day }}
      .d-flex
        template(v-for='day in daysInMonth')
          template(v-if='day < 21 && day > 10')
            .w-100.p-1.unselectable(@click='pickDate(day)')
              | {{ day }}
      .d-flex
        template(v-for='day in daysInMonth')
          template(v-if='day < 31 && day > 20')
            .w-100.p-1.unselectable(@click='pickDate(day)')
              | {{ day }}
        template(v-if='daysInMonth < 29')
          template(v-for='n in 2')
            .w-100.p-1
      .d-flex(v-if='daysInMonth > 30')
        .w-100.p-1.unselectable(@click='pickDate(31)')
          | 31
        template(v-for='n in 9')
          .w-100.p-1.unselectable
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
      // default: function() { return new Date() },
    }, 
    placeholder: String, 
    disabled: null, 
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
</style>
