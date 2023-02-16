import { fill } from './utilities';

const fields = [
  'pQuantity',
  'pUnitPrice',
  'pDescription',
  'pAmount',
];

export default data => fill({}, fields, data);
