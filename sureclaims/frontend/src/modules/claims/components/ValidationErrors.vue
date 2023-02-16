<template>
  <div class="validation-errors--container">
    <!-- Dialog -->
    <el-dialog
      width="80%"
      top="5vh"
      :visible.sync="innerVisible"
    >
      <template slot="title">
        <h5 class="m--regular-font-size-lg4" style="margin-bottom: 0">
          Validation Summary
        </h5>
      </template>

      <div class="m--padding-left-40 m--padding-right-40">

        <el-tabs class="" v-model="active">
          <el-tab-pane name="xml">
            <span slot="label">
              View XML
            </span>
            <div class="m--padding-top-20 m--padding-bottom-20">
              <codemirror
                :value="xml"
                :options="codemirrorOptions"
              />
            </div>
          </el-tab-pane>

          <el-tab-pane name="errors" v-if="errors && errors.length">
            <span slot="label">
              <el-badge :value="errors.length">
                Validation Errors
              </el-badge>
            </span>
            <div class="m--padding-top-20 m--padding-bottom-20">
              <ol>
                <li :key="index" v-for="(error, index) in errors">
                  <span v-html="error"></span>
                </li>
              </ol>
            </div>
          </el-tab-pane>
        </el-tabs>
      </div>

      <span slot="footer" class="dialog-footer">
        <el-button
          type="text"
          @click="hide"
          class="btn btn-feature"
          style="min-width: 120px"
        >
          Close
        </el-button>
        <a :href="xmlLink" target="_blank">
          <el-button
            class="btn btn-feature"
            style="min-width: 120px"
          >
            Download XML File
          </el-button>
        </a>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import 'codemirror/mode/xml/xml';
  import 'codemirror/theme/duotone-light.css';

  import {
    Dialog,
    Button,
    Tabs,
    TabPane,
    Row,
    Col,
    Badge,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';

  export default {
    name: 'validation-errors',

    mixins: [],

    components: {
      FontAwesomeIcon,
      'el-dialog': Dialog,
      'el-button': Button,
      'el-tabs': Tabs,
      'el-tab-pane': TabPane,
      'el-row': Row,
      'el-col': Col,
      'el-badge': Badge,
    },

    props: {
      xmlLink: {
        type: String
      },
      visible: {
        type: Boolean,
        default: false,
      },
      xml: {
        type: String,
      },
      errors: {
        type: Array,
        default: () => []
      },
    },

    data() {
      return {
        active: 'xml',
        loading: 0,
        formSubmitting: false,
        editing: false,
        codemirrorOptions: {
          lineWrapping: true,
          theme: 'duotone-light',
          styleActiveLine: true,
          lineNumbers: true,
          line: true,
          readOnly: true,
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

    watch: {
      innerVisible () {
        if (!this.errors) {
          this.active = 'xml';
        }
      }
    },
  };
</script>

<style scoped>
</style>
