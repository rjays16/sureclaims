import { fill } from './utilities';

const fields = [
  'pCourseDate',
  'pFindings',
  'pAction',
];

export default data => fill({}, fields, data);
