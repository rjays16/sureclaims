<template>
  <el-option
    class="employer-detail"
    :label="label"
    :value="value"
  >
    <div class="employer-detail--container">
      <div class="employer-detail--wrapper">
        <span class="employer-detail--code">{{ data.name }}</span>
        <div class="employer-detail--description-wrapper">
          <span class="employer-detail--description">{{ data.pen }} - {{ data.address }}</span>
        </div>
      </div>
    </div>
  </el-option>
</template>

<script>
  import {
    Option,
  } from 'element-ui';
  import fontAwesomeIcon from '@fortawesome/vue-fontawesome';

  export default {

    name: 'select-employer-option',

    components: {
      'el-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    props: ['label', 'value', 'data'],

  };

</script>

<style lang="scss" scoped>
  .employer-detail {
    height: auto;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    white-space: initial;
  }

  .employer-detail.selected {
    color: #0a8cf0 !important;
  }

  .employer-detail span {
    line-height: 1.5rem !important;
  }

  .employer-detail:last-of-type {
    border-bottom: 0;
  }

  .employer-detail--container {
    position: relative;
    padding: 10px 0;
    font-weight: normal !important;
  }

  .employer-detail--wrapper {
    position: relative;
    line-height: 24px;
  }

  .employer-detail .employer-detail--code {
    display: block;
    font-weight: 500 !important;
    font-size: 1.02rem;
  }

  .employer-detail .employer-detail--description-wrapper {
    width: 25em;
    height: 100%;
  }

  .employer-detail .employer-detail--description {
    display: inline-block;
    color: #aaa;
    font-size: 0.9rem;
    line-height: 20px !important;
  }

</style>