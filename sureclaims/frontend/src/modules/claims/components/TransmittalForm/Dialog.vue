<template>
  <div>

    <select-claims-dialog
      :bus="bus"
      :form-data="formData"
      :visible.sync="selectClaims.visible"
    />

    <!-- Dialog -->
    <el-dialog
      fullscreen
      top="5vh"
      :visible.sync="innerVisible"
    >
      <template slot="title">
        <h5 class="m--regular-font-size-lg4" style="margin-bottom: 1rem">
          Transmit Claims
        </h5>
      </template>
      <div class="">

        <transmittal-form
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

        <el-button
          type="primary"
          class="btn btn-feature"
          style="min-width: 140px"
          @click="submitForm(true)"
          :disabled="formSubmitting"
        >
          <span v-if="!formSubmitting">Save & Close</span>
          <span v-if="formSubmitting">
            Saving
            <font-awesome-icon :icon="['far', 'spinner']" spin />
          </span>
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
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import TransmittalForm from './index';
  import SelectClaimsDialog from './SelectClaimsDialog';

  export default {
    name: 'transmittal-dialog',

    mixins: [TransformsPersonData, UsesLookups],

    components: {
      'el-dialog': Dialog,
      'el-button': Button,
      'el-badge': Badge,
      FontAwesomeIcon,
      'transmittal-form': TransmittalForm,
      'select-claims-dialog': SelectClaimsDialog,
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

        selectClaims: {
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

      submitForm(closeOnSave) {
        this.closeOnSave = closeOnSave;
        this.bus.$emit('form.submit');
      },

      submitting() {
        this.formSubmitting = true;
      },

      submitted() {
        this.formSubmitting = false;
        if (this.closeOnSave) {
          this.$emit('update:visible', false);
        }
      },

      failed() {
        this.formSubmitting = false;
      },

      show(toggle = true) {
        this.$emit('update:visible', toggle);
      },

      hide() {
        this.show(false);
      },
    },

    mounted() {
      this.bus.$on('form.submitting', this.submitting);
      this.bus.$on('form.submitted', this.submitted);
      this.bus.$on('form.failed', this.failed);
      this.bus.$on('select.claims', () => {
        this.selectClaims.visible = true;
      });
    },
  };
</script>

<style scoped>

</style>