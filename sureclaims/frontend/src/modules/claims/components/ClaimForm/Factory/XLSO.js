import { fill } from './utilities';

const fields = [
  'pDiagnosticDate',
  'pDiagnosticType',
  'pDiagnosticName',
  'pQuantity',
];

export default data => fill({}, fields, data);
