import _get from 'lodash/get';
import { fill } from './utilities';
import ESSENTIAL from './ESSENTIAL';

const fields = [
  'pEssentialNewbornCare',
  'pNewbornHearingScreeningTest',
  'pNewbornScreeningTest',
  'pFilterCardNo',
  'pNewbornHearingRegistryNo',
  'pNewbornHearingScreeningTestResult',
];


export default (data, withDefault = false) => {
  let defaultData = {
    pEssentialNewbornCare: 'Y',
    pNewbornHearingScreeningTestResult: 'X',
    pNewbornScreeningTest: 'Y',
    pNewbornHearingRegistryNo: 'N/A',
    pFilterCardNo: 'N/A',
  };

  if (!withDefault) {
    defaultData = {};
  }

  const result = fill({}, fields, defaultData, data);

  result.ESSENTIAL = ESSENTIAL(
    _get(data, 'ESSENTIAL', {}), withDefault,
  );

  return result;
};
