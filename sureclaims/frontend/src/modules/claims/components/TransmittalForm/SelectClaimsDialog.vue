<template>
  <div class="select-claims-dialog--container">

    <!-- Dialog -->
    <el-dialog
      width="80%"
      top="7vh"
      @open="diaglogOpenedHandler"
      :visible.sync="innerVisible"
    >
      <template slot="title">
        <h5 class="m--regular-font-size-lg2" style="margin-bottom: 1rem">
          Select Claims
        </h5>
      </template>

      <!-- Dialog content -->
      <div class="m--padding-left-40 m--padding-right-40 m--padding-bottom-20">

        <!-- Datatable -->
        <div class="m-datatable m-datatable--default m-datatable--brand m-datatable--loaded">
          <el-row type="flex" justify="space-between">
            <el-col :span="20">
              <!-- Search filters go here -->
              <form @submit.prevent="submitFilters">
                <el-row :gutter="10">
                  <el-col :span="10">
                    <el-input
                      placeholder="Search name ..."
                      prefix-icon="el-icon-search"
                      v-model="filters.currentSearch"
                      clearable
                      :disabled="!!loading"
                    >
                      <el-button
                        native-type="submit"
                        slot="append"
                        :disabled="!!loading"
                        class="btn btn-bold"
                      >
                        Go
                      </el-button>
                    </el-input>
                  </el-col>
                  <!-- <el-col :span="10">
                    <el-date-picker
                      v-model="filters.dateRange"
                      type="daterange"
                      format="MM-dd-yyyy"
                      value-format="MM-dd-yyyy"
                      align="right"
                      unlink-panels
                      range-separator="-"
                      start-placeholder="Period from ..."
                      end-placeholder="To ..."
                      style="width: 100%;"
                    />
                  </el-col> -->
                  <el-col :span="4">
                    <el-button
                      class="btn"
                      @click="refreshList"
                      :disabled="loading > 0"
                    >
                      <font-awesome-icon :icon="['far', 'sync-alt']" /> &nbsp;REFRESH
                    </el-button>
                  </el-col>
                </el-row>
              </form>
            </el-col>
            <el-col :span="4">

            </el-col>
          </el-row>

          <el-table
            class="m--margin-top-20"
            :data="claims.claims"
            :row-class-name="tableRowClassName"
            v-loading="!!loading"
            stripe
            max-height="400"
            empty-text="No claims currently added..."
            style="width: 100%"
          >
            <el-table-column
              type="index"
              width="70"
            />

            <el-table-column
              label="Claim #"
              min-width="120"
            >
              <template slot-scope="scope">
                <span v-if="!!scope.row.claimNumber">{{ scope.row.claimNumber }}</span>
                <em v-if="!scope.row.claimNumber">-</em>
              </template>
            </el-table-column>

            <el-table-column
              label="Patient Name"
              min-width="200"
            >
              <template slot-scope="scope">
                <template v-if="scope.row.patient">
                  <span class="m--font-boldest">
                    {{ fullname(scope.row.patient) }}
                  </span>
                  <br />
                  <small class="text-muted">
                    {{ lookup('sex', _get(scope.row, 'patient.sex')) }}
                    {{ age(scope.row.patient) }}
                  </small>
                </template>
                <template v-else>
                  <el-tooltip 
                    content="Not indicated or maybe deleted" 
                    placement="top-start" 
                    transition="el-zoom-in-bottom">
                    <span class="m--font-boldest text-muted">Not Indicated</span>
                  </el-tooltip>
                </template>
              </template>
            </el-table-column>

            <el-table-column
              label="Confinement"
              min-width="180"
            >
              <template slot-scope="scope">
                <template v-if="scope.row.admissionDate || scope.row.dischargeDate">
                  <span v-if="scope.row.admissionDate">
                    <font-awesome-icon :icon="['far', 'calendar']" class="text-info" />
                    {{ scope.row.admissionDate }}
                  </span>
                  <br/>
                  <span v-if="scope.row.dischargeDate">
                    <font-awesome-icon :icon="['far', 'calendar-check']" class="text-info" />
                    {{ scope.row.dischargeDate }}
                  </span>
                </template>
                <template v-else>
                  <span class="m--font-boldest text-muted">Not Indicated</span>
                </template>
              </template>
            </el-table-column>

            <el-table-column
              label="Status"
              min-width="120"
              align="center"
            >
              <template slot-scope="scope">
                <span
                  v-if="!!scope.row.isValid"
                >
                  <el-tag class="m--font-boldest" size="medium">
                    READY!
                  </el-tag>
                </span>
                <span
                  v-if="!scope.row.isValid"
                >
                  <el-tag type="danger" class="m--font-boldest" size="medium">
                    WITH ERRORS
                  </el-tag>
                </span>
              </template>
            </el-table-column>

            <el-table-column
              fixed="right"
              label="Actions"
              align="center"
              width="100"
            >
              <template slot-scope="scope">
                <el-button
                  plain
                  size="small"
                  :disabled="selected(scope.row)"
                  @click="select(scope.row)"
                >
                  <font-awesome-icon :icon="['far', 'plus']" />
                </el-button>
              </template>
            </el-table-column>
          </el-table>

          <pagination :bus="innerBus" />
        </div>

      </div>

      <span slot="footer" class="dialog-footer">
        <el-button
          type="text"
          @click="hide"
          class="btn btn-feature"
          style="min-width: 140px"
        >
          Done
        </el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import Vue from 'vue';
  import _get from 'lodash/get';
  import _some from 'lodash/some';
  import {
    Table,
    TableColumn,
    Dialog,
    Input,
    Tooltip,
    Select,
    Option,
    DatePicker,
    Tag,
    Button,
    Row,
    Col,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import CLAIMS_QUERY from '@/modules/claims/graphql/queries/ClaimsQuery.gql';
  import Pagination from '@/modules/core/components/Pagination';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import TransmittalForm from './index';

  export default {
    name: 'select-claims-dialog',

    mixins: [TransformsPersonData, UsesLookups],

    components: {
      FontAwesomeIcon,
      'el-dialog': Dialog,
      'el-table': Table,
      'el-table-column': TableColumn,
      'el-input': Input,
      'el-tooltip': Tooltip,
      'el-button': Button,
      'el-tag': Tag,
      'el-row': Row,
      'el-col': Col,
      'el-select': Select,
      'el-option': Option,
      'el-date-picker': DatePicker,
      'transmittal-form': TransmittalForm,
      pagination: Pagination,
    },

    props: {
      bus: {
        type: Object,
        required: true,
      },

      visible: {
        type: Boolean,
        default: false,
      },
      formData: {
        required: true,
        type: Object,
      },
    },

    data() {
      return {
        innerBus: new Vue(),
        currentPage: 1,
        currentPageIndex: 1,
        currentSearch: '',

        loading: 0,

        skipQuery: false,

        filters: {
          currentSearch: '',
          dateRange: '',
        },

        claims: {
          claims: [],
          pageInfo: {
            currentPage: 1,
            lastPage: 1,
            total: 0,
            pageSize: null,
            hasMorePages: false,
          },
        },
      };
    },

    apollo: {
      claims: {
        query: CLAIMS_QUERY,
        variables() {
          const transmittal = this.formData.id || null;
          return {
            name: this.currentSearch,
            page: this.currentPage,
            isAttached: false,
            transmittal
          };
        },

        loadingKey: 'loading',

        result(result) {
          this.processResult(result);
        },

        skip() {
          return this.skipQuery;
        },

        fetchPolicy: 'network-only',
      },
    },

    computed: {
      innerVisible: {
        get() {
          return this.visible;
        },

        set(value) {
          this.show(value);
        },
      },
    },

    methods: {
      _get(object, path, defaultValue = null) {
        return _get(object, path, defaultValue);
      },

      tableRowClassName({ row }) {
        return this.selected(row) ? 'success-row' : '';
      },

      selected(claim) {
        return _some(this.formData.claims, value => claim.id === value.id);
      },

      select(claim) {
        this.formData.claims.push(claim);
      },

      show(toggle = true) {
        this.$emit('update:visible', toggle);
      },

      hide() {
        this.show(false);
      },

      processResult({ loading, data }) {
        if (!loading) {
          const pagination = data.claims.pageInfo;
          this.currentPageIndex = ((pagination.currentPage - 1) * pagination.pageSize) + 1;
          this.innerBus.$emit('pagination.set', pagination);
        }
      },

      paginationIndex(index) {
        return index + this.currentPageIndex;
      },

      page(currentPage) {
        this.currentPage = currentPage;
      },

      refreshList () {
        this.$apollo.queries.claims.refetch();
      },

      diaglogOpenedHandler() {
        this.refreshList();
      },

      submitFilters() {
        this.currentSearch = this.filters.currentSearch;
        this.currentPage = 1;
      }
    },

    mounted() {
      this.innerBus.$on('pagination.page', this.page);
    },
  };
</script>

<style scoped>

  .select-claims-dialog--container >>> .el-table .success-row.success-row td {
    background: #f0f9eb !important;
  }

</style>