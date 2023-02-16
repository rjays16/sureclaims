import { fill } from './utilities';

const fields = [
  'pAbdomenId',
  'pAbdomenRem'
];

export default data => fill({}, fields, data);
