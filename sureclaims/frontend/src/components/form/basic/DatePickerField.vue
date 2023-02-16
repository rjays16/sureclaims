<template>
  <div :class="{
    'input-group m-input-group': $slots.append || $slots.prepend,
     'm-input-group--solid': ($slots.prepend || $slots.append) && solid,
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
    <input
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
      v-bind="Object.assign({}, schemaProps($_schema), inputFieldProps($_schema))"
      :type="$_schema.inputType && $_schema.inputType.toLowerCase() || null"
      :placeholder="placeholder"
    />

    <div class="input-group-append input-group-addon" v-if="$slots.append">
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
    props: {
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

    mixins: [AcceptsSchema, HasInputFieldProps],
  };

</script>
