<template>
  <el-option
    class="icd-code-detail"
    :label="label"
    :value="value"
  >
    <div class="icd-code-detail--container">
      <div class="icd-code-detail--wrapper">
        <span class="icd-code-detail--code">{{ data.code }}</span>
        <div class="icd-code-detail--description-wrapper">
          <span class="icd-code-detail--description">{{ data.description }}</span>
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
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  export default {

    name: 'select-icd-code-option',

    mixins: [TransformsPersonData, UsesLookups],

    components: {
      'el-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    props: ['label', 'value', 'data'],

  };

</script>

<style scoped>
  .icd-code-detail {
    height: auto;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    white-space: initial;
  }

  .icd-code-detail.selected {
    color: #0a8cf0 !important;
  }

  .icd-code-detail span {
    line-height: 1.5rem !important;
  }

  .icd-code-detail:last-of-type {
    border-bottom: 0;
  }

  .icd-code-detail--container {
    position: relative;
    padding: 10px 0;
    font-weight: normal !important;
    height: 60px;
  }

  .icd-code-detail--wrapper {
    position: relative;
    line-height: 24px;
  }

  .icd-code-detail .icd-code-detail--code {
    display: block;
    font-weight: 500 !important;
    font-size: 1.02rem;
  }

  .icd-code-detail .icd-code-detail--description-wrapper {
    width: 25em;
    height: 100%;
  }

  .icd-code-detail .icd-code-detail--description {
    display: inline-block;
    color: #aaa;
    font-size: 0.9rem;
    line-height: 20px !important;
    float: left;
  }

  @keyframes marquee {
    0%   { transform: translate(0, 0); }
    100% { transform: translate(-100%, 0); }
  }

</style>