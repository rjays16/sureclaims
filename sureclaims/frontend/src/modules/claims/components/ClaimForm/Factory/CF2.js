import _get from 'lodash/get';
import { fill, fillList } from './utilities';
import DIAGNOSIS from './DIAGNOSIS';
import SPECIAL from './SPECIAL';
import PROFESSIONALS from './PROFESSIONALS';
import CONSUMPTION from './CONSUMPTION';
import APR from './APR';

const fields = [
  'pPatientReferred',
  'pReferredIHCPAccreCode',
  'pAdmissionDate',
  'pAdmissionTime',
  'pDischargeDate',
  'pDischargeTime',
  'pDisposition',
  'pExpiredDate',
  'pExpiredTime',
  'pReferralIHCPAccreCode',
  'pReferralReasons',
  'pAccommodationType',
  'pHasAttachedSOA',
];

export default (data) => {
  const result = fill({}, fields, {
    pPatientReferred: 'N',
    pHasAttachedSOA: 'Y',
  }, data);

  result.DIAGNOSIS = DIAGNOSIS(
    _get(data, 'DIAGNOSIS', {}),
  );

  result.SPECIAL = SPECIAL(
    _get(data, 'SPECIAL', {}),
  );

  result.SPECIAL = SPECIAL(
    _get(data, 'SPECIAL', {}),
  );

  result.PROFESSIONALS = fillList(
    _get(data, 'PROFESSIONALS', []),
    PROFESSIONALS,
  );

  result.CONSUMPTION = CONSUMPTION(
    _get(data, 'CONSUMPTION', {}),
  );

  result.APR = APR(
    _get(data, 'APR', {}),
  );

  return result;
};
