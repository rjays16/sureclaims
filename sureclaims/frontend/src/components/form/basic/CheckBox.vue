<template>
  <label :class="[
    'm-checkbox',
    {
     [`m-checkbox--state-${controlOptions.contextType}`]: controlOptions.contextType && !controlOptions.contextOnCheck,
     [`m-checkbox--${controlOptions.contextType}`]: controlOptions.contextType && controlOptions.contextOnCheck,
     [`m-checkbox--${controlOptions.contextType}`]: controlOptions.contextType && controlOptions.contextOnCheck,
     [`m-checkbox--bold`]: controlOptions.bold,
     [`m-checkbox--solid`]: controlOptions.solid,
     [`m-checkbox--disabled`]: disabled,
    },
  ]">
    <input

      type="checkbox"
      :checked="checked"
      v-model="$_model"
      v-validate="$_schema.validate && $_schema.validate.rules || ''"
      :data-vv-as="$_schema.validate && $_schema.validate.as || null"
      :data-vv-delay="$_schema.validate && $_schema.validate.delay || null"
      :data-vv-name="$_schema.validate && $_schema.validate.name || null"
      :data-vv-value-path="$_schema.validate && $_schema.validate.valuePath || null"
      :data-vv-validate-on="$_schema.validate && $_schema.validate.validateOn || null"
      v-bind="Object.assign({}, $props, schemaProps($_schema))"
      @change="$_schema.onChange || null"
    />
    {{ label }}
    <span></span>
  </label>
</template>

<script>

  import AcceptsSchema from '../AcceptsSchema';

  export default {
    mixins: [AcceptsSchema],
    props: {
      label: {
        type: String,
        default: '',
      },

      checked: {
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
