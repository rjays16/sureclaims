<template>
  <layout>
    <!-- Dialog -->
    <doctor-dialog
      :visible.sync="editing"
      :form-data="editable"
      :bus="bus"
    />

    <!-- Datatable -->
    <div class="m-datatable m-datatable--default m-datatable--brand m-datatable--loaded">
      <el-row type="flex" justify="space-between">
        <el-col :span="12">
          <el-row :gutter="10" type="flex">
            <el-col :span="12">
              <form
                @submit.prevent="changeQuery"
              >
                <el-input
                  placeholder="Search name ..."
                  prefix-icon="el-icon-search"
                  v-model="search"
                  clearable
                  :disabled="loading > 0"
                >
                  <el-button
                    native-type="submit"
                    slot="append"
                    :disabled="loading > 0"
                    class="btn btn-bold"
                  >
                    Go
                  </el-button>
                </el-input>
              </form>
            </el-col>
            <el-col :span="2">
              <el-button 
                class="btn"
                @click="refreshList"
                :disabled="loading > 0">
                <font-awesome-icon :icon="['far', 'sync-alt']" /> &nbsp;REFRESH
              </el-button>
            </el-col>
          </el-row>
        </el-col>
        <el-col :span="4">
          <el-button
            type="success"
            class="btn btn-feature"
            @click="() => edit()"
          >
            <font-awesome-icon :icon="['far', 'plus']" />
            New Doctor
          </el-button>
        </el-col>
      </el-row>

      <el-table
        class="m--margin-top-20"
        :data="doctors.doctors"
        v-loading="loading > 0"
        stripe
        max-height="400"
        empty-text="No doctors currently registered ..."
        style="width: 100%"
      >
        <el-table-column
          type="index"
          width="50"
          :index="paginationIndex">
        ></el-table-column>

        <el-table-column
          width="200"
          prop="pan"
          label="Accreditation No."
        />

        <el-table-column
          width="200"
          prop="tin"
          label="TIN"
        />

        <el-table-column
          label="Full Name"
          width="350"
        >
          <template slot-scope="scope">
            <el-tooltip content="Edit this doctor" placement="top-start" transition="el-zoom-in-bottom">
              <span class="m--font-boldest">
                <a href="#" @click="() => edit(scope.row)">
                  {{ fullname(scope.row) }}
                </a>
              </span>
            </el-tooltip>
            <br />
            <small class="text-muted">
              {{ age(scope.row) }}
            </small>
          </template>
        </el-table-column>

        <el-table-column
          prop="birthDate"
          label="Birth Date"
          width="120"
        >
        </el-table-column>

        <el-table-column
          fixed="right"
          label="Actions"
          width="200"
        >
          <template slot-scope="scope">
            <el-tooltip content="Check accreditation" placement="top-start" transition="el-zoom-in-bottom">
              <el-button type="text" @click="() => edit(scope.row)">
                <font-awesome-icon :icon="['far', 'clipboard-check']" size="lg" transform="shrink-2" />
              </el-button>
            </el-tooltip>

            <el-tooltip content="Edit this doctor" placement="top-start" transition="el-zoom-in-bottom">
              <el-button type="text" @click="() => edit(scope.row)">
                <font-awesome-icon :icon="['far', 'pencil']" size="lg" transform="shrink-2" />
              </el-button>
            </el-tooltip>

            <el-tooltip content="Delete this doctor" placement="top-start" transition="el-zoom-in-bottom">
              <el-button type="text" @click="() => confirmDelete(scope.row.id)" class="m--margin-left-10">
                <font-awesome-icon :icon="['far', 'trash']" size="lg" transform="shrink-2" />
              </el-button>
            </el-tooltip>
          </template>
        </el-table-column>
      </el-table>

      <pagination :bus="bus" />
    </div>
  </layout>
</template>

<script>
  import Vue from 'vue';
  import { Table, TableColumn, Dialog, Input, Tooltip, Button, Row, Col } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import DOCTORS_QUERY from '@/modules/doctors/graphql/queries/DoctorsQuery.gql';
  import DELETE_DOCTOR_MUTATION from '@/modules/doctors/graphql/mutations/DeleteDoctorMutation.gql';
  import UsesLayout from '@/modules/core/mixins/UsesLayout';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';
  import Pagination from '@/modules/core/components/Pagination';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import DoctorDialog from '../components/DoctorDialog';

  export default {
    name: 'member-list-view',

    mixins: [
      UsesLayout,
      UsesLookups,
      SetsPageMeta,
      TransformsPersonData,
    ],

    components: {
      FontAwesomeIcon,
      'el-dialog': Dialog,
      'el-table': Table,
      'el-table-column': TableColumn,
      'el-input': Input,
      'el-tooltip': Tooltip,
      'el-button': Button,
      'el-row': Row,
      'el-col': Col,
      pagination: Pagination,
      'doctor-dialog': DoctorDialog,
    },

    data() {
      return {
        bus: new Vue(),

        loading: 0,
        formSubmitting: false,

        editing: false,
        editable: {},

        search: '',
        currentSearch: '',
        currentPage: 1,
        currentPageIndex: 1,

        skipQuery: false,

        doctors: {
          doctors: [],
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

    mounted() {
      this.bus.$on('form.submitting', this.submitting);
      this.bus.$on('form.submitted', this.submitted);
      this.bus.$on('form.failed', this.failed);

      this.bus.$on('pagination.page', this.page);
    },

    apollo: {
      doctors: {
        query: DOCTORS_QUERY,
        variables() {
          return {
            name: this.currentSearch,
            page: this.currentPage,
          };
        },
        loadingKey: 'loading',
        result(result) {
          this.processResult(result);
        },
        skip() {
          return this.skipQuery;
        },
        fetchPolicy: 'cache-and-network',
      },
    },

    methods: {
      refreshList() {
        this.$apollo.queries.doctors.refetch();
      },

      deleteDoctor(id) {
        const variables = {
          name: this.currentSearch,
          page: this.currentPage,
        };
        this.loading += 1;
        this.$apollo.mutate({
          mutation: DELETE_DOCTOR_MUTATION,
          variables: {
            id,
          },
          refetchQueries: [
            {
              query: DOCTORS_QUERY,
              variables,
            },
          ],
        }).then(({ data: { deleteDoctor } }) => {
          const name = this.fullname(deleteDoctor);
          this.$snotify.success(`Doctor ${name} successfully deleted!`, 'Success!');
        }).catch(() => {
          this.$snotify.error('Failed to delete item', 'Oops!');
        }).then(() => {
          this.loading -= 1;
        });
      },

      edit(data = null) {
        if (data) {
          this.editable = JSON.parse(JSON.stringify(data));
        } else {
          this.editable = {
            pan: '',
            tin: '',
            lastName: '',
            firstName: '',
            middleName: '',
            suffix: '',
            birthDate: '',
          };
        }

        this.editing = true;
      },

      processResult({ loading, data }) {
        if (!loading) {
          const pagination = data.doctors.pageInfo;
          this.currentPageIndex = ((pagination.currentPage - 1) * pagination.pageSize) + 1;
          this.bus.$emit('pagination.set', pagination);
        }
      },

      paginationIndex(index) {
        return index + this.currentPageIndex;
      },

      page(currentPage) {
        this.currentPage = currentPage;
      },

      submitting() {
        // Do nothing for now
      },

      submitted() {
        this.editing = false;
      },

      failed() {
        // Do nothing for now
      },

      changeQuery() {
        const query = {
          search: this.search,
        };
        this.currentSearch = query.search;
        this.currentPage = 1;
      },

      confirmDelete(id) {
        this.$snotify.confirm('Remove this doctor from our records?', 'Delete?', {
          timeout: 5000,
          showProgressBar: true,
          closeOnClick: false,
          pauseOnHover: true,
          buttons: [
            {
              text: 'Yes',
              action: (toast) => {
                this.$snotify.remove(toast.id);
                this.deleteDoctor(id);
              },
              bold: false,
            },
            {
              text: 'Close',
              action: (toast) => {
                this.$snotify.remove(toast.id);
              },
              bold: true,
            },
          ],
        });
      },
    },
  };
</script>
