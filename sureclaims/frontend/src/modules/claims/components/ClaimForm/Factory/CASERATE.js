import _get from 'lodash/get';
import { fill } from './utilities';
import CATARACT from './CATARACT';

const fields = [
  'pCaseRateCode',
  'pICDCode',
  'pRVSCode',
  'pCaseRateAmount',
];

export default (data) => {
  const result = fill({}, fields, data);

  result.CATARACT = CATARACT(
    _get(data, 'CATARACT', {}),
  );

  return result;
};
