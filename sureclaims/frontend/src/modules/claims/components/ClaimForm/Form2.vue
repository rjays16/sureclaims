<template>
  <section>
    <h1>Patient Details</h1>

    <el-form-item label="Patient Is Referred">
      <el-switch
        v-model="claim.CF2.pPatientReferred"
        :active-text="isReferred ? 'YES' : 'NO'"
        active-value="Y"
        inactive-value="N"
      />
    </el-form-item>

    <el-form-item label="Referring Facility" v-if="isReferred">
      <el-col :span="12">
        <el-input
          v-model="claim.CF2.pReferredIHCPAccreCode"
          class="m--margin-bottom-10"
        />

        <span class="hint">
          Referral Facility Accreditation Code
        </span>
        <!--<el-input v-model="claim.CF2.pReferredIHCPAccreCode" />-->
        <!--<span class="hint">-->
        <!--Referring Facility Accreditation Code-->
        <!--</span>-->
      </el-col>
    </el-form-item>

    <el-form-item label="Admission Date & Time">
      <el-row :gutter="20">
        <el-col :span="8">
          <el-form-item 
            prop="CF2.pAdmissionDate"
            :rules="[
              { required: true, message: 'Please input admission date', trigger: 'change' }
            ]"
          >
            <el-date-picker
              type="date"
              placeholder="Pick a date"
              v-model="claim.CF2.pAdmissionDate"
              format="MM-dd-yyyy"
              value-format="MM-dd-yyyy"
              style="width: 100%;"
            />
          </el-form-item>
        </el-col>
        <span class="hint">
          Format: hh:mm:ssAM/PM Ex. 11:11:07PM
        </span>
        <el-col :span="8">
          <el-form-item
            prop="CF2.pAdmissionTime"
            :rules="[
              { required: true, message: 'Please input admission time', trigger: 'change' }
            ]"
          >
            <el-time-picker
              type="date"
              placeholder="Pick a time"
              v-model="claim.CF2.pAdmissionTime"
              format="hh:mmA"
              value-format="hh:mm:ssA"
              style="width: 100%;"
              @focus="setDefaultTime('claim.CF2.pAdmissionTime')"
            />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form-item>

    <el-form-item label="Discharge Date & Time">
      <el-row :gutter="20">
        <el-col :span="8">
          <el-form-item
            prop="CF2.pDischargeDate"
            :rules="[
              { required: true, message: 'Please input discharge date', trigger: 'change' }
            ]"
          >
            <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.pDischargeDate"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
          </el-form-item>
        </el-col>
        <span class="hint">
          Format: hh:mm:ssAM/PM Ex. 11:11:07PM
        </span>
        <el-col :span="8">
          <el-form-item
            prop="CF2.pDischargeTime"
            :rules="[
              { required: true, message: 'Please input discharge time', trigger: 'change' }
            ]"
          >
            <el-time-picker
              placeholder="Pick a time"
              v-model="claim.CF2.pDischargeTime"
              format="hh:mmA"
              value-format="hh:mm:ssA"
              style="width: 100%;"
              @focus="setDefaultTime('claim.CF2.pDischargeTime')"
            />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form-item>

    <el-form-item label="Has Attached SOA">
      <el-switch
        v-model="claim.CF2.pHasAttachedSOA"
        :active-text="hasAttachedSOA ? 'YES' : 'NO'"
        active-value="Y"
        inactive-value="N"
      />
    </el-form-item>

    <h2>Patient Status</h2>

    <el-form-item label="Type of Accommodation">
      <el-col :span="16">
        <el-select v-model="claim.CF2.pAccommodationType" placeholder="Select accommodation" style="width: 100%;">
          <el-option
            v-for="(item, key) in lookupTypes('patient.accommodationType')"
            :key="key"
            :label="item"
            :value="key"
          />
        </el-select>
      </el-col>
    </el-form-item>

    <el-form-item label="Patient's Disposition">
      <el-col :span="16">
        <el-select v-model="claim.CF2.pDisposition" placeholder="Select disposition" style="width: 100%;">
          <el-option
            v-for="(item, key) in lookupTypes('patient.dispositionType')"
            :key="key"
            :label="item"
            :value="key"
          />
        </el-select>
      </el-col>
    </el-form-item>

    <el-row
      :gutter="0"
      v-if="field('claim.CF2.pDisposition') === 'E'"
      type="flex"
      justify="space-between"
    >
      <el-col :span="11">
        <el-form-item label="Expired Date">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.pExpiredDate"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-form-item>
      </el-col>
      <el-col :span="11">
        <el-form-item label="Expired Time">
          <el-time-picker
            placeholder="Pick a time"
            v-model="claim.CF2.pExpiredTime"
            format="hh:mm:ssA"
            value-format="hh:mm:ssA"
            style="width: 100%;"
          />
        </el-form-item>
      </el-col>
    </el-row>

    <el-form-item label="Referral Facility" v-if="field('claim.CF2.pDisposition') === 'T'">
      <el-col :span="12">
        <el-input
          v-model="claim.CF2.pReferralIHCPAccreCode"
          class="m--margin-bottom-10"
        />

        <span class="hint">
          Referral Facility Accreditation Code
        </span>
      </el-col>
    </el-form-item>

    <el-form-item label="Referral Reasons" v-if="field('claim.CF2.pDisposition') === 'T'">
      <el-col :span="18">
        <el-input
          type="textarea"
          v-model="claim.CF2.pReferralReasons"
          :rows="3"
        />
      </el-col>
    </el-form-item>

  </section>
</template>

<script>

  import {
    Row,
    Col,
    Form,
    FormItem,
    Input,
    DatePicker,
    TimePicker,
    Option,
    Select,
    Switch,
    Button,
    Tooltip,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import SelectHciCode from '@/modules/core/components/form/SelectHci';
  import moment from 'moment';

  export default {

    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-date-picker': DatePicker,
      'el-time-picker': TimePicker,
      'el-form-item': FormItem,
      'el-input': Input,
      'el-option': Option,
      'el-select': Select,
      'el-switch': Switch,
      'el-button': Button,
      'el-tooltip': Tooltip,
      'font-awesome-icon': FontAwesomeIcon,
      'select-hci-code': SelectHciCode
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
      isReferred() {
        return this.claim.CF2.pPatientReferred === 'Y';
      },
      hasAttachedSOA() {
        return this.claim.CF2.pHasAttachedSOA === 'Y';
      },
      disposition() {
        return this.claim.CF2.pDisposition;
      },
    },

    watch: {
      isReferred(value) {
        if (!value) {
          this.setField('claim.CF2.pReferredIHCPAccreCode', '');
        }
      },

      disposition(value) {
        if (value !== 'E') { // Expired
          this.setField('claim.CF2.pExpiredDate', '');
          this.setField('claim.CF2.pExpiredTime', '');
        }

        if (value !== 'T') { // Expired
          this.setField('claim.CF2.pReferralIHCPAccreCode', '');
          this.setField('claim.CF2.pReferralReasons', '');
        }
      },
    },

    methods: {
      setDefaultTime (field) {
        const value = this.field(field, moment('00:00', 'HH:mm').format('HH:mmA'));
        this.setField(field, value);
      }
    }

  };

</script>

