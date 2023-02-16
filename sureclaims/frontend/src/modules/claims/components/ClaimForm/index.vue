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
            :form-data="formData"
            :bus="navigationBus"
            :current-active="navigation"
          />
        </div>
      </el-aside>
      <el-main>
        <el-row :gutter="20" class="margin-side-20">
          <el-col :span="24" :xs="24">
            <el-alert
              :v-model="type"
              :title="message_type"
              :type="type"
              @close="MessageClose">
              {{ message }}
            </el-alert>
           <!--  <el-alert
              v-show="success"
              title="Success Message: "
              type="success"
              @close="MessageClose">
              {{ message }}
            </el-alert> -->
          </el-col>
        </el-row>
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
                :model="claim"
                :rules="rules"
                inline-message
                status-icon
                ref="form"
                label-width="200px"
                label-position="left"
              >
                <claim-information :form-data="formData" :claim="claim" v-show="navigation === 'CLAIM'" />

                <claim-form-1 :form-data="formData" :claim="claim" v-show="navigation === 'CLAIM.CF1'" />

                <claim-form-2 :form-data="formData" :claim="claim" v-show="navigation === 'CLAIM.CF2'" />

                <diagnosis :form-data="formData" :claim="claim" v-show="navigation === 'CLAIM.CF2.DIAGNOSIS'" />

                <repetitive-procedure :form-data="formData" :claim="claim" v-show="navigation === 'CLAIM.CF2.SPECIAL.PROCEDURES'" />

                <packages :form-data="formData" :claim="claim" v-show="navigation === 'CLAIM.CF2.SPECIAL.PACKAGES'" />

                <professionals :form-data="formData" :claim="claim" v-show="navigation === 'CLAIM.CF2.PROFESSIONALS'" />

                <consumption :form-data="formData" :claim="claim" v-show="navigation === 'CLAIM.CF2.CONSUMPTION'" />

                <consent-apr :form-data="formData" :claim="claim" v-show="navigation === 'CLAIM.CF2.APR'" />

                <all-case-rate :form-data="formData" :claim="claim" v-show="navigation === 'CLAIM.ALLCASERATE'" />

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
  import ClaimInformation from './ClaimInformation';
  import ClaimForm1 from './Form1';
  import ClaimForm2 from './Form2';
  import Diagnosis from './Diagnosis';
  import RepetitiveProcedure from './RepetitiveProcedure';
  import Packages from './Packages';
  import Professionals from './Professionals';
  import Consumption from './Consumption';
  import ConsentApr from './ConsentApr';
  import AllCaseRate from './AllCaseRate';

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
      'claim-information': ClaimInformation,
      'claim-form-1': ClaimForm1,
      'claim-form-2': ClaimForm2,
      'diagnosis': Diagnosis,
      'repetitive-procedure': RepetitiveProcedure,
      'packages': Packages,
      'professionals': Professionals,
      'consumption': Consumption,
      'consent-apr': ConsentApr,
      'all-case-rate': AllCaseRate,
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

      bus: {
        type: Object,
      },
    },

    data() {
      return {
        type: '',
        message_type: '',
        navigation: 'CLAIM',
        navigationBus: new Vue(),
        loading: false,
        rules: {
        },
      };
    },

    methods: {
      checkDuplicateIcdCode() {
        const icdCodes = [];
        const codes = [];
        this.claim.CF2.DIAGNOSIS.DISCHARGE.map(value => icdCodes.push(value.ICDCODE));
        icdCodes.map(b => b.map(c => codes.push(c.pICDCode)));
        // icdCodes.filter
        // const me = codes.sort().filter((item, pos, ary) => !pos || item !== ary[pos - 1]);
        const duplicate = codes.filter((item, index) => codes.indexOf(item) !== index);
        if (duplicate.length) {
          this.$snotify.warning(`There is a duplicate in ICDCODES - ${JSON.stringify(duplicate)}`, 'Warning!');
          this.message = `There is a duplicate in ICDCODES - ${JSON.stringify(duplicate)}`;
          this.type = 'warning';
          this.message_type = 'Warning Message';
          return false;
        }
        return true;
      },

      saveClaim() {
        const id = this.formData.id;
        const data = {
          patient: this.formData.patient,
          data: JSON.stringify(this.claim),
        };

        let mutation;

        const variables = {
          ...data,
        };
        if (id) {
          mutation = UPDATE_CLAIM_MUTATION;
          variables.id = id;
        } else {
          mutation = CREATE_CLAIM_MUTATION;
        }

        this.bus.$emit('form.submitting');

        this.$apollo.mutate({
          mutation,
          variables,
          refetchQueries: [
            'Claims',
          ],
        }).then(({ data: { updateClaim, createClaim } }) => {
          if (createClaim) {
            this.formData.id = createClaim.id;
            this.$set(this.formData, 'validationErrors', JSON.parse(createClaim.validationErrors));
            this.$set(this.formData, 'xml', createClaim.xml);
            this.$set(this.formData, 'xmlLink', createClaim.xmlLink);
            this.$snotify.success('Claim successfully created!', 'Success!');
            this.message = 'Claim successfully created!';
            this.type = 'success';
            this.message_type = 'Success Message';
          } else if (updateClaim) {
            this.$set(this.formData, 'validationErrors', JSON.parse(updateClaim.validationErrors));
            this.$set(this.formData, 'xml', updateClaim.xml);
            this.$snotify.success('Claim successfully updated!', 'Success!');
            this.message = 'Claim successfully updated!';
            this.type = 'success';
            this.message_type = 'Success Message';

            // this.messageNotify();
          }
          this.bus.$emit('form.submitted');
        }).catch((error) => {
          this.$snotify.error(`Saving failed - ${error.message}`, 'Oops!');
          this.message = `Saving failed - ${error.message}`;
          this.bus.$emit('form.failed');
          this.type = 'error';
          this.message_type = 'Error Message';
        });
      },

      submit() {
        this.$refs.form.validate((valid) => {
          // Check first if the ICDCODES has a duplicate
          if (this.checkDuplicateIcdCode()) {
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
          }
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
