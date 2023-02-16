import { fill } from './utilities';

const fields = [
  'pTotalActualCharges',
  'pDiscount',
  'pPhilhealthBenefit',
  'pTotalAmount',
  'pMemberPatient',
  'pHMO',
  'pOthers',
];

export default data => fill({}, fields, {
  pTotalActualCharges: 0,
  pDiscount: 0,
  pPhilhealthBenefit: 0,
  pTotalAmount: 0,
  pMemberPatient: 'N',
  pHMO: 'N',
  pOthers: 'N',
}, data);
