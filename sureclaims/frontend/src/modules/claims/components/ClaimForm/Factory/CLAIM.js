import _get from 'lodash/get';
import { fill } from './utilities';
import CF1 from './CF1';
import CF2 from './CF2';
import ALLCASERATE from './ALLCASERATE';
import ZBENEFIT from './ZBENEFIT';
import CF3 from './CF3';
import PARTICULARS from './PARTICULARS';
import RECEIPTS from './RECEIPTS';
import DOCUMENTS from './DOCUMENTS';

const fields = [
  'pClaimNumber',
  'pTrackingNumber',
  'pPhilhealthClaimType',
  'pPatientType',
  'pIsEmergency',
];

export default (data) => {
  const result = fill({}, fields, {
    pPhilhealthClaimType: 'ALL-CASE-RATE',
    pPatientType: 'O',
    pIsEmergency: 'N',
  }, data);

  result.CF1 = CF1(_get(data, 'CF1', {}));
  result.CF2 = CF2(_get(data, 'CF2', {}));
  result.ALLCASERATE = ALLCASERATE(_get(data, 'ALLCASERATE', {}));
  result.ZBENEFIT = ZBENEFIT(_get(data, 'ZBENEFIT', {}));
  result.CF3 = CF3(_get(data, 'CF3', {}));
  result.PARTICULARS = PARTICULARS(_get(data, 'PARTICULARS', {}));
  result.RECEIPTS = RECEIPTS(_get(data, 'RECEIPTS', {}));
  result.DOCUMENTS = DOCUMENTS(_get(data, 'DOCUMENTS', {}));

  return result;
};
