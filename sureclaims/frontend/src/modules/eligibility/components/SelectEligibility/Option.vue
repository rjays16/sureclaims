<template>
  <el-option
    class="eligibility-detail"
    :label="label"
    :value="value"
  >
    <div class="eligibility-detail--container">
      <div class="eligibility-detail--wrapper">
        <div>
          <span class="eligibility-detail--asof">As Of: <em>{{ scope.asOf }}</em></span>
        </div>
        <div>
          <span class="eligibility-detail--tracking-number" v-if="!!scope.trackingNumber">Tracking#: <em>{{ scope.trackingNumber }}</em></span>
          <span class="eligibility-detail--tracking-number" v-if="!scope.trackingNumber"><em>No tracking number</em></span>
        </div>
        <div>
          <span>Confinement: </span>
          <template v-if="hasConfinement(scope)">
            <font-awesome-icon :icon="['far', 'calendar']" class="text-info" /> <em>{{ scope.admitted }}</em>
            -
            <font-awesome-icon :icon="['far', 'calendar-check']" class="text-info" /> <em>{{ scope.discharged }}</em></span>
          </template>
          <template v-else>
            <em>None</em>
          </template>
        </div>
        <div>
          Result:
          <span class="eligibility-detail--result" v-if="!!scope.isOk && !!scope.trackingNumber"><em class="text-success">ELIGIBLE</em></span>
          <span class="eligibility-detail--result" v-else><em class="text-danger">NOT ELIGIBLE</em></span>
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

    mixins: [TransformsPersonData, UsesLookups],

    components: {
      'el-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    props: ['label', 'value', 'scope'],

    methods: {
      hasConfinement(eligibility) {
        return !!eligibility.admitted && !!eligibility.discharged;
      }
    }

  };

</script>

<style scoped>
  .eligibility-detail {
    height: auto;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  }

  .eligibility-detail.selected {
    color: #0a8cf0 !important;
  }

  .eligibility-detail span {
    line-height: 1rem !important;
  }

  .eligibility-detail:last-of-type {
    border-bottom: 0;
  }

  .eligibility-detail--container {
    padding: 10px;
    font-weight: normal !important;
  }

  .eligibility-detail--wrapper {
    line-height: 24px;
  }

  .eligibility-detail em {
    font-style: normal;
    font-weight: 600;
  }

</style>