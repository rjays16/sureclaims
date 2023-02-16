<template>
  <div>
    <el-row>
      <el-col :span="18">
        <el-form-item label="PhilHealth Member" prop="isMember">
          <el-switch
            v-model="isMember"
            :active-text="isMember ? 'This person is the PhilHealth member' : 'This person is a dependent'"
          />
        </el-form-item>

        <el-form-item 
          label="PIN" 
          prop="member.pin"
        >
          <el-input v-model="formData.member.pin">
            <el-button
              slot="append"
              @click="getPIN"
              :loading="loadingGetPIN"
            >
              Get PIN
            </el-button>
          </el-input>
          <span class="hint">PHIC Identification No</span>
        </el-form-item>

        <div v-show="!isMember">

          <el-form-item label="Select Primary Holder">
            <select-person
              v-model="formData.member.principalId"
              :selected="formData.member.principal"
              style="width: 100%"
              @selectedChanged="principalHolderChanged($event)"
            />
          </el-form-item>

          <el-form-item 
            label="Relation to the Member" 
            prop="member.relation"
            :rules="[{
              required: true,
              message: 'Please provide Relation', 
              trigger: 'blur'
            }]">
            <el-col :span="11">
              <el-select 
                v-model="formData.member.relation" 
                :disabled="isMember"
                placeholder="Select one ..."
              >
                <el-option
                  v-for="(item, key) in lookupTypes('dependent.relation')"
                  :key="key"
                  :label="item"
                  :value="key">
                </el-option>
              </el-select>
            </el-col>
          </el-form-item>

        </div>

        <template v-if="isMember">

          <el-form-item 
            label="Membership Type" 
            prop="member.membershipType"
            :rules="[{
              required: true, message: 'Please provide membership type', trigger: 'blur'
            }]"
          >
            <el-col :span="16">
              <el-select v-model="formData.member.membershipType" placeholder="Select">
                <el-option
                  v-for="(item, key) in lookupTypes('member.membershipType')"
                  :key="key"
                  :label="item"
                  :value="key">
                </el-option>
              </el-select>
            </el-col>
            <br>
            <span class="hint">Select only the membership type what your hospital is actually using.</span>
          </el-form-item>

          <template v-if="['S', 'G', 'K'].includes(formData.member.membershipType)">
            <el-form-item 
              label="Search Employer"
            >
              <select-employer
                v-model="formData.member.employerQuery"
                style="width: 100%"
                placeholder="(Optional)"
                @selectedChanged="employerSelectedChanged($event)"
              />
              <span class="hint">Must be at least 7 characters to search</span>
              <div>
                <el-alert
                  title="Fields below can be filled manually or by searching"
                  type="info"
                  show-icon
                  class="mt-2"
                  style="display: initial;"
                  :closable="false">
                </el-alert>
              </div>
            </el-form-item>
            <el-form-item 
              label="PEN" 
              prop="member.pen"
              :rules="[{
                required: true, message: 'Please provide Employer PEN', trigger: 'blur'
              }]"
            >
              <el-col :span="16">
                <el-input v-model="formData.member.pen"></el-input>
                <span class="hint">PhilHealth Employer No</span>
              </el-col>
            </el-form-item>

            <el-form-item 
              label="Employer" 
              prop="member.employerName"
              :rules="[{
                required: true, message: 'Please provide Employer Name', trigger: 'blur'
              }]"
            >
              <el-col :span="16">
                <el-input v-model="formData.member.employerName"></el-input>
              </el-col>
            </el-form-item>
          </template>

        </template>
      </el-col>
      <el-col :span="6" class="m--padding-20">
        <el-collapse-transition>
          <template v-if="pinError">
            <aside class="form-description">
              <div
                class="alert alert-danger m-alert m-alert--outline"
                role="alert"
              >
                <h5>No Member PIN Found</h5>
                <template v-if="typeof pinError === 'string'">
                  <strong>{{pinError}}</strong>
                </template>
                <template v-else>
                  <strong>Correctly fill the following fields:</strong>
                  <ul>
                    <li v-for="message,key in pinError" :key="key">
                      {{message}}
                    </li>
                  </ul>
                </template>
              </div>
            </aside>
          </template>
        </el-collapse-transition>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import {
    Row,
    Col,
    FormItem,
    Select,
    Switch,
    Option,
    Input,
    Alert,
    Button,
  } from 'element-ui';
  import CollapseTransition from 'element-ui/lib/transitions/collapse-transition';
  import fontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import _flatmap from 'lodash/flatMap';

  import SelectPerson from '@/modules/core/components/form/SelectPerson';
  import SelectEmployer from '@/modules/core/components/form/SelectEmployer';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import BaseComponent from '@/modules/core/mixins/BaseComponent';

  import VERIFY_PIN_MUTATION from '@/modules/eligibility/graphql/mutations/VerifyPinMutation.gql';
  import { formatError } from '@/helpers/graphql';

  export default {
    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form-item': FormItem,
      'el-input': Input,
      'el-select': Select,
      'el-switch': Switch,
      'el-option': Option,
      'el-alert': Alert,
      'el-button': Button,
      'el-collapse-transition': CollapseTransition,
      'font-awesome-icon': fontAwesomeIcon,
      'select-person': SelectPerson,
      'select-employer': SelectEmployer,
    },

    props: ['formData'],

    mixins: [TransformsPersonData, UsesLookups, BaseComponent],

    data() {
      return {
        member: null,
        personsPage: {},
        pinError: null,
        loadingGetPIN: false
      };
    },

    computed: {
      isMember: {
        get() {
          return this.formData.member.relation === 'M';
        },

        set(value) {
          if (value) {
            this.formData.member.relation = 'M';
          } else {
            this.formData.member.relation = null;
          }
        },
      }
    },

    methods: {
      getPIN() {
        this.pinError = null;
        this.formData.member.pin = null;
        this.loadingGetPIN = true;

        this.$apollo.mutate({
          mutation: VERIFY_PIN_MUTATION,
          variables: this.formData,
        }).then(({ data: { verifyPin } }) => {
          this.formData.member.pin = verifyPin;
        }).catch(({ graphQLErrors: [{ message, validation }] }) => {
          if (message === 'validation') {
            this.pinError = _flatmap(validation);
          } else {
            this.pinError = message;
          }
        }).then(() => {
          this.loadingGetPIN = false;
        });
      },
      principalHolderChanged(value) {
        const pin = value.member.pin || '';
        this.pinError = null;
        if (!pin) {
          this.pinError = 'Selected principal person has no PIN';
        }
        if (this.formData.member.pin) {
          this.prompOverwriteMemberPin(pin);
        } else {
          this.formData.member.pin = pin;
        }
      },
      employerSelectedChanged(employer) {
        this.formData.member = Object.assign(this.formData.member, {
          pen: employer.pen,
          employerName: employer.name
        });
      },
      prompOverwriteMemberPin(pin) {
        this.$confirm('Do you want to overwrite the PIN from the selected primary holder?', 'Warning', {
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
          type: 'warning'
        }).then(() => {
          this.formData.member.pin = pin;
        });
      }
    }
  };

</script>
