<template>
  <form-group
    :has-errors="errors.has(name)"
  >
    <label slot="label">
      {{ label }}
      <span class="m--font-danger" v-if="required">*</span>
    </label>
    <template slot="input">
      <select2
        :name="name"
        ref="inputField"
        :select2-options="select2Options"
        v-bind="Object.assign({}, controlOptions)"
      >
        <option v-for="(item, key) in $_schema.items" :key="key" :value="key">{{ item.label }}</option>
        <slot name="append" slot="append" v-if="$slots.append"/>
        <slot name="prepend" slot="prepend" v-if="$slots.prepend"/>
        <slot name="appendIcon" slot="appendIcon" v-if="$slots.appendIcon"/>
        <slot name="prependIcon" slot="prependIcon" v-if="$slots.prependIcon"/>
      </select2>
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
  import Select2 from './basic/Select2';
  import AcceptsSchema from './AcceptsSchema';

  export default {
    components: {
      Select2,
      FormGroup,
    },

    mixins: [AcceptsSchema],

    props: {
      label: {
        type: String,
      },

      required: {
        type: String,
      },

      hint: {
        type: String,
      },

      items: {
        type: Object,
        default: () => ({}),
      },

      select2Options: {
        type: Object,
      },

      controlOptions: {
        type: Object,
      },
    },
  };
</script>
