<template>
  <div class="card">
    <div class="card-body">
      <span>{{caseRate.itemCode}}</span>
      <span class="h6 m--font-transform-u text-primary pull-right">{{caseRate.type}}</span>
      <p class="small">{{caseRate.itemDescription}}</p>
      <span>{{caseRate.caseRateCode}}</span>
      <small>(case rate code)</small>
      <p class="small">{{caseRate.caseRateDescription}}</p>
      <span class="m--icon-font-size-lg1 text-success m--font-bolder">{{caseRate.amount | currency}}</span>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      caseRate: {
        type: Object,
        required: true
      }
    }
  };
</script>

<style scoped>
  .card .card-body {
    line-height: 24px;
  }
</style>