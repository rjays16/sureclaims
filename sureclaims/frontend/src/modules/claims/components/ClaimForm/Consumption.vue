<template>
  <section>
    <h1>Benefits Consumption</h1>

    <el-form-item label="Enough Benefits ?">
      <el-col :span="16">
        <el-switch
          v-model="claim.CF2.CONSUMPTION.pEnoughBenefits"
          :active-text="field('claim.CF2.CONSUMPTION.pEnoughBenefits') === 'Y' ? 'YES' : 'NO'"
          active-value="Y"
          inactive-value="N"
        />
      </el-col>
    </el-form-item>

    <!-- ENOUGH BENEFITS = YES -->
    <el-row
      v-if="field('claim.CF2.CONSUMPTION.pEnoughBenefits') === 'Y'"
    >

      <el-form-item label="Total HCI Fees">
        <el-col :span="8">
          <currency-input
            v-model="claim.CF2.CONSUMPTION.BENEFITS.pTotalHCIFees"
          >
            <font-awesome-icon
              slot="prefix"
              style="width: 25px"
              :icon="['far', 'ruble-sign']"
            />
          </currency-input>
        </el-col>
      </el-form-item>

      <el-form-item label="Total Prof Fees">
        <el-col :span="8">
          <currency-input
            v-model="claim.CF2.CONSUMPTION.BENEFITS.pTotalProfFees"
          >
            <font-awesome-icon
              slot="prefix"
              style="width: 25px"
              :icon="['far', 'ruble-sign']"
            />
          </currency-input>
        </el-col>
      </el-form-item>

      <el-form-item label="Grand Total">
        <span class="m--icon-font-size-lg1 text-success m--font-bolder">
          {{ pGrandTotal }}
        </span>
      </el-form-item>

    </el-row>

    <!-- NOT ENOUGH BENEFITS: HCI FEES -->
    <el-form label-position="top" v-if="field('claim.CF2.CONSUMPTION.pEnoughBenefits') !== 'Y'">
      <el-row :gutter="40">
        <el-col :span="11">
          <consumption-hci-fees :form-data="formData" :claim="claim" />
        </el-col>
        <el-col :span="11">
          <consumption-prof-fees :form-data="formData" :claim="claim" />
        </el-col>
      </el-row>
    </el-form>

    <section v-if="field('claim.CF2.CONSUMPTION.pEnoughBenefits') !== 'Y'">
      <h2>Purchases During Confinement</h2>

      <el-form-item label="Drugs/Medicines/Supplies">
        <el-col :span="16">
          <el-switch
            v-model="claim.CF2.CONSUMPTION.PURCHASES.pDrugsMedicinesSupplies"
            :active-text="field('claim.CF2.CONSUMPTION.PURCHASES.pDrugsMedicinesSupplies') === 'Y' ? 'YES' : 'NO'"
            active-value="Y"
            inactive-value="N"
          />
        </el-col>
      </el-form-item>

      <template v-if="field('claim.CF2.CONSUMPTION.PURCHASES.pDrugsMedicinesSupplies') === 'Y'">
        <el-form-item label="Total Amount">
          <el-col :span="8">
            <currency-input
              v-model="claim.CF2.CONSUMPTION.PURCHASES.pDMSTotalAmount"
            >
              <font-awesome-icon
                slot="prefix"
                style="width: 25px"
                :icon="['far', 'ruble-sign']"
              />
            </currency-input>
          </el-col>
        </el-form-item>
      </template>

      <el-form-item label="Examinations/Diagnostics">
        <el-col :span="16">
          <el-switch
            v-model="claim.CF2.CONSUMPTION.PURCHASES.pExaminations"
            :active-text="field('claim.CF2.CONSUMPTION.PURCHASES.pExaminations') === 'Y' ? 'YES' : 'NO'"
            active-value="Y"
            inactive-value="N"
          />
        </el-col>
      </el-form-item>

      <template v-if="field('claim.CF2.CONSUMPTION.PURCHASES.pExaminations') === 'Y'">
        <el-form-item label="Total Amount">
          <el-col :span="8">
            <currency-input
              v-model="claim.CF2.CONSUMPTION.PURCHASES.pExamTotalAmount"
            >
              <font-awesome-icon
                slot="prefix"
                style="width: 25px"
                :icon="['far', 'ruble-sign']"
              />
            </currency-input>
          </el-col>
        </el-form-item>
      </template>
    </section>
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
    Option,
    Select,
    Switch,
    Button,
    Tooltip,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import CurrencyInput from '@/modules/core/components/form/CurrencyInput';

  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  import { formatCurrency } from '@/helpers/number';

  import ConsumptionHciFees from './ConsumptionHciFees';
  import ConsumptionProfFees from './ConsumptionProfFees';

  export default {

    mixins: [BaseComponent, UsesLookups],

    components: {
      ConsumptionHciFees,
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
      'currency-input': CurrencyInput,
      'consumption-hci-fees': ConsumptionHciFees,
      'consumption-prof-fees': ConsumptionProfFees,
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
      };
    },

    computed: {
      pGrandTotal() {
        return formatCurrency(this.field('claim.CF2.CONSUMPTION.BENEFITS.pGrandTotal', 0));
      },

      grandTotal() {
        const hciFees = this.field('claim.CF2.CONSUMPTION.BENEFITS.pTotalHCIFees', 0);
        const profFees = this.field('claim.CF2.CONSUMPTION.BENEFITS.pTotalProfFees', 0);
        return hciFees + profFees;
      },
    },

    watch: {
      grandTotal(value) {
        this.setField('claim.CF2.CONSUMPTION.BENEFITS.pGrandTotal', value);
      }
    },

  };

</script>