<template>
  <div class="m--padding-top-20 m--padding-left-40 m--padding-right-40">
    <section>
      <el-form
        :disabled="!!loading"
        :model="formData"
        inline-message
        status-icon
        ref="form"
        label-width="200px"
        label-position="top"
      >
        <el-row :gutter="20">
          <el-col :span="10">
            <el-form-item label="By Month">
              <el-date-picker
                v-model="month"
                type="month"
                placeholder="Pick a month"
                value-format="MM-yyyy"
              >
              </el-date-picker>
            </el-form-item>
          </el-col>
          <el-col :span="5" style="margin: 0 auto">
            <el-form-item label="PDF">
              <el-button
                plain
                class="btn"
                @click="openReportByMonth"
              >
                <font-awesome-icon :icon="['far', 'file-pdf']" />
                Print PDF
              </el-button>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </section>

    <section>
      <el-form
        inline-message
        status-icon
        label-width="200px"
        label-position="top"
      >
        <el-row :gutter="20">
          <el-col :span="15">
            <el-form-item label="By Month Range" >
              <el-date-picker
                v-model="monthRange"
                type="daterange"
                format="yyyy-MM"
                value-format="yyyy-MM"
                align="right"
                unlink-panels
                range-separator="-"
                start-placeholder="Period from ..."
                end-placeholder="To ..."
                style="width:100%;"
              />
            </el-form-item>

          </el-col>
          <el-col :span="5" style="margin: 0 auto">
            <el-form-item label="PDF">
              <el-button
                plain
                class="btn"
                @click="openReportByMonthRange"
              >
                <font-awesome-icon :icon="['far', 'file-pdf']" />
                Print PDF
              </el-button>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
    </section>

    <br />
  </div>
</template>

<script>
  import _get from 'lodash/get';
  import {
    Table,
    TableColumn,
    Row,
    Col,
    Form,
    FormItem,
    Tooltip,
    Input,
    Tag,
    DatePicker,
    Button,
    Select,
    Option,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import EmptyState from '@/modules/core/components/EmptyState';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import { url } from '@/helpers/url';

  export default {
    name: 'transmittal-report-form',

    components: {
      'el-table': Table,
      'el-table-column': TableColumn,
      'el-tooltip': Tooltip,
      'el-button': Button,
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-form-item': FormItem,
      'el-date-picker': DatePicker,
      'el-input': Input,
      'el-tag': Tag,
      'el-select': Select,
      'el-option': Option,
      'font-awesome-icon': FontAwesomeIcon,
      'empty-state': EmptyState,
    },

    mixins: [UsesLookups],

    props: {
      formData: {
        required: true,
        type: Object,
      },

      bus: {
        type: Object,
        required: true,
      },
    },

    data() {
      return {
        loading: 0,
        month: '',
        monthFrom: '',
        monthTo: '',
        monthRange: [],
      };
    },

    methods: {
      _get(object, path, defaultValue = null) {
        return _get(object, path, defaultValue);
      },

      openReportByMonth() {
        if (this.month) {
          window.open(url(`document/transmittalByMonth/${this.month}`), 'mywindow', 'status=1');
        }
      },

      openReportByMonthRange() {
        if (this.monthRange) {
          window.open(url(`document/transmittalByMonthRange/${this.monthRange}`), 'mywindow', 'status=1');
        }
      },
    },

    mounted() {
    },
  };

</script>

<style scoped>

</style>
