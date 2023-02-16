import { fill } from './utilities';

const fields = [
  'pRelatedProcedure',
  'pRVSCode',
  'pProcedureDate',
  'pLaterality',
  'SC_WITHLATERAL'
];

export default data => fill({}, fields, {
  pLaterality: 'N/A',
  SC_WITHLATERAL: false
}, data);
