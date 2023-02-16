import _get from 'lodash/get';
import { fill, fillList } from './utilities';
import ITEM from './ITEM';

const fields = [
  'pCompanyName',
  'pCompanyTIN',
  'pBIRPermitNumber',
  'pReceiptNumber',
  'pReceiptDate',
  'pVATExemptSale',
  'pVAT',
  'pTotal',
];

export default (data) => {
  const result = fill({}, fields, data);

  result.ITEM = fillList(
    _get(data, 'ITEM', []),
    ITEM,
  );

  return result;
};
