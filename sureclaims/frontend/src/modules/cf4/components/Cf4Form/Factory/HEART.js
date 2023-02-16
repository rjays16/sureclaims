import { fill } from './utilities';

const fields = [
  'pHeartId',
  'pHeartRem'
];

export default data => fill({}, fields, data);
