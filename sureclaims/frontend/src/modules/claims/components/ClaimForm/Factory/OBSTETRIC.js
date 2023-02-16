import { fill } from './utilities';

const fields = [
  'pMultiplePregnancy',
  'pOvarianCyst',
  'pMyomaUteri',
  'pPlacentaPrevia',
  'pMiscarriages',
  'pStillBirth',
  'pPreEclampsia',
  'pEclampsia',
  'pPrematureContraction',
];

export default data => fill({}, fields, data);
