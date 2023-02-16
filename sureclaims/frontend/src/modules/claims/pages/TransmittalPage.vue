<template>
  <layout>

    <!-- Dialog -->
    <transmittal-dialog
      :bus="bus"
      :form-data="transmittalForm.data"
      :visible.sync="transmittalForm.visible"
    />

    <!-- Dialog -->
    <transmittal-report-dialog
      :bus="bus"
      :form-data="transmittalReport.data"
      :visible.sync="transmittalReport.visible"
    />

    <!-- Dialog -->
    <report-dialog
      :bus="bus"
      :form-data="report.data"
      :visible.sync="report.visible"
    />

    <!-- Validation Errors Dialog -->
    <view-xml
      v-bind="viewClaimsXMLOptions"
      :visible.sync="viewClaimsXMLOptions.visible"
    />

    <!-- Datatable -->
    <el-row :gutter="20" class="margin-side-20">
          <el-col :span="24" :xs="24">
            <el-alert
              v-show="error"
              title="Error Message "
              type="error"
              @close="MessageClose">
              {{ message }}
            </el-alert>
            <el-alert
              v-show="success"
              title="Success Message: "
              type="success"
              @close="MessageClose">
              {{ message }}
            </el-alert>
          </el-col>
    </el-row>
    <div class="m-datatable m-datatable--default m-datatable--brand m-datatable--loaded m--padding-top-10">
      <el-row type="flex" justify="space-between">
        <el-col :span="16">
          <form
            @submit.prevent="changeQuery"
          >
            <el-row :gutter="10">

              <el-col :span="8">
                <el-input
                  placeholder="Search Transmittal # ..."
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
              </el-col>

              <el-col :span="3">
                <el-select v-model="filters.status" placeholder="Status ..." style="width:100%;">
                  <el-option value="pending" label="Pending">
                    <span class="text-muted m--font-boldest">Pending</span>
                  </el-option>
                  <el-option value="success" label="Transmitted">
                    <span class="text-success m--font-boldest">Transmitted</span>
                  </el-option>
                  <el-option value="error" label="With Errors">
                    <span class="text-danger m--font-boldest">With Errors</span>
                  </el-option>
                </el-select>
              </el-col>
              <el-col :span="9">
                <el-date-picker
                  v-model="filters.dateRange"
                  type="daterange"
                  format="yyyy-MM-dd"
                  value-format="yyyy-MM-dd"
                  align="right"
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
                  :disabled="loading > 0"
                >
                  <font-awesome-icon :icon="['far', 'sync-alt']" /> &nbsp;REFRESH
                </el-button>
              </el-col>
            </el-row>
          </form>
        </el-col>

        <el-col :span="4" align="right">
          <el-button
            type="success"
            class="btn"
            @click="openReport"
          >
            <font-awesome-icon :icon="['far', 'print']" />
            Reports
          </el-button>
        </el-col>

        <el-col :span="4" align="right">
          <el-button
            type="success"
            class="btn btn-feature"
            @click="edit()"
          >
            <font-awesome-icon :icon="['far', 'plus']" />
            New Transmittal
          </el-button>
        </el-col>
      </el-row>

      <el-table
        class="m--margin-top-20"
        :data="transmittals.transmittals"
        v-loading="loading > 0"
        stripe
        max-height="400"
        empty-text="No transmittals found ..."
        style="width: 100%"
      >
        <el-table-column type="expand">
          <template slot-scope="scope">
            <el-row type="flex" :gutter="24">
              <el-col :span="4" align="right"><b>Created By:</b></el-col>
              <el-col :span="4" style="color: #716aca"><i v-if="scope.row.createdBy">{{ scope.row.createdBy }}</i></el-col>
              <el-col :span="4" align="right"><b>Transmitted By: </b></el-col>
              <el-col :span="4" style="color: #716aca"><i v-if="scope.row.updatedBy">{{ scope.row.updatedBy }}</i></el-col>
              <el-col :span="4" align="right"><b>Receipt Ticket No: </b></el-col>
              <el-col :span="4" style="color: #716aca"><i v-if="scope.row.receiptTicketNo">{{ scope.row.receiptTicketNo }}</i></el-col>
            </el-row>
            <br>

            <el-table
              class=""
              :data="scope.row.claims"
              v-loading="!!loading"
              stripe
              border
              size="small"
              empty-text="No claims included ..."
              style="width: 100%"
            >
              <el-table-column
                type="index"
                width="50"
                align="center"
              />

              <el-table-column
                min-width="120"
                label="Claim #"
                align="center"
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
                label="Member Name"
                min-width="200"
              >
                <template slot-scope="scope">
                  <span class="m--font-bolder" v-if="isPrincipalMember(scope.row.patient)">
                    <span class="m--font-boldest text-muted">Principal Holder</span>
                  </span>
                  <template v-else>
                    <template v-if="_get(scope.row.patient, 'member.principal', false)">
                      <span class="m--font-bolder">
                        {{ principalName(scope.row.patient) }}
                      </span>
                      <br />
                      <small>{{ relationToMember(scope.row.patient) }}</small>
                    </template>
                    <template v-else>
                      <span class="m--font-boldest text-muted">Not Indicated</span>
                    </template>
                  </template>
                </template>
              </el-table-column>

              <el-table-column
                label="Confinement"
                width="180"
              >
                <template slot-scope="scope">
                  <template v-if="scope.row.admissionDate || scope.row.dischargeDate">
                    <span v-if="scope.row.admissionDate">
                      <font-awesome-icon :icon="['far', 'calendar']" />
                      {{ scope.row.admissionDate }}
                    </span>
                      <br/>
                      <span v-if="scope.row.dischargeDate">
                      <font-awesome-icon :icon="['far', 'calendar-check']" />
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
                width="120"
                align="center"
              >
                <template slot-scope="scope">
                  <span
                    v-if="!!scope.row.isValid"
                    @click.prevent="viewClaimsXML(scope.row)"
                  >
                    <el-tag class="m--font-boldest" size="medium">
                      READY!
                    </el-tag>
                  </span>
                  <a href="#"
                    v-if="!scope.row.isValid"
                    @click.prevent="viewClaimsXML(scope.row)"
                  >
                    <el-tag type="danger" class="m--font-boldest" size="medium">
                      WITH ERRORS
                    </el-tag>
                  </a>
                </template>
              </el-table-column>
            </el-table>
          </template>
        </el-table-column>

        <el-table-column
          width="150"
          label="Transmittal #"
        >
          <template slot-scope="scope">
            <span v-if="!!scope.row.transmittalNo">{{ scope.row.transmittalNo }}</span>
            <em v-if="!scope.row.transmittalNo">-</em>
          </template>
        </el-table-column>

        <el-table-column
          label="Created"
          width="150"
        >
          <template slot-scope="scope">
            <span>
              <font-awesome-icon :icon="['far', 'calendar']" class="text-info" />
              {{ scope.row.createdAt.split(' ').shift() }}
            </span>
            <br/>
            <span>
              <font-awesome-icon :icon="['far', 'clock']" class="text-info" />
              {{ scope.row.createdAt.split(' ').pop() }}
            </span>
          </template>
        </el-table-column>

        <el-table-column
          width="100"
          label="# Claims"
          align="center"
        >
          <template slot-scope="scope">
            <span v-if="scope.row.claims && scope.row.claims.length">{{ scope.row.claims.length }}</span>
            <span v-else class="m--font-boldest text-muted">None</span>
          </template>
        </el-table-column>

        <el-table-column
          label="Date Transmitted"
          width="165"
        >
          <template slot-scope="scope">
            <span v-if="!scope.row.transmitDate">
              Not transmitted yet
            </span>
            <span v-if="scope.row.transmitDate">
              <font-awesome-icon :icon="['far', 'calendar']" />
              {{ scope.row.transmitDate }}
            </span>
            <br/>
            <span v-if="scope.row.transmitTime">
              <font-awesome-icon :icon="['far', 'clock']" />
              {{ scope.row.transmitTime }}
            </span>
          </template>
        </el-table-column>

        <el-table-column
          label="Remarks"
          min-width="200"
          prop="remarks"
        >
          <template slot-scope="scope">
            <span class="transmittal--notes">{{scope.row.notes}}</span>
          </template>
        </el-table-column>

        <el-table-column
          label="Status"
          width="150"
          align="center"
        >
          <template slot-scope="scope">
            <a
              href="#"
              v-if="status(scope.row) === 'success'"
            >
              <el-tag type="succes" class="m--font-boldest" size="medium">
                TRANSMITTED
              </el-tag>
            </a>
            <a
              href="#"
              v-if="!!scope.row.isValid && status(scope.row) === 'pending'"
            >
              <el-tag class="m--font-boldest" size="medium">
                READY!
              </el-tag>
            </a>
            <a
              href="#"
              v-if="!scope.row.isValid || status(scope.row) === 'error'"
            >
              <el-tag type="danger" class="m--font-boldest" size="medium">
                WITH ERRORS
              </el-tag>
            </a>
          </template>
        </el-table-column>

        <!-- <el-table-column
          label="Auto-transmit"
          width="150"
        >
          <template slot-scope="scope">
            <el-switch
              v-if="!!scope.row.isValid && !isTransmitted(scope.row)"
              v-loading.body="autoTransmitLoading[scope.row.id]"
              @change="toggleAutoTransmit(scope.row)"
              v-model="autoTransmit[scope.row.id]" />
          </template>
        </el-table-column> -->

        <el-table-column
          label="Transmit"
          width="100"
          align="center"
        >
          <template slot-scope="scope">
              <el-tooltip content="Upload Transmittal" placement="top-start" transition="el-zoom-in-bottom">
                <div v-loading.body="!!transmitLoading[scope.row.id]">
                  <el-button
                    v-if="!!scope.row.isValid && !isMapped(scope.row)"
                    type="text"
                    @click="transmit(scope.row)">
                    <font-awesome-icon :icon="['far', 'cloud-upload-alt']" size="lg" transform="shrink-2" />
                  </el-button>
                </div>
              </el-tooltip>
          </template>
        </el-table-column>

        <el-table-column
          fixed="right"
          label="Actions"
          width="120"
        >
          <template slot-scope="scope">
            <template v-if="status(scope.row) !== 'success'">
              <el-tooltip content="Edit this transmittal" placement="top-start" transition="el-zoom-in-bottom">
                <el-button type="text" @click="edit(scope.row)">
                  <font-awesome-icon :icon="['far', 'pencil']" size="lg" transform="shrink-2" />
                </el-button>
              </el-tooltip>
            </template>

            <template v-if="status(scope.row) !== 'success'">
              <el-popover
                placement="top"
                width="220"
                v-model="popoversDelete[scope.row.id]"
              >
                <p>Are you sure you want to delete this transmittal?</p>
                <div style="text-align: right; margin: 0">
                  <el-button size="small" type="text" @click="popoversDelete[scope.row.id] = false">cancel</el-button>
                  <el-button type="danger" size="small" @click="deleteTransmittal(scope.row); popoversDelete[scope.row.id] = false">delete</el-button>
                </div>

                <el-button
                  type="text"
                  class="m--margin-left-5"
                  slot="reference"
                  @click="popoversDelete[scope.row.id] = true"
                  :disabled="!!popoversDelete[scope.row.id]"
                >
                  <font-awesome-icon :icon="['far', 'trash']" size="lg" transform="shrink-2" />
                </el-button>
              </el-popover>
            </template>

            <template>
              <el-tooltip content="Open Category Report" placement="top-start" transition="el-zoom-in-bottom">
                <el-button type="text" @click="openTransmittalReport(scope.row)">
                  <font-awesome-icon :icon="['far', 'print']" size="lg" transform="shrink-2" />
                </el-button>
              </el-tooltip>
            </template>

          </template>
        </el-table-column>
      </el-table>

      <pagination :bus="paginationBus" />
    </div>
  </layout>
</template>

<script>
  import Vue from 'vue';
  import _get from 'lodash/get';
  import {
    Table,
    TableColumn,
    Dialog,
    Select,
    Option,
    Input,
    DatePicker,
    Tooltip,
    Tag,
    Button,
    Switch,
    Row,
    Col,
    Popover,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import TRANSMITTALS_QUERY from '@/modules/claims/graphql/queries/TransmittalsQuery.gql';
  import TOGGLE_AUTO_TRANSMIT_MUTATION from '@/modules/claims/graphql/mutations/ToggleAutoTransmitMutation.gql';
  import DELETE_TRANSMITTAL_MUTATION from '@/modules/claims/graphql/mutations/DeleteTransmittalMutation.gql';
  import UPLOAD_TRANSMITTAL_MUTATION from '@/modules/claims/graphql/mutations/UploadTransmittalMutation.gql';
  import UsesLayout from '@/modules/core/mixins/UsesLayout';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';
  import Pagination from '@/modules/core/components/Pagination';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import MessageAlert from '@/modules/core/mixins/MessageAlert';
  import { url } from '@/helpers/url';
  import ViewClaimsXML from '../components/ViewClaimsXML';
  import TransmittalDialog from '../components/TransmittalForm/Dialog';
  import TransmittalReportDialog from '../components/TransmittalReport/Dialog';
  import ReportDialog from '../components/ReportForm/Dialog';

  export default {
    name: 'transmittal-page',

    mixins: [
      UsesLayout,
      UsesLookups,
      SetsPageMeta,
      TransformsPersonData,
      MessageAlert
    ],

    components: {
      FontAwesomeIcon,
      'el-dialog': Dialog,
      'el-table': Table,
      'el-table-column': TableColumn,
      'el-input': Input,
      'el-date-picker': DatePicker,
      'el-tooltip': Tooltip,
      'el-button': Button,
      'el-tag': Tag,
      'el-row': Row,
      'el-col': Col,
      'el-select': Select,
      'el-switch': Switch,
      'el-option': Option,
      'el-popover': Popover,
      pagination: Pagination,
      'transmittal-dialog': TransmittalDialog,
      'transmittal-report-dialog': TransmittalReportDialog,
      'report-dialog': ReportDialog,
      'view-xml': ViewClaimsXML,
    },

    data() {
      return {
        bus: new Vue(),
        paginationBus: new Vue(),

        loading: 0,
        formSubmitting: false,

        search: '',
        currentSearch: '',
        currentPage: 1,
        currentPageIndex: 1,

        filters: {
          status: '',
          dateRange: [],
        },

        report: {
          visible: false,
          data: {
            month: '',
            year: '',
            monthRange: [],
          },
        },

        transmittalReport: {
          visible: false,
          data: {
            transmittalNo: '',
          }
        },

        transmittalForm: {
          visible: false,
          data: {
            transmittalNo: '',
            notes: '',
            claims: [],
          },
        },

        viewClaimsXMLOptions: {
          xmlLink: '',
          xml: '',
          visible: false,
        },

        skipQuery: false,

        transmittals: {
          transmittals: [],
          pageInfo: {
            currentPage: 1,
            lastPage: 1,
            total: 0,
            pageSize: null,
            hasMorePages: false,
          },
        },

        autoTransmitLoading: {},
        autoTransmit: {},
        transmitLoading: {},
        popoversDelete: {},
      };
    },

    mounted() {
      this.bus.$on('form.submitting', this.submitting);
      this.bus.$on('form.submitted', this.submitted);
      this.bus.$on('form.failed', this.failed);

      this.bus.$on('dialog.close', this.closeDialog);

      this.paginationBus.$on('pagination.page', this.page);
    },

    apollo: {
      transmittals: {
        query: TRANSMITTALS_QUERY,
        variables() {
          return {
            dateRange: this.dateRangeFilter,
            name: this.currentSearch,
            transmittalNumber: this.currentSearch,
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

        pollInterval: 60000,

        fetchPolicy: 'cache-and-network',
      },
    },
    computed: {
      dateRangeFilter() {
        // @todo Format to Y-m-d later.
        return this.filters.dateRange;
      },
    },

    methods: {
      _get(object, path, defaultValue = null) {
        return _get(object, path, defaultValue);
      },

      openTransmittalReport(data = null) {
        if (data) {
          this.$set(this.transmittalReport, 'data', JSON.parse(JSON.stringify(data)));
        } else {
          this.$set(this.transmittalReport, 'data', {
            transmittalNo: '',
          });
        }
        this.$set(this.transmittalReport, 'visible', true);
      },

      openReport(data = null) {
        if (data) {
          this.$set(this.report, 'data', JSON.parse(JSON.stringify(data)));
        } else {
          this.$set(this.report, 'data', {
            month: '',
            year: '',
            monthRange: '',
          });
        }
        this.$set(this.report, 'visible', true);
      },

      viewClaimsXML(claim) {
        this.viewClaimsXMLOptions.xmlLink = claim.xmlLink;
        this.viewClaimsXMLOptions.xml = claim.xml;
        this.viewClaimsXMLOptions.visible = true;
      },

      refreshList() {
        this.$apollo.queries.transmittals.refetch();
      },

      status(transmittal) {
        if (transmittal.receiptTicketNo) {
          return 'success';
        }

        if (transmittal.transmitErrors) {
          return 'error';
        }

        return 'pending';
      },

      isMapped(transmittal) {
        return transmittal.status === 'mapped';
      },

      deleteTransmittal(transmittal) {
        this.loading += 1;
        this.$apollo.mutate({
          mutation: DELETE_TRANSMITTAL_MUTATION,
          variables: {
            id: transmittal.id,
          },
          loadingKey: 'autoTransmitLoading',
          refetchQueries: [
            'Transmittals'
          ],
        }).then(({ data: { deleteTransmittal } }) => {
          const name = this.fullname(deleteTransmittal);
          this.$snotify.success(`Transmittal ${name} successfully deleted!`, 'Success!');
          this.message = `Transmittal ${name} successfully deleted!`;
          this.success = true;
        }).catch(() => {
          this.$snotify.error('Failed to delete item', 'Oops!');
          this.message = 'Failed to delete item';
          this.error = true;
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

      edit(data = null) {
        if (data) {
          this.$set(this.transmittalForm, 'data', JSON.parse(JSON.stringify(data)));
        } else {
          this.$set(this.transmittalForm, 'data', {
            transmittalNo: '',
            notes: '',
            claims: [],
          });
        }
        this.$set(this.transmittalForm, 'visible', true);
      },

      processResult({ loading, data }) {
        if (!loading) {
          const pagination = data.transmittals.pageInfo;
          this.currentPageIndex = ((pagination.currentPage - 1) * pagination.pageSize) + 1;
          this.paginationBus.$emit('pagination.set', pagination);

          data.transmittals.transmittals.forEach((transmittal) => {
            this.$set(this.autoTransmitLoading, transmittal.id, false);
            this.$set(this.autoTransmit, transmittal.id, transmittal.autoTransmit);
            this.$set(this.popoversDelete, transmittal.id, false);
          });
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
        this.currentSearch = query.search;
        this.currentPage = 1;
      },

      toggleAutoTransmit(transmittal) {
        /* eslint-disable no-param-reassign */
        this.autoTransmitLoading[transmittal.id] = true;
        this.$apollo.mutate({
          mutation: TOGGLE_AUTO_TRANSMIT_MUTATION,
          variables: {
            id: transmittal.id,
            toggle: !transmittal.autoTransmit,
          },
          refetchQueries: [
            'Transmittals',
          ],
        }).then(({ data: { toggleAutoTransmit } }) => {
          if (toggleAutoTransmit) {
            // transmittal.autoTransmit = toggleAutoTransmit.autoTransmit;
            if (toggleAutoTransmit.autoTransmit) {
              this.$snotify.success('This transmittal will now be uploaded automatically', 'Success!');
              this.message = 'This transmittal will now be uploaded automatically';
              this.success = true;
            } else {
              this.$snotify.success('Auto-transmit is now disabled for this transmittal', 'Success!');
              this.message = 'Auto-transmit is now disabled for this transmittal';
              this.success = true;
            }
          }
        }).catch(({ graphQLErrors }) => {
          const message = graphQLErrors.map(a => a.message).join(' ');
          this.$snotify.error(`Auto-transmit update failed - ${message}`, 'Oops!');
          this.message = `Auto-transmit update failed - ${message}`;
          this.error = true;
          this.refreshList();
        }).then(() => {
          this.autoTransmitLoading[transmittal.id] = false;
        });
      },

      transmit(transmittal) {
        this.$set(this.transmitLoading, transmittal.id, true);
        this.$apollo.mutate({
          mutation: UPLOAD_TRANSMITTAL_MUTATION,
          variables: {
            id: transmittal.id,
          },
          refetchQueries: [
            'Transmittals',
          ],
        }).then(({ data: { uploadTransmittal } }) => {
          if (uploadTransmittal.receiptTicketNo || false) {
            this.$snotify.success('Transmit is now disabled for this transmittal', 'Success!');
            this.message = 'Transmit is now disabled for this transmittal';
            this.success = true;
          } else {
            this.$snotify.error(`Transmittal upload failed - ${uploadTransmittal.transmitErrors}`, 'Oops!');
            this.message = `Transmittal upload failed - ${uploadTransmittal.transmitErrors}`;
            this.error = true;
          }
          this.refreshList();
        }).catch(({ graphQLErrors }) => {
          const message = graphQLErrors.map(a => a.message).join(' ');
          this.$snotify.error(`Transmittal upload failed - ${message}`, 'Oops!');
          this.message = `Transmittal upload failed - ${message}`;
          this.error = true;
          this.refreshList();
        }).then(() => {
          this.$set(this.transmitLoading, transmittal.id, false);
        });
      },

      confirmDelete(id) {
        this.$snotify.confirm('Remove this transmittal from our records?', 'Delete?', {
          timeout: 5000,
          showProgressBar: true,
          closeOnClick: false,
          pauseOnHover: true,
          buttons: [
            {
              text: 'Yes',
              action: (toast) => {
                this.$snotify.remove(toast.id);
                this.deleteTransmittal(id);
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
<style scoped>
  .transmittal--notes {
    max-height: 5em;
    overflow-y: auto;
    display: inline-block;
  }
</style>
