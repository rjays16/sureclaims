import { fill } from './utilities';

const fields = [
  'pChestId',
  'pChestRem'
];

export default data => fill({}, fields, data);
