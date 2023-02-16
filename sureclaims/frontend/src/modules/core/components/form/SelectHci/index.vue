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
      placeholder="Search HCI"
      style="width: 100%"
      :remote-method="onSearch"
      loading-text="Loading ..."
      :loading="loading > 0"
    >
      <select-hci-code-option
        v-for="hci in hcis"
        :key="hci.id"
        :label="label(hci)"
        :value="hci.hospitalName"
        :data="hci"
      />
    </el-select>
  </div>
</template>

<script>
  import {
    Select,
  } from 'element-ui';
  import _debounce from 'lodash/debounce';

  import HCI_QUERY from '@/modules/core/graphql/queries/HciQuery.gql';
  import Option from './Option';

  export default {

    name: 'select-hci-code',

    components: {
      'el-select': Select,
      'select-hci-code-option': Option,
    },


    props: {
      value: {
        type: String,
        default: null,
      },
    },

    data() {
      return {
        skipHciCodesQuery: false,
        currentSearch: '',
        loading: 0,
        loadingDefault: 0,
        hcis: {},
      };
    },

    apollo: {
      hcis: {
        query: HCI_QUERY,
        variables() {
          return {
            search: this.currentSearch,
          };
        },

        loadingKey: 'loading',

      },
    },

    methods: {
      label(hci) {
        return `${hci.hospitalName}`;
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