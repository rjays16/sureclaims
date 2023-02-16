import _get from 'lodash/get';
import { fill } from './utilities';
import DEFINEDPATREPREL from './DEFINEDPATREPREL';
import OTHERPATREPREL from './OTHERPATREPREL';
import DEFINEDREASONFORSIGNING from './DEFINEDREASONFORSIGNING';
import OTHERREASONFORSIGNING from './OTHERREASONFORSIGNING';

const fields = [
  'pDateSigned',
];

export default (data) => {
  const result = fill({}, fields, data);

  result.DEFINEDPATREPREL = DEFINEDPATREPREL(
    _get(data, 'DEFINEDPATREPREL', {}),
  );

  result.OTHERPATREPREL = OTHERPATREPREL(
    _get(data, 'OTHERPATREPREL', {}),
  );

  result.DEFINEDREASONFORSIGNING = DEFINEDREASONFORSIGNING(
    _get(data, 'DEFINEDREASONFORSIGNING', {}),
  );

  result.OTHERREASONFORSIGNING = OTHERREASONFORSIGNING(
    _get(data, 'OTHERREASONFORSIGNING', {}),
  );

  return result;
};
