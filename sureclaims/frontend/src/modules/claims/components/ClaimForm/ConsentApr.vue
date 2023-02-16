<template>
  <section>
    <h1>Consent to APR</h1>

    <el-form-item label="Consent Provided By">
      <el-col :span="12">
        <el-select v-model="claim.CF2.APR.SC_CONSENTEDBY" placeholder="Select provider" style="width: 100%;">
          <el-option
            v-for="(item, key) in lookupTypes('apr.consentProviderType')"
            :key="key"
            :label="item"
            :value="key"
          />
        </el-select>
      </el-col>
    </el-form-item>

    <template v-if="claim.CF2.APR.SC_CONSENTEDBY">
      <el-form-item label="Consent Is Indicated Using?">
        <el-switch
          v-model="claim.CF2.APR.SC_CONSENTEDUSING"
          :active-text="field('claim.CF2.APR.SC_CONSENTEDUSING') === 'SIGNATURE' ? 'SIGNATURE' : 'THUMBMARK'"
          active-value="SIGNATURE"
          inactive-value="THUMBMARK"
        />
      </el-form-item>

      <template v-if="isConsentedUsingSignature">
        <h2>Signature Details</h2>

        <el-form-item label="Date Signed">
          <el-col :span="12">
            <el-date-picker
              placeholder="Pick a date"
              v-model="pDateSigned"
              format="MM-dd-yyyy"
              value-format="MM-dd-yyyy"
              style="width: 100%;"
            />
          </el-col>
        </el-form-item>
      </template>
      
      <template v-if="isSignedByRepresentative">
        <h2>Representative Consent Details</h2>

        <el-form-item label="Relationship">
          <el-col :span="12">
            <el-select v-model="pRelCode" placeholder="Select relationship" style="width: 100%;">
              <el-option
                v-for="(item, key) in lookupTypes('apr.consentRelationType')"
                :key="key"
                :label="item"
                :value="key"
              />
            </el-select>
            <span class="hint">
              Relationship of the representative to the patient
            </span>
          </el-col>
        </el-form-item>

        <el-form-item label="Other Relationship, Specify" v-if="isRelationshipOthers">
          <el-col :span="12">
            <el-input
              type="textarea"
              v-model="pRelDesc"
              :rows="2"
              :maxlength="50"
            />
            <span class="hint">
              Required if "Other" is selected in Relationship field
            </span>
          </el-col>
        </el-form-item>

        <el-form-item label="Reason">
          <el-col :span="12">
            <el-select v-model="pReasonCode" placeholder="Select reason" style="width: 100%;">
              <el-option
                v-for="(item, key) in lookupTypes('apr.consentReasonType')"
                :key="key"
                :label="item"
                :value="key"
              />
            </el-select>
            <span class="hint">
              Reason for signing on behalf of the patient
            </span>
          </el-col>
        </el-form-item>

        <el-form-item label="Other Reason, Specify" v-if="isReasonOthers">
          <el-col :span="12">
            <el-input
              type="textarea"
              v-model="pReasonDesc"
              :rows="2"
              :maxlength="50"
            />
            <span class="hint">
              Required if "Other" is selected in Reason field
            </span>
          </el-col>
        </el-form-item>
      </template>
    </template>

  </section>
</template>

<script>

  import {
    Col,
    FormItem,
    Input,
    DatePicker,
    Option,
    Select,
    Switch
  } from 'element-ui';
  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  export default {

    components: {
      'el-col': Col,
      'el-date-picker': DatePicker,
      'el-form-item': FormItem,
      'el-input': Input,
      'el-option': Option,
      'el-select': Select,
      'el-switch': Switch
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

    data () {
      return {
        pDateSigned: null,
        pRelCode: null,
        pRelDesc: '',
        pReasonCode: null,
        pReasonDesc: ''
      };
    },
    mounted() {
      this.reset();
    },
    computed: {
      apr() {
        return this.field('claim.CF2.APR', {});
      },
      consentedBy() {
        return this.field('claim.CF2.APR.SC_CONSENTEDBY', 'P');
      },
      isConsentedUsingSignature () {
        return this.field('claim.CF2.APR.SC_CONSENTEDUSING', 'SIGNATURE') === 'SIGNATURE';
      },
      isSignedByRepresentative () {
        return (this.consentedBy === 'R') && this.isConsentedUsingSignature;
      },
      isRelationshipOthers() {
        return this.pRelCode === 'O';
      },
      isReasonOthers() {
        return this.pReasonCode === 'O';
      },
    },

    watch: {
      apr(value) {
        this.reset();
      },
      consentedBy(value) {
        this.setField('claim.CF2.APR.APRBYTHUMBMARK.pThumbmarkedBy', value);
      },
      pDateSigned(value) {
        if (this.isSignedByRepresentative) {
          this.setField('claim.CF2.APR.APRBYPATREPSIG.pDateSigned', value);
        } else {
          this.setField('claim.CF2.APR.APRBYPATSIG.pDateSigned', value);
        }
      },
      pRelCode(value) {
        this.setField('claim.CF2.APR.APRBYPATREPSIG.SC_RELCODE', this.pRelCode);
        if (this.pRelCode === 'O') {
          this.setField('claim.CF2.APR.APRBYPATREPSIG.OTHERPATREPREL.pRelCode', this.pRelCode);
        } else {
          this.setField('claim.CF2.APR.APRBYPATREPSIG.DEFINEDPATREPREL.pRelCode', this.pRelCode);
        }
      },
      pRelDesc(value) {
        this.setField('claim.CF2.APR.APRBYPATREPSIG.OTHERPATREPREL.pRelDesc', this.pRelDesc);
      },
      pReasonCode(value) {
        this.setField('claim.CF2.APR.APRBYPATREPSIG.DEFINEDREASONFORSIGNING.pReasonCode', this.pReasonCode);
      },
      pReasonDesc(value) {
        this.setField('claim.CF2.APR.APRBYPATREPSIG.OTHERREASONFORSIGNING.pReasonDesc', this.pReasonDesc);
      },
    },

    methods: {
      reset() {
        if (this.isSignedByRepresentative) {
          this.pDateSigned = this.field('claim.CF2.APR.APRBYPATREPSIG.pDateSigned');
        } else {
          this.pDateSigned = this.field('claim.CF2.APR.APRBYPATSIG.pDateSigned');
        }

        // IF pRelCode == O
        if (this.field('claim.CF2.APR.APRBYPATREPSIG.SC_RELCODE') !== 'O') {
          this.pRelCode = this.field('claim.CF2.APR.APRBYPATREPSIG.DEFINEDPATREPREL.pRelCode');
        } else {
          this.pRelCode = this.field('claim.CF2.APR.APRBYPATREPSIG.OTHERPATREPREL.pRelCode');
        }
        this.pRelDesc = this.field('claim.CF2.APR.APRBYPATREPSIG.OTHERPATREPREL.pRelDesc');

        // IF pReasonCode == O
        this.pReasonCode = this.field('claim.CF2.APR.APRBYPATREPSIG.DEFINEDREASONFORSIGNING.pReasonCode');
        this.pReasonDesc = this.field('claim.CF2.APR.APRBYPATREPSIG.OTHERREASONFORSIGNING.pReasonDesc');

        this.pThumbmarkedBy = this.field('claim.CF2.APR.APRBYTHUMBMARK.pThumbmarkedBy');
      },
    }
  };

</script>

