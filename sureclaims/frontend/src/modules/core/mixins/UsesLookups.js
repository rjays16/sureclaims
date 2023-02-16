import { mapGetters } from 'vuex';

export default {

  computed: {
    ...mapGetters('Core', [
      'lookup',
      'lookupTypes',
    ]),
  },

  // Shorthand methods
  methods: {
  },
};
