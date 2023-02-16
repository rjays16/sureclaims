<template>
  <div
    id="elgibility-form-container"
    class="check-eligibility-form m--padding-20 m--padding-top-30"
  >
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
    <el-row type="flex" justify="space-between">
      <el-col :span="14" :xs="24">
        <el-form
          :model="formData"
          :rules="rules"
          inline-message
          ref="form"
          label-width="250px"
          label-position="left"
          :disabled="loading > 0"
        >
          <el-form-item label="Select the Patient" prop="patient">
            <select-person
              v-model="formData.patient"
              style="width: 100%"
            />
          </el-form-item>

          <el-form-item prop="check" v-if="formData.patient">
            <div
              v-loading="loadingPatient > 0"
              class="check-list m--padding-20"
            >
              <el-row type="flex" justify="space-between">
                <el-col :span="1" align="top">

                  <font-awesome-icon
                    v-if="!loadingPatient && !patientErrors && !memberErrors"
                    :icon="['far', 'check-circle']"
                    class="text-success"
                    size="2x"
                    transform="shrink-2"
                  />

                  <font-awesome-icon
                    v-if="!loadingPatient && (patientErrors || memberErrors)"
                    :icon="['far', 'exclamation-circle']"
                    class="text-danger"
                    size="2x"
                    transform="shrink-2"
                  />

                </el-col>
                <el-col :span="21" align="top">

                  <div v-if="selectedPrincipal">

                    <div class="heading">{{ selectedPrincipal ? fullname(selectedPrincipal) : '-' }}</div>
                    <div class="subheading">
                      <el-tooltip content="Membership Type" placement="top">
                        <span>{{ selectedMember ? lookup('member.membershipType', selectedMember.membershipType) : 'Not set' }}</span>
                      </el-tooltip>
                      ,
                      <el-tooltip content="Age" placement="top">
                        <span>{{ selectedPrincipal.birthDate ? age(selectedPrincipal) : 'Not set' }}</span>
                      </el-tooltip>
                      ,
                      <el-tooltip content="Sex" placement="top">
                        <span>{{ selectedPrincipal.sex ? lookup('sex', selectedPrincipal.sex) : 'Not set' }}</span>
                      </el-tooltip>
                    </div>

                    <el-row class="detail m--margin-bottom-20">
                      <el-col class="detail-label" :span="8">PIN</el-col>
                      <el-col class="detail-value" :span="16">{{ selectedMember.principal.member.pin || '-' }}</el-col>

                      <el-col class="detail-label" :span="8">Birth Date</el-col>
                      <el-col class="detail-value" :span="16">{{ selectedPrincipal.birthDate || '-' }}</el-col>

                      <el-col class="detail-label" :span="8">Relation</el-col>
                      <el-col class="detail-value" :span="16">
                        {{ (selectedMember.relation && lookup('dependent.relation', selectedMember.relation)) || '-' }}
                      </el-col>

                    </el-row>
                  </div>

                  <div class="check-list-errors" v-if="patientErrors || memberErrors">

                    <h5>Please correct the ff errors:</h5>

                    <ul>
                      <template v-if="patientErrors">
                        <template
                          v-for="(fieldErrors, field, index) in patientValidator.errors.collect()"
                        >
                          <li :key="index" v-for="error in uniq(fieldErrors)">
                            <a
                              href="#"
                              class=""
                            >
                              <span class="">
                                {{ error }}
                              </span>
                            </a>
                          </li>
                        </template>
                      </template>
                      <template v-if="memberErrors">
                        <template
                          v-for="(fieldErrors, field, index) in memberValidator.errors.collect()"
                        >
                          <li :key="index" v-for="error in uniq(fieldErrors)">
                            <a
                              href="#"
                              class=""
                            >
                              <span class="">
                                {{ error }}
                              </span>
                            </a>
                          </li>
                        </template>
                      </template>
                    </ul>
                  </div>

                </el-col>
              </el-row>
            </div>
            <el-input v-model="formData.check" v-show="false" />
          </el-form-item>

          <el-form-item label="">
            <el-switch
              v-model="formData.isFinal"
              prop="isFinal"
              :active-text="formData.isFinal ? 'This is the Final check' : 'This is an Initial check'"
            />
          </el-form-item>

          <el-form-item label="Admission Date" prop="admissionDate" required>
            <el-date-picker
              v-model="formData.admissionDate"
              format="MM-dd-yyyy"
              value-format="MM-dd-yyyy"
              start-placeholder="MM-DD-YYYY"
            />
          </el-form-item>

          <el-form-item label="Discharge Date" prop="dischargeDate" v-if="formData.isFinal">
            <el-date-picker
              v-model="formData.dischargeDate"
              format="MM-dd-yyyy"
              value-format="MM-dd-yyyy"
              start-placeholder="MM-DD-YYYY"
            />
          </el-form-item>

          <el-form-item label="Total Charges (Actual)" prop="actualAmount" v-if="formData.isFinal">
            <el-col :span="12">
              <currency-input
                v-model="formData.actualAmount"
              >
                <font-awesome-icon
                  slot="prefix"
                  style="width: 25px"
                  :icon="['far', 'ruble-sign']"
                />
              </currency-input>
            </el-col>
          </el-form-item>

          <el-form-item label="Total Amount Claimed" prop="claimAmount" v-if="formData.isFinal">
            <el-col :span="12">
              <currency-input
                v-model="formData.claimAmount"
              >
                <font-awesome-icon
                  slot="prefix"
                  style="width: 25px"
                  :icon="['far', 'ruble-sign']"
                />
              </currency-input>
            </el-col>
          </el-form-item>

          <el-form-item label="RVS" v-if="formData.isFinal">
            <el-row>
              <el-col :span="12">
                <select-rvs-code
                  v-model="formData.rvs"
                  @selectionChanged="(rvs) => { this.rvsProcedureName=rvs.description; }"
                  class="m--margin-bottom-10"
                />
                <span class="hint">Leave blank if no surgery is to be done</span>
              </el-col>              
            </el-row>
            <el-row v-if="rvsProcedureName">
              <el-col :span="24">
                <div class="card">
                  <span class="card-body mb-0 p-3 text-primary">{{rvsProcedureName}}</span>
                </div>
              </el-col>
            </el-row>
          </el-form-item>

        </el-form>
      </el-col>

      <el-col :span="7" align="top">
        <template v-if="result">
          <affix
            relative-element-selector="#elgibility-form-container"
            :offset="{ top: 40, bottom: 0}"
            style="width: 350px"
          >
            <check-eligibility-results :eligibility-result="result" />
          </affix>
        </template>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  // import _debounce from 'lodash/debounce';
  import _uniq from 'lodash/uniq';
  import {
    Row,
    Col,
    Form,
    FormItem,
    Input,
    DatePicker,
    Option,
    Select,
    Switch,
    Button,
    Tooltip,
  } from 'element-ui';
  import { Validator } from 'vee-validate';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import MemberFormDialog from '@/modules/members/components/MemberForm/Dialog';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';

  import PERSON_QUERY from '@/modules/core/graphql/queries/PersonQuery.gql';
  import CHECK_ELIGIBILITY_MUTATION from '@/modules/eligibility/graphql/mutations/CheckEligibilityMutation.gql';

  import CurrencyInput from '@/modules/core/components/form/CurrencyInput';
  import SelectPerson from '@/modules/core/components/form/SelectPerson';
  import SelectRvsCode from '@/modules/core/components/form/SelectRvsCode';
  import MessageAlert from '@/modules/core/mixins/MessageAlert';

  import CheckEligibilityResults from './Results';

  export default {

    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-date-picker': DatePicker,
      'el-form-item': FormItem,
      'el-input': Input,
      'el-option': Option,
      'el-select': Select,
      'el-switch': Switch,
      'el-button': Button,
      'el-tooltip': Tooltip,
      'font-awesome-icon': FontAwesomeIcon,
      'member-form-dialog': MemberFormDialog,
      'currency-input': CurrencyInput,
      'select-person': SelectPerson,
      'check-eligibility-results': CheckEligibilityResults,
      'select-rvs-code': SelectRvsCode,
    },

    mixins: [UsesLookups, TransformsPersonData, MessageAlert],

    props: {
      formData: {
        type: Object,
        default() {
          return {
            patient: '',
            rvs: '',
            check: null,
            admissionDate: '',
            dischargeDate: '',
            actualAmount: '0.00',
            claimAmount: '0.00',
            isFinal: false,
          };
        },
      },
      bus: {
        type: Object,
      },
    },

    data() {
      const validateSwitch = (rule, value, callback) => {
        if (value) {
          this.$refs.form.validateField('dischargeDate');
          this.$refs.form.validateField('actualAmount');
          this.$refs.form.validateField('claimAmount');
        }
        callback();
      };

      const validateFinalRule = (rule, value, callback) => {
        if (value === '' && this.formData.isFinal) {
          switch (rule.field) {
            case 'dischargeDate':
              callback(new Error('Discharge Date is required for final eligibility check'));
              break;
            case 'actualAmount':
              callback(new Error('Actual Amount is required for final eligibility check'));
              break;
            case 'claimAmount':
              callback(new Error('Claim Amount is required for final eligibility check'));
              break;
            default: {
              callback();
            }
          }
        } else {
          callback();
        }
      };

      return {
        rules: {
          patient: [
            { required: true, message: 'Please select patient', trigger: 'change' },
          ],
          check: [
            { required: true, message: 'Please complete all missing information', trigger: 'change' },
          ],
          isFinal: [
            { validator: validateSwitch, trigger: 'change' },
          ],
          admissionDate: [
            { required: true, message: 'Please select admission date', trigger: 'blur' },
          ],
          dischargeDate: [
            { validator: validateFinalRule, trigger: 'blur' },
          ],
          actualAmount: [
            { validator: validateFinalRule, trigger: 'blur' },
          ],
          claimAmount: [
            { validator: validateFinalRule, trigger: 'blur' },
          ],
        },

        loading: 0,
        loadingPatient: 0,
        patient: null,

        fetchPatient: {
          member: {
            principal: null,
          },
        },

        patientValidator: new Validator({
          lastName: 'required',
          firstName: 'required',
          birthDate: 'required',
          sex: 'required',
        }),

        memberValidator: new Validator({
          lastName: 'required',
          firstName: 'required',
          birthDate: 'required',
          sex: 'required',
          pin: 'required',
          membershipType: 'required',
          relation: 'required',
        }),

        patientErrors: false,
        memberErrors: false,

        result: null,
        submittingForm: false,

        rvsProcedureName: ''
      };
    },

    computed: {
      selectedPatient() {
        return this.formData.patient;
      },

      selectedMember() {
        return (this.fetchPatient && this.fetchPatient.member) || null;
      },

      selectedPrincipal() {
        return (
          this.fetchPatient &&
          this.fetchPatient.member &&
          this.fetchPatient.member.principal
        ) || null;
      },
    },

    apollo: {
      fetchPatient: {
        query: PERSON_QUERY,

        variables() {
          return {
            id: this.selectedPatient,
          };
        },

        update({ person }) {
          this.patientErrors = false;
          this.memberErrors = false;
          this.formData.check = '';

          if (person) {
            this.patientValidator.reset();
            this.patientValidator.validateAll(person).then(() => {
              if (!person.member) {
                this.patientValidator.errors.add('membr', 'Membership details not found');
              }
              if (!person.member.principal) {
                this.patientValidator.errors.add('principal', 'Principal member not set');
              }
              this.patientErrors = this.patientValidator.errors.any();
              this.formData.check = (!this.patientErrors && !this.memberErrors) ? 'OK' : '';
            });

            this.memberValidator.reset();
            if (person.member && person.member.principal) {
              this.memberValidator.validateAll({
                lastName: person.member.principal.lastName,
                firstName: person.member.principal.firstName,
                birthDate: person.member.principal.birthDate,
                sex: person.member.principal.sex,
                pin: person.member.principal.member.pin,
                membershipType: person.member.membershipType,
                relation: person.member.relation,
              }).then(() => {
                this.memberErrors = this.memberValidator.errors.any();
                this.formData.check = (!this.patientErrors && !this.memberErrors) ? 'OK' : '';
              });
            }
          }
          return person;
        },

        loadingKey: 'loadingPatient',

        error() {
          this.formData.patient = null;
          this.$snotify.error(
            'Error occurred while retrieving patient record. Please try again',
            'Server Error',
          );
        },

        skip() {
          return !this.selectedPatient;
        },
      },
    },

    methods: {
      uniq: _uniq,

      checkEligibility() {
        this.result = null;
        this.bus.$emit('form.submitting');
        const {
          admissionDate,
          dischargeDate,
          ...restData
        } = this.formData;

        const variables = {
          confinementDates: [admissionDate, dischargeDate],
          ...restData,
        };

        this.$apollo.mutate({
          mutation: CHECK_ELIGIBILITY_MUTATION,
          variables,
        }).then(({ data: { checkEligibility } }) => {
          this.result = checkEligibility;
          this.bus.$emit('form.submitted');
        }).catch((error) => {
          this.$snotify.error(`Saving failed - ${error.message}`, 'Oops!');
          this.message = `Saving failed - ${error.message}`;
          this.bus.$emit('form.failed');
          this.messageNotify();
        });
      },

      submit() {
        this.$refs.form.validate((valid) => {
          if (valid) {
            this.checkEligibility();
            return;
          }
          this.message = ': Please fill up all required information';
          this.messageNotify();
          this.$snotify.error('Please ensure that all your information is correct');
        });
      },

      submitting() {
        this.submittingForm = true;
      },

      submitted() {
        this.submittingForm = false;
      },

      failed() {
        this.submittingForm = false;
      },
    },

    mounted() {
      if (this.bus) {
        this.bus.$on('form.submit', this.submit);
        this.bus.$on('form.submitting', this.submitting);
        this.bus.$on('form.submitted', this.submitted);
        this.bus.$on('form.failed', this.failed);
      }
    },
  };
</script>

<style scoped>
  .margin-buttom-20 {
    margin-bottom: 20px;
  }

  .check-list {
    border: 1px solid #E6E8EE;
    border-radius: 4px;
    min-height: 140px;
  }

  .check-list-errors >>> h5 {
    font-size: 0.9rem;
    line-height: 20px;
    font-weight: 600;
    text-transform: uppercase;
    color: #9596a7;
  }

  .check-list >>> li {
    line-height: 1.1rem;
  }

  .check-list >>> .heading {
    display: block;
    font-weight: 500;
    font-size: 1.2rem;
    height: 28px;
    line-height: 28px;
    color: #666;
  }

  .check-list >>> .subheading {
    display: block;
    line-height: 20px;
    height: 20px;
    font-weight: 400;
  }

  .check-list >>> .detail {
    margin-top: 10px;
  }

  .check-list >>> .detail .detail-label {
    line-height: 20px;
    height: 20px;
    font-weight: 600;
    font-size: 0.9rem;
  }

  .check-list >>> .detail .detail-value {
    line-height: 20px;
    height: 20px;
  }
  
  .card .card-body {
    line-height: 1.5 em;
  }
</style>