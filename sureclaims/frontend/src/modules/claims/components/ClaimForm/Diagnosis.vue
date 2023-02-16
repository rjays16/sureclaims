<template>
  <section>
    <h1>Diagnosis</h1>

    <el-form-item
      label="Admitting Diagnosis"
      prop="CF2.DIAGNOSIS.pAdmissionDiagnosis"
    >
      <el-col :span="18">
        <el-input
          type="textarea"
          v-model="claim.CF2.DIAGNOSIS.pAdmissionDiagnosis"
          :rows="2"
        />
      </el-col>
    </el-form-item>

    <el-form-item label="Diagnosis">
      <el-col :span="24">
        <div class="item-list">
          <template v-for="(discharge, index) in field('claim.CF2.DIAGNOSIS.DISCHARGE', [])">
            <div class="item-list--item">
              <h6 class="item-list--heading">
                <span>{{ ordinalize(index + 1) }} DIAGNOSIS</span>
              </h6>

              <el-tooltip content="Remove this discharge entry" placement="top-start">
                <a
                  class="item-list--delete btn btn-secondary m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--air"
                  @click="unmakeDischarge(index)"
                >
                  <i class="la la-trash"></i>
                </a>
              </el-tooltip>

              <div class="m--padding-left-20 m--padding-right-20">
                <el-form-item
                  :prop="'CF2.DIAGNOSIS.DISCHARGE.' + index + '.pDischargeDiagnosis'"
                  :rules="[{
                    required: true, 
                    message: 'Please provide discharge diagnosis', 
                    trigger: 'blur'
                  }]"
                >
                  <el-input
                    v-model="discharge.pDischargeDiagnosis"
                    class="m--margin-bottom-20"
                    placeholder="Discharge Diagnosis"
                    type="textarea"
                    :rows="2"
                  />
                </el-form-item>

                <el-row type="flex" :gutter="20" >

                  <el-col :span="10">

                    <h2>ICD Codes</h2>

                    <template v-for="(icdCode, icdIndex) in discharge.ICDCODE">
                      <el-form-item label-width="30px">
                        <span slot="label">
                          <el-tooltip content="Remove this ICD Code entry" placement="top-start">
                            <el-button type="text" @click="unmakeICDCode(discharge.ICDCODE, icdIndex)">
                              <font-awesome-icon :icon="['far', 'times']" />
                            </el-button>
                          </el-tooltip>
                        </span>
                        <el-form-item>
                          <!--
                          <el-input
                            class="m--margin-bottom-10"
                            v-model="icdCode.pICDCode"
                            placeholder="Enter ICD Code"
                          />
                          -->
                          <select-icd-code
                            v-model="icdCode.pICDCode"
                            class="m--margin-bottom-10"
                          />
                        </el-form-item>
                      </el-form-item>
                    </template>

                    <el-form-item label-width="30px">
                      <el-badge :value="discharge.ICDCODE.length">
                        <el-button
                          plain
                          size="small"
                          @click="makeICDCode(discharge.ICDCODE)"
                        >
                          Add more ICD Codes
                        </el-button>
                      </el-badge>
                    </el-form-item>

                  </el-col>

                  <el-col :span="14">
                    <h2>RVS Codes</h2>

                    <template v-for="(rvsCode, rvsIndex) in discharge.RVSCODES">
                      <el-form-item label-width="30px">
                        <span slot="label">
                          <el-tooltip content="Remove this RVS Code entry" placement="top-start">
                            <el-button type="text" @click="unmakeRVSCode(discharge.RVSCODES, rvsIndex)">
                              <font-awesome-icon :icon="['far', 'times']" />
                            </el-button>
                          </el-tooltip>
                        </span>
                        <select-rvs-code
                          v-model="rvsCode.pRVSCode"
                          @selectionChanged="rvsSelectionChanged(discharge.RVSCODES, $event, rvsIndex)"
                          class="m--margin-bottom-10"
                        />
                      </el-form-item>

                      <el-form-item label-width="30px">
                        <el-input
                          class="m--margin-bottom-10"
                          v-model="rvsCode.pRelatedProcedure"
                          placeholder="Name of Related Procedure"
                        />
                      </el-form-item>

                      <el-form-item label-width="30px">
                        <el-date-picker
                          class="m--margin-bottom-10"
                          placeholder="Date of procedure ..."
                          v-model="rvsCode.pProcedureDate"
                          format="MM-dd-yyyy"
                          value-format="MM-dd-yyyy"
                          style="width: 75%;"
                        />
                      </el-form-item>

                      <el-form-item label-width="30px">
                        <el-select
                          class="m--margin-bottom-10"
                          v-model="rvsCode.pLaterality"
                          placeholder="Laterality ..."
                          :disabled="rvsCode.SC_WITHLATERAL"
                          style="width: 75%;"
                        >
                          <el-option
                            v-for="(item, key) in lookupTypes('procedure.laterality')"
                            :key="key"
                            :label="item"
                            :value="key"
                          />
                        </el-select>

                      </el-form-item>

                      <hr />
                    </template>

                    <el-form-item label-width="30px">
                      <el-badge :value="discharge.RVSCODES.length">
                        <el-button
                          plain
                          size="small"
                          @click="makeRVSCode(discharge.RVSCODES)"
                        >
                          Add more RVS Codes
                        </el-button>
                      </el-badge>
                    </el-form-item>

                  </el-col>


                </el-row>
              </div>
            </div>
          </template>

          <hr
            v-if="field('claim.CF2.DIAGNOSIS.DISCHARGE', []).length > 0"
            style="margin: 2rem 0 1rem"
          />
          <el-button
            type="text"
            @click="makeDischarge"
          >
            <font-awesome-icon :icon="['far', 'plus']" />
            Add more diagnosis
          </el-button>
        </div>
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
    Badge,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import SelectRvsCode from '@/modules/core/components/form/SelectRvsCode';
  import dischargeFactory from '@/modules/claims/components/ClaimForm/Factory/DISCHARGE';
  import icdCodeFactory from '@/modules/claims/components/ClaimForm/Factory/ICDCODE';
  import rvsCodeFactory from '@/modules/claims/components/ClaimForm/Factory/RVSCODES';
  import SelectIcdCode from '@/modules/core/components/form/SelectIcdCode';
  import { ordinalize as _ordinalize } from '@/helpers/lang';
  import _set from 'lodash/set';

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
      'select-rvs-code': SelectRvsCode,
      'select-icd-code': SelectIcdCode,
      'el-badge': Badge,
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
    },

    watch: {
    },


    methods: {
      ordinalize(n) {
        return _ordinalize(n);
      },

      makeDischarge() {
        const discharges = this.field('claim.CF2.DIAGNOSIS.DISCHARGE', null);
        if (discharges) {
          discharges.push(dischargeFactory());
        }
      },

      unmakeDischarge(index) {
        const discharges = this.field('claim.CF2.DIAGNOSIS.DISCHARGE', null);
        if (discharges) {
          discharges.splice(index, 1);
        }
      },

      makeICDCode(container) {
        if (container) {
          container.push(icdCodeFactory());
        }
      },

      unmakeICDCode(container, index) {
        if (container) {
          container.splice(index, 1);
        }
      },

      makeRVSCode(container) {
        if (container) {
          container.push(rvsCodeFactory());
        }
      },

      unmakeRVSCode(container, index) {
        if (container) {
          container.splice(index, 1);
        }
      },

      rvsSelectionChanged(container, rvs, index) {
        _set(container[index], 'pRelatedProcedure', rvs.description);
        const laterality = !rvs.with_laterality ? 'N' : '';
        _set(container[index], 'pLaterality', laterality);
        _set(container[index], 'SC_WITHLATERAL', !rvs.with_laterality);
      }
    },
  };

</script>

<style scoped>
  .item-list {
    position: relative;
  }

  .item-list--item {
    margin-top: 2rem;
    position: relative;
    overflow: hidden;
    padding: 1rem 1rem 0 0;
  }

  .item-list--item:first-of-type {
    margin-top: 0;
  }

  .item-list--heading {
    display: block;
    line-height: 36px;
    height: 36px;
    position: relative;
    margin-bottom: 1rem;
  }

  .item-list--heading:after {
    content: '';
    height: 50%;
    width: 100%;

    position: absolute;
    top: 0;
    border-bottom: 1px solid #f3f2fa;
    z-index: 0;
  }

  .item-list--heading > span {
    position: absolute;
    z-index: 1;
    line-height: 36px;
    color: #666;
    background-color: #fff;
    padding: 0 10px;
  }

  .item-list--delete {
    position: absolute;
    top: 1rem;
    right: 1rem;
  }

</style>

