<template>
  <div>
    <!-- Dialog -->
    <el-dialog
      top="5vh"
      fullscreen
      :visible.sync="innerVisible"
    >
      <template slot="title">
        <h5 class="m--regular-font-size-lg4" style="margin-bottom: 1rem">
          Claim Form 4 Details
        </h5>
      </template>
      <div class="">
        <claim-form
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
          disabled
          v-if="!formData.patient"
        >
          No patient selected
        </el-button>

        <el-button
          type="primary"
          class="btn btn-feature"
          style="min-width: 140px"
          @click="submitForm(true)"
          :disabled="formSubmitting"
          v-if="formData.patient"
        >
          <span v-if="!formSubmitting">Save & Close</span>
          <span v-if="formSubmitting">
            Saving
            <font-awesome-icon :icon="['far', 'spinner']" spin/>
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
  import ClaimForm4 from './index';

  export default {
    name: 'cf4-dialog',

    mixins: [TransformsPersonData, UsesLookups],

    components: {
      FontAwesomeIcon,
      'el-dialog': Dialog,
      'el-button': Button,
      'el-badge': Badge,
      'claim-form': ClaimForm4,
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
      CF4: {
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
        editing: false,
        showValidation: false,
      };
    },

    computed: {
      hasErrors() {
        return this.formData.validationErrors && this.formData.validationErrors.length > 0;
      },
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

      validationErrors() {
        this.showValidation = true;
      },

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
          this.bus.$emit('dialog.close');
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
    },
  };
</script>

<style scoped>

</style
