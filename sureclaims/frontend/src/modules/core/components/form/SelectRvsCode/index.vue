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
      placeholder="Search RVS Code"
      style="width: 100%"
      :remote-method="onSearch"
      loading-text="Loading ..."
      :loading="loading > 0"
    >
      <select-rvs-code-option
        v-for="rvsCode in rvsCodes"
        :key="rvsCode.id"
        :label="label(rvsCode)"
        :value="rvsCode.code"
        :data="rvsCode"
      />
    </el-select>
  </div>
</template>
<script>
  import {
    Select
  } from 'element-ui';
  import _debounce from 'lodash/debounce';

  import RVS_CODES_QUERY from '@/modules/core/graphql/queries/RvsCodesQuery.gql';
  import Option from './Option';

  export default {
    name: 'select-rvs-code',
    components: {
      'el-select': Select,
      'select-rvs-code-option': Option
    },

    props: {
      value: {
        type: String,
        default: null,
      },
    },

    data() {
      return {
        currentSearch: '',
        loading: 0,
        loadingDefault: 0,
        rvsCodes: {},
      };
    },

    apollo: {
      rvsCodes: {
        query: RVS_CODES_QUERY,
        variables() {
          return {
            search: this.currentSearch,
          };
        },

        loadingKey: 'loading',


      },
    },
    methods: {
      label(rvsCode) {
        return `${rvsCode.code}`;
      },

      updateValue(value) {
        if (!value) {
          this.$emit('input', null);
          this.$emit('selectionChanged', null);
          return;
        }
        this.$emit('input', value);
        const code = this.rvsCodes.find(a => a.code === value);
        this.$emit('selectionChanged', code);
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

<style scoped>

</style>
