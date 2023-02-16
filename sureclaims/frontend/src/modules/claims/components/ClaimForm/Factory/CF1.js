import { fill } from './utilities';

const fields = [
  'pMemberPIN',
  'pMemberLastName',
  'pMemberFirstName',
  'pMemberSuffix',
  'pMemberMiddleName',
  'pMemberBirthDate',
  'pMemberShipType',
  'pMailingAddress',
  'pZipCode',
  'pMemberSex',
  'pLandlineNo',
  'pMobileNo',
  'pEmailAddress',
  'pPatientIs',
  'pPatientPIN',
  'pPatientLastName',
  'pPatientFirstName',
  'pPatientSuffix',
  'pPatientMiddleName',
  'pPatientBirthDate',
  'pPatientSex',
  'pPEN',
  'pEmployerName',
];

export default data => fill({}, fields, data);
