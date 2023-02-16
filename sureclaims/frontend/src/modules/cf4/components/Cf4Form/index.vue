<template>
  <div id="claim-form--container">
    <el-container>
      <el-aside
        width="250px"
        style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); min-height: 90vh"
        v-bar="{ preventParentScroll: false }"
      >
        <div>
          <claim-menu
            :formData="formData"
            :bus="navigationBus"
            :current-active="navigation"
          />
        </div>
      </el-aside>
      <el-main>
        <div
          style="height: 90vh"
          v-bar="{
            preventParentScroll: false
          }"
        >
          <div>
            <div class="m--padding-top-10 m--padding-left-40 m--padding-right-40 m--padding-bottom-20">
              <el-form
                :disabled="loading"
                :model="CF4"
                :rules="rules"
                inline-message
                status-icon
                ref="form"
                label-width="200px"
                label-position="left"
              >
<!--                <patient-profile :form-data="formData" :CF4="cf4" v-show="navigation === 'CF4.PATIENTPROFILE'"/>-->

                <chief-complaint :formData="formData" :CF4="CF4" v-show="navigation === 'CF4.CHIEFCOMPLAINT'"/>

                <present-illness :formData="formData" :CF4="CF4" v-show="navigation === 'CF4.PRESENTILNESS'"/>

                <pertinent-past-medical :formData="formData" :CF4="CF4" v-show="navigation === 'CF4.PERTINENTPASTMEDICAL'"/>

                <pertinent-sign-symptoms :formData="formData" :CF4="CF4"
                                      v-show="navigation === 'CF4.PERTINENTSIGNSYMPTOMS'"/>

                <obgyne :formData="formData" :CF4="CF4" v-show="navigation === 'CF4.OBGYNE'"/>

                <physical-examination :formData="formData" :CF4="CF4" v-show="navigation === 'CF4.PHYSICALEXAMINATION'"/>

                <course-ward :formData="formData" :CF4="CF4" v-show="navigation === 'CF4.COURSEWARD'"/>

                <medicine :formData="formData" :CF4="CF4" v-show="navigation === 'CF4.MEDICINE'"/>
              </el-form>
            </div>
          </div>
        </div>
      </el-main>
    </el-container>
  </div>
</template>

<script>
  import Vue from 'vue';
  import {
    Container,
    Aside,
    Main,
    Row,
    Col,
    Form,
    FormItem,
    Tooltip,
    Input,
    DatePicker,
    Button,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import CREATE_CLAIM_MUTATION from '@/modules/claims/graphql/mutations/CreateClaimMutation.gql';
  import UPDATE_CLAIM_MUTATION from '@/modules/claims/graphql/mutations/UpdateClaimMutation.gql';

  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import TransformsClaimData from '@/modules/claims/mixins/TransformsClaimData';
  import MessageAlert from '@/modules/core/mixins/MessageAlert';

  import ClaimMenu from './Menu';
  import PatientProfile from './PatientProfile';
  import ChiefComplaint from './ChiefComplaint';
  import PresentIllness from './PresentIllness';
  import PertinentPastMedical from './PertinentPastMedical';
  import PertinentSignSymptoms from './PertinentSignSymptoms';
  import OBGyne from './OBGyne';
  import PhysicalExamination from './PhysicalExamination';
  import CourseWard from './CourseWard';
  import Medicine from './Medicine';

  /* eslint-disable quote-props */
  export default {

    mixins: [TransformsPersonData, TransformsClaimData, MessageAlert],

    components: {
      FontAwesomeIcon,
      'el-container': Container,
      'el-aside': Aside,
      'el-main': Main,
      'el-tooltip': Tooltip,
      'el-button': Button,
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-form-item': FormItem,
      'el-date-picker': DatePicker,
      'el-input': Input,

      'claim-menu': ClaimMenu,
      'patient-profile': PatientProfile,
      'chief-complaint': ChiefComplaint,
      'present-illness': PresentIllness,
      'pertinent-past-medical': PertinentPastMedical,
      'pertinent-sign-symptoms': PertinentSignSymptoms,
      'obgyne': OBGyne,
      'physical-examination': PhysicalExamination,
      'course-ward': CourseWard,
      'medicine': Medicine,
    },

    props: {
      formData: {
        required: true,
        type: Object,
      },

      CF4: {
        required: true,
        type: Object,
      },

      bus: {
        type: Object,
      },
    },

    data() {
      return {
        type: '',
        message_type: '',
        navigation: 'CF4',
        navigationBus: new Vue(),
        loading: false,
        rules: {},
      };
    },

    methods: {
      // saveClaim() {
      //   const id = this.formData.id;
      //   const data = {
      //     patient: this.formData.patient,
      //     data: JSON.stringify(this.claim),
      //   };
      //
      //   let mutation;
      //
      //   const variables = {
      //     ...data,
      //   };
      //   if (id) {
      //     mutation = UPDATE_CLAIM_MUTATION;
      //     variables.id = id;
      //   } else {
      //     mutation = CREATE_CLAIM_MUTATION;
      //   }
      //
      //   this.bus.$emit('form.submitting');
      //
      //   this.$apollo.mutate({
      //     mutation,
      //     variables,
      //     refetchQueries: [
      //       'Claims',
      //     ],
      //   }).then(({data: {updateClaim, createClaim}}) => {
      //     if (createClaim) {
      //       this.formData.id = createClaim.id;
      //       this.$set(this.formData, 'validationErrors', JSON.parse(createClaim.validationErrors));
      //       this.$set(this.formData, 'xml', createClaim.xml);
      //       this.$set(this.formData, 'xmlLink', createClaim.xmlLink);
      //       this.$snotify.success('Claim successfully created!', 'Success!');
      //       this.message = 'Claim successfully created!';
      //       this.type = 'success';
      //       this.message_type = 'Success Message';
      //     } else if (updateClaim) {
      //       this.$set(this.formData, 'validationErrors', JSON.parse(updateClaim.validationErrors));
      //       this.$set(this.formData, 'xml', updateClaim.xml);
      //       this.$snotify.success('Claim successfully updated!', 'Success!');
      //       this.message = 'Claim successfully updated!';
      //       this.type = 'success';
      //       this.message_type = 'Success Message';
      //
      //       // this.messageNotify();
      //     }
      //     this.bus.$emit('form.submitted');
      //   }).catch((error) => {
      //     this.$snotify.error(`Saving failed - ${error.message}`, 'Oops!');
      //     this.message = `Saving failed - ${error.message}`;
      //     this.bus.$emit('form.failed');
      //     this.type = 'error';
      //     this.message_type = 'Error Message';
      //   });
      // },

      submit() {
        this.$refs.form.validate((valid) => {
          if (valid) {
            this.cleanClaim();
            this.saveClaim();
            return true;
          }
          this.message = ': Please fill up all required information';
          this.type = 'error';
          this.message_type = 'Error Message';
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

      // Activated by menu
      activated(item) {
        this.navigation = item.index;
      },
    },

    mounted() {
      if (this.bus) {
        this.bus.$on('form.submit', this.submit);
        this.bus.$on('form.submitting', this.submitting);
        this.bus.$on('form.submitted', this.submitted);
        this.bus.$on('form.failed', this.failed);
        this.bus.$on('form.reset', this.reset);
        this.navigationBus.$on('navigation.activated', this.activated);
      }
    },
  };

</script>


<style scoped>
  .margin-side-20 {
    margin-left: 20px;
    margin-right: 20px;
  }

  #claim-form--container {
    border-top: 1px solid #f2f2f2;
  }

  section >>> h1 {
    font-size: 1.1rem;
    font-weight: 600;
    /* border-bottom: 1px solid #ededed; */
    border-bottom: 1px solid #f7f7f7;
    padding-bottom: 2rem;
    margin-bottom: 2rem;
    color: #909399;
    text-transform: uppercase;
  }

  section >>> h2 {
    color: #34bfa3;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin: 2rem 0;
  }

</style>
