import _get from 'lodash/get';

import SPECIALPROCEDURES from './SPECIALPROCEDURES';

const elements = [
  'HEMODIALYSIS',
  'PERITONEAL',
  'LINAC',
  'COBALT',
  'TRANSFUSION',
  'BRACHYTHERAPHY',
  'CHEMOTHERAPY',
  'DEBRIDEMENT',
  'IMRT',
];

export default (data) => {
  const result = {};

  elements.forEach((element) => {
    result[element] = SPECIALPROCEDURES(_get(data, element, {}));
  });

  return result;
};
