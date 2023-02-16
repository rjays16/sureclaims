import { fill } from './utilities';

const fields = [
  'pDrugsMedicinesSupplies',
  'pDMSTotalAmount',
  'pExaminations',
  'pExamTotalAmount',
];

export default data => fill({}, fields, {
  pDrugsMedicinesSupplies: 'N',
  pDMSTotalAmount: 0,
  pExaminations: 'N',
  pExamTotalAmount: 0,
}, data);
