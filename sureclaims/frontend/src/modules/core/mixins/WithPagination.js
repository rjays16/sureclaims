import _range from 'lodash/range';

export default {
  props: {
    bus: {
      type: Object,
    },
  },

  data() {
    return {
      pageLinkStart: 0,
      pageLinkEnd: 0,
      pageLinksShown: 5,
      currentPage: 1,
      lastPage: -1,
      pageSize: -1,
      totalItems: 0,
    };
  },

  computed: {
    fromItems() {
      return ((this.currentPage - 1) * this.pageSize) + 1;
    },

    toItems() {
      let index = (this.currentPage * this.pageSize);
      if (index > this.totalItems) {
        index = this.totalItems;
      }
      return index;
    },

    linkNumbers() {
      return _range(this.pageLinkStart, this.pageLinkEnd);
    },

  },

  methods: {

    paginationIndex(index) {
      return index + this.fromItems;
    },

    ensurePageLinkBounds() {
      if (this.pageLinkEnd > this.lastPage) {
        this.pageLinkEnd = this.lastPage + 1;
      }

      this.pageLinkStart = this.pageLinkEnd - this.pageLinksShown;
      if (this.pageLinkStart < 1) {
        this.pageLinkStart = 1;
      }

      this.pageLinkEnd = this.pageLinkStart + this.pageLinksShown;
      if (this.pageLinkEnd > this.lastPage) {
        this.pageLinkEnd = this.lastPage + 1;
      }
    },

    prevPageLinks() {
      this.pageLinkStart -= this.pageLinksShown;
      this.pageLinkEnd = this.pageLinkStart + this.pageLinksShown;
      this.ensurePageLinkBounds();
    },

    nextPageLinks() {
      this.pageLinkStart += this.pageLinksShown;
      this.pageLinkEnd = this.pageLinkStart + this.pageLinksShown;
      this.ensurePageLinkBounds();
    },

    toPage(page) {
      this.currentPage = page;
      this.bus.$emit('pagination.page', page);
    },

    toFirstPage() {
      if (this.currentPage > 1) {
        this.currentPage = 1;
        this.bus.$emit('pagination.page', this.currentPage);
      }
    },

    toPrevPage() {
      if (this.currentPage > 1) {
        this.currentPage -= 1;
        this.bus.$emit('pagination.page', this.currentPage);
      }
    },

    toNextPage() {
      if (this.currentPage < this.lastPage) {
        this.currentPage += 1;
        this.bus.$emit('pagination.page', this.currentPage);
      }
    },

    toLastPage() {
      if (this.currentPage < this.lastPage) {
        this.currentPage = this.lastPage;
        this.bus.$emit('pagination.page', this.currentPage);
      }
    },
  },

};

