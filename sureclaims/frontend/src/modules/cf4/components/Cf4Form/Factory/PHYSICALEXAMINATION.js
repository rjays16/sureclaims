import _get from 'lodash/get';
import VITALSIGN from './VITALSIGN';
import GENERALSURVEY from './GENERALSURVEY';
import HEENT from './HEENT';
import CHEST from './CHEST';
import HEART from './HEART';
import ABDOMEN from './ABDOMEN';
import GUIE from './GUIE';
import SKIN from './SKIN';
import NEURO from './NEURO';

export default (data) => {
  const result = {};

  result.VITALSIGN = VITALSIGN(_get(data, 'VITALSIGN', {}));
  result.GENERALSURVEY = GENERALSURVEY(_get(data, 'GENERALSURVEY', {}));
  result.HEENT = HEENT(_get(data, 'HEENT', {}));
  result.CHEST = CHEST(_get(data, 'CHEST', {}));
  result.HEART = HEART(_get(data, 'HEART', {}));
  result.ABDOMEN = ABDOMEN(_get(data, 'ABDOMEN', {}));
  result.GUIE = GUIE(_get(data, 'GUIE', {}));
  result.SKIN = SKIN(_get(data, 'SKIN', {}));
  result.NEURO = NEURO(_get(data, 'NEURO', {}));

  return result;
};
