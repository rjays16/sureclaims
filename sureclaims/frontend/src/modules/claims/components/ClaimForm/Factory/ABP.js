import { fill } from './utilities';

const fields = [
  'pDay0ARV',
  'pDay3ARV',
  'pDay7ARV',
  'pRIG',
  'pABPOthers',
  'pABPSpecify',
];

export default data => fill({}, fields, data);
