import Vue from 'vue';
import {
  Alert,
} from 'element-ui';

export default {
  methods: {
    messageNotify () {
      this.error = true;
    },

    MessageClose () {
      this.error = false;
      this.success = false;
    },
  },

  data() {
    return {
      error: false,
      success: false,
      message: '',
    };
  },

  components: {
    'el-alert': Alert,
  },
};
