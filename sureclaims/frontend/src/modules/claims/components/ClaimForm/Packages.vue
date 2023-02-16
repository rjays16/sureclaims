<template>
  <section>
    <h1>Packages</h1>

    <el-form-item label="Applicable Packages">
      <el-row>
        <template v-for="key in Object.keys(packages).slice(0,3)">
          <el-col :span="8" :key="key">
            <el-form-item>
              <el-checkbox
                v-model="packages[key].selected"
                @change="tick($event, key)"
              >
                {{ packages[key].label }}
              </el-checkbox>
            </el-form-item>
          </el-col>
        </template>
      </el-row>

      <el-row>
        <template v-for="key in Object.keys(packages).slice(3,6)">
          <el-col :span="8" :key="key">
            <el-form-item>
              <el-checkbox
                v-model="packages[key].selected"
                @change="tick($event, key)"
              >
                {{ packages[key].label }}
              </el-checkbox>
            </el-form-item>
          </el-col>
        </template>
      </el-row>

    </el-form-item>

    <br />

    <template v-if="packages['MCP'].selected">
      <h2>Maternity Care Package</h2>

      <el-form-item label="1st Check-up Date">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.MCP.pCheckUpDate1"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>

      <el-form-item label="2nd Check-up Date">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.MCP.pCheckUpDate2"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>

      <el-form-item label="3rd Check-up Date">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.MCP.pCheckUpDate3"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>

      <el-form-item label="4th Check-up Date">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.MCP.pCheckUpDate4"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>
    </template>

    <template v-if="packages['TBDOTS'].selected">

      <h2>TB-DOTS</h2>

      <el-form-item label="Type of TB">
        <el-col :span="5">
          <el-select v-model="claim.CF2.SPECIAL.TBDOTS.pTBType" placeholder="Select TB Type" style="width: 100%;">
            <el-option
              v-for="(item, key) in lookupTypes('claim.tbType')"
              :key="key"
              :label="item"
              :value="key"
            />
          </el-select>
        </el-col>
      </el-form-item>

      <el-form-item label="NTP Card No.">
        <el-col :span="5">
          <el-input v-model="claim.CF2.SPECIAL.TBDOTS.pNTPCardNo" :maxlength="10">
            <font-awesome-icon slot="suffix" :icon="['far', 'hashtag']" style="width: 25px" />
          </el-input>
        </el-col>
      </el-form-item>

    </template>

    <template v-if="packages['ABP'].selected">

      <h2>Animal Bites Package</h2>

      <el-form-item label="Day 0 ARV">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.ABP.pDay0ARV"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>

      <el-form-item label="Day 3 ARV">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.ABP.pDay3ARV"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>

      <el-form-item label="Day 7 ARV">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.ABP.pDay7ARV"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>

      <el-form-item label="Rabies Immunoglobulin">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.ABP.pRIG"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>

      <el-form-item label="Date (Others)">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.ABP.pABPOthers"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>

      <el-form-item label="Others (Please specify)">
        <el-col :span="20">
          <el-input v-model="claim.CF2.SPECIAL.ABP.pABPSpecify" />
        </el-col>
      </el-form-item>

    </template>

    <template v-if="packages['NCP'].selected">

      <h2>Newborn Care Package</h2>

      <table width="1600" border="0">
        <tr>
          <td width="300">
            <el-form-item label="Essential Newborn Care?" label-width="320px">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.pEssentialNewbornCare === '' ? claim.CF2.SPECIAL.NCP.pEssentialNewbornCare = 'Y' : claim.CF2.SPECIAL.NCP.pEssentialNewbornCare"
              :active-text="claim.CF2.SPECIAL.NCP.pEssentialNewbornCare === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"/>
          </el-form-item>
          </td>
          <td width="800">
            <el-form-item label="Newborn Screening Test?" label-width="320px">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.pNewbornScreeningTest === '' ? claim.CF2.SPECIAL.NCP.pNewbornScreeningTest = 'Y' : claim.CF2.SPECIAL.NCP.pNewbornScreeningTest"
              :active-text="claim.CF2.SPECIAL.NCP.pNewbornScreeningTest === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"
            />
          </el-form-item></td>
        </tr>
        <tr>
          <td>
            <el-form-item label="Immediate drying of newborn ?" label-width="320px" style="margin-left: 30px;">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.ESSENTIAL.pDrying === '' ? claim.CF2.SPECIAL.NCP.ESSENTIAL.pDrying = 'Y' : claim.CF2.SPECIAL.NCP.ESSENTIAL.pDrying"
              :active-text="claim.CF2.SPECIAL.NCP.ESSENTIAL.pDrying === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"/>
          </el-form-item>
          </td>
          <td>
            <el-form-item label="With OR Attached ?" label-width="250px" style="margin-left: 60px">
            <el-switch v-if="claim.CF2.SPECIAL.NCP.pNewbornScreeningTest === 'N'"
              disabled="isActive"
              :active-text="'NO'"
              active-value="Y"
              inactive-value="N"/>
            <el-switch
              v-else
              :active-text="'NO'"
              active-value="Y"
              inactive-value="N"/>
          </el-form-item>
          </td>
        </tr>
        <tr>
          <td>
            <el-form-item label="Early skin-to-skin contact ?" label-width="320px" style="margin-left: 30px;">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.ESSENTIAL.pSkinToSkin === '' ? claim.CF2.SPECIAL.NCP.ESSENTIAL.pSkinToSkin = 'Y' : claim.CF2.SPECIAL.NCP.ESSENTIAL.pSkinToSkin"
              :active-text="claim.CF2.SPECIAL.NCP.ESSENTIAL.pSkinToSkin === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"/>
          </el-form-item>
          </td>

          <td>
            <el-form-item label="Filter Card Number" style="margin-left: 60px">
            <el-col :span="6"
              v-if="claim.CF2.SPECIAL.NCP.pNewbornScreeningTest === 'N'">
              <el-input
                disabled="isActive"
                v-model="claim.CF2.SPECIAL.NCP.pFilterCardNo"
                maxlength="20" style="margin-left: 50px"/>
            </el-col>
              <el-col :span="6"
                v-else>
                <el-input
                  v-model="claim.CF2.SPECIAL.NCP.pFilterCardNo === '' && claim.CF2.SPECIAL.NCP.pNewbornScreeningTest === 'Y' ? claim.CF2.SPECIAL.NCP.pFilterCardNo = 'N/A' : claim.CF2.SPECIAL.NCP.pFilterCardNo"
                  maxlength="20" style="margin-left: 50px"/>
              </el-col>
          </el-form-item>
          </td>
        </tr>
        <tr>
          <td>
            <el-form-item label="Timely cord clamping ?" label-width="320px" style="margin-left: 30px;">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.ESSENTIAL.pCordClamping === '' ? claim.CF2.SPECIAL.NCP.ESSENTIAL.pCordClamping = 'Y' : claim.CF2.SPECIAL.NCP.ESSENTIAL.pCordClamping"
              :active-text="claim.CF2.SPECIAL.NCP.ESSENTIAL.pCordClamping === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"/>
          </el-form-item>
          </td>
        </tr>
        <tr>
          <td>
            <el-form-item label="Eye prophylaxis ?" label-width="320px" style="margin-left: 30px;">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.ESSENTIAL.pProphylaxis === '' ? claim.CF2.SPECIAL.NCP.ESSENTIAL.pProphylaxis = 'Y' : claim.CF2.SPECIAL.NCP.ESSENTIAL.pProphylaxis"
              :active-text="claim.CF2.SPECIAL.NCP.ESSENTIAL.pProphylaxis === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"
            />
          </el-form-item></td>
        </tr>
        <tr>
          <td> <el-form-item label="Weighing of the newborn ?" label-width="320px" style="margin-left: 30px;">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.ESSENTIAL.pWeighing === '' ? claim.CF2.SPECIAL.NCP.ESSENTIAL.pWeighing = 'Y' : claim.CF2.SPECIAL.NCP.ESSENTIAL.pWeighing"
              :active-text="claim.CF2.SPECIAL.NCP.ESSENTIAL.pWeighing === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"
            />
          </el-form-item></td>

          <td>
            <el-form-item label="Newborn Hearing Screening Test?" label-width="320px">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTest === '' ? claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTest = 'Y' : claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTest"
              :active-text="claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTest === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"
            />
          </el-form-item></td>
        </tr>
        <tr>
          <td>  <el-form-item label="Vitamin K administration ?" label-width="320px" style="margin-left: 30px;">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.ESSENTIAL.pVitaminK === '' ? claim.CF2.SPECIAL.NCP.ESSENTIAL.pVitaminK = 'Y' : claim.CF2.SPECIAL.NCP.ESSENTIAL.pVitaminK"
              :active-text="claim.CF2.SPECIAL.NCP.ESSENTIAL.pVitaminK === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"
            />
          </el-form-item></td>

          <td>
            <el-form-item label="With OR Attached ?" label-width="250px" style="margin-left: 60px">
            <el-switch
              v-if="claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTest === 'N'"
              disabled="isActive"
              :active-text="'NO'"
              active-value="P"
              inactive-value="X"
            />
              <el-switch
                v-else
                :active-text="'NO'"
                active-value="P"
                inactive-value="X"
              />
          </el-form-item>
          </td>
        </tr>
        <tr>
          <td> <el-form-item label="BCG vaccination ?" label-width="320px" style="margin-left: 30px;">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.ESSENTIAL.pBCG === '' ? claim.CF2.SPECIAL.NCP.ESSENTIAL.pBCG = 'Y' : claim.CF2.SPECIAL.NCP.ESSENTIAL.pBCG"
              :active-text="claim.CF2.SPECIAL.NCP.ESSENTIAL.pBCG === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"/>
          </el-form-item></td>

          <td>
            <el-form-item label="NHST Registration Card No." style="margin-left: 60px">
              <el-col
                :span="6"
                v-if="claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTest === 'N'">
                <el-input
                  disabled="isActive"
                  v-model="claim.CF2.SPECIAL.NCP.pNewbornHearingRegistryNo"
                  maxlength="20" style="margin-left: 50px; margin-top: 25px"/>
              </el-col>
              <el-col
                :span="6"
                v-else>
                <el-input
                  v-model="claim.CF2.SPECIAL.NCP.pNewbornHearingRegistryNo === '' && claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTest === 'Y' ? claim.CF2.SPECIAL.NCP.pNewbornHearingRegistryNo = 'N/A' : claim.CF2.SPECIAL.NCP.pNewbornHearingRegistryNo"
                  maxlength="20" style="margin-left: 50px; margin-top: 25px"/>
              </el-col>
            </el-form-item>
          </td>
        </tr>
        <tr>
          <td><el-form-item label="Non-separation of mother/baby?" label-width="320" style="margin-left: 30px;">
            <el-switch style="margin-left: 70px"
              v-model="claim.CF2.SPECIAL.NCP.ESSENTIAL.pNonSeparation === '' ? claim.CF2.SPECIAL.NCP.ESSENTIAL.pNonSeparation = 'Y' : claim.CF2.SPECIAL.NCP.ESSENTIAL.pNonSeparation"
              :active-text="claim.CF2.SPECIAL.NCP.ESSENTIAL.pNonSeparation === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"
            />
            <span class="hint">For early breastfeeding initiation</span>
          </el-form-item></td>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td> <el-form-item label="Hepatitis B vaccination ?" label-width="320px" style="margin-left: 30px;">
            <el-switch
              v-model="claim.CF2.SPECIAL.NCP.ESSENTIAL.pHepatitisB === '' ? claim.CF2.SPECIAL.NCP.ESSENTIAL.pHepatitisB = 'Y' : claim.CF2.SPECIAL.NCP.ESSENTIAL.pHepatitisB"
              :active-text="claim.CF2.SPECIAL.NCP.ESSENTIAL.pHepatitisB === 'Y' ? 'YES' : 'NO'"
              active-value="Y"
              inactive-value="N"
            />
          </el-form-item></td>
          <td>
            <el-form-item label="NHST Result" style="margin-left: 60px">
            <el-col>
              <el-select
                v-if="claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTest === 'N'"
                v-model="claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTestResult = 'X'"
                disabled="isActive"
                placeholder="NOT APPLICABLE"
                style="margin-left: 45px; height: 40px; width: 200px">
                <el-option value="" disabled selected>Select NHST Result</el-option>
                <el-option
                  v-for="nhst in nhstlist"
                  :value="nhst.pass"
                  :label="nhst.nhstresult"
                  :key="nhst.pass">
                </el-option>
              </el-select>

              <el-select
                v-else
                placeholder="SELECT NHST RESULT"
                v-model="claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTestResult === '' ? claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTestResult = 'X' : claim.CF2.SPECIAL.NCP.pNewbornHearingScreeningTestResult"
                style="margin-left: 45px; height: 40px; width: 200px">
                <el-option value="" disabled selected>Select NHST Result</el-option>
                <el-option
                  v-for="nhst in nhstlist"
                  :value="nhst.pass"
                  :label="nhst.nhstresult"
                  :key="nhst.pass">
                </el-option>
              </el-select>
            </el-col>
          </el-form-item></td>
        </tr>
      </table>
    </template>

    <template v-if="packages['HIVAIDS'].selected">

      <h2>HIV/AIDS Package</h2>

      <el-form-item label="Laboratory No.">
        <el-col :span="12">
          <el-input v-model="claim.CF2.SPECIAL.HIVAIDS.pLaboratoryNumber" />
        </el-col>
      </el-form-item>

    </template>

    <template v-if="packages['CATARACTINFO'].selected">

      <h2>Cataract Information</h2>

      <el-form-item label="Cataract Pre Auth">
        <el-col :span="12">
          <el-input v-model="claim.CF2.SPECIAL.CATARACTINFO.pCataractPreAuth" />
        </el-col>
      </el-form-item>

      <el-form-item label="Left Eye IOL Sticker No.">
        <el-col :span="12">
          <el-input v-model="claim.CF2.SPECIAL.CATARACTINFO.pLeftEyeIOLStickerNumber" />
        </el-col>
      </el-form-item>

      <el-form-item label="Left Eye IOL Expiry">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.CATARACTINFO.pLeftEyeIOLExpiryDate"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>

      <el-form-item label="Right Eye IOL Sticker No.">
        <el-col :span="12">
          <el-input v-model="claim.CF2.SPECIAL.CATARACTINFO.pRightEyeIOLStickerNumber" />
        </el-col>
      </el-form-item>

      <el-form-item label="Right Eye IOL Expiry">
        <el-col :span="12">
          <el-date-picker
            placeholder="Pick a date"
            v-model="claim.CF2.SPECIAL.CATARACTINFO.pRightEyeIOLExpiryDate"
            format="MM-dd-yyyy"
            value-format="MM-dd-yyyy"
            style="width: 100%;"
          />
        </el-col>
      </el-form-item>

    </template>

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
    CheckboxGroup,
    Checkbox,
    Option,
    Select,
    Switch,
    Button,
    Tooltip,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import MCPFactory from './Factory/MCP';
  import TBDOTSFactory from './Factory/TBDOTS';
  import ABPFactory from './Factory/ABP';
  import NCPFactory from './Factory/NCP';
  import HIVAIDSFactory from './Factory/HIVAIDS';
  import CATARACTINFOFactory from './Factory/CATARACTINFO';


  export default {

    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-date-picker': DatePicker,
      'el-time-picker': TimePicker,
      'el-form-item': FormItem,
      'el-input': Input,
      'el-checkbox-group': CheckboxGroup,
      'el-checkbox': Checkbox,
      'el-option': Option,
      'el-select': Select,
      'el-switch': Switch,
      'el-button': Button,
      'el-tooltip': Tooltip,
      'font-awesome-icon': FontAwesomeIcon,
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
        nst: false,
        packages: {
          MCP: { label: 'Maternity Care', selected: false },
          TBDOTS: { label: 'TB-DOTS', selected: false },
          ABP: { label: 'Animal Bite', selected: false },
          NCP: { label: 'Newborn Care', selected: false },
          HIVAIDS: { label: 'HIV AIDS', selected: false },
          CATARACTINFO: { label: 'Cataract', selected: false },
        },

        nhst: {
          id: ''
        },


        nhstlist: [
          {
            pass: 'P',
            nhstresult: 'PASS'
          },
          {
            pass: 'R',
            nhstresult: 'REFER'
          },
          {
            pass: 'X',
            nhstresult: 'NOT APPLICABLE'
          }
        ]
      };
    },

    computed: {
      specials() {
        return this.field('claim.CF2.SPECIAL', []);
      },
    },

    watch: {
      specials: {
        immediate: true,
        handler(value) {
          this.resetCheckboxes();
        }
      }
    },

    methods: {
      tick(toggle, key) {
        if (!toggle) {
          this.setField(`claim.CF2.SPECIAL.${key}`, this.getFactoryByName(key));
        } else {
          this.setField(`claim.CF2.SPECIAL.${key}`, this.getFactoryByName(key, true));
        }
      },
      resetCheckboxes() {
        Object.keys(this.packages).forEach((key) => {
          const special = JSON.stringify(this.field(`claim.CF2.SPECIAL.${key}`, {}));
          const factory = JSON.stringify(this.getFactoryByName(key));
          if (special !== factory) {
            this.packages[key].selected = true;
          } else {
            this.packages[key].selected = false;
          }
        });
      },
      getFactoryByName(name, withDefault = false) {
        let factory = null;
        switch (name) {
          case 'MCP':
            factory = MCPFactory();
            break;
          case 'TBDOTS':
            factory = TBDOTSFactory();
            break;
          case 'ABP':
            factory = ABPFactory();
            break;
          case 'NCP':
            factory = NCPFactory({}, withDefault);
            break;
          case 'HIVAIDS':
            factory = HIVAIDSFactory();
            break;
          case 'CATARACTINFO':
            factory = CATARACTINFOFactory();
            break;
          default:
            // console.error(`Unknown Special: ${name}`);
        }
        return factory;
      }

    },
  };
</script>

<style scoped>
  .sessions {
    margin-top: 2rem;
  }

  .sessions:first-of-type {
    margin-top: 0;
  }

.hint{
  font-size: 7px;
}

</style>

