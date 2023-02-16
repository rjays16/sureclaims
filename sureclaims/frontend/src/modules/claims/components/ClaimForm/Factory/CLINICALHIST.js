import { fill } from './utilities';

const fields = [
  'pVitalSigns',
  'pPregnancyLowRisk',
  'pLMP',
  'pMenarcheAge',
  'pObstetricG',
  'pObstetricP',
  'pObstetric_T',
  'pObstetric_P',
  'pObstetric_A',
  'pObstetric_L',
];

export default data => fill({}, fields, data);
