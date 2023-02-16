<template>
  <form
    :class="[
      'm-form',
      'm-form--fit',
      'm-form--label-align-right',
      {
        'm-form--state': stateful,
        'm-form--group-seperator-dashed': separator,
      },
    ]"
    :data-vv-scope="scope"
    @submit.prevent="onSubmit"
  >
    <slot/>
  </form>
</template>

<script>

  export default {
    components: {},

    props: {
      scope: {
        type: String,
        default: undefined,
      },

      onSubmit: {
        type: Function,
      },

      stateful: {
        type: Boolean,
        default: undefined,
      },

      separator: {
        type: Boolean,
        default: undefined,
      },
    },
  };
</script>
