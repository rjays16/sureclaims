import { fill } from './utilities';

const fields = [
  'pRelCode',
  'pRelDesc',
];

export default data => fill({}, fields, data);
