import { mapState, mapActions } from 'vuex';

export default {
  props: {
    pageMeta: {
      type: Object,
      default: () => ({
        title: '',
        description: '',
      }),
    },
  },

  computed: {
    ...mapState('Core', {
      pageTitle: state => state.pageTitle,
    }),
  },

  methods: {
    ...mapActions('Core', [
      'setPageTitle',
    ]),
  },

  metaInfo() {
    return {
      title: this.pageMeta.title,
      meta: [
        {
          vmid: 'description',
          name: 'description',
          content: this.pageMeta.description,
        },
      ],
    };
  },

  mounted() {
    if (this.pageMeta && this.pageMeta.title) {
      this.setPageTitle(this.pageMeta.title);
    }
  },

  updated() {
    if (this.pageMeta && this.pageMeta.title) {
      this.setPageTitle(this.pageMeta.title);
    }
  },
};
