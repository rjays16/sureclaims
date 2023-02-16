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
      placeholder="Search by name (LASTNAME, FIRSNAME)"
      style="width: 100%"
      :remote-method="onSearch"
      loading-text="Loading ..."
      :loading="loading > 0"
    >
      <select-person-option
        v-if="currentSelected"
        v-show="false"
        :value="currentSelected.id"
        :label="fullname(currentSelected)"
        :data="currentSelected"
      />
      <select-person-option
        v-for="person in personsPage.persons"
        :key="person.id"
        :label="fullname(person)"
        :value="person.id"
        :data="person"
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

  import PERSONS_PAGE_QUERY from '@/modules/core/graphql/queries/PersonsPageQuery.gql';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  import Option from './Option';

  export default {

    components: {
      'el-select': Select,
      'select-person-option': Option,
      'font-awesome-icon': fontAwesomeIcon,
    },

    props: {
      selected: {
        type: Object,
        default: null,
      },
      value: {
        type: String,
        default: null,
      },
    },

    mixins: [TransformsPersonData, UsesLookups],

    data() {
      return {
        member: null,
        skipPersonsQuery: false,
        skipPersonQuery: true,
        currentSearch: '',
        loading: 0,
        loadingDefault: 0,
        personsPage: {},
        currentSelected: null,
      };
    },

    apollo: {
      personsPage: {
        query: PERSONS_PAGE_QUERY,
        variables() {
          return {
            name: this.currentSearch,
            page: 1,
          };
        },
        loadingKey: 'loading',
        skip() {
          return this.skipPersonsQuery;
        },
      },
    },

    methods: {
      updateValue(value) {
        if (!value) {
          this.currentSelected = null;
          this.$emit('input', null);
          this.$emit('selectedChanged', null);
          return;
        }
        if (this.$emit('selectedChanged', null)) {
          this.$emit('input', value);
          const person = this.personsPage.persons.find(a => a.id === value);
          this.$emit('selectedChanged', person);
        }
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
