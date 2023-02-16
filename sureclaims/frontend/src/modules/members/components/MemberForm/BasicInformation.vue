<template>
  <div class="m--padding-20 m--padding-top-30">

    <el-form-item label="Last Name" prop="lastName">
      <el-input v-model="formData.lastName" />
    </el-form-item>

    <el-form-item label="First Name" prop="firstName">
      <el-input v-model="formData.firstName" />
    </el-form-item>

    <el-form-item label="Middle Name" prop="middleName">
      <el-input v-model="formData.middleName" />
    </el-form-item>

    <el-form-item label="Suffix" prop="suffix">
      <el-col :span="30">
        <el-input v-model="formData.suffix" />
      </el-col>
    </el-form-item>

    <el-form-item label="Sex" prop="sex">
      <el-col :span="30">
        <el-select v-model="formData.sex" placeholder="Select">
          <el-option
            v-for="(item, key) in lookupTypes('sex')"
            :key="key"
            :label="item"
            :value="key">
          </el-option>
        </el-select>
      </el-col>
    </el-form-item>

    <el-form-item label="Birth Date" prop="birthDate">
      <el-col :span="30 ">
        <el-date-picker
          type="date"
          editable
          placeholder="Pick a date"
          v-model="formData.birthDate"
          format="MM-dd-yyyy"
          value-format="MM-dd-yyyy"
          style="width: 100%;"
        >
        </el-date-picker>
      </el-col>
      <span class="hint">
        {{ formData.birthDate ? 'Age: '+'('+age(formData)+')' : '' }}
      </span>
    </el-form-item>

  </div>
</template>

<script>

  // import moment from moment;
  import {
    Row,
    Col,
    Form,
    FormItem,
    Input,
    DatePicker,
    Option,
    Select,
    Button,
  } from 'element-ui';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';
  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import moment from 'moment';

  export default {
    mixins: [TransformsPersonData, UsesLookups],

    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-date-picker': DatePicker,
      'el-form-item': FormItem,
      'el-input': Input,
      'el-option': Option,
      'el-select': Select,
      'el-button': Button,
    },

    props: ['formData'],

    data() {
      return {
      };
    },

    computed: {
    },

    methods: {
    },
  };

</script>
