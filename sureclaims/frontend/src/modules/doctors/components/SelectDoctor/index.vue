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
      placeholder="Search doctor name (LASTNAME, FIRSTNAME)"
      style="width: 100%"
      :remote-method="onSearch"
      :loading="loading > 0"
    >
      <select-doctor-option
        v-if="currentSelected"
        v-show="false"
        :value="currentSelected.id"
        :label="label(currentSelected)"
        :data="currentSelected"
      />
      <select-doctor-option
        v-for="doctor in doctors.doctors"
        :key="doctor.id"
        :label="label(doctor)"
        :value="doctor.id"
        :data="doctor"
        :already-selected="alreadySelected.includes(doctor.id) && doctor.id != value"
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

  import DOCTORS_QUERY from '@/modules/doctors/graphql/queries/DoctorsQuery.gql';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  import Option from './Option';

  export default {

    components: {
      'el-select': Select,
      'select-doctor-option': Option,
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
      alreadySelected: {
        type: Array,
        default: []
      }
    },

    mixins: [TransformsPersonData, UsesLookups],

    data() {
      return {
        member: null,
        skipDoctorsQuery: false,
        currentSearch: '',
        loading: 0,
        loadingDefault: 0,
        doctors: {},
        currentSelected: null,
      };
    },

    apollo: {
      doctors: {
        query: DOCTORS_QUERY,
        variables() {
          return {
            name: this.currentSearch,
            page: 1,
          };
        },
        loadingKey: 'loading',
        skip() {
          return this.skipDoctorsQuery;
        },
      },
    },

    methods: {
      label(doctor) {
        let result = this.fullname(doctor);
        if (doctor.pan) {
          result = result.concat(`, Accreditation Code: ${doctor.pan}`);
        } else {
          result = result.concat(', No Accreditation Code');
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
        const doctor = this.doctors.doctors.find(a => a.id === value);
        this.$emit('selectedChanged', doctor);
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
      this.currentSelected = this.selected;
      this.currentSearch = this.value;
    },
  };
</script>
