<template>
  <form-group
    :has-errors="errors.has(name)"
  >
    <label slot="label" v-if="label">
      {{ label }}
      <span class="m--font-danger" v-if="required">*</span>
    </label>
    <template slot="input">
      <input-field
        :name="name"
        v-bind="Object.assign({}, inputFieldProps($_schema), controlOptions || {})"
      >
        <slot name="append" slot="append" v-if="$slots.append" />
        <slot name="prepend" slot="prepend" v-if="$slots.prepend" />
        <slot name="appendIcon" slot="appendIcon" v-if="$slots.appendIcon" />
        <slot name="prependIcon" slot="prependIcon" v-if="$slots.prependIcon" />
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

  import FormGroup from './FormGroup';
  import InputField from './basic/InputField';
  import AcceptsSchema from './AcceptsSchema';
  import HasInputFieldProps from './basic/HasInputFieldProps';

  export default {
    components: {
      FormGroup,
      InputField,
    },

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

      controlOptions: {
        type: Object,
      },
    },

    mixins: [AcceptsSchema, HasInputFieldProps],
  };

</script>
