import _get from 'lodash/get';

import ADMITREASON from './ADMITREASON';
import COURSE from './COURSE';

export default (data) => {
  const result = {};

  result.ADMITREASON = ADMITREASON(
    _get(data, 'ADMITREASON', {}),
  );

  result.COURSE = COURSE(
    _get(data, 'COURSE', {}),
  );

  return result;
};
