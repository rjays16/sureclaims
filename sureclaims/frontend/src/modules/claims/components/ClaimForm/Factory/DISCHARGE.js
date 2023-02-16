import _get from 'lodash/get';
import { fill, fillList } from './utilities';
import ICDCODE from './ICDCODE';
import RVSCODES from './RVSCODES';

const fields = [
  'pDischargeDiagnosis',
];

export default (data) => {
  const result = fill({}, fields, data);

  result.ICDCODE = fillList(
    _get(data, 'ICDCODE', []),
    ICDCODE,
  );

  result.RVSCODES = fillList(
    _get(data, 'RVSCODES', []),
    RVSCODES,
  );

  return result;
};
