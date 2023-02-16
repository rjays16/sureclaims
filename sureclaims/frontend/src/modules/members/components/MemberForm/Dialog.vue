<template>
  <div>
    <!-- Dialog -->
    <el-dialog
      v-if="formData"
      top="5vh"
      width="80%"
      :visible.sync="innerVisible"
      v-on:close="close"
    >
      <template slot="title">
        <h5 class="m--regular-font-size-lg4" style="margin-bottom: 0">
          {{ fullname(formData) }}
        </h5>
        <span class="text-muted">
          {{ formData.sex ? lookup('sex', formData.sex) : 'Not indicated' }},
          {{ formData.birthDate ? age(formData) : 'No birth date indicated' }}
        </span>
      </template>

      <div class="m--padding-left-40 m--padding-right-40">
        <member-form
          :form-data="formData"
          :bus="bus"
        />
      </div>

      <span slot="footer" class="dialog-footer">
        <el-button
          type="text"
          @click="hide"
          class="btn btn-feature"
          style="width: 160px"
        >
          Close
        </el-button>
        <el-button
          type="primary"
          class="btn btn-feature"
          style="width: 160px"
          @click="submitForm"
          :disabled="formSubmitting"
        >
          <span v-if="!formSubmitting">Save</span>
          <span v-if="formSubmitting">
            Saving
            <font-awesome-icon v-if :icon="['far', 'spinner']" spin />
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
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import MemberForm from './index';

  export default {
    name: 'member-form-dialog',

    props: {
      visible: {
        type: Boolean,
        default: false,
      },
      formData: {
        type: Object,
      },
      bus: {
        type: Object,
        default() {
          return new Vue();
        },
      },
    },

    mixins: [TransformsPersonData, UsesLookups],

    components: {
      'el-dialog': Dialog,
      'el-button': Button,
      FontAwesomeIcon,
      MemberForm,
    },

    data() {
      return {
        innerBus: new Vue(),
        loading: 0,
        formSubmitting: false,
        editing: false,
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

      submitForm() {
        this.bus.$emit('form.submit');
      },

      submitting() {
        this.formSubmitting = true;
      },

      submitted() {
        this.formSubmitting = false;
      },

      failed() {
        this.formSubmitting = false;
      },

      show(toggle = true) {
        this.$emit('update:visible', toggle);
      },

      hide() {
        this.show(false);
        this.bus.$emit('form.closed');
      },

      close() {
        this.bus.$emit('form.closed');
      }
    },

    mounted() {
      this.bus.$on('form.submitting', this.submitting);
      this.bus.$on('form.submitted', this.submitted);
      this.bus.$on('form.failed', this.failed);
    },
  };
</script>