import { fill } from './utilities';

const fields = [
  'pVisitDate',
  'pAOGWeeks',
  'pWeight',
  'pCardiacRate',
  'pRespiratoryRate',
  'pBloodPressure',
  'pTemperature',
];

export default data => fill({}, fields, data);
