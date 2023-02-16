<template>
  <div class="m--padding-top-20 m--padding-left-40 m--padding-right-40">
    <el-row :gutter="20" class="margin-buttom-20">
      <el-col :span="24" :xs="24">
        <el-alert
          v-show="error"
          title="Error Message "
          type="error"
          @close="MessageClose">
          {{ message }}
        </el-alert>
      </el-col>
    </el-row>
    <section>
      <el-form
        :disabled="!!loading"
        :model="formData"
        :rules="rules"
        inline-message
        status-icon
        ref="form"
        label-width="200px"
        label-position="top"
      >
        <el-row :gutter="20">
          <el-col :span="6">
            <el-form-item label="Transmittal Number" prop="transmittalNo">
              <el-input
                v-model="formData.transmittalNo"
                placeholder="Transmittal No"
                :maxlength="20"
                :disabled="!!formData.id"
              />
            </el-form-item>
          </el-col>

          <el-col :span="6">
            <el-form-item label="Notes" prop="notes">
              <el-input
                type="textarea"
                v-model="formData.notes"
                placeholder="Notes ..."
                :rows="3"
              />
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </section>

    <template v-if="formData.claims.length === 0">
      <empty-state style="margin: 2em auto">
        <span slot="label">No claims added yet</span>
        <span slot="description">You can only add claims that have been successfully validated here.</span>
        <br />
        <el-button
          plain
          class="btn btn-default btn-feature"
          @click="bus.$emit('select.claims')"
        >
          <font-awesome-icon :icon="['far', 'plus']" />
          Add Claims Now
        </el-button>
      </empty-state>
    </template>

    <section v-if="formData.claims.length !== 0">
      <el-row type="flex" justify="space-between">
        <el-col :span="9">
          <h1>
            Claims included in this transmittal
            <span style="text-transform: none; opacity: 0.6">
              ( {{ this.formData.claims.length }} total items )
            </span>
          </h1>
        </el-col>

        <el-col :span="12" align="right">
          <el-button
            plain
            @click="removeAll"
          >
            <font-awesome-icon :icon="['far', 'ban']" />
            Remove all
          </el-button>

          <el-button
            plain
            @click="bus.$emit('select.claims')"
          >
            <font-awesome-icon :icon="['far', 'plus']" />
            Add Claim
          </el-button>
        </el-col>
      </el-row>

      <div class="m-datatable m-datatable--default m-datatable--brand m-datatable--loaded">
        <el-row type="flex" justify="space-between">
          <el-col :span="14">
          </el-col>

          <el-col :span="4">
          </el-col>
        </el-row>
        <el-table
          class=""
          :data="formData.claims"
          v-loading="!!loading"
          stripe
          max-height="400"
          empty-text="No claims found ..."
          style="width: 100%"
        >
          <el-table-column
            type="index"
            width="50"
            align="center"
          />

          <el-table-column
            label="Claim #"
            align="center"
            min-width="150"
          >
            <template slot-scope="scope">
              <span v-if="!!scope.row.claimNumber">{{ scope.row.claimNumber }}</span>
              <em v-if="!scope.row.claimNumber">-</em>
            </template>
          </el-table-column>

          <el-table-column
            label="Patient Name"
            min-width="250"
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
                <span class="m--font-boldest text-muted">Not Indicated</span>
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
            min-width="180"
          >
            <template slot-scope="scope">
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
          </el-table-column>

          <el-table-column
            label="Status"
            width="150"
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
                @click="remove(scope.row)"
              >
                <font-awesome-icon :icon="['far', 'trash']" class="text-danger" />
              </el-button>
            </template>
          </el-table-column>

        </el-table>

      </div>
    </section>

    <br />
    <br />
  </div>
</template>

<script>
  import _findIndex from 'lodash/findIndex';
  import _get from 'lodash/get';
  import {
    Table,
    TableColumn,
    Row,
    Col,
    Form,
    FormItem,
    Tooltip,
    Input,
    Tag,
    DatePicker,
    Button,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import EmptyState from '@/modules/core/components/EmptyState';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import MessageAlert from '@/modules/core/mixins/MessageAlert';

  import CREATE_TRANSMITTAL_MUTATION from '@/modules/claims/graphql/mutations/CreateTransmittalMutation.gql';
  import UPDATE_TRANSMITTAL_MUTATION from '@/modules/claims/graphql/mutations/UpdateTransmittalMutation.gql';

  export default {
    name: 'transmittal-form',

    components: {
      'el-table': Table,
      'el-table-column': TableColumn,
      'el-tooltip': Tooltip,
      'el-button': Button,
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-form-item': FormItem,
      'el-date-picker': DatePicker,
      'el-input': Input,
      'el-tag': Tag,
      'font-awesome-icon': FontAwesomeIcon,
      'empty-state': EmptyState,
    },

    mixins: [UsesLookups, TransformsPersonData, MessageAlert],

    props: {
      formData: {
        required: true,
        type: Object,
      },

      bus: {
        type: Object,
        required: true,
      },
    },

    data() {
      return {
        loading: 0,

        rules: {
          transmittalNo: [
            { required: true, message: 'Please input the transmittal no', trigger: 'blur' },
          ],
        },
      };
    },

    methods: {
      _get(object, path, defaultValue = null) {
        return _get(object, path, defaultValue);
      },

      save() {
        const id = this.formData.id;
        const data = {
          transmittalNo: this.formData.transmittalNo,
          notes: this.formData.notes,
          claims: this.formData.claims.map(o => o.id),
        };

        let mutation;

        const variables = {
          ...data,
        };
        if (id) {
          mutation = UPDATE_TRANSMITTAL_MUTATION;
          variables.id = id;
        } else {
          mutation = CREATE_TRANSMITTAL_MUTATION;
        }

        this.bus.$emit('form.submitting');

        this.$apollo.mutate({
          mutation,
          variables,
          refetchQueries: [
            'Transmittals',
          ],
        }).then(({ data: { updateTransmittal, createTransmittal } }) => {
          if (createTransmittal) {
            this.formData.id = createTransmittal.id;
            this.$snotify.success('Transmittal successfully created!', 'Success!');
          } else if (updateTransmittal) {
            this.$snotify.success('Transmittal successfully updated!', 'Success!');
          }
          this.bus.$emit('form.submitted');
        }).catch(({ graphQLErrors }) => {
          const message = graphQLErrors.map(a => a.message).join(' ');
          this.$snotify.error(`Saving failed - ${message}`, 'Oops!');
          this.message = `Saving failed - ${message}`;
          this.bus.$emit('form.failed');
          this.messageNotify();
        });
      },

      remove(claim) {
        const index = _findIndex(this.formData.claims, value => claim.id === value.id);
        if (index >= 0) {
          this.formData.claims.splice(index, 1);
        }
      },

      removeAll() {
        this.$snotify.confirm('Clear the current selection?', 'Confirm clear', {
          timeout: 5000,
          showProgressBar: true,
          closeOnClick: false,
          pauseOnHover: true,
          buttons: [
            {
              text: 'Yes',
              action: (toast) => {
                this.$snotify.remove(toast.id);
                this.formData.claims.splice(0, this.formData.claims.length);
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

      submit() {
        this.$refs.form.validate((valid) => {
          if (valid) {
            this.save();
            return true;
          }
          this.message = ': Please fill up all required information';
          this.messageNotify();
          this.$snotify.error('Please fill up all required information');
          return false;
        });
      },

      submitting() {
        this.loading = true;
      },

      submitted() {
        this.loading = false;
      },

      failed() {
        this.loading = false;
      },

      reset() {
        this.$refs.form.resetFields();
      },
    },

    mounted() {
      if (this.bus) {
        this.bus.$on('form.submit', this.submit);
        this.bus.$on('form.submitting', this.submitting);
        this.bus.$on('form.submitted', this.submitted);
        this.bus.$on('form.failed', this.failed);
        this.bus.$on('form.reset', this.reset);
      }
    },
  };

</script>

<style scoped>
  section >>> h1 {
    font-size: 1rem;
    font-weight: 600;
    /* border-bottom: 1px solid #ededed; */
    border-bottom: 6px solid #f7f7f7;
    padding-bottom: 1rem;
    margin-bottom: 2rem;
    color: #909399;
    text-transform: uppercase;
  }
</style>