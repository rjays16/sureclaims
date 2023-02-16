<template>
  <div
    class="money el-input"
    :class="{
      [`el-input--${size}`]: !!size,
      'is-disabled' : disabled,
      'el-input--prefix': !!$slots.prefix,
      'el-input--suffix': !!$slots.suffix,
      'el-input-group el-input-group--prepend' : !!$slots.prepend,
      'el-input-group el-input-group--append' : !!$slots.append,
    }"
  >
    <div
      v-if="!!$slots.prepend"
      class="el-input-group__prepend"
    >
      <slot name="prepend"></slot>
    </div>
    <input
      ref="ipt"
      autocomplete="off"
      validateevent="true"
      class="el-input__inner"
      :disabled="disabled"
      :placeholder="placeholder"
      :size="size"
      v-model="val"
      @blur="() => $emit('blur')"
      @change="value => $emit('change', value)"
    />

    <span class="el-input__prefix" v-if="!!$slots.prefix">
      <span class="el-input__prefix-inner">
        <slot name="prefix" />
      </span>
    </span>

    <span class="el-input__suffix" v-if="!!$slots.suffix">
      <span class="el-input__suffix-inner">
        <slot name="suffix" />
      </span>
    </span>

    <div
      v-if="!!$slots.append"
      class="el-input-group__append"
    >
      <slot name="append"></slot>
    </div>
  </div>
</template>

<script>
  import { Input } from 'element-ui';

  export default {

    components: {
      'el-input': Input,
    },

    props: {
      placeholder: {
        required: false,
      },

      size: {
        required: false,
      },

      clearable: {
        required: false,
      },

      prefixIcon: {
        required: false,
      },

      suffixIcon: {
        required: false,
      },

      value: {
        required: false,
        default: '',
      },
      disabled: {
        type: Boolean,
        required: false,
        default: false,
      },
      fixed: {
        type: Number,
        required: false,
      },
    },

    computed: {
      val: {
        get() {
          return this.numToMoney(this.value, this.fixed || 2);
        },

        set(v) {
          const fixed = this.fixed || 2;
          const val = this.value;
          const fix1 = v.replace(/[^0-9,.]/g, '');
          const fix2 = v.replace(/,/g, '');
          const fix3 = parseFloat(parseFloat(fix2).toFixed(fixed));
          const nan = isNaN(fix3);
          if (fix1 !== v || nan || val === fix3) {
            this.numToMoney(this.value, fixed);
          } else if (!nan) {
            this.$emit('input', fix3);
            this.$emit('change', fix3);
          }
        },
      },
    },

    methods: {
      updateValue(val) {
        this.val = val;
      },

      numToMoney(v, n) {
        const fixed = this.fixed || 2;
        let r;
        const ipt = this.$refs.ipt;
        if (v === '' || v === undefined) {
          return v;
        }

        const [a, b = ''] = (`${v}`).split(/(?=\.)/);
        const x = a.length % 3;
        r = a.substr(0, x)
          + a.substr(x).replace(/(\d{3})/g, ',$1').substr(+!x);
        if (n) {
          r += (+(0 + b)).toFixed(n).substr(1);
        } else if (b) {
          r += b.substr(0, fixed + 1);
        }
        if (ipt) {
          let st = -1;
          if (document.activeElement === ipt) {
            const val = `${ipt.value}`;
            if (val === '' || /^\.0*$/g.test(val)) {
              this.$emit('input', '');
              this.$emit('change', '');
              return '';
            }
            const point = val.indexOf('.');
            const l0 = val.length;
            const l1 = r.length;
            const fix = l1 - l0;
            const s0 = ipt.selectionStart;
            const s1 = ipt.selectionEnd;

            /* eslint-disable no-nested-ternary */
            if (point !== -1 && s0 > point) {
              st = (s0 - fix) + (
                fix > 0 ?
                  s0 === s1 ?
                    s0 - point === fix && fix === 1 ?
                      0 : 1
                    : 2
                  : -1
              );
            } else if (val.length === 1 && s0 === s1 && fix === fixed + 1) {
              st = 1;
            } else {
              st = s0 + fix;
            }
          }
          ipt.value = r;
          if (st !== -1) {
            ipt.setSelectionRange(st, st);
          }
        }
        return r;
      },
    },
  };
</script>
