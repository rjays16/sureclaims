<template>
  <layout>
    <router-view />
  </layout>
</template>

<script>

  import DefaultLayout from '@/modules/core/layouts/DefaultLayout';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';

  export default {
    components: {
      layout: DefaultLayout,
    },

    mixins: [SetsPageMeta],

    props: {
    },

  };
</script>
