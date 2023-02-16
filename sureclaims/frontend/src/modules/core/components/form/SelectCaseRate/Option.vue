<template>
  <el-option
    class="employer-detail"
    :label="label"
    :value="value"
  >
    <div class="employer-detail--container">
      <div class="employer-detail--wrapper">
        <p class="employer-detail--code mb-0">
          <span class="m--font-transform-u text-info">{{ data.caseType }}</span> - {{ data.itemCode }} -
          <span v-if="condition === 1 && data.itemCode === '99460'" class="m--font-transform-u text-success m--font-bolder" v-show="caserateCount === 1">{{data.amount - 200 | currency}}</span>
          <span v-else class="m--font-transform-u text-success m--font-bolder" v-show="caserateCount === 1">{{data.amount | currency}}</span>
          <span v-if="condition === 1 && data.itemCode === '99460'" class="m--font-transform-u text-success m--font-bolder" v-show="caserateCount === 2">{{data.secondaryAmount - 200 | currency}}</span>
          <span v-else class="m--font-transform-u text-success m--font-bolder" v-show="caserateCount === 2">{{data.secondaryAmount | currency}}</span>
        </p>
        <p class="employer-detail--description-wrapper">
          <span class="employer-detail--description">{{ data.itemDescription }}</span>
        </p>
        <div class="employer-detail--code mb-0">
          <small class="m--font-transform-u">{{ data.code }}</small> -
          <small class="employer-detail--description" v-show="data.description.length">{{ data.description }}</small>
          <em class="employer-detail--description small" v-show="!data.description.length">No Description</em>

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

    name: 'select-caserate-option',

    components: {
      'el-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    props: ['label', 'value', 'data', 'caserateCount', 'condition'],
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
    height: 100%;
    width: 50em;
  }

  .employer-detail .employer-detail--description {
    display: inline-block;
    color: #aaa;
    font-size: 0.9rem;
    line-height: 20px !important;
  }

</style>
