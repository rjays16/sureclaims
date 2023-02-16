import _get from 'lodash/get';
import { fillList } from './utilities';
import DOCUMENT from './DOCUMENT';

export default (data) => {
  const result = {};

  result.DOCUMENT = fillList(
    _get(data, 'DOCUMENT', []),
    DOCUMENT,
  );

  return result;
};
