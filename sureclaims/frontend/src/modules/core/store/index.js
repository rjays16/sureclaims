//
import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
  // Dynamic configuration for page elments
  pageTitle: null,
  navigation: {
    top: [],
  },
  lookupTypes: [],
  pageLoading: false,
};

export default {
  namespaced: true,
  state,
  actions,
  getters,
  mutations,
};

