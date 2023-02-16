import { fill } from './utilities';

const fields = [
  'pHypertension',
  'pHeartDisease',
  'pDiabetes',
  'pThyroidDisaster',
  'pObesity',
  'pAsthma',
  'pEpilepsy',
  'pRenalDisease',
  'pBleedingDisorders',
  'pPreviousCS',
  'pUterineMyomectomy',
];

export default data => fill({}, fields, data);
