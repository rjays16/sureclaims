<template>
  <el-option
    class="member-detail"
    :label="label"
    :value="value"
  >
    <div class="member-detail--container">
      <div class="member-detail--wrapper">
        <span class="member-detail--fullname">{{ fullname(data) }}</span>
        <span class="member-detail--sex">
          <font-awesome-icon class="m--font-danger" :icon="['far', 'female']" v-if="data.sex === 'F'" />
          <font-awesome-icon class="m--font-info" :icon="['far', 'male']" v-if="data.sex === 'M'" />
        </span>
        <span class="member-detail--age">{{ age(data) }}</span>
      </div>

      <template v-if="data.member">
        <div class="member-detail--wrapper" v-if="data.member.relation === 'M'">
          <span class="member-detail--pin">
            <font-awesome-icon class="m--font-success" :icon="['far', 'hashtag']" transform="shrink-4" />
            {{ (data.member.pin) || '-' }}
          </span>
          <span class="member-detail--membership">
            {{ (lookup('member.membershipType', data.member.membershipType)) || '' }}
          </span>
        </div>

        <div class="member-detail--wrapper" v-if="data.member.relation !== 'M'">
          <span class="member-detail--pin">
            <font-awesome-icon class="m--font-success" :icon="['far', 'child']" transform="shrink-4" />
          </span>
          <span class="member-detail--membership">
            Dependent,
            {{ lookup('dependent.relation', data.member.relation) || '' }}
          </span>
        </div>
      </template>

      <div class="member-detail--wrapper">
        <span class="member-detail--address">{{ data.mailingAddress || '-No address-' }}</span>
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

    props: ['label', 'value', 'data'],

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