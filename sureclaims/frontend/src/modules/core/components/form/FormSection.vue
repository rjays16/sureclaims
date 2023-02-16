<template>
  <div class="m-form__section">
    <div class="m-form__heading">
      <h3 class="m-form__heading-title">
        <slot name="title"/>
      </h3>
    </div>
    <slot />
  </div>
</template>

<script>
  export default {
    name: 'form-section',
  };
</script>
