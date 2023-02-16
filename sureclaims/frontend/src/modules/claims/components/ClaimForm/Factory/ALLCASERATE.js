import _get from 'lodash/get';
import { fillList } from './utilities';

import CASERATE from './CASERATE';

export default (data) => {
  const result = {};

  result.CASERATE = fillList(
    _get(data, 'CASERATE', []),
    CASERATE,
  );

  return result;
};
