<template>
  <section>
    <h1>Professional Details</h1>

    <el-form-item label="Professional Fees">
      <el-col :span="24">
        <div class="item-list">

          <template v-for="(pf, index) in claim.CF2.PROFESSIONALS">
            <div class="item-list--item">
              <h6 class="item-list--heading">
                <span>ENTRY # {{ index + 1}}</span>
              </h6>

              <el-tooltip content="Remove this professional fee entry" placement="top-start">
                <a
                  class="item-list--delete btn btn-secondary m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--air"
                  @click="unmake(index)"
                >
                  <i class="la la-trash"></i>
                </a>
              </el-tooltip>

              <div class="m--padding-left-20 m--padding-right-20">
                <el-form-item>
                  <select-doctor
                    class="m--margin-bottom-20"
                    v-model="pf.SC_ID"
                    :already-selected="exclude"
                    @selectedChanged="doctorSelectedChanged($event, index)"
                    style="width: 100%"
                  />
                </el-form-item>

                <el-form-item label="With Co-Pay?">
                  <el-switch
                    v-model="pf.pWithCoPay"
                    :active-text="pf.pWithCoPay === 'Y' ? 'YES' : 'NO'"
                    active-value="Y"
                    inactive-value="N"
                  />
                </el-form-item>

                <el-form-item v-if="pf.pWithCoPay === 'Y'" label="Co-Pay Amount">
                  <el-col :span="14">
                    <currency-input
                      placeholder="Co-pay amount"
                      v-model="pf.pDoctorCoPay"
                    >
                      <font-awesome-icon
                        slot="prefix"
                        style="width: 25px"
                        :icon="['far', 'ruble-sign']"
                      />
                    </currency-input>
                  </el-col>
                </el-form-item>

                <el-form-item label="Date Signed">
                  <el-col :span="14">
                    <el-date-picker
                      placeholder="Pick a date"
                      v-model="pf.pDoctorSignDate"
                      format="MM-dd-yyyy"
                      value-format="MM-dd-yyyy"
                      style="width: 100%;"
                    />
                  </el-col>
                </el-form-item>
              </div>
            </div>
          </template>

          <hr v-if="claim.CF2.PROFESSIONALS.length > 0" />

          <el-form-item label-width="30px">
            <el-button
              type="text"
              size="medium"
              @click="make()"
            >
              <font-awesome-icon :icon="['far', 'plus']" />
              Add more professional fees
            </el-button>
          </el-form-item>

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

  import factory from '@/modules/claims/components/ClaimForm/Factory/PROFESSIONALS';
  import DoctorDataFragment from '@/modules/doctors/graphql/fragments/DoctorDataFragment.gql';

  import DataView from '@/modules/core/components/DataView';
  import CurrencyInput from '@/modules/core/components/form/CurrencyInput';
  import SelectDoctor from '@/modules/doctors/components/SelectDoctor';

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
      'select-doctor': SelectDoctor,
      'currency-input': CurrencyInput,
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

    data() {
      return {
        doctors: [],
      };
    },

    computed: {
      exclude() {
        return this.field('claim.CF2.PROFESSIONALS', [])
          .map(a => a.SC_ID); 
      }
    },

    methods: {

      doctorSelectedChanged(doctor, index) {
        this.claim.CF2.PROFESSIONALS.splice(index, 1, {
          ...this.claim.CF2.PROFESSIONALS[index],
          ...{
            pDoctorAccreCode: _get(doctor, 'pan', ''),
            pDoctorLastName: _get(doctor, 'lastName', ''),
            pDoctorFirstName: _get(doctor, 'firstName', ''),
            pDoctorMiddleName: _get(doctor, 'middleName', ''),
            pDoctorSuffix: _get(doctor, 'suffix', ''),
          },
        });
      },

      make() {
        const proffees = this.field('claim.CF2.PROFESSIONALS', null);
        if (proffees) {
          proffees.push(factory());
          this.doctors.push('');
        }
      },

      unmake(index) {
        const proffees = this.field('claim.CF2.PROFESSIONALS', null);
        if (proffees) {
          proffees.splice(index, 1);
          this.doctors.splice(index, 1);
        }
      },
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

