<template>
  <div class="m--padding-top-40 m--padding-right-50">
    <!-- {{ formData }} -->
    <el-form
      :disabled="loading"
      :model="formData"
      :rules="rules"
      inline-message
      status-icon
      ref="form"
      label-width="200px"
      label-position="left"
    >
      <el-form-item label="Accreditation No" prop="pan">
        <el-col :span="20">
          <el-input v-model="formData.pan">
            <template slot="append">
              <el-tooltip :content="lastPANResult" :disabled="lastPANResult === null || lastPANResult === true">
                <el-button
                  class="btn btn-bold"
                  @click="getDoctorPAN"
                  :disabled="loadingPAN"
                >
                  <span v-if="loadingPAN">
                    <font-awesome-icon :icon="['far', 'spinner']" pulse />
                  </span>

                  <span v-if="!loadingPAN">
                    <font-awesome-icon class="text-danger" :icon="['far', 'exclamation-triangle']" v-if="lastPANResult !== null && lastPANResult !== true"/>
                    <font-awesome-icon class="text-success" :icon="['far', 'check-circle']" v-if="lastPANResult === true"/>
                    Get PAN
                  </span>
                </el-button>
              </el-tooltip>
            </template>
          </el-input>
        </el-col>
      </el-form-item>

      <el-form-item label="Tax Identification No" prop="tin">
        <el-col :span="14">
          <el-input v-model="formData.tin"/>
        </el-col>
      </el-form-item>

      <el-form-item label="Last Name" prop="lastName">
        <el-input v-model="formData.lastName"/>
      </el-form-item>

      <el-form-item label="First Name" prop="firstName">
        <el-input v-model="formData.firstName"/>
      </el-form-item>

      <el-form-item label="Middle Name" prop="middleName">
        <el-input v-model="formData.middleName"/>
      </el-form-item>

      <el-form-item label="Suffix" prop="suffix">
        <el-col :span="12">
          <el-input v-model="formData.suffix"/>
        </el-col>
      </el-form-item>

      <el-form-item label="Birth Date" prop="birthDate">
        <el-col :span="12">
          <el-date-picker
            type="date"
            editable
            placeholder="Pick a date"
            v-model="formData.birthDate"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          >
          </el-date-picker>
        </el-col>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
  import _pick from 'lodash/pick';
  import { Row, Col, Form, FormItem, Tooltip, Input, DatePicker, Button } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import CREATE_DOCTOR_MUTATION from '@/modules/doctors/graphql/mutations/CreateDoctorMutation.gql';
  import UPDATE_DOCTOR_MUTATION from '@/modules/doctors/graphql/mutations/UpdateDoctorMutation.gql';
  import DOCTOR_PAN_MUTATION from '@/modules/doctors/graphql/mutations/GetDoctorPANMutation.gql';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';

  import { formatError } from '@/helpers/graphql';

  export default {

    mixins: [TransformsPersonData],

    components: {
      FontAwesomeIcon,
      'el-tooltip': Tooltip,
      'el-button': Button,
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-form-item': FormItem,
      'el-date-picker': DatePicker,
      'el-input': Input,
    },

    props: {
      formData: {
        type: Object,
        default() {
          return {
            pan: '',
            tin: '',
            lastName: '',
            firstName: '',
            middleName: '',
            birthDate: '',
            suffix: '',
            sex: '',
          };
        },
      },
      bus: {
        type: Object,
      },
    },

    data() {
      return {
        loading: false,
        rules: {
          pan: [
            { required: true, message: 'Please input doctor PAN', trigger: 'blur' },
          ],
          lastName: [
            { required: true, message: 'Please input last name', trigger: 'blur' },
          ],
          firstName: [
            { required: true, message: 'Please input first name', trigger: 'blur' },
          ],
        },

        loadingPAN: false,
        lastPANResult: null,
      };
    },

    methods: {
      saveDoctor() {
        const id = this.formData.id;
        const data = _pick(this.formData, [
          'pan',
          'tin',
          'lastName',
          'firstName',
          'middleName',
          'suffix',
          'birthDate',
          'sex',
        ]);

        let mutation;

        const variables = {
          ...data,
        };
        if (id) {
          mutation = UPDATE_DOCTOR_MUTATION;
          variables.id = id;
        } else {
          mutation = CREATE_DOCTOR_MUTATION;
        }

        this.bus.$emit('form.submitting');

        this.$apollo.mutate({
          mutation,
          variables,
        }).then(({ data: { updateDoctor, createDoctor } }) => {
          if (createDoctor) {
            this.$snotify.success(`Doctor ${this.fullname(createDoctor)} successfully created!`, 'Success!');
          } else if (updateDoctor) {
            this.$snotify.success(`Doctor ${this.fullname(updateDoctor)} successfully updated!`, 'Success!');
          }
          this.bus.$emit('form.submitted');
        }).catch((error) => {
          this.$snotify.error(`Saving failed - ${error.message}`, 'Oops!');
          this.bus.$emit('form.failed');
        });
      },

      getDoctorPAN() {
        this.loadingPAN = true;
        this.$apollo.mutate({
          mutation: DOCTOR_PAN_MUTATION,
          variables: {
            tin: this.formData.tin,
            lastName: this.formData.lastName,
            firstName: this.formData.firstName,
            middleName: this.formData.middleName,
            suffix: this.formData.suffix,
            birthDate: this.formData.birthDate,
          },
          refetchQueries: [
            'Doctors',
          ],
        }).then(({ data: { getDoctorPAN } }) => {
          this.lastPANResult = true;
          this.formData.pan = getDoctorPAN;
        }).catch((error) => {
          this.lastPANResult = formatError(error);
          this.formData.pan = '';
        }).then(() => {
          this.loadingPAN = false;
        });
      },

      submit() {
        this.$refs.form.validate((valid) => {
          if (valid) {
            this.saveDoctor();
            return true;
          }
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
