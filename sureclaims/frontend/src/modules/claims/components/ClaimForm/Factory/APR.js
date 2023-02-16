import _get from 'lodash/get';

import APRBYPATSIG from './APRBYPATSIG';
import APRBYPATREPSIG from './APRBYPATREPSIG';
import APRBYTHUMBMARK from './APRBYTHUMBMARK';

export default (data) => {
  const result = {};

  result.APRBYPATSIG = APRBYPATSIG(
    _get(data, 'APRBYPATSIG', {}),
  );

  result.APRBYPATREPSIG = APRBYPATREPSIG(
    _get(data, 'APRBYPATREPSIG', {}),
  );

  result.APRBYTHUMBMARK = APRBYTHUMBMARK(
    _get(data, 'APRBYTHUMBMARK', {}),
  );

  return result;
};
