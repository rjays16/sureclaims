import _get from 'lodash/get';
import { fill, fillList } from './utilities';

import CLINICAL from './CLINICAL';
import LABDIAG from './LABDIAG';
import PHEX from './PHEX';

const fields = [
  'pBriefHistory',
  'pReferredReason',
  'pIntensive',
  'pMaintenance',
];

export default (data) => {
  const result = fill({}, fields, data);

  result.CLINICAL = fillList(
    _get(data, 'CLINICAL', []),
    CLINICAL,
  );

  result.LABDIAG = fillList(
    _get(data, 'LABDIAG', []),
    LABDIAG,
  );

  result.PHEX = PHEX(
    _get(data, 'PHEX', {}),
  );

  return result;
};
