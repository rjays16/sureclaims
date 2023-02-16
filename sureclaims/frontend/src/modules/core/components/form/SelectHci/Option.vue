<template>
  <el-option
    class="hci-code-detail"
    :label="label"
    :value="value"
  >
    <div class="hci-code-detail--container">
      <div class="hci-code-detail--wrapper">
        <span class="hci-code-detail-code">{{ data.hospitalName }}</span>
        <div class="hci-code-detail--description-wrapper">
          <span class="hci-code-detail--description">{{ data.accreditationCode }}</span>
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

    name: 'select-hci-code-option',


    components: {
      'el-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    props: ['label', 'value', 'data'],

  };

</script>

<style scoped>
  .hci-code-detail {
    height: auto;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  }

  .hci-code-detail.selected {
    color: #0a8cf0 !important;
  }

  .hci-code-detail span {
    line-height: 1.5rem !important;
  }

  .hci-code-detail:last-of-type {
    border-bottom: 0;
  }

  .hci-code-detail--container {
    position: relative;
    padding: 10px 0;
    font-weight: normal !important;
    height: 60px;
    overflow: hidden;
  }

  .hci-code-detail--wrapper {
    position: relative;
    line-height: 24px;
  }

  .hci-code-detail .hci-code-detail--code {
    display: block;
    font-weight: 500 !important;
    font-size: 1.02rem;
  }

  .hci-code-detail .hci-code-detail--description-wrapper {
    position: absolute;
    overflow: hidden;
    width: 200%;
    height: 40px;
    animation: marquee 10s linear infinite;
    animation-play-state: paused;
  }

  .hci-code-detail:hover .hci-code-detail--description-wrapper {
    animation-play-state: running;
  }

  .hci-code-detail .hci-code-detail--description {
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