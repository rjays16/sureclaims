<template>
  <form-group
    :has-errors="errors.has(name)"
  >
    <label slot="label">
      {{ label }}
      <span class="m--font-danger" v-if="required">*</span>
    </label>
    <template slot="input">
      <text-area
        :name="name"
        v-bind="Object.assign({}, controlOptions)"
      />
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
  import TextArea from './basic/TextArea';
  import AcceptsSchema from './AcceptsSchema';

  export default {
    components: {
      FormGroup,
      TextArea,
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

      controlOptions: {
        type: Object,
      },
    },
  };

</script>
