import {
  PAGE_TITLE,
  PAGE_LOADING,
  REGISTER_LOOKUP_TYPE,
  REGISTER_MENU,
} from './types';

// Registers a lookup type
const registerLookupType = ({ commit }, payload) => {
  commit(REGISTER_LOOKUP_TYPE, payload);
};

// Registers a menu item
const registerMenu = ({ commit }, payload) => {
  commit(REGISTER_MENU, payload);
};

// Registers many menu items
const registerMenus = ({ commit }, payload) => {
  Object.keys(payload).forEach(key =>
    commit(REGISTER_MENU, {
      key,
      menu: payload[key],
    }),
  );
};

// Sets the page title
const setPageTitle = ({ commit }, payload) => {
  commit(PAGE_TITLE, payload);
};

// Shows or hides the page loading indicator
const setPageLoading = ({ commit }, payload) => {
  commit(PAGE_LOADING, payload);
};

export default {
  setPageTitle,
  setPageLoading,
  registerMenu,
  registerMenus,
  registerLookupType,
};
