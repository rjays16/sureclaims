<template>
  <layout>
    <verify-pin-form />
  </layout>
</template>

<script>
  import UsesLayout from '@/modules/core/mixins/UsesLayout';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';
  import VerifyPinForm from '../components/VerifyPinForm';

  export default {
    mixins: [
      UsesLayout,
      SetsPageMeta,
    ],

    components: {
      'verify-pin-form': VerifyPinForm,
    },
  };
</script>