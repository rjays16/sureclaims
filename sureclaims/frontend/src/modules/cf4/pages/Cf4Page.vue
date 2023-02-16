<template>
  <layout>

    <index2 :bus="bus"/>

    <cf4-dialog
      :visible.sync="editing"
      :formData="editable.data"
      :CF4="editable.CF4"
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
            @click="edit()"
            class="btn btn-feature"
            style="width: 160px"
          >
            <span>Generate CF4</span>
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
  import Cf4Form from '../components/Cf4Form';
  import Cf4Dialog from '../components/Cf4Form/Dialog';
  import { CF4 as cf4Factory } from '../components/Cf4Form/Factory/index';
  import Index2 from '../components/Cf4Form/index2';

  export default {

    mixins: [
      UsesLayout,
      SetsPageMeta,
    ],

    components: {
      Index2,
      FontAwesomeIcon,
      'el-button': Button,
      'el-row': Row,
      'el-col': Col,
      'cf4-form': Cf4Form,
      'cf4-dialog': Cf4Dialog
    },

    data() {
      return {
        bus: new Vue(),
        formSubmitting: false,

        editing: false,
        editable: {
          data: {
            patient: null,
          },
          CF4: cf4Factory(),
        },
      };
    },

    mounted() {
      this.bus.$on('form.submitting', this.submitting);
      this.bus.$on('form.submitted', this.submitted);
      this.bus.$on('form.failed', this.failed);
    },

    methods: {
      edit(data = null) {
        if (data) {
          this.bus.$on('personData', (d) => {
            this.editable = {
              data: {
                id: d.id,
                selectedPatient: d.patient,
                patient: String(d.patientId),
              },
              CF4: JSON.parse(d.data),
            };
          });
        } else {
          this.editable = {
            data: {
              id: null,
              patient: null,
            },
            CF4: cf4Factory(),
          };
        }
        console.log('editable', this.editable);


        // this.editing = true;
      },

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
