import { fill } from './utilities';

const fields = [
  'pDateAction',
  'pDoctorsAction'
];

export default data => fill({}, fields, data);
