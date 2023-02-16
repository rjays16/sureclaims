<template>
  <div
    class="verify-pin-form m--padding-20 m--padding-top-30"
  >
    <!-- Dialog -->
    <member-form-dialog
      :visible.sync="memberDialog"
      :form-data="newMemberData"
      :bus="bus"
    />
    <el-row :gutter="20">
        <el-col :span="24">
          <el-alert
            v-show="error"
            title="Error Message "
            type="error"
            @close="MessageClose">
            {{ message }}
          </el-alert>
        </el-col>
    </el-row>
    <el-form
      :model="formData"
      :rules="rules"
      inline-message
      status-icon
      ref="form"
      label-position="top"
      :disabled="loading"
    >
      <el-row :gutter="40">
        <el-col :span="16" :xs="24">

          <el-row :gutter="16">
            <el-col :span="8">
              <el-form-item label="Last Name" prop="lastName">
                <el-input v-model="formData.lastName" />
              </el-form-item>
            </el-col>

            <el-col :span="8">
              <el-form-item label="First Name" prop="firstName">
                <el-input v-model="formData.firstName" />
              </el-form-item>
            </el-col>

            <el-col :span="8">
              <el-form-item label="Middle Name" prop="middleName">
                <el-input v-model="formData.middleName" />
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="16">
            <el-col :span="8">
              <el-form-item label="Suffix" prop="suffix">
                <el-input v-model="formData.suffix" />
              </el-form-item>
            </el-col>

            <el-col :span="8">
              <el-form-item label="Birth Date" prop="birthDate">
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
              </el-form-item>
            </el-col>
          </el-row>

          <hr style="margin: 2rem 0" />

          <div class="m--align-right">
            <el-button
              type="text"
              class="btn btn-feature"
              style="width: 140px"
              @click="back"
            >
              Back
            </el-button>

            <el-button
              v-show="!formData.member.pin"
              type="primary"
              @click="submitForm"
              class="btn btn-feature"
              style="width: 140px"
              :disabled="loading"
            >
              <span v-if="!loading">Verify</span>
              <span v-if="loading">
                Verifying
                <font-awesome-icon :icon="['far', 'spinner']" spin />
              </span>
            </el-button>

            <el-button
              type="button"
              v-if="formData.member.pin"
              class="btn btn-feature btn-secondary "
              style="width: 140px"
              :disabled="loading"
              @click="resetForm"
            >
              <span>Reset</span>
            </el-button>

          </div>
        </el-col>

        <el-col :span="8">
          <el-collapse-transition>
            <template
              v-if="verifyError"
            >
              <aside class="form-description">
                <div
                  class="alert alert-danger m-alert m-alert--outline"
                  role="alert"
                >
                  <strong>
                    Error!
                  </strong>
                  {{ verifyError }}
                </div>
              </aside>
            </template>
          </el-collapse-transition>

          <el-collapse-transition>
            <template
              v-if="!verifyError && formData.member.pin"
            >
              <aside class="form-description">
                <strong>
                  Great!
                </strong>
                <p>
                The PIN registered for this member is:
                </p>

                <h3 class="m--margin-top-10 m--font-success">
                  {{ formData.member.pin }}
                </h3>

                <br/>

                <el-button
                  type="button"
                  class="btn btn-sm btn-secondary m-btn--wide"
                  v-if="formData.member.pin"
                  style="padding: 10px;"
                  @click="createMember"
                  :disabled="loading"
                >
                  <font-awesome-icon :icon="['far', 'plus']" />
                  <span>Add as a New Record</span>
                </el-button>

              </aside>
            </template>
          </el-collapse-transition>
        </el-col>
      </el-row>

    </el-form>
  </div>
</template>

<script>
  import Vue from 'vue';
  import _defaultsDeep from 'lodash/defaultsDeep';
  import {
    Row,
    Col,
    Form,
    FormItem,
    Input,
    DatePicker,
    Option,
    Select,
    Button,
  } from 'element-ui';
  import CollapseTransition from 'element-ui/lib/transitions/collapse-transition';
  import { FontAwesomeIcon, FontAwesomeLayers } from '@fortawesome/vue-fontawesome';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import VERIFY_PIN_MUTATION from '@/modules/eligibility/graphql/mutations/VerifyPinMutation.gql';
  import MemberFormDialog from '@/modules/members/components/MemberForm/Dialog';
  import { formatError } from '@/helpers/graphql';
  import MessageAlert from '@/modules/core/mixins/MessageAlert';

  export default {
    mixins: [UsesLookups, MessageAlert],

    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-date-picker': DatePicker,
      'el-form-item': FormItem,
      'el-input': Input,
      'el-option': Option,
      'el-select': Select,
      'el-button': Button,
      'el-collapse-transition': CollapseTransition,
      FontAwesomeLayers,
      FontAwesomeIcon,
      MemberFormDialog,
    },

    props: {
      formData: {
        type: Object,
        default() {
          return {
            lastName: '', // 'QUIÑONES ',
            firstName: '', // 'ALVIN JED',
            middleName: '', // 'MONSANTO',
            birthDate: '', // '1983-07-18',
            suffix: '',
            member: { pin: '' },
          };
        },
      },
    },

    data() {
      return {
        bus: new Vue(),
        memberDialog: false,
        newMemberData: {},
        memberFormSubmitting: false,

        loading: false,
        verifyError: null,
        rules: {
          lastName: [
            { required: true, message: 'Please input last name', trigger: 'blur' },
          ],
          firstName: [
            { required: true, message: 'Please input first name', trigger: 'blur' },
          ],
          sex: [
            { required: true, message: 'Please input sex', trigger: 'blur' },
          ],
          birthDate: [
            { required: true, message: 'Please input birth date', trigger: 'blur' },
          ],
        },
      };
    },

    methods: {
      createMember() {
        const normalized = JSON.parse(
          JSON.stringify(
            _defaultsDeep(this.formData, {
              lastName: '',
              firstName: '',
              middleName: '',
              birthDate: '',
              suffix: '',
              sex: '',
              member: {
                relation: 'M',
                principal: {},
              },
            }),
          ),
        );
        this.newMemberData = normalized;
        this.memberDialog = true;
      },

      submitForm() {
        this.$refs.form.validate((valid) => {
          if (valid) {
            this.verifyPin();
            return true;
          }
          // this.$snotify.error('Please check the details you entered', 'Invalid input');
          this.$snotify.error('Please check the details you entered', 'Invalid input');
          this.message = ': Please fill up all required information';
          this.messageNotify();
          return false;
        });
      },

      resetForm() {
        this.$refs.form.resetFields();
        this.formData.member.pin = '';
        this.verifyError = null;
      },

      verifyPin() {
        this.verifyError = null;
        this.formData.member.pin = null;
        this.loading = true;

        this.$apollo.mutate({
          mutation: VERIFY_PIN_MUTATION,
          variables: this.formData,
        }).then(({ data: { verifyPin } }) => {
          if (verifyPin.match(/\d+/)) {
            this.formData.member.pin = verifyPin;
            this.verifyError = null;
          } else {
            this.formData.member.pin = null;
            this.verifyError = verifyPin;
          }
        }).catch((error) => {
          this.message = `Saving failed - ${error}`;
          this.messageNotify();
          this.verifyError = formatError(error);
        }).then(() => {
          this.loading = false;
        });
      },

      back() {
        this.$router.back();
      },

      submitting() {
        this.memberFormSubmitting = true;
      },

      submitted() {
        this.memberFormSubmitting = false;
        this.memberDialog = false;
      },

      failed() {
        this.memberFormSubmitting = false;
      },
    },

    mounted() {
      this.bus.$on('form.submitting', this.submitting);
      this.bus.$on('form.submitted', this.submitted);
      this.bus.$on('form.failed', this.failed);
    },
  };
</script>

<style scoped>
  .verify-pin-form {
  }

  .verify-pin-form >>> .el-form-item__label {
    padding: 0;
    margin: 0;
  }

  aside.form-description {
    padding: 40px;
    border-left: 2px solid #ededed;
  }
</style>