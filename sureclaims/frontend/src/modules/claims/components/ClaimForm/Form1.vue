<template>
  <section>
    <h1>Claim Form 1</h1>

    <el-row type="flex" justify="space-between">
      <el-col :span="11">
        <h2>Member Data</h2>
        <data-view :view-data="member" class="m--margin-bottom-40"/>
      </el-col>

      <el-col :span="11">
        <h2>Patient Data</h2>
        <data-view :view-data="patient" class="m--margin-bottom-40"/>
      </el-col>
    </el-row>

    <el-row>
      <el-col :span="11">
        <h2>Additional Data</h2>
        <data-view :view-data="memberAdditional" class="m--margin-bottom-40"/>
      </el-col>
    </el-row>

  </section>
</template>

<script>
  /* eslint-disable quote-props */
  import {
    Row,
    Col,
    FormItem,
  } from 'element-ui';
  import DataView from '@/modules/core/components/DataView';
  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  export default {

    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form-item': FormItem,
      'data-view': DataView,
    },

    mixins: [BaseComponent, UsesLookups],

    props: {
      formData: {
        type: Object,
        required: true,
      },

      claim: {
        type: Object,
        required: true,
      },
    },

    computed: {
      member() {
        return {
          'PIN': this.field('claim.CF1.pMemberPIN'),
          'Last Name': this.field('claim.CF1.pMemberLastName'),
          'First Name': this.field('claim.CF1.pMemberFirstName'),
          'Middle Name': this.field('claim.CF1.pMemberMiddleName'),
          'Suffix': this.field('claim.CF1.pMemberSuffix'),
          'Birth Date': this.field('claim.CF1.pMemberBirthDate'),
          'Membership Type': this.lookup('member.membershipType', this.field('claim.CF1.pMemberShipType')),
          'Sex': this.lookup('sex', this.field('claim.CF1.pMemberSex')),
        };
      },

      memberAdditional() {
        return {
          'Mailing Address': this.field('claim.CF1.pMailingAddress'),
          'Zip Code': this.field('claim.CF1.pZipCode'),
          'Landline No': this.field('claim.CF1.pLandlineNo'),
          'Mobile No': this.field('claim.CF1.pMobileNo'),
          'Email Address': this.field('claim.CF1.pEmailAddress'),
        };
      },

      patient() {
        return {
          'Patient Is': this.lookup('dependent.relation', this.field('claim.CF1.pPatientIs')),
          'Patient PIN': this.field('claim.CF1.pPatientPIN'),
          'Patient Last Name': this.field('claim.CF1.pPatientLastName'),
          'Patient First Name': this.field('claim.CF1.pPatientFirstName'),
          'Patient Middle Name': this.field('claim.CF1.pPatientMiddleName'),
          'Patient Suffix': this.field('claim.CF1.pPatientSuffix'),
          'Patient Birth Date': this.field('claim.CF1.pPatientBirthDate'),
        };
      },
    },

  };

</script>

<style scoped>

  .claim-form--data {
    margin-bottom: 1rem;
    line-height: 20px;
  }

  .claim-form--data >>> .el-form-item__label {
    line-height: 20px;
  }

  .claim-form--data >>> .el-form-item__content {
    line-height: 20px;
  }

</style>