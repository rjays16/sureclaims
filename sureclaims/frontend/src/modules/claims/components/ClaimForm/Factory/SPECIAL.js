import _get from 'lodash/get';

import PROCEDURES from './PROCEDURES';
import MCP from './MCP';
import TBDOTS from './TBDOTS';
import ABP from './ABP';
import NCP from './NCP';
import HIVAIDS from './HIVAIDS';
import CATARACTINFO from './CATARACTINFO';

export default (data) => {
  const result = {};

  result.PROCEDURES = PROCEDURES(
    _get(data, 'PROCEDURES', {}),
  );

  result.MCP = MCP(
    _get(data, 'MCP', {}),
  );

  result.TBDOTS = TBDOTS(
    _get(data, 'TBDOTS', {}),
  );

  result.ABP = ABP(
    _get(data, 'ABP', {}),
  );

  result.NCP = NCP(
    _get(data, 'NCP', {}),
  );

  result.HIVAIDS = HIVAIDS(
    _get(data, 'HIVAIDS', {}),
  );

  result.CATARACTINFO = CATARACTINFO(
    _get(data, 'CATARACTINFO', {}),
  );

  return result;
};
