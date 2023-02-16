import { fill } from './utilities';

const fields = [
  'pDoctorAccreCode',
  'pDoctorLastName',
  'pDoctorFirstName',
  'pDoctorMiddleName',
  'pDoctorSuffix',
  'pWithCoPay',
  'pDoctorCoPay',
  'pDoctorSignDate',
];

export default data => fill({}, fields, data);
