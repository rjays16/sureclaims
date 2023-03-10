import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
  user: null,
  pending: false,
  error: null,
};

export default {
  namespaced: true,
  state,
  actions,
  getters,
  mutations,
};
