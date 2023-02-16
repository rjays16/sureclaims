import { fill } from './utilities';

const fields = [
  'pNeuroId',
  'pNeuroRem'
];

export default data => fill({}, fields, data);
