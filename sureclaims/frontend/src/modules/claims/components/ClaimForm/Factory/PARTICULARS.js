import _get from 'lodash/get';
import { fillList } from './utilities';

import DRGMED from './DRGMED';
import XLSO from './XLSO';

export default (data) => {
  const result = {};

  result.DRGMED = fillList(
    _get(data, 'DRGMED', []),
    DRGMED,
  );

  result.XLSO = fillList(
    _get(data, 'XLSO', []),
    XLSO,
  );

  return result;
};
