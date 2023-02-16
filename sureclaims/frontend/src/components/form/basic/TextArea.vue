<template>
  <div :class="{
    'input-group m-input-group': $slots.append || $slots.prepend,
    'm-input-icon': $slots.prependIcon || $slots.appendIcon,
    'm-input-icon--left': $slots.prependIcon,
    'm-input-icon--right': $slots.appendIcon,
  }">
    <div class="input-group-prepend" v-if="$slots.prepend">
      <slot name="prepend" />
    </div>
    <span class="m-input-icon__icon m-input-icon__icon--left" v-if="$slots.prependIcon">
      <slot name="prependIcon" />
    </span>
    <textarea
      :id="name"
      ref="input"
      :class="[
        'form-control m-input',
        {
          'm-input--air': air,
          'm-input--pill': pill,
          'm-input--solid': solid,
          'm-input--square': square,
        }
      ]"
      v-model="$_model"
      v-validate="$_schema.validate && $_schema.validate.rules || ''"
      :data-vv-as="$_schema.validate && $_schema.validate.as || null"
      :data-vv-delay="$_schema.validate && $_schema.validate.delay || null"
      :data-vv-name="$_schema.validate && $_schema.validate.name || null"
      :data-vv-value-path="$_schema.validate && $_schema.validate.valuePath || null"
      :data-vv-validate-on="$_schema.validate && $_schema.validate.validateOn || null"
      v-bind="Object.assign({}, schemaProps($_schema))"

      :rows="rows"

      @change="$_schema.onChange || null"
    />

    <div class="input-group-append" v-if="$slots.append">
      <slot name="append" />
    </div>
    <span class="m-input-icon__icon m-input-icon__icon--right" v-if="$slots.appendIcon">
      <slot name="appendIcon" />
    </span>
  </div>
</template>

<script>

  import AcceptsSchema from '../AcceptsSchema';
  import HasInputFieldProps from './HasInputFieldProps';

  export default {

    mixins: [AcceptsSchema, HasInputFieldProps],

    props: {
      rows: {
        type: Number,
        default: 2,
      },

      air: {
        type: Boolean,
        default: undefined,
      },

      pill: {
        type: Boolean,
        default: undefined,
      },

      square: {
        type: Boolean,
        default: undefined,
      },

      solid: {
        type: Boolean,
        default: undefined,
      },
    },

  };

</script>
