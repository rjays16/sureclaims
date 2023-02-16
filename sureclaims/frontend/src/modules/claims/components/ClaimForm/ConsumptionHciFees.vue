<template>
  <div>
    <h2>HCI Fees</h2>

    <el-form-item label="Total Actual Charges">
      <el-row>
        <el-col :span="16">
          <currency-input
            v-model="claim.CF2.CONSUMPTION.HCIFEES.pTotalActualCharges"
          >
            <font-awesome-icon
              slot="prefix"
              style="width: 25px"
              :icon="['far', 'ruble-sign']"
            />
          </currency-input>
        </el-col>
      </el-row>
    </el-form-item>

    <el-form-item label="Discount Amount">
      <el-row>
        <el-col :span="16">
          <currency-input
            v-model="discountAmount"
          >
            <font-awesome-icon
              slot="prefix"
              style="width: 25px"
              :icon="['far', 'ruble-sign']"
            />
          </currency-input>
          <span class="hint">
            Total Discount Amount
          </span>
        </el-col>
      </el-row>
    </el-form-item>

    <el-form-item label="Total PhilHealth Benefit">
      <el-row>
        <el-col :span="16">
          <currency-input
            v-model="claim.CF2.CONSUMPTION.HCIFEES.pPhilhealthBenefit"
          >
            <font-awesome-icon
              slot="prefix"
              style="width: 25px"
              :icon="['far', 'ruble-sign']"
            />
          </currency-input>
          <span class="hint">
            Total PhilHealth Benefit Amount
          </span>
        </el-col>
      </el-row>
    </el-form-item>

    <el-form-item label="Total Excess">
      <span class="m--icon-font-size-lg1 text-success m--font-bolder">
        {{ formattedTotalAmount }}
      </span>
    </el-form-item>

    <el-form-item>
      <el-switch
        v-model="claim.CF2.CONSUMPTION.HCIFEES.pMemberPatient"
        :active-text="field('claim.CF2.CONSUMPTION.HCIFEES.pMemberPatient') === 'Y' ? 'Member/Patient' : 'Not Member/Patient'"
        active-value="Y"
        inactive-value="N"
      />
    </el-form-item>

    <el-form-item>
      <el-switch
        v-model="claim.CF2.CONSUMPTION.HCIFEES.pHMO"
        :active-text="field('claim.CF2.CONSUMPTION.HCIFEES.pHMO') === 'Y' ? 'With HMO' : 'Without HMO'"
        active-value="Y"
        inactive-value="N"
      />
    </el-form-item>

    <el-form-item>
      <el-switch
        v-model="claim.CF2.CONSUMPTION.HCIFEES.pOthers"
        :active-text="field('claim.CF2.CONSUMPTION.HCIFEES.pOthers') === 'Y' ? 'With other benefits' : 'No other benefits'"
        active-value="Y"
        inactive-value="N"
      />
      <span class="hint">
        PCSO, Promissory note, etc.
      </span>
    </el-form-item>
  </div>
</template>

<script>
  import {
    Row,
    Col,
    Form,
    FormItem,
    Switch,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import CurrencyInput from '@/modules/core/components/form/CurrencyInput';

  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  import { formatCurrency } from '@/helpers/number';

  export default {
    name: 'consumption-hci-fees',

    mixins: [BaseComponent, UsesLookups],

    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-form-item': FormItem,
      'el-switch': Switch,
      'font-awesome-icon': FontAwesomeIcon,
      'currency-input': CurrencyInput,
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

    data() {
      return {
        discountAmount: 0,
      };
    },

    mounted () {
      this.setDicsountAmount(this.pDiscount);
    },

    computed: {
      formattedTotalAmount() {
        return formatCurrency(this.field('claim.CF2.CONSUMPTION.HCIFEES.pTotalAmount', 0));
      },
      pTotalActualCharges() {
        return this.field('claim.CF2.CONSUMPTION.HCIFEES.pTotalActualCharges', 0);
      },
      pPhilhealthBenefit() {
        return this.field('claim.CF2.CONSUMPTION.HCIFEES.pPhilhealthBenefit', 0);
      },
      pDiscount() {
        return this.field('claim.CF2.CONSUMPTION.HCIFEES.pDiscount', 0);
      },
      pTotalAmount() {
        return this.pTotalActualCharges - (this.discountAmount + this.pPhilhealthBenefit);
      },
    },

    watch: {
      pTotalAmount(value) {
        this.setField('claim.CF2.CONSUMPTION.HCIFEES.pTotalAmount', value || 0);
      },
      discountAmount(value) {
        const discount = value || 0;
        this.setField('claim.CF2.CONSUMPTION.HCIFEES.pDiscount', discount > 0 ? this.pTotalActualCharges - discount : 0);
      },
      pDiscount(value) {
        this.setDicsountAmount(value);
      },
    },

    methods: {
      setDicsountAmount (value) {
        const discount = value || 0;
        this.discountAmount = discount > 0 ? this.pTotalActualCharges - discount : 0;
      }
    }
  };
</script>