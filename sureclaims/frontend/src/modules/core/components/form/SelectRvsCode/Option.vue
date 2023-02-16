<template>
  <el-option
    class="rvs-code-detail"
    :label="label"
    :value="value"
  >
    <div class="rvs-code-detail--container">
      <div class="rvs-code-detail--wrapper">
        <span class="rvs-code-detail--code">{{ data.code }}</span>
        <div class="rvs-code-detail--description-wrapper">
          <span class="rvs-code-detail--description">{{ data.description }}</span>
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

    name: 'select-rvs-code-option',


    components: {
      'el-option': Option,

      'font-awesome-icon': fontAwesomeIcon,
    },

    props: ['label', 'value', 'data'],

  };

</script>

<style scoped>
  .rvs-code-detail {
    height: auto;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    white-space: initial;
  }

  .rvs-code-detail.selected {
    color: #0a8cf0 !important;
  }

  .rvs-code-detail span {
    line-height: 1.5rem !important;
  }

  .rvs-code-detail:last-of-type {
    border-bottom: 0;
  }

  .rvs-code-detail--container {
    position: relative;
    padding: 10px 0;
    font-weight: normal !important;
    height: 60px;
  }

  .rvs-code-detail--wrapper {
    position: relative;
    line-height: 24px;
  }

  .rvs-code-detail .rvs-code-detail--code {
    display: block;
    font-weight: 500 !important;
    font-size: 1.02rem;
  }

  .rvs-code-detail .rvs-code-detail--description-wrapper {
    width: 25em;
    height: 100%;
  }

  .rvs-code-detail .rvs-code-detail--description {
    display: inline-block;
    color: #aaa;
    font-size: 0.9rem;
    line-height: 20px !important;
    float: left;
  }

</style>