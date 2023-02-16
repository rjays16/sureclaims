import { fill } from './utilities';

const fields = [
  'pHeentId',
  'pHeentRem'
];

export default data => fill({}, fields, data);
