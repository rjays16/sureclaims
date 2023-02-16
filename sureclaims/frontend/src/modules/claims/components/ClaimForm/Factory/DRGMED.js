import { fill } from './utilities';

const fields = [
  'pPurchaseDate',
  'pDrugCode',
  'pPNDFCode',
  'pGenericName',
  'pBrandName',
  'pPreparation',
  'pQuantity>',
];

export default data => fill({}, fields, data);
