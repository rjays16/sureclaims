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
<!--            <el-input v-model="formData.check" v-show="false" />-->
          </el-form-item>

        </el-form>
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
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';

  import PERSON_QUERY from '@/modules/core/graphql/queries/PersonQuery.gql';

  import SelectPerson from '@/modules/core/components/form/SelectPerson';
  import MessageAlert from '@/modules/core/mixins/MessageAlert';


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
      'select-person': SelectPerson,
    },

    mixins: [UsesLookups, TransformsPersonData, MessageAlert],

    props: {
      formData: {
        type: Object,
        default() {
          return {
            patient: '',
            check: null,
          };
        },
      },

      CF4: {
        type: Object
      },

      bus: {
        type: Object,
      },
    },

    data() {
      return {
        rules: {
          patient: [
            { required: true, message: 'Please select patient', trigger: 'change' },
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
            this.bus.$emit('personData', person);
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
              this.$emit('person', person);
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
