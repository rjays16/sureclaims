import _get from 'lodash/get';
import { fill } from './utilities';
import BENEFITS from './BENEFITS';
import HCIFEES from './HCIFEES';
import PROFFEES from './PROFFEES';
import PURCHASES from './PURCHASES';

const fields = [
  'pEnoughBenefits',
];

export default (data) => {
  const result = fill({}, fields, {
    pEnoughBenefits: 'N'
  }, data);

  result.BENEFITS = BENEFITS(
    _get(data, 'BENEFITS', {}),
  );

  result.HCIFEES = HCIFEES(
    _get(data, 'HCIFEES', {}),
  );

  result.PROFFEES = PROFFEES(
    _get(data, 'PROFFEES', {}),
  );

  result.PURCHASES = PURCHASES(
    _get(data, 'PURCHASES', {}),
  );

  return result;
};
