import { fill } from './utilities';

const fields = [
  'pSystolic',
  'pDiastolic',
  'pHr',
  'pRr',
  'pTemp'
];

export default data => fill({}, fields, data);
