<template>
  <layout>

    <!-- Dialog -->
    <member-form-dialog
      v-bind="memberFormOptions"
      :visible.sync="memberFormOptions.visible"
    />

    <!-- Supporting Documents Dialog -->
    <supporting-documents
      v-bind="supportingDocumentsOptions"
      :visible.sync="supportingDocumentsOptions.visible"
    />

    <!-- Validation Errors Dialog -->
    <validation-errors
      v-bind="validationErrorsOptions"
      :visible.sync="validationErrorsOptions.visible"
    />

    <!-- Dialog -->
    <claim-dialog
      :visible.sync="editing"
      :form-data="editable.data"
      :claim="editable.claim"
      :bus="bus"
    />

    <!-- Datatable -->
    <div class="m-datatable m-datatable--default m-datatable--brand m-datatable--loaded">
      <el-row type="flex" justify="space-between">
        <el-col :span="18">
          <!-- Search filters go here -->
          <el-row :gutter="10" type="flex">
            <el-col :span="7">
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
            <el-col :span="7">
              <form
                @submit.prevent="changeQuery"
              >
              <el-input
                placeholder="Search claim no ..."
                prefix-icon="el-icon-search"
                v-model="claimsSearch"
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
            <el-col :span="5">
              <el-select @change="changeQuery" v-model="filters.status" placeholder="Status ..." style="width: 100%;">
                <el-option value="" label="All claim status">
                  <span class="text-muted m--font-bolder">All claim status</span>
                </el-option>
                <el-option value="ready" label="Ready">
                  <span class="text-info m--font-boldest uppercase">READY</span>
                </el-option>
                <el-option value="error" label="With Errors">
                  <span class="text-danger m--font-boldest uppercase">WITH ERRORS</span>
                </el-option>
                <el-option value="IN PROCESS" label="In Process">
                  <span class="text-success m--font-boldest uppercase">IN PROCESS</span>
                </el-option>
                 <el-option value="transmitted" label="Transmitted">
                  <span class="text-success m--font-boldest uppercase">TRANSMITTED</span>
                </el-option>
                <el-option value="RETURN" label="Return">
                  <span class="text-success m--font-boldest uppercase">RETURN</span>
                </el-option>
                <el-option value="DENIED" label="Denied">
                  <span class="text-success m--font-boldest uppercase">DENIED</span>
                </el-option>
                <el-option value="WITH CHEQUE" label="With Cheque">
                  <span class="text-success m--font-boldest uppercase">WITH CHEQUE</span>
                </el-option>
                <el-option value="WITH VOUCHER" label="With Voucher">
                  <span class="text-success m--font-boldest uppercase">WITH VOUCHER</span>
                </el-option>
              </el-select>
            </el-col>
            <el-col :span="8">
              <el-date-picker
                v-model="filters.dateRange"
                type="daterange"
                value-format="MM-dd-yyyy"
                format="MM-dd-yyyy"
                align="left"
                unlink-panels
                range-separator="-"
                start-placeholder="Period from ..."
                end-placeholder="To ..."
                style="width:100%;"
              />
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
        <el-col :span="4" align="right">
          <el-button
            type="success"
            class="btn btn-feature"
            @click="edit()"
          >
            <font-awesome-icon :icon="['far', 'plus']" />
            New Claim
          </el-button>
        </el-col>
      </el-row>

      <el-table
        class="m--margin-top-20"
        :data="claims.claims"
        v-loading="loading > 0"
        stripe
        max-height="400"
        empty-text="No claims found ..."
        highlight-current-row
        style="width: 100%"
      >
        <el-table-column type="expand">
          <template slot-scope="scope">
            <div>
              <el-row type="flex" justify="space-between" :gutter="24">
                <el-col :span="8" align="right"><b>Transmittal No.:</b></el-col>
                <el-col :span="8" style="color: #716aca"><i v-if="scope.row.transmittalId">{{ scope.row.transmittal.transmittalNo }}</i></el-col>

                <el-col :span="4" align="left"><b>Date Inquiry:</b></el-col>
                <el-col :span="8" align="left" style="color: #716aca"><i v-if="scope.row.claimStatusDetail">{{ scope.row.claimStatusDetail.dateInquiry }}</i></el-col>
              </el-row>

              <el-row type="flex" justify="space-between" :gutter="24">
                <el-col :span="8" align="right"><b>Claim Status: </b></el-col>
                <el-col :span="8" style="color: #716aca"><i v-if="scope.row.status">{{ scope.row.status }}</i></el-col>

                <el-col :span="4" align="left"><b>Date Received:</b></el-col>
                <el-col :span="8" align="left" style="color: #716aca"><i v-if="scope.row.claimStatusDetail">{{ scope.row.claimStatusDetail.claimDateReceived }}</i></el-col>
              </el-row>

              <el-row type="flex" justify="space-between" :gutter="24">
                <el-col :span="8" align="right"><b>Claim Series No.: </b></el-col>
                <el-col :span="8" style="color: #716aca"><i v-if="scope.row.lhioSeriesNo">{{ scope.row.lhioSeriesNo }}</i></el-col>

                <el-col :span="4" align="left"><b>Date Refile:</b></el-col>
                <el-col :span="8" align="left" style="color: #716aca"><i v-if="scope.row.claimStatusDetail">{{ scope.row.claimStatusDetail.claimDateRefile }}</i></el-col>
              </el-row>
            </div>
          </template>
        </el-table-column>

        <el-table-column
          width="120"
          label="Claim #"
          align="center"
        >
          <template slot-scope="scope">
            <small v-if="!!scope.row.claimNumber">{{ scope.row.claimNumber }}</small>
            <em v-if="!scope.row.claimNumber">-</em>
          </template>
        </el-table-column>

        <el-table-column
          label="Patient Name"
          min-width="240">
          <template slot-scope="scope">
            <template v-if="scope.row.patient">
              <el-tooltip content="Edit patient information" placement="top-start" transition="el-zoom-in-bottom">
                <span class="m--font-boldest">
                  <a href="#" @click="editPerson(scope.row.patient)">
                    {{ fullname(scope.row.patient) }}
                  </a>
                </span>
              </el-tooltip>
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
          label="Member Name"
          min-width="240">
          <template slot-scope="scope">
            <span class="m--font-bolder" v-if="isPrincipalMember(scope.row.patient)">
              <span class="m--font-boldest text-muted">Principal Holder</span>
            </span>
            <template v-else>
              <template v-if="_get(scope.row.patient, 'member.principal', false)">
                <el-tooltip content="Edit member information" placement="top-start" transition="el-zoom-in-bottom">
                  <span class="m--font-boldest">
                    <a href="#" @click="editPerson(scope.row.patient.member.principal)">
                      {{ principalName(scope.row.patient) }}
                    </a>
                  </span>
                </el-tooltip>
                <br />
                <small class="text-muted">{{ relationToMember(scope.row.patient) }}</small>
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
          </template>
        </el-table-column>

        <el-table-column
          label="Confinement"
          width="130">
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
          width="160"
          align="center"
        >
          <template slot-scope="scope">
            <a
              href="#"
              v-if="!!scope.row.isValid && !scope.row.status"
              @click.prevent="validationErrors(scope.row)"
            >
              <el-tag v-if="!scope.row.lhioSeriesNo && scope.row.isValid == 1" class="m--font-boldest" size="medium">
                READY!
              </el-tag>
              <el-tag v-else class="m--font-boldest" size="medium">
                TRANSMITTED
              </el-tag>
            </a>
            <a
              href="#"
              v-if="(!scope.row.lhioSeriesNo && !scope.row.isValid && !scope.row.status) "
              @click.prevent="validationErrors(scope.row)"
            >
              <el-tag type="danger" class="m--font-boldest" size="medium">
                WITH ERRORS
              </el-tag>
            </a>

            <a
              href="#"
              v-if="!!scope.row.lhioSeriesNo"
              @click.prevent="showClaimStatusDetails(scope.row.id)"
            >
              <el-tag
                v-if="ClaimStatus.isValid(scope.row.status)"
                type="success"
                class="m--font-boldest uppercase"
                size="medium">
                {{scope.row.status}}
              </el-tag>
            </a>
            <el-tooltip
              v-if="!!scope.row.lhioSeriesNo"
              content="Refresh Claim Status?"
              placement="top-start"
              transition="el-zoom-in-bottom">
              <el-button @click="refreshClaimStatus(scope.row.id)" type="text">
                <font-awesome-icon :icon="['far', 'redo']"/>
              </el-button>
            </el-tooltip>
          </template>
        </el-table-column>

        <el-table-column
          label="Documents"
          width="120"
          align="center"
        >
          <template slot-scope="scope">
            <el-button type="text" class="m--font-bolder" @click="supportingDocuments(scope.row)">
              <template v-if="!!scope.row.supportingDocuments && scope.row.supportingDocuments.length > 0">
                {{ scope.row.supportingDocuments.length }} files
              </template>
              <template v-else>
                <font-awesome-icon :icon="['far', 'plus']" />
                Upload
              </template>
            </el-button>
          </template>
        </el-table-column>

        <el-table-column
          fixed="right"
          label="Actions"
          width="120"
          align="center"
        >
          <template slot-scope="scope">
            <template v-if="scope.row.status === 'RETURN' && scope.row.returnDocuments !== null">
              <el-tooltip content="Refile to PHIC" placement="top-start" transition="el-zoom-in-bottom">
                <el-button type="text" @click="refileClaim(scope.row)">
                  <font-awesome-icon :icon="['far', 'upload']" size="lg" transform="shrink-2" />
                </el-button>
              </el-tooltip>
            </template>

            <template v-if="!scope.row.lhioSeriesNo">
              <el-tooltip content="Edit this claim" placement="top-start" transition="el-zoom-in-bottom">
                <el-button type="text" @click="edit(scope.row)">
                  <font-awesome-icon :icon="['far', 'pencil']" size="lg" transform="shrink-2" />
                </el-button>
              </el-tooltip>

              <el-tooltip content="Delete this claim?" placement="top-start" transition="el-zoom-in-bottom">
                <el-button type="text" @click="confirmDelete(scope.row.id)" class="m--margin-left-5">
                  <font-awesome-icon :icon="['far', 'trash']" size="lg" transform="shrink-2" />
                </el-button>
              </el-tooltip>
            </template>
            <el-tooltip content="Print CSF" placement="top-start" transition="el-zoom-in-bottom">
              <el-button type="text" @click="printCsf(scope.row)">
                <font-awesome-icon :icon="['far', 'file-pdf']" size="lg" transform="shrink-2" />
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
  import _get from 'lodash/get';
  import { url } from '@/helpers/url';
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
  import DELETE_CLAIM_MUTATION from '@/modules/claims/graphql/mutations/DeleteClaimMutation.gql';
  import REFRESH_CLAIM_STATUS_MUTATION from '@/modules/claims/graphql/mutations/RefreshClaimStatusMutation.gql';
  import REFILE_MUTATION from '@/modules/claims/graphql/mutations/RefileMutation.gql';
  import UsesLayout from '@/modules/core/mixins/UsesLayout';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';
  import Pagination from '@/modules/core/components/Pagination';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import MemberFormDialog from '@/modules/members/components/MemberForm/Dialog';
  import ClaimDialog from '../components/ClaimForm/Dialog';
  import ValidationErrors from '../components/ValidationErrors';
  import { CLAIM as claimFactory } from '../components/ClaimForm/Factory/index';
  import SupportingDocuments from '../components/SupportingDocuments';
  import ClaimStatusValidator from '../lib/ClaimStatusValidator';

  export default {
    name: 'claims-list-view',

    mixins: [
      UsesLayout,
      UsesLookups,
      SetsPageMeta,
      TransformsPersonData,
    ],

    components: {
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
      'font-awesome-icon': FontAwesomeIcon,
      pagination: Pagination,
      'claim-dialog': ClaimDialog,
      'validation-errors': ValidationErrors,
      'member-form-dialog': MemberFormDialog,
      'supporting-documents': SupportingDocuments,
    },

    data() {
      return {
        bus: new Vue(),

        loading: 0,
        formSubmitting: false,

        creating: false,
        creatable: {},
        editing: false,
        editable: {
          data: {
            patient: null,
          },
          claim: claimFactory(),
        },

        filters: {
          name: '',
          dateRange: '',
          status: '',
        },

        search: '',
        currentSearch: '',
        currentSearchClaim: '',
        claimsSearch: '',
        currentPage: 1,
        currentPageIndex: 1,

        validationErrorsOptions: {
          xmlLink: '',
          errors: [],
          xml: '',
          visible: false,
        },

        supportingDocumentsOptions: {
          claim: null,
          visible: false,
        },

        memberFormOptions: {
          formData: null,
          visible: false,
        },

        skipQuery: false,

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

    mounted() {
      this.page();
      this.ClaimStatus = ClaimStatusValidator;
      this.bus.$on('form.submitting', this.submitting);
      this.bus.$on('form.submitted', this.submitted);
      this.bus.$on('form.failed', this.failed);
      this.bus.$on('dialog.close', this.closeDialog);
      this.bus.$on('pagination.page', this.page);
    },

    apollo: {
      claims: {
        query: CLAIMS_QUERY,

        variables() {
          return {
            name: this.currentSearch,
            claimNo: this.currentSearchClaim,
            page: this.currentPage,
            status: this.filters.status,
          };
        },
        loadingKey: 'loading',

        result(result) {
          this.processResult(result);
        },

        skip() {
          return this.skipQuery;
        },
      },
    },

    methods: {
      _get(object, path, defaultValue = null) {
        return _get(object, path, defaultValue);
      },

      refileClaim(row) {
        const data = {
          id: row.id,
          lhioSeriesNo: row.lhioSeriesNo,
          returnDocuments: JSON.stringify(row.returnDocuments),
          supportingDocuments: JSON.stringify(row.supportingDocuments)
        };

        const variables = {
          ...data,
        };
        this.loading += 1;
        this.$apollo.mutate({
          mutation: REFILE_MUTATION,
          variables,
          refetchQueries: [
            'Claims',
          ],
        }).then(() => {
          this.$snotify.success('Claim successfully refiled!', 'Success!');
          this.refreshClaimStatus(row.id);
        }).catch((error) => {
          this.$snotify.error(`Refile failed - ${error.message}`, 'Oops!');
        }).then(() => {
          this.loading -= 1;
        });
      },

      refreshList() {
        this.$apollo.queries.claims.refetch();
      },

      deleteClaim(id) {
        this.loading += 1;
        this.$apollo.mutate({
          mutation: DELETE_CLAIM_MUTATION,
          variables: {
            id,
          },
          refetchQueries: [
            'Claims',
          ],
        }).then(({ data: { deleteClaim } }) => {
          const name = this.fullname(deleteClaim.patient);
          this.$snotify.success(`Claim ${name} successfully deleted!`, 'Success!');
        }).catch(() => {
          this.$snotify.error('Failed to delete item', 'Oops!');
        }).then(() => {
          this.loading -= 1;
        });
      },

      create(data = null) {
        if (data) {
          this.creatable = JSON.parse(JSON.stringify(data));
        } else {
          this.creatable = {
          };
        }

        this.creating = true;
      },

      printCsf(data = null) {
        console.log(data);
        window.open(url(`document/printCsf/${String(data.patientId)}`), 'mywindow', 'status=1');
      },

      edit(data = null) {
        if (data) {
          this.editable = {
            data: {
              id: data.id,
              selectedPatient: data.patient,
              xml: data.xml,
              xmlLink: data.xmlLink,
              isValid: data.isValid,
              validationErrors: JSON.parse(data.validationErrors),
              patient: String(data.patientId),
            },
            claim: JSON.parse(data.data),
          };
        } else {
          this.editable = {
            data: {
              id: null,
              patient: null,
              xml: '',
              xmlLink: null,
              isValid: false,
              validationErrors: [],
            },
            claim: claimFactory(),
          };
        }

        this.editing = true;
      },

      editPerson(member = null) {
        this.memberFormOptions.formData = JSON.parse(JSON.stringify(member));
        this.memberFormOptions.visible = true;
      },

      validationErrors(claim) {
        this.validationErrorsOptions.xmlLink = claim.xmlLink;
        this.validationErrorsOptions.xml = claim.xml;
        this.validationErrorsOptions.errors = JSON.parse(claim.validationErrors);
        this.validationErrorsOptions.visible = true;
      },

      supportingDocuments(claim) {
        // console.log(claim);
        this.supportingDocumentsOptions.claim = claim;
        this.supportingDocumentsOptions.visible = true;
      },

      processResult({ loading, data }) {
        if (!loading) {
          const pagination = data.claims.pageInfo;
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
      },

      closeDialog() {
        this.editing = false;
      },

      failed() {
        // Do nothing for now
      },

      changeQuery() {
        const query = {
          search: this.search,
        };
        const queryClaim = {
          claimsSearch: this.claimsSearch,
        };
        this.currentSearch = query.search;
        this.currentSearchClaim = queryClaim.claimsSearch;
        this.currentPage = 1;
      },

      confirmDelete(id) {
        this.$snotify.confirm('Remove this claim from our records?', 'Delete?', {
          timeout: 5000,
          showProgressBar: true,
          closeOnClick: false,
          pauseOnHover: true,
          buttons: [
            {
              text: 'Yes',
              action: (toast) => {
                this.$snotify.remove(toast.id);
                this.deleteClaim(id);
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

      showClaimStatusDetails(id) {
        // console.log(id, 'Show claim status details like in process, denied, etc. details...');
      },

      refreshClaimStatus(id) {
        const variables = {
          name: this.currentSearch,
          claimNo: this.claimsSearch,
          page: this.currentPage,
        };
        this.loading += 1;
        this.$apollo.mutate({
          mutation: REFRESH_CLAIM_STATUS_MUTATION,
          variables: {
            ids: [id],
          },
          refetchQueries: [
            {
              query: CLAIMS_QUERY,
              variables,
            },
          ],
        }).then(({ data: { refreshClaimStatus } }) => {
          const number = refreshClaimStatus[0].claimNumber;
          this.$snotify.success(`Claim ${number} successfully updated!`, 'Success!');
        }).catch(({ graphQLErrors: [{ message }] }) => {
          const appendMessage = '. Call your system provider for support.';
          this.$snotify.error((message || 'Failed to update item') + appendMessage, 'Oops!');
        }).then(() => {
          this.loading -= 1;
        });
      }
    },
  };
</script>
