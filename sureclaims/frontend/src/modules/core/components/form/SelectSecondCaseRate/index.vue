<template>
  <div>
    <el-select
      ref="select"
      :value="value"
      @input="updateValue($event)"
      filterable
      remote
      clearable
      default-first-option
      placeholder="Search Second Case Rate"
      style="width: 100%"
      :remote-method="onSearch"
      loading-text="Loading ..."
      :disabled="disabled"
      :loading="loading > 0"
    >
      <select-caserate-option
        v-for="caseRate in secondCaseRates"
        :key="caseRate.id"
        :label="label(caseRate)"
        :value="caseRate.id"
        :data="caseRate"
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

  import SECOND_CASERATES_QUERY from '@/modules/core/graphql/queries/SecondCaseRatesQuery.gql';
  import Option from './Option';

  const MINIMUM_SEARCH_LENGHT = 2;

  export default {

    name: 'select-second-caserate',

    components: {
      'el-select': Select,
      'select-caserate-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    props: {
      value: {
        type: [String, Number],

        default: null,
      },
    },
    data() {
      return {
        currentSearch: '',
        loading: 0,
        secondCaseRates: [],
        disabled: false // Just enable disabling on first load of component.
      };
    },

    apollo: {
      secondCaseRates: {
        query: SECOND_CASERATES_QUERY,
        variables() {
          return {
            search: this.currentSearch,
          };
        },

        loadingKey: 'loading',

        result() {
          this.disabled = false;
        }
      },
    },

    methods: {
      label(caseRate) {
        return `${caseRate.itemCode}`;
      },

      updateValue(value) {
        if (!value) {
          this.$emit('input', null);
          this.$emit('selectedChanged', null);
          return;
        }
        this.$emit('input', value);
        const data = this.secondCaseRates.find(a => a.id === value);
        this.$emit('selectedChanged', data);
      },

      onSearch(search) {
        const query = search || '';
        if (query.length >= MINIMUM_SEARCH_LENGHT) {
          this.doSearch(search, this);
        }
      },

      doSearch: _debounce((search, vm) => {
        /* eslint-disable no-param-reassign */
        vm.currentSearch = search;
      }, 100),
    },

    mounted() {
      this.currentSearch = this.value;
      this.disabled = true;
    },
  };

</script>