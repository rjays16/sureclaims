<template>
  <div>
    Chief Complaint
  </div>
</template>

<script>
  import _get from 'lodash/get';
  import _has from 'lodash/has';
  import {
    Row,
    Col,
    Form,
    FormItem,
    Input,
    DatePicker,
    Option,
    Select,
    Switch,
    Button,
    Tooltip,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import BaseComponent from '@/modules/core/mixins/BaseComponent';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  export default {

    mixins: [BaseComponent, UsesLookups],

    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-date-picker': DatePicker,
      'el-form-item': FormItem,
      'el-input': Input,
      'el-option': Option,
      'el-select': Select,
      'el-switch': Switch,
      'el-button': Button,
      'el-tooltip': Tooltip,
      'font-awesome-icon': FontAwesomeIcon,
    },

    props: {
      formData: {
        required: true,
        type: Object,
      },
      CF4: {
        required: true,
        type: Object,
      },
    },

    computed: {
    },

    methods: {
    },
  };

</script>
