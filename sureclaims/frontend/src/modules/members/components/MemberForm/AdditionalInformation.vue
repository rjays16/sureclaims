<template>
  <div class="m--padding-20 m--padding-top-30">
    <el-form-item label="Mailing Address" prop="mailingAddress">
      <el-input type="textarea" v-model="formData.mailingAddress" :rows="3" :maxlength="150" ></el-input>
    </el-form-item>

    <el-form-item label="Zip Code" prop="zipCode">
      <el-col :span="30">
        <el-input v-model="formData.zipCode" :maxlength="4"></el-input>
      </el-col>
    </el-form-item>

    <el-form-item label="E-mail Address" prop="emailAddress">
      <el-col :span="30">
        <el-input v-model="formData.emailAddress"></el-input>
      </el-col>
    </el-form-item>

    <el-form-item label="Land Line No." prop="landLineNo">
      <el-col :span="30">
        <el-input v-model="formData.landLineNo"></el-input>
      </el-col>
    </el-form-item>

    <el-form-item label="Mobile Line No." prop="mobileNo">
      <el-col :span="30">
        <el-input v-model="formData.mobileNo"></el-input>
      </el-col>
    </el-form-item>

  </div>
</template>

<script>

  import {
    Row,
    Col,
    FormItem,
    Input,
  } from 'element-ui';

  export default {
    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form-item': FormItem,
      'el-input': Input,
    },

    props: ['formData'],

    data() {
      return {
      };
    },

    methods: {
    },
  };

</script>
