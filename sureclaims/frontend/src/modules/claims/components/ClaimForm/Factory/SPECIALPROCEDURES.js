import _get from 'lodash/get';
import { fillList } from './utilities';

import SESSIONS from './SESSIONS';

export default (data) => {
  const result = {};

  result.SESSIONS = fillList(
    _get(data, 'SESSIONS', []),
    SESSIONS,
  );

  return result;
};
