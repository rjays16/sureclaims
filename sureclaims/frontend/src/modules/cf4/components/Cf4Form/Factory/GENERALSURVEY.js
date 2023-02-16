import { fill } from './utilities';

const fields = [
  'pGenSurveyId',
  'pGenSurveyRem'
];

export default data => fill({}, fields, data);
