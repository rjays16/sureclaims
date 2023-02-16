import _get from 'lodash/get';

import CF3_OLD from './CF3_OLD';
import CF3_NEW from './CF3_NEW';

export default (data) => {
  const result = {};

  result.CF3_OLD = CF3_OLD(_get(data, 'CF3_OLD', {}));
  result.CF3_NEW = CF3_NEW(_get(data, 'CF3_NEW', {}));

  return result;
};
