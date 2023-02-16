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
      placeholder="Search ICD Code"
      style="width: 100%"
      :remote-method="onSearch"
      loading-text="Loading ..."
      :loading="loading > 0"
    >
      <select-icd-code-option
        v-for="icdCode in icdCodes"
        :key="icdCode.id"
        :label="label(icdCode)"
        :value="icdCode.code"
        :data="icdCode"
      />
    </el-select>
  </div>
</template>

<script>
  import { Select } from 'element-ui';
  import _debounce from 'lodash/debounce';
  import fontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import ICD_CODES_QUERY from '@/modules/core/graphql/queries/IcdCodesQuery.gql';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import Option from './Option';

  export default {

    name: 'select-icd-code',

    components: {
      'el-select': Select,
      'select-icd-code-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    mixins: [TransformsPersonData, UsesLookups],

    props: {
      value: {
        type: String,
        default: null,
      },
    },

    data() {
      return {
        skipIcdCodesQuery: false,
        currentSearch: '',
        loading: 0,
        loadingDefault: 0,
        icdCodes: {},
      };
    },

    apollo: {
      icdCodes: {
        query: ICD_CODES_QUERY,
        variables() {
          return {
            search: this.currentSearch,
          };
        },

        loadingKey: 'loading',

        skip() {
          return this.skipIcdCodesQuery;
        },
      },
    },

    methods: {
      label(icdCode) {
        return `${icdCode.code}`;
      },

      updateValue(value) {
        if (!value) {
          this.$emit('input', null);
          return;
        }
        this.$emit('input', value);
      },

      onSearch(search) {
        this.doSearch(search, this);
      },

      doSearch: _debounce((search, vm) => {
        /* eslint-disable no-param-reassign */
        vm.currentSearch = search;
      }, 100),
    },

    mounted() {
      this.currentSearch = this.value;
    },
  };

</script>