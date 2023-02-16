import _get from 'lodash/get';
import WARD from './WARD';

export default (data) => {
  const result = {};

  result.WARD = WARD(
    _get(data, 'WARD', {}),
  );

  return result;
};
