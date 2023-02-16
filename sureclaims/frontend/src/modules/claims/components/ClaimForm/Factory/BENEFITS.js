import { fill } from './utilities';

const fields = [
  'pTotalHCIFees',
  'pTotalProfFees',
  'pGrandTotal',
];

export default data => fill({}, fields, {
  pTotalHCIFees: 0,
  pTotalProfFees: 0,
  pGrandTotal: 0
}, data);
