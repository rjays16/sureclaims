<template>
  <layout>

    <!-- Dialog -->
    <member-form-dialog
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
        <el-col :span="12" align="right">
          <el-button
            class="btn btn-feature"
            type="success"
            @click="() => newMember()"
          >
            <font-awesome-icon :icon="['far', 'plus']" />
            New Member
          </el-button>

          <el-button
            class="btn btn-feature"
            @click="() => edit()"
          >
            <font-awesome-icon :icon="['far', 'plus']" />
            New Patient
          </el-button>
        </el-col>
      </el-row>

      <el-table
        class="m--margin-top-20"
        :data="personsPage.persons"
        v-loading="loading > 0"
        stripe
        max-height="400"
        empty-text="No records found ..."
        style="width: 100%"
      >
        <el-table-column
          type="index"
          width="50"
          :index="paginationIndex">
        ></el-table-column>

        <el-table-column
          label="Type"
          width="150"
        >
          <template slot-scope="scope">
            <span v-if="scope.row.member && scope.row.member.relation === 'M' ">
              Member
              <br/>
              <small>{{ lookup('member.membershipType', scope.row.member.membershipType, '-') }}</small>
            </span>
            <span v-if="scope.row.member && scope.row.member.relation !== 'M' ">
              Dependent
              <br/>
              <small>{{ lookup('dependent.relation', scope.row.member.relation, '-') }}</small>
            </span>
          </template>
        </el-table-column>

        <el-table-column
          label="Full Name"
          width="280"
        >
          <template slot-scope="scope">
            <el-tooltip content="Edit this record" placement="top-start" transition="el-zoom-in-bottom">
              <span class="m--font-boldest">
                <a href="#" @click="() => edit(scope.row)">
                  {{ fullname(scope.row) }}
                </a>
              </span>
            </el-tooltip>
            <br />
            <small class="text-muted">
              {{ lookup('sex', scope.row.sex) }}
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
          label="Address"
        >
          <template slot-scope="scope">
            <span style="font-size: 0.9rem">{{ scope.row.mailingAddress }}</span>
          </template>
        </el-table-column>
        <el-table-column
          fixed="right"
          label="Actions"
          align="center"
          width="150"
        >
          <template slot-scope="scope">
            <el-tooltip content="Edit this record" placement="top-start" transition="el-zoom-in-bottom">
              <el-button type="text" @click="() => edit(scope.row)">
                <font-awesome-icon :icon="['far', 'pencil']" size="lg" transform="shrink-2" />
              </el-button>
            </el-tooltip>

            <el-tooltip content="Delete this record" placement="top-start" transition="el-zoom-in-bottom">
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
  import _defaultsDeep from 'lodash/defaultsDeep';
  import { Table, TableColumn, Dialog, Input, Tooltip, Button, Row, Col } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  // import { invalidateFields, ROOT } from 'apollo-cache-invalidation';
  import PERSONS_PAGE_QUERY from '@/modules/core/graphql/queries/PersonsPageQuery.gql';
  import DELETE_PERSON_MUTATION from '@/modules/core/graphql/mutations/DeletePersonMutation.gql';
  import UsesLayout from '@/modules/core/mixins/UsesLayout';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import Pagination from '@/modules/core/components/Pagination';

  import MemberForm from '../components/MemberForm';
  import MemberFormDialog from '../components/MemberForm/Dialog';

  export default {
    name: 'member-list-view',

    mixins: [
      UsesLayout,
      UsesLookups,
      SetsPageMeta,
      TransformsPersonData,
    ],

    components: {
      Pagination,
      FontAwesomeIcon,
      'el-dialog': Dialog,
      'el-table': Table,
      'el-table-column': TableColumn,
      'el-input': Input,
      'el-tooltip': Tooltip,
      'el-button': Button,
      'el-row': Row,
      'el-col': Col,
      'member-form': MemberForm,
      'member-form-dialog': MemberFormDialog,
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

        personsPage: {
          persons: [],
          pageInfo: {
            currentPage: 1,
            lastPage: 1,
            total: 0,
            pageSize: null,
            hasMorePages: false,
          },
        },

        defaultData: {
          lastName: '',
          firstName: '',
          middleName: '',
          birthDate: '',
          suffix: '',
          sex: '',
          member: {
            pin: '',
            pen: '',
            membershipType: '',
            relation: '',
            principal: {
            },
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
      personsPage: {
        query: PERSONS_PAGE_QUERY,
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
        // fetchPolicy: 'cache-first',
      },
    },

    methods: {
      refreshList() {
        this.$apollo.queries.personsPage.refetch();
      },

      paginationIndex(index) {
        return index + this.currentPageIndex;
      },

      deletePerson(id) {
        const variables = {
          name: this.currentSearch,
          page: this.currentPage,
        };

        this.loading += 1;
        // const $apollo = this.$apollo;
        /*
        const update = invalidateFields(() => [
          [ROOT, 'personsPage'],
        ]);
        */

        this.$apollo.mutate({
          mutation: DELETE_PERSON_MUTATION,
          variables: {
            id,
          },
          refetchQueries: [
            {
              query: PERSONS_PAGE_QUERY,
              variables,
            },
          ],

          // update,
        }).then(({ data: { deletePerson } }) => {
          const name = this.fullname(deletePerson);
          this.$snotify.success(`Record for ${name} successfully deleted!`, 'Success!');
        }).catch(() => {
          this.$snotify.error('Failed to delete item', 'Oops!');
        }).then(() => {
          this.loading -= 1;
        });
      },

      newMember() {
        this.edit({
          member: {
            pid: '',
            relation: 'M',
          },
        });
      },

      edit(data) {
        if (data) {
          this.editable = _defaultsDeep(JSON.parse(JSON.stringify(data)), this.defaultData);
        } else {
          this.editable = Object.assign({}, this.defaultData);
        }
        this.editing = true;
      },

      processResult({ loading, data }) {
        if (!loading) {
          const pagination = data.personsPage.pageInfo;
          this.currentPageIndex = ((pagination.currentPage - 1) * pagination.pageSize) + 1;
          this.bus.$emit('pagination.set', pagination);
        }
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
        this.$snotify.confirm('Remove this person from our records?', 'Delete?', {
          timeout: 5000,
          showProgressBar: true,
          closeOnClick: false,
          pauseOnHover: true,
          buttons: [
            {
              text: 'Yes',
              action: (toast) => {
                this.$snotify.remove(toast.id);
                this.deletePerson(id);
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
