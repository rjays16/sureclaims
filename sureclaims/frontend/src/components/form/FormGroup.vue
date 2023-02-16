<template>
  <div
    :class="[
      'form-group m-form__group',
      {
        'has-danger': hasErrors,
        'm--padding-top-10 m--padding-bottom-10': condensed,
        row: horizontal,
      },
    ]"
  >
    <template v-if="horizontal">
      <!-- Horizontal layout -->
      <div class="col-lg-3 col-form-label">
        <slot name="label"></slot>
      </div>
      <div class="col-lg-9">
        <slot name="input"></slot>
      </div>
    </template>
    <template v-else>
      <slot name="label"></slot>
      <slot name="input"></slot>
    </template>
  </div>
</template>

<script>
  export default {
    props: {
      name: {
        type: String,
      },

      hasErrors: {
        type: Boolean,
        default: undefined,
      },

      horizontal: {
        type: Boolean,
        default: undefined,
      },

      condensed: {
        type: Boolean,
        default: true,
      },

    },
  };
</script>
