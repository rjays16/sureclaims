<template>
  <div>
    <el-select
      ref="select"
      :value="value"
      @input="updateValue($event)"
      remote
      clearable
      default-first-option
      placeholder="Search recent eligibility checks"
      style="width: 100%"
      :remote-method="onSearch"
      :loading="loading > 0"
      :disabled="loading > 0"
    >
      <select-eligibility-option
        v-if="currentSelected"
        v-show="false"
        :value="currentSelected"
        :label="label(currentSelected)"
        :scope="currentSelected"
      />
      <select-eligibility-option
        v-for="eligibility in eligibilities"
        :key="eligibility.id"
        :label="label(eligibility)"
        :value="eligibility.id"
        :scope="eligibility"
      />
    </el-select>
  </div>
</template>

<script>
  import {
    Select,
  } from 'element-ui';
  import _debounce from 'lodash/debounce';
  import fontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import ELIGIBILITIES_QUERY from '@/modules/eligibility/graphql/queries/EligibilitiesQuery.gql';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  import Option from './Option';

  export default {

    components: {
      'el-select': Select,
      'select-eligibility-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    props: {
      patient: {
        type: String,
        required: true,
      },
      selected: {
        type: Object,
        default: null,
      },
      value: {
        type: [String, Object],
        default: null,
      },
    },

    mixins: [TransformsPersonData, UsesLookups],

    data() {
      return {
        member: null,
        skipEligibilitiesQuery: false,
        currentSearch: '',
        loading: 0,
        loadingDefault: 0,
        eligibilities: {},
        currentSelected: null,
      };
    },

    apollo: {
      eligibilities: {
        query: ELIGIBILITIES_QUERY,
        variables() {
          return {
            patient: this.patient,
          };
        },
        loadingKey: 'loading',
        skip() {
          return this.skipEligibilitiesQuery;
        },
      },
    },

    methods: {
      label(eligibility) {
        let result = eligibility.isOk ? 'ELIGIBLE' : 'NOT ELIGIBILE';
        if (eligibility.trackingNumber) {
          result = result.concat(`, TN# ${eligibility.trackingNumber}`);
        } else {
          result = result.concat(', No Tracking Number');
        }

        if (eligibility.asOf) {
          result = result.concat(`, As of: ${eligibility.asOf}`);
        }

        return result;
      },

      updateValue(value) {
        if (!value) {
          this.currentSelected = null;
          this.$emit('input', null);
          this.$emit('selectedChanged', null);
          return;
        }
        this.$emit('input', value);
        const eligibility = this.eligibilities.find(a => a.id === value);
        this.$emit('selectedChanged', eligibility);
      },

      onSearch(search) {
        this.doSearch(search, this);
      },

      doSearch: _debounce((search, vm) => {
        /* eslint-disable no-param-reassign */
        vm.currentSearch = search;
      }, 100),

    },

    created() {
      this.currentSelected = this.selected;
    },
  };
</script>
