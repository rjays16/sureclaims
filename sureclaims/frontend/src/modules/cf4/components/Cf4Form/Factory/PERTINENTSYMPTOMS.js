import { fill } from './utilities';

const fields = [
  'pSignsSymptoms',
  'remarks'
];

export default data => fill({}, fields, data);
