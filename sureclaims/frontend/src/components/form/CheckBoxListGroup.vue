<template>
  <form-group
    :has-errors="(Object.keys(items).filter(key => errors.has(key))).length > 0"
  >
    <label slot="label" :for="_uid">
      {{ label }}
      <span class="m--font-danger" v-if="required">*</span>
    </label>
    <template slot="input">
      <div class="m-checkbox-list">
        <template v-for="(item, itemName) in items">
          <check-box
            :key="itemName"
            :name="itemName"
            :label="item.label"
            :controlOptions="item.controlOptions"
          />
          <div class="form-control-feedback" v-show="errors.has(itemName)">
            {{ errors.first(itemName) }}
          </div>
          <span v-if="!!hint" class="m-form__help">
            {{ hint }}
          </span>
        </template>
      </div>
    </template>
  </form-group>
</template>

<script>

  import FormGroup from './FormGroup';
  import CheckBox from './basic/CheckBox';

  export default {
    components: {
      FormGroup,
      CheckBox,
    },

    inject: ['$validator'],

    props: {
      required: {
        type: Boolean,
        default: undefined,
      },

      label: {
        type: String,
      },

      hint: {
        type: String,
      },

      items: {
        type: Object,
      },
    },
  };

</script>
