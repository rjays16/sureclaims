<template>
  <div>
    <template v-for="(item, index) in items">
      <el-form-item class="data-view--data" :label="item.label">
        <span class="data-view--value">
          <template v-if="item.value === ''">
            <span v-if="!$slots.empty">
              <em class="text-muted">Not set</em>
            </span>
            <slot name="empty" />
          </template>
          <template v-else>
            {{ item.value }}
          </template>
        </span>
      </el-form-item>
    </template>
  </div>
</template>

<script>

  import {
    Row,
    Col,
    FormItem,
  } from 'element-ui';

  import BaseComponent from '@/modules/core/mixins/BaseComponent';

  export default {

    components: {
      'el-row': Row,
      'el-col': Col,
      'el-form-item': FormItem,
    },

    mixins: [BaseComponent],

    props: ['viewData'],

    computed: {
      items() {
        return Object.keys(this.viewData).map(label => ({
          label,
          value: this.viewData[label],
        }));
      },
    },

  };

</script>

<style scoped>

  .data-view--data {
    margin-bottom: 1rem;
    line-height: 20px;
  }

  .data-view--data >>> .el-form-item__label {
    line-height: 20px;
  }

  .data-view--data >>> .el-form-item__content {
    line-height: 20px;
  }

</style>