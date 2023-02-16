<template>
  <form-group
    :has-errors="errors.has(name)"
  >
    <label slot="label">
      {{ $_schema.label }}
      <span class="m--font-danger" v-if="$_schema.required">*</span>
    </label>
    <template slot="input">
      <div :class="{
        'm-radio-list': !inline,
        'm-radio-inline': inline,
      }">
        <template v-for="(label, itemName, index) in $_schema.items || {}">
          <radio
            :key="itemName"
            :name="name"
            :value="itemName"
            :label="label"
            :control-options="controlOptions"
          />
        </template>
        <div class="form-control-feedback" v-show="errors.has(name)">
          {{ errors.first(name) }}
        </div>
      </div>
    </template>
  </form-group>
</template>

<script>

  import FormGroup from './FormGroup';
  import Radio from './basic/Radio';
  import AcceptsSchema from './AcceptsSchema';

  export default {
    components: {
      FormGroup,
      Radio,
    },

    mixins: [AcceptsSchema],

    inject: ['$validator'],

    props: {
      label: {
        type: String,
      },

      inline: {
        type: Boolean,
        default: true,
      },

      controlOptions: {
        type: Object,
        default: () => ({}),
      },
    },
  };

</script>
