export default {

  props: {
    useLayout: Object,
    default: null,
  },

  created() {
    if (this.useLayout) {
      // Load layout component
      this.$options.components.layout = this.useLayout;
    }
  },

};
