import _get from 'lodash/get';
import { fillList } from './utilities';

import RECEIPT from './RECEIPT';

export default (data) => {
  const result = {};

  result.RECEIPT = fillList(
    _get(data, 'RECEIPT', []),
    RECEIPT,
  );

  return result;
};
