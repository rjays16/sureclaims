<template>
  <layout>
    <check-eligbility-form
      :bus="bus"
    />


    <el-row>
      <el-col :span="16" :xs="24">
        <hr style="margin: 2rem 0"/>
        <div class="m--align-right">
          <el-button
            type="text"
            class="btn btn-feature"
            style="width: 160px"
            @click="membersList"
          >
            Return to List
          </el-button>

          <el-button
            type="primary"
            @click="submitForm"
            class="btn btn-feature"
            style="width: 160px"
            :disabled="formSubmitting"
          >
            <span v-if="!formSubmitting">Check</span>
            <span v-if="formSubmitting">
                Checking
                <font-awesome-icon v-if :icon="['far', 'spinner']" spin />
              </span>
          </el-button>
        </div>
      </el-col>
    </el-row>


  </layout>
</template>

<script>
  import Vue from 'vue';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import { Button, Row, Col } from 'element-ui';

  import UsesLayout from '@/modules/core/mixins/UsesLayout';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';
  import CheckEligibilityForm from '../components/CheckEligibilityForm';

  export default {

    mixins: [
      UsesLayout,
      SetsPageMeta,
    ],

    components: {
      FontAwesomeIcon,
      'el-button': Button,
      'el-row': Row,
      'el-col': Col,
      'check-eligbility-form': CheckEligibilityForm,
    },

    data() {
      return {
        bus: new Vue(),
        formSubmitting: false,
      };
    },

    mounted() {
      this.bus.$on('form.submitting', this.submitting);
      this.bus.$on('form.submitted', this.submitted);
      this.bus.$on('form.failed', this.failed);
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

      membersList() {
        this.$router.push({ name: 'members-list' });
      },

    },
  };
</script>