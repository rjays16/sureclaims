import { fill } from './utilities';

const fields = [
  'pCheckUpDate1',
  'pCheckUpDate2',
  'pCheckUpDate3',
  'pCheckUpDate4',
];

export default data => fill({}, fields, data);
