<template>
  <section>
    <h1>Claim information</h1>

    <el-form-item label="Select the patient" required>
      <el-col :span="20">
        <select-person
          :selected="formData.selectedPatient"
          v-model="formData.patient"
          style="width: 100%"
          @selectedChanged="patientChanged"
        />
      </el-col>
    </el-form-item>

    <el-form-item label="Tracking Number" v-if="formData.patient">
      <el-col :span="20">
        <select-eligibility
          :patient="formData.patient"
          v-model="claim.SC_ELIGIBILITYID"
          @selectedChanged="eligibilityChanged($event)"
          style="width: 100%"
        />
      </el-col>
      <el-col :span="24">
        <span class="hint">Assigned through online eligibility check (if available)</span>
      </el-col>
    </el-form-item>

    <el-form-item label="Patient type" required>
      <el-col :span="16">
        <el-select v-model="claim.pPatientType" placeholder="Select type">
          <el-option
            v-for="(item, key) in lookupTypes('patient.patientType')"
            :key="key"
            :label="item"
            :value="key">
          </el-option>
        </el-select>
      </el-col>
    </el-form-item>

    <!-- <el-form-item label="Claim Number" required>
      <el-col :span="12">
        <el-input v-model="claim.pClaimNumber" />
      </el-col>
    </el-form-item> -->

    <el-form-item label="Type of Claim" required>
      <el-col :span="16">
        <el-select v-model="claim.pPhilhealthClaimType" placeholder="Select type">
          <el-option
            v-for="(item, key) in lookupTypes('claim.claimType')"
            :key="key"
            :label="item"
            :value="key">
          </el-option>
        </el-select>
      </el-col>
    </el-form-item>

    <el-form-item label="Emergency?">
      <el-switch
        v-model="claim.pIsEmergency"
        :active-text="claim.pIsEmergency === 'Y' ? 'YES' : 'NO'"
        active-value="Y"
        inactive-value="N"
      />
    </el-form-item>

  </section>
</template>

<script>
  import _get from 'lodash/get';
  import _has from 'lodash/has';
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
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import PersonDataFragment from '@/modules/core/graphql/fragments/PersonDataFragment.gql';

  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  import SelectPerson from '@/modules/core/components/form/SelectPerson';
  import SelectEligibility from '@/modules/eligibility/components/SelectEligibility';

  export default {

    mixins: [BaseComponent, UsesLookups],

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
      'select-person': SelectPerson,
      'select-eligibility': SelectEligibility,
      'font-awesome-icon': FontAwesomeIcon,
    },

    props: {
      formData: {
        required: true,
        type: Object,
      },
      claim: {
        required: true,
        type: Object,
      },
    },

    computed: {
      patient() {
        return this.formData.patient;
      }
    },

    methods: {
      eligibilityChanged(value) {
        let trackingNumber = '';
        if (_has(value, 'trackingNumber')) {
          trackingNumber = value.trackingNumber || '';
        }
        this.setField('claim.pTrackingNumber', trackingNumber);
      },
      patientChanged(value) {
        // reset selected tracking number
        if (this.field('claim.SC_ELIGIBILITYID')) {
          this.setField('claim.SC_ELIGIBILITYID', null);
        }
      },
    },

    watch: {
      patient(value) {
        if (value) {
          const person = this.$apollo.provider.clients.defaultClient.readFragment({
            id: `Person:${value}`,
            fragment: PersonDataFragment,
            fragmentName: 'PersonData',
          });

          if (person) {
            this.claim.CF1 = {
              ...this.claim.CF1,
              ...{
                pMemberPIN: _get(person, 'member.principal.member.pin', ''),
                pMemberLastName: _get(person, 'member.principal.lastName', ''),
                pMemberFirstName: _get(person, 'member.principal.firstName', ''),
                pMemberMiddleName: _get(person, 'member.principal.middleName', ''),
                pMemberSuffix: _get(person, 'member.principal.suffix', ''),
                pMemberBirthDate: _get(person, 'member.principal.birthDate', ''),
                pMemberShipType: _get(person, 'member.membershipType', ''),
                pMailingAddress: _get(person, 'member.principal.mailingAddress', ''),
                pZipCode: _get(person, 'member.principal.zipCode', ''),
                pMemberSex: _get(person, 'member.principal.sex', ''),
                pLandlineNo: _get(person, 'member.principal.landLineNo', ''),
                pMobileNo: _get(person, 'member.principal.mobilieNo', ''),
                pEmailAddress: _get(person, 'member.principal.emailAddress', ''),
                pPatientIs: _get(person, 'member.relation', ''),
                pPatientPIN: _get(person, 'member.pin', '') || '00-00000000000-0',
                pPatientLastName: _get(person, 'lastName', ''),
                pPatientFirstName: _get(person, 'firstName', ''),
                pPatientMiddleName: _get(person, 'middleName', ''),
                pPatientSuffix: _get(person, 'suffix', ''),
                pPatientBirthDate: _get(person, 'birthDate', ''),
                pPatientSex: _get(person, 'sex', ''),
                pPEN: _get(person, 'member.pen', ''),
                pEmployerName: _get(person, 'member.employerName', ''),
              },
            };
          }
        }
      },
    },
  };

</script>