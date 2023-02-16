<template>
  <layout>
    <div class="m-datatable m-datatable--default m-datatable--brand m-datatable--loaded">
      <el-row type="flex" justify="space-between">
        <el-col :span="18">
          <!-- Search filters go here -->
          <el-row :gutter="24" type="flex">
            <el-col :span="12">
              <el-date-picker
                v-model="filters.dateRange"
                type="daterange"
                value-format="MM-dd-yyyy"
                format="MM-dd-yyyy"
                align="left"
                range-separator="-"
                start-placeholder="Period from ..."
                end-placeholder="To ..."
                style="width:100%;"
              />
            </el-col>
          </el-row>
        </el-col>
      </el-row>

      <el-row type="flex" justify="space-between">
        <el-table
          class="m--margin-top-20"
          :data="reports"
          v-loading="loading > 0"
          stripe
          max-height="400"
          empty-text="No claims found ..."
          highlight-current-row
          style="width: 100%"
        >
          <el-table-column
            min-width="50"
            label="No."
            align="center"
          >
            <template slot-scope="scope">
              <small v-if="!!scope.row.count">{{ scope.row.count }}</small>
            </template>
          </el-table-column>
          <el-table-column
            min-width="120"
            label="Report Name"
            align="center"
          >
            <template slot-scope="scope">
              <small v-if="!!scope.row.name">{{ scope.row.name }}</small>
            </template>
          </el-table-column>

          <el-table-column
            min-width="120"
            label="Action"
            align="center"
          >
            <template slot-scope="scope">
              <el-tooltip content="Print PDF" placement="top-start" transition="el-zoom-in-bottom">
                <el-button type="text" @click="showReport(scope.row.url, 'pdf')">
                  <font-awesome-icon :icon="['far', 'file-pdf']" size="lg" transform="shrink-2"/>
                </el-button>
              </el-tooltip>
              <el-tooltip content="Print EXCEL" placement="top-start" transition="el-zoom-in-bottom">
                <el-button type="text" @click="showReport(scope.row.url, 'excel')">
                  <font-awesome-icon :icon="['far', 'file-excel']" size="lg" transform="shrink-2"/>
                </el-button>
              </el-tooltip>
            </template>
          </el-table-column>

        </el-table>
      </el-row>
    </div>
  </layout>
</template>

<script>
  import Vue from 'vue';
  import _get from 'lodash/get';
  import {
    Table,
    TableColumn,
    Dialog,
    Input,
    Tooltip,
    Select,
    Option,
    DatePicker,
    Tag,
    Button,
    Row,
    Col,
    Form,
    FormItem
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import UsesLayout from '@/modules/core/mixins/UsesLayout';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import { url } from '@/helpers/url';
  import { mapState, mapActions } from 'vuex';
  import authService from '@/modules/auth/services/AuthService';

  export default {
    name: 'report-page',

    mixins: [
      UsesLayout,
      UsesLookups,
      SetsPageMeta,
      TransformsPersonData,
    ],

    components: {
      'el-dialog': Dialog,
      'el-table': Table,
      'el-table-column': TableColumn,
      'el-input': Input,
      'el-tooltip': Tooltip,
      'el-button': Button,
      'el-tag': Tag,
      'el-row': Row,
      'el-col': Col,
      'el-select': Select,
      'el-option': Option,
      'el-date-picker': DatePicker,
      'el-form': Form,
      'el-form-item': FormItem,
      'font-awesome-icon': FontAwesomeIcon,
    },

    data() {
      return {
        bus: new Vue(),

        item: 'report',
        reports: [
          {
            count: 1,
            name: 'Total No. of Transmittal Based on PHIC Category',
            url: 'totalPhicCategory'
          },
          // {
          //   count: 2,
          //   name: 'Summary of PHIC Claims Transmitted',
          //   url: 'transmittalByMonth'
          // },
        ],

        filters: {
          name: '',
          dateRange: '',
        },
      };
    },

    computed: {
      ...mapState('Auth', {
        user: state => state.user,
      }),
    },

    methods: {
      _get(object, path, defaultValue = null) {
        return _get(object, path, defaultValue);
      },

      showReport(apiUrl, format = 'pdf') {
        const account = this.user.nickname;
        if (this.filters.dateRange) {
          window.open(
            url(`document/${apiUrl}/${this.filters.dateRange}&${format}&${account}`),
            'mywindow',
            'status=1'
          );
        } else {
          this.$snotify.error('Date Range is empty', 'Oops!');
        }
      },
    },

    mounted() {
      if (!this.user) {
        authService.userInfo()
          .then(profile => this.login(profile))
          .catch((error) => {
            // this.$snotify.error(error, 'app-top-bar error');
          });
      }
    },
  };
</script>

<style scoped>
</style>
