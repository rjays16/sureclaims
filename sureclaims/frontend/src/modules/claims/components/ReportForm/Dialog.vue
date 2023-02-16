<template>
  <div>
    <!-- Dialog -->
    <el-dialog
      modal
      top="5vh"
      :visible.sync="innerVisible"
    >
      <template slot="title">
        <h5 class="m--regular-font-size-lg4" style="margin-bottom: 1rem">
          Transmittal Report
        </h5>
      </template>
      <div class="">

        <report-form
          :form-data="formData"
          :bus="bus"
        />

      </div>

      <span slot="footer" class="dialog-footer">
        <el-button
          type="text"
          @click="hide"
          class="btn btn-feature"
          style="min-width: 140px"
        >
          Close
        </el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import Vue from 'vue';
  import {
    Dialog,
    Button,
    Badge,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import ReportForm from './index';

  export default {
    name: 'report-dialog',

    mixins: [UsesLookups],

    components: {
      'el-dialog': Dialog,
      'el-button': Button,
      'el-badge': Badge,
      FontAwesomeIcon,
      'report-form': ReportForm,
    },

    props: {
      visible: {
        type: Boolean,
        default: false,
      },

      formData: {
        required: true,
        type: Object,
      },

      bus: {
        type: Object,
        default() {
          return new Vue();
        },
      },
    },

    data() {
      return {
        innerBus: new Vue(),

        closeOnSave: false,
        loading: 0,
        formSubmitting: false,

        report: {
          visible: false,
        },
      };
    },

    computed: {
      innerVisible: {
        get() {
          return this.visible;
        },

        set(value) {
          this.show(value);
        },
      },
    },

    methods: {
      show(toggle = true) {
        this.$emit('update:visible', toggle);
      },

      hide() {
        this.show(false);
      },
    },

    mounted() {
    },
  };
</script>

<style scoped>

</style>
