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
      :placeholder="placeholder"
      style="width: 100%"
      :remote-method="onSearch"
      loading-text="Loading ..."
      :loading="loading > 0"
    >
      <select-employer-option
        v-for="employer in employers"
        :key="employer.id"
        :label="label(employer)"
        :value="employer.pen"
        :data="employer"
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

  import EMPLOYERS_QUERY from '@/modules/core/graphql/queries/EmployersQuery.gql';
  import Option from './Option';

  const MINIMUM_SEARCH_LENGHT = 7;

  export default {

    name: 'select-employer',

    components: {
      'el-select': Select,
      'select-employer-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    props: {
      value: {
        type: String,
        default: null,
      },
      placeholder: {
        type: String,
        default: 'Search Employer'
      }
    },

    data() {
      return {
        currentSearch: '',
        loading: 0,
        loadingDefault: 0,
        employers: [],
      };
    },

    // apollo: {
    //   employers: {
    //     query: EMPLOYERS_QUERY,
    //     variables() {
    //       return {
    //         name: this.currentSearch,
    //       };
    //     },
    //     update(data) {
    //       const result = data.employers || [];
    //       console.log(result);
    //       return result;
    //     },
    //     loadingKey: 'loading'
    //   },
    // },

    methods: {
      label(employer) {
        return `${employer.name}`;
      },

      updateValue(pen) {
        if (!pen) {
          this.$emit('input', null);
          this.$emit('selectedChanged', null);
          return;
        }
        this.$emit('input', pen);
        const employer = this.employers.find(a => a.pen === pen);
        this.$emit('selectedChanged', employer);
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
        vm.loading = true;
        vm.$apolloProvider.defaultClient.query({
          query: EMPLOYERS_QUERY,
          fetchPolicy: 'network-only',
          variables: {
            name: vm.currentSearch
          }
        }).then(({ data: employers }) => {
          vm.employers = employers.employers || [];
        }).catch(({ graphQLErrors }) => {
          vm.employers = [];
        }).finally(() => {
          vm.loading = false;
        });
      }, 100),
    },

    mounted() {
      this.currentSearch = this.value;
    },
  };

</script>