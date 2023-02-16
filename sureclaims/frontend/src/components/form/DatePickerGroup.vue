<template>
  <form-group
    class="date"
    :has-errors="errors.has(name)"
  >
    <label slot="label" v-if="label">
      {{ label }}
      <span class="m--font-danger" v-if="required">*</span>
    </label>
    <template slot="input">
      <input-field
        :name="name"
        ref="inputField"
        v-bind="Object.assign({ }, inputFieldProps($_schema), controlOptions)"
      >
        <slot name="append" slot="append" v-if="$slots.append"/>
        <slot name="prepend" slot="prepend" v-if="$slots.prepend"/>
        <slot name="appendIcon" slot="appendIcon" v-if="$slots.appendIcon"/>
        <slot name="prependIcon" slot="prependIcon" v-if="$slots.prependIcon"/>
        <button
          ref="button"
          slot="append"
          type="button"
          class="btn btn-secondary"
          :disabled="disabled"
        >
          <i class="la la-calendar"></i>
        </button>
      </input-field>
      <div class="form-control-feedback" v-show="errors.has(name)">
        {{ errors.first(name) }}
      </div>
      <span v-if="!!hint" class="m-form__help">
      {{ hint }}
    </span>
    </template>
  </form-group>
</template>

<script>
  import jQuery from 'jquery';
  import 'bootstrap-datepicker';

  import FormGroup from './FormGroup';
  import InputField from './basic/InputField';
  import AcceptsSchema from './AcceptsSchema';
  import HasInputFieldProps from './basic/HasInputFieldProps';

  const defaultOptions = {
    format: 'mm-dd-yyyy',
    autoclose: true,
    todayHighlight: true,
    showOnFocus: false,
    clearBtn: true,
    orientation: 'auto',
    templates: {
      leftArrow: '<i class="la la-angle-left"></i>',
      rightArrow: '<i class="la la-angle-right"></i>',
    },
  };

  export default {
    components: {
      InputField,
      FormGroup,
    },

    mixins: [AcceptsSchema, HasInputFieldProps],

    props: {
      label: {
        type: String,
      },

      required: {
        type: Boolean,
      },

      hint: {
        type: String,
      },

      datePickerOptions: {
        type: Object,
      },

      controlOptions: {
        type: Object,
      },
    },

    methods: {
      // callback for when the selector popup is closed.
      onChangeDate() {
        const el = this.$refs.inputField.$refs.input;
        const event = document.createEvent('HTMLEvents');
        event.initEvent('input', false, true);
        el.dispatchEvent(event);
      },
    },

    mounted() {
      const $el = jQuery(this.$refs.inputField.$refs.input);
      $el.datepicker(jQuery.extend(
        defaultOptions,
        this.datePickerOptions,
      )).on('changeDate', this.onChangeDate);

      jQuery(this.$refs.button).on('click', () => {
        $el.datepicker('show');
      });
    },
  };
</script>

<style>
  @import '~bootstrap-datepicker/dist/css/bootstrap-datepicker3.css';
</style>
