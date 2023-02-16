<template>
  <layout>

    <member-form
      :bus="bus"
      :form-data="formData"
    />

    <hr />

    <el-row>
      <el-col :span="16" :xs="24">
        <div class="m--align-right">
          <el-button
            type="text"
            style="font-size: 1.05rem; padding: 1em; width: 160px"
            @click="membersList"
          >
            Return to List
          </el-button>

          <el-button
            type="primary"
            @click="submitForm"
            style="font-size: 1.05rem; padding: 1em; width: 160px"
            :disabled="formSubmitting"
          >
            <span v-if="!formSubmitting">Create</span>
            <span v-if="formSubmitting">
                Creating
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
  import { Button, Row, Col } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import UsesLayout from '@/modules/core/mixins/UsesLayout';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';

  import MemberForm from '../components/MemberForm';

  export default {
    name: 'member-list-view',

    props: {
      formData: {
        type: Object,
      },
    },

    mixins: [
      UsesLayout,
      UsesLookups,
      SetsPageMeta,
      TransformsPersonData,
    ],

    components: {
      FontAwesomeIcon,
      'el-button': Button,
      'el-row': Row,
      'el-col': Col,

      'member-form': MemberForm,
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
        this.membersList();
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
