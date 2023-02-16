<template>
  <el-option
    class="member-detail"
    :label="label"
    :value="value"
    :disabled="isDisabled"
  >
    <div class="member-detail--container">
      <div class="member-detail--wrapper">
        <div>
          <span class="member-detail--fullname">{{ fullname(data) }}</span>
          <span class="member-detail--age">{{ age(data) }}</span>
        </div>
        <div v-if="!!data.pan" class="text-muted">Accreditation Code: {{ data.pan }}</div>
        <div v-if="!data.pan" class="text-danger">No accreditation code</div>
        <div v-if="alreadySelected" class="text-warning">Already selected!</div>
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

    mixins: [TransformsPersonData, UsesLookups],

    components: {
      'el-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    props: ['label', 'value', 'data', 'already-selected'],

    computed: {
      isDisabled() {
        return !this.data.pan || this.alreadySelected;
      }
    }

  };

</script>

<style scoped>
  .member-detail {
    height: auto;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  }

  .member-detail.selected {
    color: #0a8cf0 !important;
  }

  .member-detail span {
    line-height: 1rem !important;
  }

  .member-detail:last-of-type {
    border-bottom: 0;
  }

  .member-detail--container {
    padding: 10px;
    font-weight: normal !important;
  }

  .member-detail--wrapper {
    line-height: 24px;
  }

  .member-detail .member-detail--fullname {
    display: inline-block;
    font-weight: 500 !important;
    font-size: 1.02rem;
    margin-right: 5px;
  }

  .member-detail .member-detail--sex {
    margin-right: 5px;
  }

  .member-detail .member-detail--address {
    color: #aaa;
    font-size: 0.9rem;
  }
</style>