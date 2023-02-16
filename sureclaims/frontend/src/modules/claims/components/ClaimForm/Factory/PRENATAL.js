import _get from 'lodash/get';
import { fill, fillList } from './utilities';

import CLINICALHIST from './CLINICALHIST';
import OBSTETRIC from './OBSTETRIC';
import MEDISURG from './MEDISURG';
import CONSULTATION from './CONSULTATION';

const fields = [
  'pPrenatalConsultation',
  'pMCPOrientation',
  'pExpectedDeliveryDate ',
];

export default (data) => {
  const result = fill({}, fields, data);

  result.CLINICALHIST = CLINICALHIST(
    _get(data, 'CLINICALHIST', {}),
  );

  result.OBSTETRIC = OBSTETRIC(
    _get(data, 'OBSTETRIC', {}),
  );

  result.MEDISURG = MEDISURG(
    _get(data, 'MEDISURG', {}),
  );

  result.CONSULTATION = fillList(
    _get(data, 'CONSULTATION', []),
    CONSULTATION,
  );

  return result;
};
