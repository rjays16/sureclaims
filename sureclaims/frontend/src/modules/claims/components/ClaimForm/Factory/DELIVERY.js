import { fill } from './utilities';

const fields = [
  'pDeliveryDate',
  'pDeliveryTime',
  'pObstetricIndex',
  'pAOGLMP',
  'pDeliveryManner',
  'pPresentation',
  'pFetalOutcome',
  'pSex',
  'pBirthWeight',
  'pAPGARScore',
  'pPostpartum',
];

export default data => fill({}, fields, data);
