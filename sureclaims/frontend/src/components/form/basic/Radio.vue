<template>
  <label :class="[
    'm-radio',
    {
     [`m-radio--state-${controlOptions.contextType}`]: controlOptions.contextType && !controlOptions.contextOnCheck,
     [`m-radio--${controlOptions.contextType}`]: controlOptions.contextType && controlOptions.contextOnCheck,
     [`m-radio--${controlOptions.contextType}`]: controlOptions.contextType && controlOptions.contextOnCheck,
     [`m-radio--bold`]: controlOptions.bold,
     [`m-radio--solid`]: controlOptions.solid,
     [`m-radio--disabled`]: disabled,
    },
  ]">
    <input
      type="radio"
      :name="name"
      :checked="checked"
      :disabled="disabled"
      :label="label"

      v-model="$_model"
      v-validate="$_schema.validate && $_schema.validate.rules || ''"
      :data-vv-as="$_schema.validate && $_schema.validate.as || null"
      :data-vv-delay="$_schema.validate && $_schema.validate.delay || null"
      :data-vv-name="$_schema.validate && $_schema.validate.name || null"
      :data-vv-value-path="$_schema.validate && $_schema.validate.valuePath || null"
      :data-vv-validate-on="$_schema.validate && $_schema.validate.validateOn || null"
      v-bind="Object.assign({}, schemaProps($_schema))"
      @change="$_schema.onChange || null"

      :value="value"
    />
    {{ label }}
    <span></span>
  </label>
</template>

<script>

  import ActsAsFormControl from '../AcceptsSchema';

  export default {
    mixins: [ActsAsFormControl],

    props: {
      name: {
        type: String,
      },

      label: {
        type: String,
        default: '',
      },

      value: {
        type: String,
        default: '',
      },

      checked: {
        type: Boolean,
        default: undefined,
      },

      disabled: {
        type: Boolean,
        default: undefined,
      },

      controlOptions: {
        type: Object,
        default: () => ({}),
      },
    },
  };
</script>
