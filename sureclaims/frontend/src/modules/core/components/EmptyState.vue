<template>
  <div class="empty-state">
    <div class="empty-state--container">
      <template v-if="$slots.icon">
        <span class="empty-state--icon">
          <slot name="icon" />
        </span>
      </template>
      <template v-if="$slots.label">
        <span class="empty-state--label">
          <slot name="label" />
        </span>
      </template>
      <template v-if="$slots.description">
        <p class="empty-state--description">
          <slot name="description" />
        </p>
      </template>
      <slot />
    </div>
  </div>
</template>

<script>
  export default {
    name: 'empty-state',
  };
</script>

<style scoped>
  .empty-state {
    display: -webkit-box;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    text-align: center;
    max-width: 420px;
    padding: 36px;
    margin: 0 auto;
    position: relative;
    transition: opacity .15s cubic-bezier(0,0,.2,1),-webkit-transform .3s cubic-bezier(0,0,.2,1);
    transition: opacity .15s cubic-bezier(0,0,.2,1),transform .3s cubic-bezier(0,0,.2,1);
    transition: opacity .15s cubic-bezier(0,0,.2,1),transform .3s cubic-bezier(0,0,.2,1),-webkit-transform .3s cubic-bezier(0,0,.2,1);
    will-change: transform,opacity;
  }

  .empty-state--container {
    display: -webkit-box;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    flex-direction: column;
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: center;
    justify-content: center;
    text-align: center;
    transition: opacity .4s cubic-bezier(.4,0,.2,1);
    will-change: opacity;
  }

  .empty-state--icon > * {
    width: 120px;
    min-width: 120px;
    height: 120px;
    font-size: 120px!important;
    margin: 0;
    margin-bottom: 2rem;
  }

  .empty-state--label > * {
    font-size: 1.3rem;
    font-weight: 500;
    line-height: 40px;
    margin-bottom: 2rem;
  }

  .empty-state--description > * {
    margin: 1rem 0;
    font-size: 1.1rem;
    line-height: 24px;
  }
</style>