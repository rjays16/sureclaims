import _isArray from 'lodash/isArray';
import _some from 'lodash/some';
import _find from 'lodash/find';

import {
  PAGE_LOADING,
  PAGE_TITLE,
  REGISTER_LOOKUP_TYPE,
  REGISTER_MENU,
} from './types';

/**
 *
 * @param {Array} menuItems
 * @param {String} key
 * @param {Object} menu
 * @returns {Array}
 */
const getDeepMenu = (menuItems, key) =>
  _find(menuItems, (item) => {
    if (item.id === key && item.items) {
      return true;
    }
    return item.items && getDeepMenu(item.items, key);
  });

/**
 *
 * @param {Object} stateNavigation
 * @param {String} key
 *
 * @return Array
 * @private
 */
const getMenuList = (stateNavigation, key) => {
  if (key in stateNavigation) {
    return stateNavigation[key];
  }

  let found;
  _some(Object.keys(stateNavigation), (innerKey) => {
    const menu = getDeepMenu(stateNavigation[innerKey], key);
    if (menu) {
      found = menu;
    }
    return menu;
  });
  return found;
};

export default {
  // Sets the page title
  [PAGE_TITLE](state, payload) {
    state.pageTitle = payload;
  },

  // Registers a new menu object
  [REGISTER_MENU](state, { key, menu }) {
    const normalizedMenu = _isArray(menu) ? menu : [menu];
    const referencedMenu = getMenuList(state.navigation, key);
    if (referencedMenu) {
      normalizedMenu.forEach((singleMenu) => {
        if (referencedMenu.items) {
          referencedMenu.items.push(singleMenu);
        } else if (_isArray(referencedMenu)) {
          referencedMenu.push(singleMenu);
        }
      });
    }
  },

  // Registers a lookup type
  [REGISTER_LOOKUP_TYPE](state, { domain, type, value }) {
    /*
    state.lookupTypes[domain] = Object.assign({}, state.lookupTypes[domain] || {}, {
      [type]: value,
    });
    */
    state.lookupTypes.push({ domain, type, value });
  },

  // Indicates that the page is loading or not
  [PAGE_LOADING](state, payload) {
    state.pageLoading = payload;
  },
};
