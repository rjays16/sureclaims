<template>
  <div class="m-form__section">
    <div v-if="!!title" class="m-form__heading">
      <h3 class="m-form__heading-title">
        {{ title }}
      </h3>
    </div>
    <slot />
  </div>
</template>

<script>
  export default {
    name: 'form-section',
    props: {
      title: {
        type: String,
      },
    },
  };
</script>
