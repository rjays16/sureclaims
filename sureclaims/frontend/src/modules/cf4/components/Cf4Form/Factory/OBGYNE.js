import _get from 'lodash/get';
import MENSTRUAL from './MENSTRUAL';
import PREGNANCY from './PREGNANCY';

export default (data) => {
  const result = {};

  result.MENSTRUAL = MENSTRUAL(_get(data, 'VITALSIGN', {}));
  result.PREGNANCY = PREGNANCY(_get(data, 'GENERALSURVEY', {}));

  return result;
};
