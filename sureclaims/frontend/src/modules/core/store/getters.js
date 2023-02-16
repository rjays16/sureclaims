const navigation = state => key => state.navigation[key];

const pageTitle = state => () => state.pageTitle;

const pageLoading = state => () => state.pageLoading;

const lookupTypes = state => domain =>
  state.lookupTypes.filter(item => item.domain === domain)
    .reduce((prev, current) => {
      /* eslint-disable no-param-reassign */
      prev[current.type] = current.value;
      return prev;
    }, {});

const lookup = state => (domain, type, defaultValue = '') => {
  const found = state.lookupTypes.find(item => item.domain === domain && item.type === type);
  return (found && found.value) || defaultValue;
};

export default {
  lookup,
  lookupTypes,
  pageTitle,
  pageLoading,
  navigation,
};

