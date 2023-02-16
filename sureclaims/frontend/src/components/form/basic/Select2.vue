<template>
  <div :class="{
    'input-group m-input-group': $slots.append || $slots.prepend,
  }">
    <div class="input-group-prepend" v-if="$slots.prepend">
      <slot name="prepend"/>
    </div>
    <select
      :id="name"
      ref="input"
      class="form-control"
      :name="name"
      :disabled="disabled"
      v-model="$_model"
      v-validate="$_schema.validate && $_schema.validate.rules || ''"
      :data-vv-as="$_schema.validate && $_schema.validate.as || null"
      :data-vv-delay="$_schema.validate && $_schema.validate.delay || null"
      :data-vv-name="$_schema.validate && $_schema.validate.name || null"
      :data-vv-value-path="$_schema.validate && $_schema.validate.valuePath || null"
      :data-vv-validate-on="$_schema.validate && $_schema.validate.validateOn || null"
      v-bind="Object.assign({}, schemaProps($_schema))"
      :type="$_schema.inputType && $_schema.inputType.toLowerCase() || null"
      @change="$_schema.onChange || null"
    >
      <slot/>
    </select
      :id="name">
    <div class="input-group-append" v-if="$slots.append">
      <slot name="append"/>
    </div>
  </div>
</template>

<script>

  import jQuery from 'jquery';
  import 'select2';
  import AcceptsSchema from '../AcceptsSchema';

  const defaultOptions = {
    allowClear: true,
  };

  export default {

    mixins: [AcceptsSchema],

    props: {
      value: {
        type: String,
      },
      select2Options: {
        type: Object,
        default: () => ({}),
      },
    },

    mounted() {
      const $el = jQuery(this.$refs.input);
      let recursionProtection = 0;
      $el.select2(jQuery.extend(
        defaultOptions,
        this.select2Options,
      )).val(this.model[this.name])
        .trigger('change')
        .on('change', (e) => {
          if (recursionProtection > 0) {
            e.preventDefault();
            e.stopPropagation();
            recursionProtection = 0;
            return;
          }

          recursionProtection += 1;
          const el = this.$refs.input;
          const event = document.createEvent('HTMLEvents');
          event.initEvent('change', false, true);
          el.dispatchEvent(event);
        });
    },

    destroyed() {
      jQuery(this.$refs.input).off().select2('destroy');
    },
  };
</script>

<style>
  @import "~select2/dist/css/select2.min.css";
</style>
