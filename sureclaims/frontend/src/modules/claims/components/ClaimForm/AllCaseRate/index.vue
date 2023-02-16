<template>
  <section>
    <h1>All Case Rate</h1>

    <el-form-item label="Case Rate">
      <el-col :span="24">
        <div class="item-list">

          <template v-for="(caseRate, index) in this.claim.ALLCASERATE.CASERATE">
            <div class="item-list--item">
              <h6 class="item-list--heading">
                <span>CASE RATE # {{ index + 1}}</span>
              </h6>

              <el-popover
                v-if="index === 0"
                placement="top"
                width="200"
                trigger="click"
                v-model="popoverRemoveMessage">
                <p>This will remove second case rate as well!</p>
                <div style="text-align: right; margin: 0">
                  <el-button size="mini" type="text" @click="popoverRemoveMessage = false">cancel</el-button>
                  <el-button type="danger" size="mini" @click="unmake(index); popoverRemoveMessage = false;">confirm</el-button>
                </div>
                <el-tooltip slot="reference" content="Remove this case rate entry" placement="top-start">
                  <a
                    class="item-list--delete btn btn-secondary m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--air"
                    @click="popoverRemoveMessage = true"
                  >
                    <i class="la la-trash"></i>
                  </a>
                </el-tooltip>
              </el-popover>
              <el-tooltip v-else content="Remove this case rate entry" placement="top-start">
                <a
                  class="item-list--delete btn btn-secondary m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--air"
                  @click="unmake(index)"
                >
                  <i class="la la-trash"></i>
                </a>
              </el-tooltip>

              <div class="m--padding-left-20 m--padding-right-20">
                <el-form-item class="m--margin-bottom-20">
                  <el-col :span="8">
                    <select-caserate
                      v-model="caseRate.SC_ITEMCODE"
                      :second-only="index !== 0"
                      :condition="claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTest === 'N' ?  1 : 0"
                      :placeholder="index === 0 ? 'Search First Case Rate' : 'Search Second Case Rate'"
                      @selectedChanged="caseRateSelectedChanged($event, index)"
                      :caserateCount="claim.ALLCASERATE.CASERATE.length"
                      style="width: 100%"
                    />
                  </el-col>
                  &nbsp;
                  <span class="hint">Assigned through online eligibility check (if available)</span>
                </el-form-item>
                <input type="hidden" :model="caseRate.pCaseRateCode" />
                <input type="hidden" :model="caseRate.pICDCode" />
                <input type="hidden" :model="caseRate.pRVSCode" />
                <input type="hidden" v-model="claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTest === 'N' && caseRate.pRVSCode === '99460' ? caseRate.pCaseRateAmount = '2750' : caseRate.pCaseRateAmount"/>
                <el-row v-show="caseRate.pCaseRateCode">
                  <el-col :span="16">
                      <caserate-detail :caseRate="formatCaseRateDetail(caseRate)" />
                  </el-col>
                </el-row>
              </div>
            </div>
          </template>

          <template v-if="isAllowedSecondCaseRate()">
            <hr />

            <el-form-item label-width="30px">
              <el-button
                type="text"
                size="medium"
                @click="make()"
              >
                <font-awesome-icon :icon="['far', 'plus']" />
                Add case rate
              </el-button>
            </el-form-item>
          </template>

        </div>
      </el-col>
    </el-form-item>

  </section>
</template>

<script>
  /* eslint-disable */
  import _get from 'lodash/get';
  import {
    Row,
    Col,
    Form,
    FormItem,
    Button,
    Tooltip,
    Popover,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import factory from '@/modules/claims/components/ClaimForm/Factory/CASERATE';
  import SelectCaseRate from '@/modules/core/components/form/SelectCaseRate';
  import CaseRateDetail from './CaseRateDetail';
  import NCP from "../Factory/NCP";
  export default {

    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-form-item': FormItem,
      'el-button': Button,
      'el-tooltip': Tooltip,
      'el-popover': Popover,
      'font-awesome-icon': FontAwesomeIcon,
      'select-caserate': SelectCaseRate,
      'caserate-detail': CaseRateDetail,
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

    data() {
      return {
        popoverRemoveMessage: false
      };
    },

    methods: {

      caseRateSelectedChanged(data, index) {
        let pICDCode = '', pRVSCode = '';
        if (_get(data, 'isIcd', '')) {
          pICDCode = _get(data, 'itemCode', '');
        } else {
          pRVSCode = _get(data, 'itemCode', '');
        }

        const caseRate = this.claim.ALLCASERATE.CASERATE;
        let bool = true;

        console.log(_get(data, 'itemCode', 0));

        if (index === 1) {
          if ((caseRate[0].pRVSCode === '99432' && pRVSCode === '99460') ||
            (caseRate[0].pRVSCode === '99460' && pRVSCode === '99432') ||
            (caseRate[0].pRVSCode === '99432' && pRVSCode === '99432') ||
            (caseRate[0].pRVSCode === '99460' && pRVSCode === '99460')
          )  {
            bool = false;
            this.unmake(index);
            this.$snotify.error(`Unable to assign Newborn Package to both Case Rate`, 'Oops!');
          }
        }

        /**
         * Set primary and secondary case rate amount
         */
        if (bool) {
          let pCaseRateAmount = _get(data, 'amount', '');
          if (index > 0) {
            pCaseRateAmount = _get(data, 'secondaryAmount', '');
          }

          this.claim.ALLCASERATE.CASERATE.splice(index, 1, {
            ...this.claim.ALLCASERATE.CASERATE[index],
            ...{
              pCaseRateCode: _get(data, 'code', ''),
              SC_ITEMCODE: _get(data, 'itemCode', ''),
              SC_DESCRIPTION: _get(data, 'description', ''),
              SC_ITEMDESCRIPTION: _get(data, 'itemDescription', ''),
              SC_CASETYPE: _get(data, 'caseType', ''),
              pCaseRateAmount,
              pICDCode,
              pRVSCode
            },
          });
        }
      },

      make() {
        const caseRate = this.field('claim.ALLCASERATE.CASERATE', null);
        if (caseRate) {
          caseRate.push(factory());
        }
      },

      unmake(index) {
        const caseRate = this.field('claim.ALLCASERATE.CASERATE', null);
        if (index === 0 ) {
          // If first case: remove all
          this.setField('claim.ALLCASERATE.CASERATE', []);
          return;
        }

        if (caseRate) {
          caseRate.splice(index, 1);
        }
      },
      formatCaseRateDetail(caseRate) {
        return {
          type: caseRate.SC_CASETYPE,
          itemCode: caseRate.SC_CASETYPE === 'icd' ? caseRate.pICDCode : caseRate.pRVSCode,
          itemDescription: caseRate.SC_ITEMDESCRIPTION,
          caseRateCode: caseRate.pCaseRateCode,
          caseRateDescription: caseRate.SC_DESCRIPTION,
          amount: caseRate.pCaseRateAmount
        };
      },

      /**
       * Check if first case rates if there is first case rate
       * @return {Boolean} [description]
       */
      isAllowedSecondCaseRate() {
        const caseRate = this.field('claim.ALLCASERATE.CASERATE', null);
        if (!caseRate || !caseRate.length) {
          return true;
        }
        return (caseRate.length < 2 && caseRate[0]);
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
