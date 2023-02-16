<template>
  <div>
    <div class="row">
      <div class="col-lg-12">
        <portlet
          title="Basic Portlet"
          subtitle="portlet sub title"
          air
        >
          <i slot="icon" class="flaticon-calendar"></i>
          Lorem Ipsum is simply dummy text of the printing and typesetting industry.
          Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
          when an unknown printer took a galley of type and scrambled. Lorem Ipsum is
          simply dummy text of the printing and typesetting industry. Lorem Ipsum has
          been the industry's standard dummy text ever since the 1500s, when an unknown
          printer took a galley of type and scrambled.
        </portlet>
      </div>
    </div>
  </div>
</template>

<script>
  //
  import Portlet from '@/components/Portlet';
  import SetsPageMeta from '@/modules/core/mixins/SetsPageMeta';

  export default {
    name: 'check-spc',
    components: {
      Portlet,
    },
    mixins: [SetsPageMeta],
    data() {
      return {
      };
    },
  };
</script>
