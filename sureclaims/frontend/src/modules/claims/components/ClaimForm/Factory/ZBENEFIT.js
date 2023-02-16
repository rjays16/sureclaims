import { fill } from './utilities';

const fields = [
  'pZBenefitCode',
  'pPreAuthDate',
];

export default data => fill({}, fields, data);
