
export const LOGIN = 'Auth/LOGIN';
export const LOGIN_FAILED = 'Auth/LOGIN_FAILED';
export const LOGOUT = 'Auth/LOGOUT';

export default {
  [LOGOUT](state) {
    state.user = null;
    state.pending = false;
    state.error = null;
  },

  [LOGIN](state, payload) {
    state.user = payload;
    state.pending = false;
    state.error = null;
  },

  [LOGIN_FAILED](state, payload) {
    state.user = null;
    state.pending = false;
    state.error = payload;
  },
};
