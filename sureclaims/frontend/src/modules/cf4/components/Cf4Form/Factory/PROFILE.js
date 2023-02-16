import { fill } from './utilities';

const fields = [
  'pClaimID',
  'pClaimsTransmittalID',
  'pPatientPin',
  'pPatientLname',
  'pPatientFname',
  'pPatientMname',
  'pPatientExtname',
  'pPatientDob',
  'pPatientSex',
  'pPatientType'
];

export default data => fill({}, fields, data);
