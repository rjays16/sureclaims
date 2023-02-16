<template>
  <section>
    <h1>Repetitive Procedures</h1>

    <el-form-item label="Procedures">
      <template v-for="counter in 3">
        <el-row :key="counter">
          <template v-for="key in Object.keys(specialProcedures).slice((counter - 1) * 3, counter * 3)">
            <el-col :span="8" :key="key">
              <el-form-item>
                <el-checkbox
                  v-model="specialProcedures[key].selected"
                  @change="toggle => tick(key, toggle)"
                >
                  {{ specialProcedures[key].label }}
                </el-checkbox>
              </el-form-item>
            </el-col>
          </template>
        </el-row>
      </template>
    </el-form-item>

    <template v-for="(procedure, key) in specialProcedures" v-if="procedure.selected">
      <hr />
      <el-form-item
        :label="procedure.label"
        label-width="250px"
      >
        <el-row>
          <el-col :span="14" class="sessions">
            <template v-for="(session, sessionIndex) in field(`claim.CF2.SPECIAL.PROCEDURES.${key}.SESSIONS`, [])">
              <transition name="el-fade-in">
                <el-form-item label-width="30px">
                  <span :key="sessionIndex" slot="label" style="text-align: center">
                    <el-tooltip content="Remove this session" placement="top-start">
                      <el-button type="text" @click="unmake(key, sessionIndex)">
                        <font-awesome-icon :icon="['far', 'times']" />
                      </el-button>
                    </el-tooltip>
                  </span>
                  <el-form-item>
                    <el-date-picker
                      class="m--margin-bottom-10"
                      placeholder="Date of session"
                      v-model="session.pSessionDate"
                      format="MM-dd-yyyy"
                      value-format="MM-dd-yyyy"
                      style="width: 100%;"
                    />
                  </el-form-item>
                </el-form-item>
              </transition>
            </template>

            <el-form-item label-width="30px">
              <el-badge
                :value="field(`claim.CF2.SPECIAL.PROCEDURES.${key}.SESSIONS`, []).length"
              >
                <el-button
                  plain
                  size="small"
                  @click="make(key)"
                >
                  <font-awesome-icon :icon="['far', 'plus']" />
                  Add more session dates
                </el-button>
              </el-badge>
            </el-form-item>
          </el-col>
        </el-row>

      </el-form-item>
    </template>

    <br />
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
    Badge,
    Select,
    Switch,
    Button,
    Tooltip,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import factory from './Factory/SESSIONS';

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
      'el-badge': Badge,
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

      procedure: {
        type: String,
        required: false,
      },

      factory: {
        type: Function,
        required: false,
      },

      title: {
        type: String,
        required: false,
      },
    },

    data() {
      return {
        specialProcedures: {
          HEMODIALYSIS: { label: 'Hemodialysis', selected: false },
          PERITONEAL: { label: 'Peritoneal', selected: false },
          LINAC: { label: 'Radiotherapy (LINAC)', selected: false },
          COBALT: { label: 'Radiotherapy (COBALT)', selected: false },
          TRANSFUSION: { label: 'Blood Transfusion', selected: false },
          BRACHYTHERAPHY: { label: 'Brachytherapy', selected: false },
          CHEMOTHERAPY: { label: 'Chemotherapy', selected: false },
          DEBRIDEMENT: { label: 'Simple Debridement', selected: false },
          IMRT: { label: 'IMRT', selected: false },
        },
      };
    },

    computed: {
      procedures() {
        return this.field('claim.CF2.SPECIAL.PROCEDURES');
      }
    },

    watch: {
      procedures(value) {
        this.resetCheckboxes();
      }
    },

    methods: {
      make(key) {
        const sessions = this.field(`claim.CF2.SPECIAL.PROCEDURES.${key}.SESSIONS`, null);
        if (sessions) {
          sessions.push(factory());
        }
      },

      unmake(key, index) {
        const sessions = this.field(`claim.CF2.SPECIAL.PROCEDURES.${key}.SESSIONS`, null);
        if (sessions) {
          sessions.splice(index, 1);
        }
      },

      tick(key, toggle) {
        if (!toggle) {
          const sessions = this.field(`claim.CF2.SPECIAL.PROCEDURES.${key}.SESSIONS`, []);
          sessions.splice(0, sessions.length);
        }
      },

      resetCheckboxes() {
        Object.keys(this.specialProcedures).forEach((key) => {
          if (this.field(`claim.CF2.SPECIAL.PROCEDURES.${key}.SESSIONS`, []).length > 0) {
            this.specialProcedures[key].selected = true;
          } else {
            this.specialProcedures[key].selected = false;
          }
        });
      }
    },

    mounted() {
      this.resetCheckboxes();
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
</style>

