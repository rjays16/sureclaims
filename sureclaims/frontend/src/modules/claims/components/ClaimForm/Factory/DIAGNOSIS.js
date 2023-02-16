import _get from 'lodash/get';
import { fill, fillList } from './utilities';
import DISCHARGE from './DISCHARGE';

const fields = [
  'pAdmissionDiagnosis',
];

export default (data) => {
  const result = fill({}, fields, data);

  result.DISCHARGE = fillList(
    _get(data, 'DISCHARGE', []),
    DISCHARGE,
  );

  return result;
};
