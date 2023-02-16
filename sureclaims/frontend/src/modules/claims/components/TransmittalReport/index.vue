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
            <el-form-item label="All Category">
              <el-input
                v-model="formData.transmittalNo"
                placeholder="Transmittal No"
                :maxlength="20"
                :disabled="!!formData.id"
              />
            </el-form-item>
          </el-col>
          <el-col :span="5" style="margin: 0 auto">
            <el-form-item label="PDF">
              <el-button
                plain
                class="btn"
                @click="byTransmittalPdf"
              >
                <font-awesome-icon :icon="['far', 'file-pdf']" />
                Print PDF
              </el-button>
            </el-form-item>
          </el-col>
          <el-col :span="5" style="margin: 0 auto">
            <el-form-item label="EXCEL">
              <el-button
                plain
                class="btn"
                @click="byTransmittalExcel"
              >
                <font-awesome-icon :icon="['far', 'file-excel']" />
                Print Excel
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
          <el-col :span="10">
            <el-form-item label="By Category" >
              <el-select v-model="selectedCategory" placeholder="Select">
                <el-option
                  v-for="item in options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>
            </el-form-item>

          </el-col>
          <el-col :span="5" style="margin: 0 auto">
            <el-form-item label="PDF">
              <el-button
                plain
                class="btn"
                @click="perCategory(selectedCategory)"
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
        options: [{
          value: 'S',
          label: 'PRIVATE EMPLOYED'
        }, {
          value: 'G',
          label: 'GOVERNMENT EMPLOYED'
        }, {
          value: 'NS',
          label: 'SELF-EMPLOYED'
        }, {
          value: 'P',
          label: 'LIFETIME MEMBER'
        }, {
          value: 'NO',
          label: 'OFW-MEMBER'
        }, {
          value: 'I',
          label: 'SPONSORED MEMBER'
        }, {
          value: 'SC',
          label: 'SENIOR CITIZEN'
        }],
        selectedCategory: '',
        pdf: false,
      };
    },

    methods: {
      _get(object, path, defaultValue = null) {
        return _get(object, path, defaultValue);
      },

      perCategory(category = null, pdf = true) {
        // console.log(this.selectedCategory);
        if (category) {
          if (pdf) {
            window.open(url(`document/transmittalByCategory/${this.formData.id}-${this.selectedCategory}`), 'mywindow', 'status=1');
          } else {
            window.open(url(`document/transmittalByCategory/${this.formData.id}-${this.selectedCategory}`), 'mywindow', 'status=1');
          }
        }
      },

      byTransmittalPdf() {
        window.open(url(`document/byTransmittalPdf/${this.formData.id}`), 'mywindow', 'status=1');
      },

      byTransmittalExcel() {
        window.open(url(`document/byTransmittalExcel/${this.formData.id}`), 'mywindow', 'status=1');
      },
    },

    mounted() {
    },
  };

</script>

<style scoped>
  section >>> h1 {
    font-size: 1rem;
    font-weight: 600;
    /* border-bottom: 1px solid #ededed; */
    border-bottom: 6px solid #f7f7f7;
    padding-bottom: 1rem;
    margin-bottom: 2rem;
    color: #909399;
    text-transform: uppercase;
  }
</style>
