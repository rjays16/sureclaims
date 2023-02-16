import { fill } from './utilities';

const fields = [
  'pTBType',
  'pNTPCardNo',
];

export default data => fill({}, fields, data);
