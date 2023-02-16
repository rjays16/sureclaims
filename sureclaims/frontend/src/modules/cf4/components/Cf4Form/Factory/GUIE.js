import { fill } from './utilities';

const fields = [
  'pGuId',
  'pGuRem'
];

export default data => fill({}, fields, data);
