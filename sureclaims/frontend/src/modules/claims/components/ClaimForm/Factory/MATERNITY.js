import _get from 'lodash/get';

import PRENATAL from './PRENATAL';
import DELIVERY from './DELIVERY';
import POSTPARTUM from './POSTPARTUM';

export default (data) => {
  const result = {};

  result.PRENATAL = PRENATAL(
    _get(data, 'PRENATAL', {}),
  );

  result.DELIVERY = DELIVERY(
    _get(data, 'DELIVERY', {}),
  );

  result.POSTPARTUM = POSTPARTUM(
    _get(data, 'POSTPARTUM', {}),
  );

  return result;
};
