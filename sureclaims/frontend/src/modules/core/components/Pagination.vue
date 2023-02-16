<template>
  <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
    <ul class="m-datatable__pager-nav" v-if="lastPage > 1">
      <li>
        <a
          title="First"
          :class="[
                'm-datatable__pager-link m-datatable__pager-link--first',
                {
                  'm-datatable__pager-link--disabled': currentPage <= 1
                }
              ]"
          :disabled="currentPage <= 1"
          @click="toFirstPage"
        ><i class="la la-angle-double-left"></i></a>
      </li>
      <li>
        <a
          title="Previous"
          :class="[
                'm-datatable__pager-link m-datatable__pager-link--prev',
                {
                  'm-datatable__pager-link--disabled': currentPage <= 1
                }
              ]"
          :disabled="currentPage <= 1"
          @click="toPrevPage"
        >
          <i class="la la-angle-left"></i>
        </a>
      </li>
      <li>
        <a
          title="More pages"
          :class="[
                'm-datatable__pager-link m-datatable__pager-link--more-prev',
                {
                  'm-datatable__pager-link--disabled': pageLinkStart <= 1
                }
              ]"
          :disabled="pageLinkStart <= 1"
          @click="prevPageLinks"
        >
          <i class="la la-ellipsis-h"></i>
        </a>
      </li>
      <li v-if="false">
        <input type="text" class="m-pager-input form-control" title="Page number" >
      </li>
      <template v-for="linkNumber in linkNumbers">
        <li :key="linkNumber">
          <a
            :class="[
                  'm-datatable__pager-link m-datatable__pager-link-number',
                  {
                    'm-datatable__pager-link--active': (linkNumber === currentPage),
                  }
                ]"
            :title="linkNumber"
            @click="() => { toPage(linkNumber) }"
          >
            {{ linkNumber }}
          </a>
        </li>
      </template>

      <li>
        <a
          title="More pages"
          :class="[
                'm-datatable__pager-link m-datatable__pager-link--more-next',
                {
                  'm-datatable__pager-link--disabled': pageLinkEnd >= lastPage
                }
              ]"
          :disabled="pageLinkEnd >= lastPage"
          @click="nextPageLinks"
        >
          <i class="la la-ellipsis-h"></i>
        </a>
      </li>

      <li>
        <a
          title="Next"
          :class="[
                'm-datatable__pager-link m-datatable__pager-link--next',
                {
                  'm-datatable__pager-link--disabled': currentPage >= lastPage
                }
              ]"
          :disabled="currentPage >= lastPage"
          @click="toNextPage"
        >
          <i class="la la-angle-right"></i></a>
      </li>

      <li>
        <a
          title="Last"
          :class="[
                'm-datatable__pager-link m-datatable__pager-link--last',
                {
                  'm-datatable__pager-link--disabled': currentPage >= lastPage
                }
              ]"
          :disabled="currentPage >= lastPage"
          @click="toLastPage"
        >
          <i class="la la-angle-double-right"></i>
        </a>
      </li>
    </ul>
    <div class="m-datatable__pager-info">
      <span
        v-if="!!totalItems"
        class="m-datatable__pager-detail"
      >
        Displaying {{ fromItems }} - {{ toItems }} of {{ totalItems }} records
      </span>
    </div>
  </div>
</template>

<script>

  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import WithPagination from '@/modules/core/mixins/WithPagination';

  export default {

    mixins: [WithPagination],

    components: {
      FontAwesomeIcon,
    },

    methods: {
      setPagination(pagination) {
        this.lastPage = pagination.lastPage;
        this.pageSize = pagination.pageSize;
        this.pageLinkStart = pagination.currentPage;
        this.totalItems = pagination.total;
        this.pageLinkEnd = this.pageLinkStart + this.pageLinksShown;
        this.ensurePageLinkBounds();
      },
    },

    mounted() {
      if (this.bus) {
        this.bus.$on('pagination.set', this.setPagination);
      }
    },
  };

</script>