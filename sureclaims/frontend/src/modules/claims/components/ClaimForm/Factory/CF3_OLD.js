import _get from 'lodash/get';
import { fill } from './utilities';
import PHEX from './PHEX';
import MATERNITY from './MATERNITY';

const fields = [
  'pChiefComplaint',
  'pBriefHistory',
  'pCourseWard',
  'pPertinentFindings',
];

export default (data) => {
  const result = fill({}, fields, data);

  result.PHEX = PHEX(
    _get(data, 'PHEX', {}),
  );

  result.MATERNITY = MATERNITY(
    _get(data, 'MATERNITY', {}),
  );

  return result;
};
