import { fill } from './utilities';

const fields = [
  'pSkinId',
  'pSkinRem'
];

export default data => fill({}, fields, data);
